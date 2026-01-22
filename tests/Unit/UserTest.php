<?php

namespace Tests\Unit;

use App\Models\Level;
use App\Models\User;
use App\Models\XpTransaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create levels for testing
        Level::factory()->create(['level_number' => 1, 'title' => 'Dreamer', 'xp_required' => 0]);
        Level::factory()->create(['level_number' => 2, 'title' => 'Believer', 'xp_required' => 100]);
        Level::factory()->create(['level_number' => 3, 'title' => 'Achiever', 'xp_required' => 300]);
    }

    public function test_user_can_add_xp(): void
    {
        $user = User::factory()->create(['total_xp' => 0]);

        $user->addXp(50, 'test', null, 'Test XP');

        $this->assertEquals(50, $user->fresh()->total_xp);
        $this->assertDatabaseHas('xp_transactions', [
            'user_id' => $user->id,
            'amount' => 50,
            'source_type' => 'test',
        ]);
    }

    public function test_user_level_updates_when_xp_threshold_reached(): void
    {
        $user = User::factory()->create(['total_xp' => 0, 'level' => 1]);

        $user->addXp(150, 'test');

        $this->assertEquals(2, $user->fresh()->level);
    }

    public function test_user_level_progress_calculation(): void
    {
        $user = User::factory()->create(['total_xp' => 50, 'level' => 1]);

        // Progress should be 50% (50 XP out of 100 needed for level 2)
        $this->assertEquals(50, $user->level_progress);
    }

    public function test_user_xp_to_next_level_calculation(): void
    {
        $user = User::factory()->create(['total_xp' => 50, 'level' => 1]);

        // 100 XP needed for level 2, user has 50, so 50 remaining
        $this->assertEquals(50, $user->xp_to_next_level);
    }

    public function test_user_streak_updates_correctly(): void
    {
        $user = User::factory()->create();

        $streak = $user->updateStreak('affirmation');

        $this->assertEquals(1, $streak->current_count);
        $this->assertTrue($streak->last_activity_date->isToday());
    }

    public function test_user_streak_increments_on_consecutive_days(): void
    {
        $user = User::factory()->create();

        // Simulate yesterday's activity - need to use format that works with the date cast
        $user->streaks()->create([
            'streak_type' => 'affirmation',
            'current_count' => 5,
            'longest_count' => 5,
            'last_activity_date' => now()->subDay(),
        ]);

        $updatedStreak = $user->updateStreak('affirmation');

        $this->assertEquals(6, $updatedStreak->current_count);
    }

    public function test_user_streak_resets_after_missed_day(): void
    {
        $user = User::factory()->create();

        // Simulate activity from 2 days ago (missed yesterday)
        $streak = $user->streaks()->create([
            'streak_type' => 'affirmation',
            'current_count' => 5,
            'longest_count' => 5,
            'last_activity_date' => now()->subDays(2)->toDateString(),
        ]);

        $updatedStreak = $user->updateStreak('affirmation');

        $this->assertEquals(1, $updatedStreak->current_count);
        $this->assertEquals(5, $updatedStreak->longest_count);
    }

    public function test_user_has_relationships(): void
    {
        $user = User::factory()->create();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class, $user->affirmations());
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class, $user->bucketListItems());
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class, $user->dreams());
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class, $user->plannerTasks());
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class, $user->habits());
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class, $user->achievements());
    }
}
