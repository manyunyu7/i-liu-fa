<?php

namespace Tests\Feature;

use App\Models\Level;
use App\Models\Reflection;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReflectionTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        Level::factory()->create(['level_number' => 1, 'title' => 'Dreamer', 'xp_required' => 0]);
        $this->user = User::factory()->create();
    }

    public function test_guest_cannot_access_reflections(): void
    {
        $response = $this->get('/reflections');

        $response->assertRedirect('/login');
    }

    public function test_user_can_view_reflections_index(): void
    {
        $response = $this->actingAs($this->user)->get('/reflections');

        $response->assertStatus(200);
        $response->assertSee('Daily Reflections');
    }

    public function test_user_can_view_create_page(): void
    {
        $response = $this->actingAs($this->user)->get('/reflections/create?type=gratitude');

        $response->assertStatus(200);
        $response->assertSee('Gratitude');
    }

    public function test_user_can_create_morning_reflection(): void
    {
        $response = $this->actingAs($this->user)->post('/reflections', [
            'type' => 'morning',
            'reflection_date' => today()->format('Y-m-d'),
            'mood' => 'happy',
            'mood_score' => 8,
            'intentions' => 'Be productive today',
        ]);

        $response->assertRedirect(route('reflections.index'));
        $this->assertDatabaseHas('reflections', [
            'user_id' => $this->user->id,
            'type' => 'morning',
            'mood' => 'happy',
        ]);
    }

    public function test_user_can_create_gratitude_reflection(): void
    {
        $response = $this->actingAs($this->user)->post('/reflections', [
            'type' => 'gratitude',
            'reflection_date' => today()->format('Y-m-d'),
            'mood' => 'grateful',
            'gratitude_items' => ['My family', 'Good health', 'This opportunity'],
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('reflections', [
            'user_id' => $this->user->id,
            'type' => 'gratitude',
        ]);
    }

    public function test_reflection_awards_xp(): void
    {
        $initialXp = $this->user->total_xp;

        $this->actingAs($this->user)->post('/reflections', [
            'type' => 'evening',
            'reflection_date' => today()->format('Y-m-d'),
            'mood' => 'calm',
            'highlights' => 'Had a great day',
        ]);

        $this->assertGreaterThan($initialXp, $this->user->fresh()->total_xp);
    }

    public function test_user_can_view_reflection(): void
    {
        $reflection = Reflection::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $response = $this->actingAs($this->user)->get("/reflections/{$reflection->id}");

        $response->assertStatus(200);
    }

    public function test_user_cannot_view_other_users_reflection(): void
    {
        $otherUser = User::factory()->create();
        $reflection = Reflection::factory()->create([
            'user_id' => $otherUser->id,
        ]);

        $response = $this->actingAs($this->user)->get("/reflections/{$reflection->id}");

        $response->assertStatus(403);
    }

    public function test_user_can_update_reflection(): void
    {
        $reflection = Reflection::factory()->create([
            'user_id' => $this->user->id,
            'mood' => 'happy',
        ]);

        $response = $this->actingAs($this->user)->put("/reflections/{$reflection->id}", [
            'mood' => 'grateful',
            'mood_score' => 9,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('reflections', [
            'id' => $reflection->id,
            'mood' => 'grateful',
        ]);
    }

    public function test_user_can_delete_reflection(): void
    {
        $reflection = Reflection::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $response = $this->actingAs($this->user)->delete("/reflections/{$reflection->id}");

        $response->assertRedirect(route('reflections.index'));
        $this->assertDatabaseMissing('reflections', ['id' => $reflection->id]);
    }

    public function test_redirects_to_edit_for_existing_reflection(): void
    {
        // Create existing reflection
        $existing = Reflection::factory()->create([
            'user_id' => $this->user->id,
            'reflection_date' => today(),
            'type' => 'morning',
        ]);

        // Try to create another morning reflection for same date
        $response = $this->actingAs($this->user)->post('/reflections', [
            'type' => 'morning',
            'reflection_date' => today()->format('Y-m-d'),
            'mood' => 'happy',
        ]);

        // Should redirect to edit page of existing reflection
        $response->assertRedirect(route('reflections.edit', $existing));
    }
}
