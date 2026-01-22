<?php

namespace Tests\Feature;

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

        Level::factory()->create(['level_number' => 1, 'title' => 'Dreamer', 'xp_required' => 0]);
        $this->user = User::factory()->create();
    }

    public function test_guest_cannot_access_achievements(): void
    {
        $response = $this->get('/achievements');

        $response->assertRedirect('/login');
    }

    public function test_user_can_view_achievements_index(): void
    {
        $response = $this->actingAs($this->user)->get('/achievements');

        $response->assertStatus(200);
        $response->assertSee('Achievements');
    }

    public function test_achievements_display_locked_and_unlocked(): void
    {
        $unlockedAchievement = Achievement::factory()->create([
            'name' => 'First Steps',
            'category' => 'milestone',
        ]);

        $lockedAchievement = Achievement::factory()->create([
            'name' => 'Grand Master',
            'category' => 'milestone',
        ]);

        UserAchievement::create([
            'user_id' => $this->user->id,
            'achievement_id' => $unlockedAchievement->id,
            'unlocked_at' => now(),
        ]);

        $response = $this->actingAs($this->user)->get('/achievements');

        $response->assertSee('First Steps');
        $response->assertSee('Grand Master');
    }

    public function test_achievements_grouped_by_category(): void
    {
        Achievement::factory()->streak()->create(['name' => 'Streak Achievement']);
        Achievement::factory()->create(['name' => 'Completion Achievement', 'category' => 'completion']);

        $response = $this->actingAs($this->user)->get('/achievements');

        $response->assertSee('Streak Achievements');
        $response->assertSee('Completion Achievements');
    }

    public function test_achievement_shows_progress(): void
    {
        Achievement::factory()->create([
            'name' => 'Get 100 XP',
            'requirement_type' => 'xp_total',
            'requirement_value' => 100,
        ]);

        $this->user->update(['total_xp' => 50]);

        $response = $this->actingAs($this->user)->get('/achievements');

        $response->assertSee('50%');
    }

    public function test_achievement_shows_unlocked_date(): void
    {
        $achievement = Achievement::factory()->create([
            'name' => 'Beginner',
        ]);

        UserAchievement::create([
            'user_id' => $this->user->id,
            'achievement_id' => $achievement->id,
            'unlocked_at' => now()->subDays(5),
        ]);

        $response = $this->actingAs($this->user)->get('/achievements');

        $response->assertSee('Unlocked');
    }

    public function test_achievement_displays_xp_reward(): void
    {
        Achievement::factory()->create([
            'name' => 'XP Hunter',
            'xp_reward' => 500,
        ]);

        $response = $this->actingAs($this->user)->get('/achievements');

        $response->assertSee('+500 XP');
    }

    public function test_secret_achievements_hidden_until_unlocked(): void
    {
        $secretAchievement = Achievement::factory()->secret()->create([
            'name' => 'Secret Achievement',
        ]);

        $response = $this->actingAs($this->user)->get('/achievements');

        $response->assertDontSee('Secret Achievement');

        // Unlock the achievement
        UserAchievement::create([
            'user_id' => $this->user->id,
            'achievement_id' => $secretAchievement->id,
            'unlocked_at' => now(),
        ]);

        $response = $this->actingAs($this->user)->get('/achievements');

        $response->assertSee('Secret Achievement');
    }

    public function test_achievement_stats_calculated_correctly(): void
    {
        Achievement::factory()->count(5)->create();

        $achievement = Achievement::first();
        UserAchievement::create([
            'user_id' => $this->user->id,
            'achievement_id' => $achievement->id,
            'unlocked_at' => now(),
        ]);

        $response = $this->actingAs($this->user)->get('/achievements');

        $response->assertSee('1/5');
    }
}
