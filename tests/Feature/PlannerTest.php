<?php

namespace Tests\Feature;

use App\Models\Level;
use App\Models\PlannerTask;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PlannerTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        Level::factory()->create(['level_number' => 1, 'title' => 'Dreamer', 'xp_required' => 0]);
        $this->user = User::factory()->create();
    }

    public function test_guest_cannot_access_planner(): void
    {
        $response = $this->get('/planner');

        $response->assertRedirect('/login');
    }

    public function test_user_can_view_planner_index(): void
    {
        $response = $this->actingAs($this->user)->get('/planner');

        $response->assertStatus(200);
        $response->assertSee('Planner');
    }

    public function test_user_can_view_create_task_page(): void
    {
        $response = $this->actingAs($this->user)->get('/planner/create');

        $response->assertStatus(200);
    }

    public function test_user_can_create_task(): void
    {
        $response = $this->actingAs($this->user)->post('/planner', [
            'title' => 'Morning meditation',
            'task_type' => 'intention',
            'task_date' => now()->toDateString(),
            'priority' => 'high',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('planner_tasks', [
            'user_id' => $this->user->id,
            'title' => 'Morning meditation',
        ]);
    }

    public function test_task_requires_title(): void
    {
        $response = $this->actingAs($this->user)->post('/planner', [
            'title' => '',
            'task_type' => 'task',
            'task_date' => now()->toDateString(),
        ]);

        $response->assertSessionHasErrors('title');
    }

    public function test_user_can_update_task(): void
    {
        $task = PlannerTask::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $response = $this->actingAs($this->user)->put("/planner/{$task->id}", [
            'title' => 'Updated Task Title',
            'task_type' => 'goal',
            'task_date' => now()->toDateString(),
            'priority' => 'low',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('planner_tasks', [
            'id' => $task->id,
            'title' => 'Updated Task Title',
        ]);
    }

    public function test_user_can_delete_task(): void
    {
        $task = PlannerTask::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $response = $this->actingAs($this->user)->delete("/planner/{$task->id}");

        $response->assertRedirect();
        $this->assertDatabaseMissing('planner_tasks', ['id' => $task->id]);
    }

    public function test_user_can_toggle_task_completion(): void
    {
        $task = PlannerTask::factory()->create([
            'user_id' => $this->user->id,
            'is_completed' => false,
            'xp_reward' => 10,
        ]);

        $response = $this->actingAs($this->user)->post("/planner/{$task->id}/toggle");

        $response->assertRedirect();
        $this->assertTrue($task->fresh()->is_completed);
    }

    public function test_completing_task_awards_xp(): void
    {
        $task = PlannerTask::factory()->create([
            'user_id' => $this->user->id,
            'is_completed' => false,
            'xp_reward' => 15,
        ]);

        $initialXp = $this->user->total_xp;

        $this->actingAs($this->user)->post("/planner/{$task->id}/toggle");

        $this->assertEquals($initialXp + 15, $this->user->fresh()->total_xp);
    }

    public function test_uncompleting_task_does_not_remove_xp(): void
    {
        $task = PlannerTask::factory()->completed()->create([
            'user_id' => $this->user->id,
            'xp_reward' => 10,
        ]);

        $this->user->update(['total_xp' => 100]);

        $this->actingAs($this->user)->post("/planner/{$task->id}/toggle");

        $this->assertEquals(100, $this->user->fresh()->total_xp);
        $this->assertFalse($task->fresh()->is_completed);
    }

    public function test_user_cannot_view_other_users_tasks(): void
    {
        $otherUser = User::factory()->create();
        $task = PlannerTask::factory()->create([
            'user_id' => $otherUser->id,
        ]);

        $response = $this->actingAs($this->user)->get("/planner/{$task->id}/edit");

        $response->assertStatus(403);
    }

    public function test_planner_filters_by_date(): void
    {
        PlannerTask::factory()->create([
            'user_id' => $this->user->id,
            'task_date' => now()->toDateString(),
            'title' => 'Today Task',
        ]);

        PlannerTask::factory()->create([
            'user_id' => $this->user->id,
            'task_date' => now()->addDay()->toDateString(),
            'title' => 'Tomorrow Task',
        ]);

        $response = $this->actingAs($this->user)->get('/planner?date=' . now()->toDateString());

        $response->assertSee('Today Task');
    }

    public function test_planner_displays_task_types(): void
    {
        PlannerTask::factory()->intention()->create([
            'user_id' => $this->user->id,
            'task_date' => today(),
        ]);

        PlannerTask::factory()->goal()->create([
            'user_id' => $this->user->id,
            'task_date' => today(),
        ]);

        $response = $this->actingAs($this->user)->get('/planner');

        // View shows task_type as badges (ucfirst)
        $response->assertSee('Intention');
        $response->assertSee('Goal');
    }
}
