<?php

namespace Tests\Feature;

use App\Models\Level;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StatsTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        Level::factory()->create(['level_number' => 1, 'title' => 'Dreamer', 'xp_required' => 0]);
        $this->user = User::factory()->create([
            'total_xp' => 500,
            'level' => 1,
            'current_streak' => 5,
            'longest_streak' => 10,
            'gems' => 100,
        ]);
    }

    public function test_guest_cannot_access_stats(): void
    {
        $response = $this->get('/stats');

        $response->assertRedirect('/login');
    }

    public function test_user_can_view_stats_page(): void
    {
        $response = $this->actingAs($this->user)->get('/stats');

        $response->assertStatus(200);
        $response->assertSee('Your Progress');
    }

    public function test_stats_display_overall_metrics(): void
    {
        $response = $this->actingAs($this->user)->get('/stats');

        $response->assertStatus(200);
        $response->assertSee('Total XP');
        $response->assertSee('Level');
        $response->assertSee('Current Streak');
    }

    public function test_stats_can_filter_by_period(): void
    {
        $response = $this->actingAs($this->user)->get('/stats?period=7');

        $response->assertStatus(200);
        $response->assertSee('Last 7 days');
    }

    public function test_stats_show_activity_breakdown(): void
    {
        $response = $this->actingAs($this->user)->get('/stats');

        $response->assertStatus(200);
        $response->assertSee('Recent Activity');
    }

    public function test_stats_show_xp_breakdown(): void
    {
        $response = $this->actingAs($this->user)->get('/stats');

        $response->assertStatus(200);
        $response->assertSee('XP Breakdown');
    }

    public function test_stats_show_weekly_activity(): void
    {
        $response = $this->actingAs($this->user)->get('/stats');

        $response->assertStatus(200);
        $response->assertSee('This Week', false);
    }

    public function test_stats_show_achievement_progress(): void
    {
        $response = $this->actingAs($this->user)->get('/stats');

        $response->assertStatus(200);
        $response->assertSee('Achievements');
    }
}
