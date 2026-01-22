<?php

namespace Tests\Feature;

use App\Models\Level;
use App\Models\Reward;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RewardTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Reward $reward;

    protected function setUp(): void
    {
        parent::setUp();

        Level::factory()->create(['level_number' => 1, 'title' => 'Dreamer', 'xp_required' => 0]);
        $this->user = User::factory()->create(['gems' => 500]);
        $this->reward = Reward::factory()->create([
            'name' => 'Test Reward',
            'slug' => 'test-reward',
            'type' => 'xp_boost',
            'cost_gems' => 100,
            'metadata' => ['amount' => 50],
        ]);
    }

    public function test_guest_cannot_access_rewards(): void
    {
        $response = $this->get('/rewards');

        $response->assertRedirect('/login');
    }

    public function test_user_can_view_rewards_index(): void
    {
        $response = $this->actingAs($this->user)->get('/rewards');

        $response->assertStatus(200);
        $response->assertSee('Rewards Shop');
    }

    public function test_user_can_purchase_reward(): void
    {
        $response = $this->actingAs($this->user)->post("/rewards/{$this->reward->id}/purchase");

        $response->assertRedirect();
        $this->assertEquals(400, $this->user->fresh()->gems);
    }

    public function test_user_cannot_purchase_without_enough_gems(): void
    {
        $this->user->update(['gems' => 50]);

        $response = $this->actingAs($this->user)->post("/rewards/{$this->reward->id}/purchase");

        $response->assertRedirect();
        $this->assertEquals(50, $this->user->fresh()->gems);
    }

    public function test_user_cannot_purchase_inactive_reward(): void
    {
        $this->reward->update(['is_active' => false]);

        $response = $this->actingAs($this->user)->post("/rewards/{$this->reward->id}/purchase");

        $response->assertRedirect();
        $this->assertEquals(500, $this->user->fresh()->gems);
    }

    public function test_xp_boost_reward_adds_xp(): void
    {
        $initialXp = $this->user->total_xp;

        $this->actingAs($this->user)->post("/rewards/{$this->reward->id}/purchase");

        $this->assertGreaterThan($initialXp, $this->user->fresh()->total_xp);
    }

    public function test_streak_freeze_reward_adds_freeze(): void
    {
        $streakFreezeReward = Reward::factory()->create([
            'name' => 'Streak Freeze',
            'slug' => 'streak-freeze',
            'type' => 'streak_freeze',
            'cost_gems' => 100,
        ]);

        $initialFreezes = $this->user->streak_freezes_available;

        $this->actingAs($this->user)->post("/rewards/{$streakFreezeReward->id}/purchase");

        $this->assertEquals($initialFreezes + 1, $this->user->fresh()->streak_freezes_available);
    }

    public function test_rewards_display_correctly(): void
    {
        $response = $this->actingAs($this->user)->get('/rewards');

        $response->assertSee($this->reward->name);
        $response->assertSee((string) $this->reward->cost_gems);
    }
}
