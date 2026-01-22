<?php

namespace Tests\Feature;

use App\Models\Dream;
use App\Models\DreamCategory;
use App\Models\Level;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DreamTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected DreamCategory $category;

    protected function setUp(): void
    {
        parent::setUp();

        Level::factory()->create(['level_number' => 1, 'title' => 'Dreamer', 'xp_required' => 0]);
        $this->user = User::factory()->create();
        $this->category = DreamCategory::factory()->system()->create();
    }

    public function test_guest_cannot_access_dreams(): void
    {
        $response = $this->get('/dreams');

        $response->assertRedirect('/login');
    }

    public function test_user_can_view_dreams_index(): void
    {
        $response = $this->actingAs($this->user)->get('/dreams');

        $response->assertStatus(200);
        $response->assertSee('Dreams');
    }

    public function test_user_can_view_create_dream_page(): void
    {
        $response = $this->actingAs($this->user)->get('/dreams/create');

        $response->assertStatus(200);
    }

    public function test_user_can_create_dream(): void
    {
        $response = $this->actingAs($this->user)->post('/dreams', [
            'title' => 'Start my own business',
            'category_id' => $this->category->id,
            'description' => 'Build a successful company',
            'priority' => 'high',
        ]);

        $response->assertRedirect('/dreams');
        $this->assertDatabaseHas('dreams', [
            'user_id' => $this->user->id,
            'title' => 'Start my own business',
        ]);
    }

    public function test_dream_requires_title(): void
    {
        $response = $this->actingAs($this->user)->post('/dreams', [
            'title' => '',
            'category_id' => $this->category->id,
        ]);

        $response->assertSessionHasErrors('title');
    }

    public function test_user_can_view_dream(): void
    {
        $dream = Dream::factory()->create([
            'user_id' => $this->user->id,
            'category_id' => $this->category->id,
        ]);

        $response = $this->actingAs($this->user)->get("/dreams/{$dream->id}");

        $response->assertStatus(200);
        $response->assertSee($dream->title);
    }

    public function test_user_can_update_dream(): void
    {
        $dream = Dream::factory()->create([
            'user_id' => $this->user->id,
            'category_id' => $this->category->id,
        ]);

        $response = $this->actingAs($this->user)->put("/dreams/{$dream->id}", [
            'title' => 'Updated Dream Title',
            'category_id' => $this->category->id,
            'priority' => 'medium',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('dreams', [
            'id' => $dream->id,
            'title' => 'Updated Dream Title',
        ]);
    }

    public function test_user_can_delete_dream(): void
    {
        $dream = Dream::factory()->create([
            'user_id' => $this->user->id,
            'category_id' => $this->category->id,
        ]);

        $response = $this->actingAs($this->user)->delete("/dreams/{$dream->id}");

        $response->assertRedirect();
        $this->assertDatabaseMissing('dreams', ['id' => $dream->id]);
    }

    public function test_user_can_manifest_dream(): void
    {
        $dream = Dream::factory()->create([
            'user_id' => $this->user->id,
            'category_id' => $this->category->id,
            'manifested_at' => null,
            'status' => 'active',
            'xp_reward' => 200,
        ]);

        $response = $this->actingAs($this->user)->post("/dreams/{$dream->id}/manifest");

        $response->assertRedirect();
        $this->assertNotNull($dream->fresh()->manifested_at);
        $this->assertEquals('manifested', $dream->fresh()->status);
    }

    public function test_user_can_add_journal_entry(): void
    {
        $dream = Dream::factory()->create([
            'user_id' => $this->user->id,
            'category_id' => $this->category->id,
        ]);

        $response = $this->actingAs($this->user)->post("/dreams/{$dream->id}/journal", [
            'content' => 'Today I made progress on my dream...',
            'mood' => 'positive',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('dream_journal_entries', [
            'dream_id' => $dream->id,
            'content' => 'Today I made progress on my dream...',
        ]);
    }

    public function test_user_cannot_view_other_users_dream(): void
    {
        $otherUser = User::factory()->create();
        $dream = Dream::factory()->create([
            'user_id' => $otherUser->id,
            'category_id' => $this->category->id,
        ]);

        $response = $this->actingAs($this->user)->get("/dreams/{$dream->id}");

        $response->assertStatus(403);
    }

    public function test_dreams_display_by_status(): void
    {
        Dream::factory()->create([
            'user_id' => $this->user->id,
            'category_id' => $this->category->id,
            'status' => 'active',
            'title' => 'Active Dream',
        ]);

        Dream::factory()->manifested()->create([
            'user_id' => $this->user->id,
            'category_id' => $this->category->id,
            'title' => 'Manifested Dream',
        ]);

        $response = $this->actingAs($this->user)->get('/dreams');

        $response->assertSee('Active Dream');
        $response->assertSee('Manifested Dream');
    }
}
