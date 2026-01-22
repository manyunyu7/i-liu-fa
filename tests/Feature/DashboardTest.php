<?php

namespace Tests\Feature;

use App\Models\Level;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Level::factory()->create(['level_number' => 1, 'title' => 'Dreamer', 'xp_required' => 0]);
    }

    public function test_guest_cannot_access_dashboard(): void
    {
        $response = $this->get('/dashboard');

        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_can_access_dashboard(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
    }

    public function test_dashboard_displays_user_stats(): void
    {
        $user = User::factory()->create([
            'total_xp' => 150,
            'current_streak' => 5,
            'level' => 1,
        ]);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSee('150');
        $response->assertSee('5');
    }

    public function test_dashboard_shows_greeting(): void
    {
        $user = User::factory()->create(['name' => 'John']);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertSee('John');
    }
}
