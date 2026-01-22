<?php

namespace Tests\Feature;

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

        Level::factory()->create(['level_number' => 1, 'title' => 'Dreamer', 'xp_required' => 0]);
        $this->user = User::factory()->create();
    }

    public function test_guest_cannot_access_habits(): void
    {
        $response = $this->get('/habits');

        $response->assertRedirect('/login');
    }

    public function test_user_can_view_habits_index(): void
    {
        $response = $this->actingAs($this->user)->get('/habits');

        $response->assertStatus(200);
        $response->assertSee('Habit Tracker');
    }

    public function test_user_can_view_create_habit_page(): void
    {
        $response = $this->actingAs($this->user)->get('/habits/create');

        $response->assertStatus(200);
    }

    public function test_user_can_create_habit(): void
    {
        $response = $this->actingAs($this->user)->post('/habits', [
            'name' => 'Drink water',
            'icon' => '💧',
            'color' => '#1CB0F6',
            'frequency' => 'daily',
            'target_count' => 8,
        ]);

        $response->assertRedirect('/habits');
        $this->assertDatabaseHas('habits', [
            'user_id' => $this->user->id,
            'name' => 'Drink water',
            'target_count' => 8,
        ]);
    }

    public function test_habit_requires_name(): void
    {
        $response = $this->actingAs($this->user)->post('/habits', [
            'name' => '',
            'frequency' => 'daily',
            'target_count' => 1,
        ]);

        $response->assertSessionHasErrors('name');
    }

    public function test_user_can_update_habit(): void
    {
        $habit = Habit::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $response = $this->actingAs($this->user)->put("/habits/{$habit->id}", [
            'name' => 'Updated Habit Name',
            'icon' => '🏃',
            'color' => '#58CC02',
            'frequency' => 'daily',
            'target_count' => 3,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('habits', [
            'id' => $habit->id,
            'name' => 'Updated Habit Name',
        ]);
    }

    public function test_user_can_delete_habit(): void
    {
        $habit = Habit::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $response = $this->actingAs($this->user)->delete("/habits/{$habit->id}");

        $response->assertRedirect();
        $this->assertDatabaseMissing('habits', ['id' => $habit->id]);
    }

    public function test_user_can_log_habit(): void
    {
        $habit = Habit::factory()->create([
            'user_id' => $this->user->id,
            'target_count' => 3,
            'xp_per_completion' => 10,
        ]);

        $response = $this->actingAs($this->user)->post("/habits/{$habit->id}/log");

        $response->assertRedirect();
        $log = HabitLog::where('habit_id', $habit->id)->first();
        $this->assertNotNull($log);
        $this->assertEquals(1, $log->count);
    }

    public function test_logging_habit_increments_count(): void
    {
        $habit = Habit::factory()->create([
            'user_id' => $this->user->id,
            'target_count' => 5,
        ]);

        HabitLog::create([
            'habit_id' => $habit->id,
            'log_date' => today(),
            'count' => 2,
        ]);

        $this->actingAs($this->user)->post("/habits/{$habit->id}/log");

        $log = HabitLog::where('habit_id', $habit->id)->first();
        $this->assertEquals(3, $log->count);
    }

    public function test_completing_habit_awards_xp(): void
    {
        $habit = Habit::factory()->create([
            'user_id' => $this->user->id,
            'target_count' => 1,
            'xp_per_completion' => 15,
        ]);

        $initialXp = $this->user->total_xp;

        $this->actingAs($this->user)->post("/habits/{$habit->id}/log");

        $this->assertEquals($initialXp + 15, $this->user->fresh()->total_xp);
    }

    public function test_habit_streak_calculation(): void
    {
        $habit = Habit::factory()->create([
            'user_id' => $this->user->id,
            'target_count' => 1,
        ]);

        // Create logs for the past 3 days
        for ($i = 2; $i >= 0; $i--) {
            HabitLog::create([
                'habit_id' => $habit->id,
                'log_date' => now()->subDays($i)->toDateString(),
                'count' => 1,
            ]);
        }

        $this->assertEquals(3, $habit->streak);
    }

    public function test_user_cannot_modify_other_users_habits(): void
    {
        $otherUser = User::factory()->create();
        $habit = Habit::factory()->create([
            'user_id' => $otherUser->id,
        ]);

        $response = $this->actingAs($this->user)->put("/habits/{$habit->id}", [
            'name' => 'Hacked',
            'frequency' => 'daily',
            'target_count' => 1,
        ]);

        $response->assertStatus(403);
    }

    public function test_inactive_habits_display_correctly(): void
    {
        Habit::factory()->create([
            'user_id' => $this->user->id,
            'name' => 'Active Habit',
            'is_active' => true,
        ]);

        Habit::factory()->inactive()->create([
            'user_id' => $this->user->id,
            'name' => 'Inactive Habit',
        ]);

        $response = $this->actingAs($this->user)->get('/habits');

        $response->assertSee('Active Habit');
        $response->assertSee('Inactive Habit');
        $response->assertSee('Inactive');
    }

    public function test_habit_shows_week_view(): void
    {
        $habit = Habit::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $response = $this->actingAs($this->user)->get('/habits');

        $response->assertSee('Last 7 days');
    }
}
