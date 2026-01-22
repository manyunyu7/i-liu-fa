<?php

namespace Tests\Unit;

use App\Models\Level;
use App\Models\PlannerTask;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PlannerTaskTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        Level::factory()->create(['level_number' => 1, 'xp_required' => 0]);
        $this->user = User::factory()->create();
    }

    public function test_can_complete_task(): void
    {
        $task = PlannerTask::factory()->create([
            'user_id' => $this->user->id,
            'is_completed' => false,
        ]);

        $task->complete();

        $this->assertTrue($task->fresh()->is_completed);
        $this->assertNotNull($task->fresh()->completed_at);
    }

    public function test_completing_task_awards_xp(): void
    {
        $task = PlannerTask::factory()->create([
            'user_id' => $this->user->id,
            'xp_reward' => 15,
        ]);

        $initialXp = $this->user->total_xp;

        $task->complete();

        $this->assertEquals($initialXp + 15, $this->user->fresh()->total_xp);
    }

    public function test_can_uncomplete_task(): void
    {
        $task = PlannerTask::factory()->completed()->create([
            'user_id' => $this->user->id,
        ]);

        $task->uncomplete();

        $this->assertFalse($task->fresh()->is_completed);
        $this->assertNull($task->fresh()->completed_at);
    }

    public function test_task_belongs_to_user(): void
    {
        $task = PlannerTask::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $this->assertInstanceOf(User::class, $task->user);
        $this->assertEquals($this->user->id, $task->user->id);
    }

    public function test_task_scoped_by_date(): void
    {
        PlannerTask::factory()->create([
            'user_id' => $this->user->id,
            'task_date' => today(),
        ]);

        PlannerTask::factory()->create([
            'user_id' => $this->user->id,
            'task_date' => today()->addDay(),
        ]);

        $todayTasks = PlannerTask::whereDate('task_date', today())->count();
        $tomorrowTasks = PlannerTask::whereDate('task_date', today()->addDay())->count();

        $this->assertEquals(1, $todayTasks);
        $this->assertEquals(1, $tomorrowTasks);
    }
}
