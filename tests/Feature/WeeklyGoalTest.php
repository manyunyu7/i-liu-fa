<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\WeeklyGoal;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WeeklyGoalTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_weekly_goals(): void
    {
        $response = $this->get('/weekly-goals');
        $response->assertRedirect('/login');
    }

    public function test_user_can_view_weekly_goals_index(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/weekly-goals');

        $response->assertStatus(200);
        $response->assertSee('Weekly Goals');
    }

    public function test_user_can_view_create_page(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/weekly-goals/create');

        $response->assertStatus(200);
        $response->assertSee('New Weekly Goal');
    }

    public function test_user_can_create_weekly_goal(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/weekly-goals', [
            'title' => 'Exercise 4 times',
            'description' => 'Go to the gym or do home workout',
            'category' => 'health',
            'target_count' => 4,
        ]);

        $response->assertRedirect(route('weekly-goals.index'));
        $this->assertDatabaseHas('weekly_goals', [
            'user_id' => $user->id,
            'title' => 'Exercise 4 times',
            'category' => 'health',
            'target_count' => 4,
        ]);
    }

    public function test_weekly_goal_requires_title(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/weekly-goals', [
            'category' => 'health',
            'target_count' => 4,
        ]);

        $response->assertSessionHasErrors('title');
    }

    public function test_user_can_update_weekly_goal(): void
    {
        $user = User::factory()->create();
        $goal = WeeklyGoal::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->put("/weekly-goals/{$goal->id}", [
            'title' => 'Updated title',
            'category' => 'learning',
            'target_count' => 5,
        ]);

        $response->assertRedirect(route('weekly-goals.index'));
        $this->assertDatabaseHas('weekly_goals', [
            'id' => $goal->id,
            'title' => 'Updated title',
        ]);
    }

    public function test_user_can_delete_weekly_goal(): void
    {
        $user = User::factory()->create();
        $goal = WeeklyGoal::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->delete("/weekly-goals/{$goal->id}");

        $response->assertRedirect(route('weekly-goals.index'));
        $this->assertDatabaseMissing('weekly_goals', ['id' => $goal->id]);
    }

    public function test_user_cannot_modify_other_users_goals(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $goal = WeeklyGoal::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->actingAs($user)->put("/weekly-goals/{$goal->id}", [
            'title' => 'Hacked!',
            'category' => 'general',
            'target_count' => 1,
        ]);

        $response->assertForbidden();
    }

    public function test_user_can_increment_progress(): void
    {
        $user = User::factory()->create();
        $goal = WeeklyGoal::factory()->create([
            'user_id' => $user->id,
            'target_count' => 5,
            'current_count' => 2,
        ]);

        $response = $this->actingAs($user)->post("/weekly-goals/{$goal->id}/increment");

        $response->assertRedirect();
        $this->assertEquals(3, $goal->fresh()->current_count);
    }

    public function test_completing_goal_awards_xp(): void
    {
        $user = User::factory()->create(['total_xp' => 0]);
        $goal = WeeklyGoal::factory()->create([
            'user_id' => $user->id,
            'target_count' => 3,
            'current_count' => 2,
            'xp_reward' => 50,
        ]);

        $this->actingAs($user)->post("/weekly-goals/{$goal->id}/increment");

        $user->refresh();
        $goal->refresh();

        $this->assertTrue($goal->is_completed);
        $this->assertEquals(50, $user->total_xp);
    }

    public function test_user_can_decrement_progress(): void
    {
        $user = User::factory()->create();
        $goal = WeeklyGoal::factory()->create([
            'user_id' => $user->id,
            'current_count' => 3,
        ]);

        $response = $this->actingAs($user)->post("/weekly-goals/{$goal->id}/decrement");

        $response->assertRedirect();
        $this->assertEquals(2, $goal->fresh()->current_count);
    }

    public function test_week_navigation_works(): void
    {
        $user = User::factory()->create();
        WeeklyGoal::factory()->create([
            'user_id' => $user->id,
            'week_start_date' => now()->subWeek()->startOfWeek(),
        ]);

        $response = $this->actingAs($user)->get('/weekly-goals?week=-1');

        $response->assertStatus(200);
        $response->assertSee('1 week(s) ago');
    }

    public function test_goals_filtered_by_week(): void
    {
        $user = User::factory()->create();

        $currentWeekGoal = WeeklyGoal::factory()->create([
            'user_id' => $user->id,
            'title' => 'Current Week Goal',
            'week_start_date' => now()->startOfWeek(),
        ]);

        $lastWeekGoal = WeeklyGoal::factory()->create([
            'user_id' => $user->id,
            'title' => 'Last Week Goal',
            'week_start_date' => now()->subWeek()->startOfWeek(),
        ]);

        $response = $this->actingAs($user)->get('/weekly-goals');

        $response->assertSee('Current Week Goal');
        $response->assertDontSee('Last Week Goal');
    }
}
