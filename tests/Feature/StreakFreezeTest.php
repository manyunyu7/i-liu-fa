<?php

namespace Tests\Feature;

use App\Models\Level;
use App\Models\Reward;
use App\Models\StreakFreeze;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StreakFreezeTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        Level::factory()->create(['level_number' => 1, 'title' => 'Dreamer', 'xp_required' => 0]);
        $this->user = User::factory()->create([
            'gems' => 500,
            'streak_freezes_available' => 2,
        ]);
    }

    public function test_guest_cannot_access_streak_freezes(): void
    {
        $response = $this->get('/streak-freeze');

        $response->assertRedirect('/login');
    }

    public function test_user_can_view_streak_freeze_index(): void
    {
        $response = $this->actingAs($this->user)->get('/streak-freeze');

        $response->assertStatus(200);
        $response->assertSee('Streak Freezes');
    }

    public function test_user_can_use_streak_freeze(): void
    {
        $response = $this->actingAs($this->user)->post('/streak-freeze/use');

        $response->assertRedirect();
        $this->assertEquals(1, $this->user->fresh()->streak_freezes_available);
        $this->assertDatabaseHas('streak_freezes', [
            'user_id' => $this->user->id,
            'is_used' => true,
        ]);
    }

    public function test_user_cannot_use_streak_freeze_without_available(): void
    {
        $this->user->update(['streak_freezes_available' => 0]);

        $response = $this->actingAs($this->user)->post('/streak-freeze/use');

        $response->assertRedirect();
        $response->assertSessionHas('error');
    }

    public function test_user_cannot_use_multiple_freezes_same_day(): void
    {
        $this->actingAs($this->user)->post('/streak-freeze/use');
        $response = $this->actingAs($this->user)->post('/streak-freeze/use');

        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertEquals(1, $this->user->fresh()->streak_freezes_available);
    }

    public function test_user_can_purchase_streak_freeze(): void
    {
        Reward::factory()->create([
            'name' => 'Streak Freeze',
            'slug' => 'streak-freeze',
            'type' => 'streak_freeze',
            'cost_gems' => 200,
            'is_active' => true,
        ]);

        $initialFreezes = $this->user->streak_freezes_available;

        $response = $this->actingAs($this->user)->post('/streak-freeze/purchase');

        $response->assertRedirect();
        $this->assertEquals($initialFreezes + 1, $this->user->fresh()->streak_freezes_available);
        $this->assertEquals(300, $this->user->fresh()->gems);
    }

    public function test_user_cannot_purchase_without_enough_gems(): void
    {
        Reward::factory()->create([
            'name' => 'Streak Freeze',
            'slug' => 'streak-freeze',
            'type' => 'streak_freeze',
            'cost_gems' => 200,
            'is_active' => true,
        ]);

        $this->user->update(['gems' => 100]);

        $response = $this->actingAs($this->user)->post('/streak-freeze/purchase');

        $response->assertRedirect();
        $response->assertSessionHas('error');
    }

    public function test_streak_freeze_history_displays(): void
    {
        StreakFreeze::factory()->create([
            'user_id' => $this->user->id,
            'freeze_date' => today()->subDay(),
        ]);

        $response = $this->actingAs($this->user)->get('/streak-freeze');

        $response->assertStatus(200);
        $response->assertSee('Freeze History');
    }
}
