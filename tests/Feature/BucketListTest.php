<?php

namespace Tests\Feature;

use App\Models\BucketListCategory;
use App\Models\BucketListItem;
use App\Models\BucketListMilestone;
use App\Models\Level;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BucketListTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected BucketListCategory $category;

    protected function setUp(): void
    {
        parent::setUp();

        Level::factory()->create(['level_number' => 1, 'title' => 'Dreamer', 'xp_required' => 0]);
        $this->user = User::factory()->create();
        $this->category = BucketListCategory::factory()->system()->create();
    }

    public function test_guest_cannot_access_bucket_list(): void
    {
        $response = $this->get('/bucket-list');

        $response->assertRedirect('/login');
    }

    public function test_user_can_view_bucket_list_index(): void
    {
        $response = $this->actingAs($this->user)->get('/bucket-list');

        $response->assertStatus(200);
        $response->assertSee('Bucket List');
    }

    public function test_user_can_view_create_bucket_list_page(): void
    {
        $response = $this->actingAs($this->user)->get('/bucket-list/create');

        $response->assertStatus(200);
    }

    public function test_user_can_create_bucket_list_item(): void
    {
        $response = $this->actingAs($this->user)->post('/bucket-list', [
            'title' => 'Visit Japan',
            'category_id' => $this->category->id,
            'description' => 'Experience Japanese culture',
            'priority' => 'high',
        ]);

        $response->assertRedirect('/bucket-list');
        $this->assertDatabaseHas('bucket_list_items', [
            'user_id' => $this->user->id,
            'title' => 'Visit Japan',
        ]);
    }

    public function test_bucket_list_item_requires_title(): void
    {
        $response = $this->actingAs($this->user)->post('/bucket-list', [
            'title' => '',
            'category_id' => $this->category->id,
        ]);

        $response->assertSessionHasErrors('title');
    }

    public function test_user_can_view_bucket_list_item(): void
    {
        $item = BucketListItem::factory()->create([
            'user_id' => $this->user->id,
            'category_id' => $this->category->id,
        ]);

        $response = $this->actingAs($this->user)->get("/bucket-list/{$item->id}");

        $response->assertStatus(200);
        $response->assertSee($item->title);
    }

    public function test_user_can_update_bucket_list_item(): void
    {
        $item = BucketListItem::factory()->create([
            'user_id' => $this->user->id,
            'category_id' => $this->category->id,
        ]);

        $response = $this->actingAs($this->user)->put("/bucket-list/{$item->id}", [
            'title' => 'Updated Title',
            'category_id' => $this->category->id,
            'priority' => 'medium',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('bucket_list_items', [
            'id' => $item->id,
            'title' => 'Updated Title',
        ]);
    }

    public function test_user_can_delete_bucket_list_item(): void
    {
        $item = BucketListItem::factory()->create([
            'user_id' => $this->user->id,
            'category_id' => $this->category->id,
        ]);

        $response = $this->actingAs($this->user)->delete("/bucket-list/{$item->id}");

        $response->assertRedirect();
        $this->assertDatabaseMissing('bucket_list_items', ['id' => $item->id]);
    }

    public function test_user_can_mark_bucket_list_item_complete(): void
    {
        $item = BucketListItem::factory()->create([
            'user_id' => $this->user->id,
            'category_id' => $this->category->id,
            'completed_at' => null,
            'xp_reward' => 100,
        ]);

        $response = $this->actingAs($this->user)->post("/bucket-list/{$item->id}/complete");

        $response->assertRedirect();
        $this->assertNotNull($item->fresh()->completed_at);
    }

    public function test_user_can_add_milestone(): void
    {
        $item = BucketListItem::factory()->create([
            'user_id' => $this->user->id,
            'category_id' => $this->category->id,
        ]);

        $response = $this->actingAs($this->user)->post("/bucket-list/{$item->id}/milestones", [
            'title' => 'Book flight',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('bucket_list_milestones', [
            'bucket_list_item_id' => $item->id,
            'title' => 'Book flight',
        ]);
    }

    public function test_user_can_toggle_milestone(): void
    {
        $item = BucketListItem::factory()->create([
            'user_id' => $this->user->id,
            'category_id' => $this->category->id,
        ]);

        $milestone = BucketListMilestone::create([
            'bucket_list_item_id' => $item->id,
            'title' => 'Test milestone',
            'is_completed' => false,
        ]);

        $response = $this->actingAs($this->user)->post("/bucket-list/milestones/{$milestone->id}/toggle");

        $response->assertRedirect();
        $this->assertTrue($milestone->fresh()->is_completed);
    }

    public function test_user_cannot_view_other_users_bucket_list_item(): void
    {
        $otherUser = User::factory()->create();
        $item = BucketListItem::factory()->create([
            'user_id' => $otherUser->id,
            'category_id' => $this->category->id,
        ]);

        $response = $this->actingAs($this->user)->get("/bucket-list/{$item->id}");

        $response->assertStatus(403);
    }

    public function test_bucket_list_progress_updates_with_milestones(): void
    {
        $item = BucketListItem::factory()->create([
            'user_id' => $this->user->id,
            'category_id' => $this->category->id,
            'progress' => 0,
        ]);

        BucketListMilestone::create([
            'bucket_list_item_id' => $item->id,
            'title' => 'Milestone 1',
            'is_completed' => true,
        ]);

        BucketListMilestone::create([
            'bucket_list_item_id' => $item->id,
            'title' => 'Milestone 2',
            'is_completed' => false,
        ]);

        $item->updateProgress();

        $this->assertEquals(50, $item->fresh()->progress);
    }
}
