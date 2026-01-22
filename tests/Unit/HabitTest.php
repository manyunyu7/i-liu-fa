<?php

namespace Tests\Unit;

use App\Models\Habit;
use App\Models\HabitLog;
use App\Models\Level;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HabitTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        Level::factory()->create(['level_number' => 1, 'xp_required' => 0]);
        $this->user = User::factory()->create();
    }

    public function test_habit_can_log_activity(): void
    {
        $habit = Habit::factory()->create([
            'user_id' => $this->user->id,
            'target_count' => 3,
        ]);

        $habit->log();

        $log = HabitLog::where('habit_id', $habit->id)->first();
        $this->assertNotNull($log);
        $this->assertEquals(1, $log->count);
        $this->assertTrue($log->log_date->isToday());
    }

    public function test_habit_log_increments_existing_entry(): void
    {
        $habit = Habit::factory()->create([
            'user_id' => $this->user->id,
            'target_count' => 5,
        ]);

        $habit->log();
        $habit->log();
        $habit->log();

        $log = HabitLog::where('habit_id', $habit->id)->first();
        $this->assertNotNull($log);
        $this->assertEquals(3, $log->count);
    }

    public function test_habit_streak_is_zero_with_no_logs(): void
    {
        $habit = Habit::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $this->assertEquals(0, $habit->streak);
    }

    public function test_habit_streak_counts_consecutive_days(): void
    {
        $habit = Habit::factory()->create([
            'user_id' => $this->user->id,
            'target_count' => 1,
        ]);

        // Create logs for 5 consecutive days ending today
        for ($i = 4; $i >= 0; $i--) {
            HabitLog::create([
                'habit_id' => $habit->id,
                'log_date' => now()->subDays($i)->toDateString(),
                'count' => 1,
            ]);
        }

        $this->assertEquals(5, $habit->streak);
    }

    public function test_habit_streak_breaks_on_missed_day(): void
    {
        $habit = Habit::factory()->create([
            'user_id' => $this->user->id,
            'target_count' => 1,
        ]);

        // Day -5, -4, -3 (skip -2), -1, 0
        HabitLog::create(['habit_id' => $habit->id, 'log_date' => now()->subDays(5)->toDateString(), 'count' => 1]);
        HabitLog::create(['habit_id' => $habit->id, 'log_date' => now()->subDays(4)->toDateString(), 'count' => 1]);
        HabitLog::create(['habit_id' => $habit->id, 'log_date' => now()->subDays(3)->toDateString(), 'count' => 1]);
        // Skipping day -2
        HabitLog::create(['habit_id' => $habit->id, 'log_date' => now()->subDays(1)->toDateString(), 'count' => 1]);
        HabitLog::create(['habit_id' => $habit->id, 'log_date' => now()->toDateString(), 'count' => 1]);

        $this->assertEquals(2, $habit->streak);
    }

    public function test_habit_streak_requires_target_completion(): void
    {
        $habit = Habit::factory()->create([
            'user_id' => $this->user->id,
            'target_count' => 3,
        ]);

        // Log with count below target
        HabitLog::create([
            'habit_id' => $habit->id,
            'log_date' => now()->toDateString(),
            'count' => 2,
        ]);

        $this->assertEquals(0, $habit->streak);
    }
}
