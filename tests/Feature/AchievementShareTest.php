<?php

namespace Tests\Feature;

use App\Models\Achievement;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AchievementShareTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_individual_achievement(): void
    {
        $user = User::factory()->create();
        $achievement = Achievement::factory()->create();

        $response = $this->actingAs($user)->get("/achievements/{$achievement->id}");

        $response->assertStatus(200);
        $response->assertSee($achievement->name);
    }

    public function test_achievement_shows_locked_status_for_unearned(): void
    {
        $user = User::factory()->create();
        $achievement = Achievement::factory()->create();

        $response = $this->actingAs($user)->get("/achievements/{$achievement->id}");

        $response->assertStatus(200);
        $response->assertSee('Locked');
    }

    public function test_achievement_shows_unlocked_status_for_earned(): void
    {
        $user = User::factory()->create();
        $achievement = Achievement::factory()->create([
            'requirement_type' => 'level',
            'requirement_value' => 1,
        ]);

        // Unlock the achievement
        $user->achievements()->create([
            'achievement_id' => $achievement->id,
            'unlocked_at' => now(),
        ]);

        $response = $this->actingAs($user)->get("/achievements/{$achievement->id}");

        $response->assertStatus(200);
        $response->assertSee('Unlocked');
    }

    public function test_share_button_visible_for_unlocked_achievement(): void
    {
        $user = User::factory()->create();
        $achievement = Achievement::factory()->create();

        $user->achievements()->create([
            'achievement_id' => $achievement->id,
            'unlocked_at' => now(),
        ]);

        $response = $this->actingAs($user)->get("/achievements/{$achievement->id}");

        $response->assertStatus(200);
        $response->assertSee('Share');
    }

    public function test_public_share_card_accessible(): void
    {
        $user = User::factory()->create();
        $achievement = Achievement::factory()->create();

        $user->achievements()->create([
            'achievement_id' => $achievement->id,
            'unlocked_at' => now(),
        ]);

        $response = $this->get("/share/achievement/{$achievement->id}/{$user->id}");

        $response->assertStatus(200);
        $response->assertSee($achievement->name);
        $response->assertSee($user->name);
        $response->assertSee('Achievement Unlocked');
    }

    public function test_share_card_404_for_locked_achievement(): void
    {
        $user = User::factory()->create();
        $achievement = Achievement::factory()->create();

        $response = $this->get("/share/achievement/{$achievement->id}/{$user->id}");

        $response->assertStatus(404);
    }

    public function test_share_card_has_social_meta_tags(): void
    {
        $user = User::factory()->create();
        $achievement = Achievement::factory()->create();

        $user->achievements()->create([
            'achievement_id' => $achievement->id,
            'unlocked_at' => now(),
        ]);

        $response = $this->get("/share/achievement/{$achievement->id}/{$user->id}");

        $response->assertStatus(200);
        $response->assertSee('og:title');
        $response->assertSee('og:description');
        $response->assertSee('twitter:card');
    }

    public function test_achievement_index_links_to_individual_achievements(): void
    {
        $user = User::factory()->create();
        $achievement = Achievement::factory()->create();

        $response = $this->actingAs($user)->get('/achievements');

        $response->assertStatus(200);
        $response->assertSee(route('achievements.show', $achievement));
    }
}
