<?php

namespace Tests\Unit;

use App\Models\Achievement;
use App\Models\Level;
use App\Models\User;
use App\Models\UserAchievement;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AchievementTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        Level::factory()->create(['level_number' => 1, 'xp_required' => 0]);
        $this->user = User::factory()->create();
    }

    public function test_can_check_and_unlock_achievement(): void
    {
        $achievement = Achievement::factory()->create([
            'requirement_type' => 'xp_total',
            'requirement_value' => 100,
            'xp_reward' => 50,
        ]);

        $this->user->update(['total_xp' => 150]);

        $unlocked = $achievement->checkAndUnlock($this->user);

        $this->assertTrue($unlocked);
        $this->assertDatabaseHas('user_achievements', [
            'user_id' => $this->user->id,
            'achievement_id' => $achievement->id,
        ]);
    }

    public function test_does_not_unlock_if_requirement_not_met(): void
    {
        $achievement = Achievement::factory()->create([
            'requirement_type' => 'xp_total',
            'requirement_value' => 1000,
        ]);

        $this->user->update(['total_xp' => 50]);

        $unlocked = $achievement->checkAndUnlock($this->user);

        $this->assertFalse($unlocked);
        $this->assertDatabaseMissing('user_achievements', [
            'user_id' => $this->user->id,
            'achievement_id' => $achievement->id,
        ]);
    }

    public function test_does_not_unlock_already_unlocked_achievement(): void
    {
        $achievement = Achievement::factory()->create([
            'requirement_type' => 'xp_total',
            'requirement_value' => 100,
            'xp_reward' => 50,
        ]);

        UserAchievement::create([
            'user_id' => $this->user->id,
            'achievement_id' => $achievement->id,
            'unlocked_at' => now(),
        ]);

        $this->user->update(['total_xp' => 200]);
        $initialXp = $this->user->total_xp;

        $unlocked = $achievement->checkAndUnlock($this->user);

        $this->assertFalse($unlocked);
        // XP should not change since already unlocked
        $this->assertEquals($initialXp, $this->user->fresh()->total_xp);
    }

    public function test_unlocking_achievement_awards_xp(): void
    {
        $achievement = Achievement::factory()->create([
            'requirement_type' => 'xp_total',
            'requirement_value' => 100,
            'xp_reward' => 75,
        ]);

        $this->user->update(['total_xp' => 100]);

        $achievement->checkAndUnlock($this->user);

        $this->assertEquals(175, $this->user->fresh()->total_xp);
    }

    public function test_streak_requirement_type_works(): void
    {
        $achievement = Achievement::factory()->create([
            'requirement_type' => 'streak',
            'requirement_value' => 7,
        ]);

        $this->user->update(['current_streak' => 7]);

        $unlocked = $achievement->checkAndUnlock($this->user);

        $this->assertTrue($unlocked);
    }

    public function test_level_requirement_type_works(): void
    {
        $achievement = Achievement::factory()->create([
            'requirement_type' => 'level',
            'requirement_value' => 5,
        ]);

        $this->user->update(['level' => 5]);

        $unlocked = $achievement->checkAndUnlock($this->user);

        $this->assertTrue($unlocked);
    }
}
