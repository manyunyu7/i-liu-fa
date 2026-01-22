<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PreferencesTest extends TestCase
{
    use RefreshDatabase;

    public function test_preferences_page_requires_authentication(): void
    {
        $response = $this->get('/preferences');
        $response->assertRedirect('/login');
    }

    public function test_user_can_view_preferences_page(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/preferences');

        $response->assertStatus(200);
        $response->assertSee('Settings');
        $response->assertSee('Sound Effects');
        $response->assertSee('Haptic Feedback');
    }

    public function test_user_can_update_preferences(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/preferences', [
            'sound_enabled' => true,
            'haptic_enabled' => false,
            'volume' => 0.8,
            'theme' => 'dark',
            'animations_enabled' => true,
            'daily_reminders' => false,
            'streak_reminders' => true,
            'achievement_notifications' => true,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $user->refresh();
        $this->assertEquals(true, $user->preferences['sound_enabled']);
        $this->assertEquals(false, $user->preferences['haptic_enabled']);
        $this->assertEquals(0.8, $user->preferences['volume']);
        $this->assertEquals('dark', $user->preferences['theme']);
    }

    public function test_preferences_validation(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/preferences', [
            'volume' => 5, // Invalid: must be between 0 and 1
            'theme' => 'invalid_theme', // Invalid: must be light, dark, or auto
        ]);

        $response->assertSessionHasErrors(['volume', 'theme']);
    }

    public function test_preferences_api_endpoint_works(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/api/preferences', [
            'sound_enabled' => false,
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $user->refresh();
        $this->assertEquals(false, $user->preferences['sound_enabled']);
    }

    public function test_preferences_api_merges_with_existing(): void
    {
        $user = User::factory()->create([
            'preferences' => [
                'sound_enabled' => true,
                'volume' => 0.5,
            ],
        ]);

        $response = $this->actingAs($user)->postJson('/api/preferences', [
            'haptic_enabled' => true,
        ]);

        $response->assertStatus(200);

        $user->refresh();
        $this->assertEquals(true, $user->preferences['sound_enabled']);
        $this->assertEquals(0.5, $user->preferences['volume']);
        $this->assertEquals(true, $user->preferences['haptic_enabled']);
    }

    public function test_default_preferences_are_applied(): void
    {
        $user = User::factory()->create(['preferences' => null]);

        $response = $this->actingAs($user)->get('/preferences');

        $response->assertStatus(200);
        // Defaults should be applied when viewing
    }

    public function test_preferences_update_returns_json_when_requested(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->postJson('/preferences', [
                'sound_enabled' => true,
                'theme' => 'light',
            ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
    }
}
