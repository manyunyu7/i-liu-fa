<?php

namespace Tests\Unit;

use App\Models\BucketListCategory;
use App\Models\BucketListItem;
use App\Models\BucketListMilestone;
use App\Models\Level;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BucketListItemTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected BucketListCategory $category;

    protected function setUp(): void
    {
        parent::setUp();

        Level::factory()->create(['level_number' => 1, 'xp_required' => 0]);
        $this->user = User::factory()->create();
        $this->category = BucketListCategory::factory()->system()->create();
    }

    public function test_can_mark_item_as_completed(): void
    {
        $item = BucketListItem::factory()->create([
            'user_id' => $this->user->id,
            'bucket_list_category_id' => $this->category->id,
            'completed_at' => null,
        ]);

        $item->markAsCompleted();

        $this->assertNotNull($item->fresh()->completed_at);
        $this->assertEquals(100, $item->fresh()->progress);
    }

    public function test_completing_item_awards_xp(): void
    {
        $item = BucketListItem::factory()->create([
            'user_id' => $this->user->id,
            'bucket_list_category_id' => $this->category->id,
            'xp_reward' => 100,
        ]);

        $initialXp = $this->user->total_xp;

        $item->markAsCompleted();

        $this->assertEquals($initialXp + 100, $this->user->fresh()->total_xp);
    }

    public function test_progress_updates_with_milestones(): void
    {
        $item = BucketListItem::factory()->create([
            'user_id' => $this->user->id,
            'bucket_list_category_id' => $this->category->id,
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
            'is_completed' => true,
        ]);

        BucketListMilestone::create([
            'bucket_list_item_id' => $item->id,
            'title' => 'Milestone 3',
            'is_completed' => false,
        ]);

        BucketListMilestone::create([
            'bucket_list_item_id' => $item->id,
            'title' => 'Milestone 4',
            'is_completed' => false,
        ]);

        $item->updateProgress();

        $this->assertEquals(50, $item->fresh()->progress);
    }

    public function test_progress_is_100_when_all_milestones_completed(): void
    {
        $item = BucketListItem::factory()->create([
            'user_id' => $this->user->id,
            'bucket_list_category_id' => $this->category->id,
        ]);

        BucketListMilestone::create([
            'bucket_list_item_id' => $item->id,
            'title' => 'Milestone 1',
            'is_completed' => true,
        ]);

        BucketListMilestone::create([
            'bucket_list_item_id' => $item->id,
            'title' => 'Milestone 2',
            'is_completed' => true,
        ]);

        $item->updateProgress();

        $this->assertEquals(100, $item->fresh()->progress);
    }

    public function test_progress_is_0_when_no_milestones(): void
    {
        $item = BucketListItem::factory()->create([
            'user_id' => $this->user->id,
            'bucket_list_category_id' => $this->category->id,
            'progress' => 50,
        ]);

        $item->updateProgress();

        $this->assertEquals(0, $item->fresh()->progress);
    }
}
