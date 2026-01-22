<?php

namespace Tests\Feature;

use App\Models\Affirmation;
use App\Models\AffirmationCategory;
use App\Models\Level;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AffirmationTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected AffirmationCategory $category;

    protected function setUp(): void
    {
        parent::setUp();

        Level::factory()->create(['level_number' => 1, 'title' => 'Dreamer', 'xp_required' => 0]);
        $this->user = User::factory()->create();
        $this->category = AffirmationCategory::factory()->create();
    }

    protected function createAffirmation(array $attributes = []): \App\Models\Affirmation
    {
        return Affirmation::factory()->create(array_merge([
            'affirmation_category_id' => $this->category->id,
        ], $attributes));
    }

    public function test_guest_cannot_access_affirmations(): void
    {
        $response = $this->get('/affirmations');

        $response->assertRedirect('/login');
    }

    public function test_user_can_view_affirmations_index(): void
    {
        $response = $this->actingAs($this->user)->get('/affirmations');

        $response->assertStatus(200);
        $response->assertSee('Affirmations');
    }

    public function test_user_can_view_create_affirmation_page(): void
    {
        $response = $this->actingAs($this->user)->get('/affirmations/create');

        $response->assertStatus(200);
        $response->assertSee('Create');
    }

    public function test_user_can_create_affirmation(): void
    {
        $response = $this->actingAs($this->user)->post('/affirmations', [
            'affirmation_category_id' => $this->category->id,
            'content' => 'I am confident and capable',
        ]);

        $response->assertRedirect('/affirmations');
        $this->assertDatabaseHas('affirmations', [
            'user_id' => $this->user->id,
            'content' => 'I am confident and capable',
        ]);
    }

    public function test_affirmation_requires_content(): void
    {
        $response = $this->actingAs($this->user)->post('/affirmations', [
            'affirmation_category_id' => $this->category->id,
            'content' => '',
        ]);

        $response->assertSessionHasErrors('content');
    }

    public function test_user_can_toggle_favorite_affirmation(): void
    {
        $affirmation = Affirmation::factory()->create([
            'user_id' => $this->user->id,
            'affirmation_category_id' => $this->category->id,
            'is_favorite' => false,
        ]);

        $response = $this->actingAs($this->user)->post("/affirmations/{$affirmation->id}/favorite");

        $response->assertRedirect();
        $this->assertTrue($affirmation->fresh()->is_favorite);
    }

    public function test_user_can_practice_affirmations(): void
    {
        Affirmation::factory()->create([
            'affirmation_category_id' => $this->category->id,
            'is_system' => true,
        ]);

        $response = $this->actingAs($this->user)->get("/affirmations/practice/{$this->category->id}");

        $response->assertStatus(200);
    }

    public function test_user_can_complete_affirmation(): void
    {
        $affirmation = Affirmation::factory()->create([
            'affirmation_category_id' => $this->category->id,
            'is_system' => true,
        ]);

        $response = $this->actingAs($this->user)->post("/affirmations/{$affirmation->id}/complete", [
            'duration' => 30,
        ]);

        $response->assertJson(['success' => true]);
        $this->assertDatabaseHas('affirmation_sessions', [
            'user_id' => $this->user->id,
            'affirmation_id' => $affirmation->id,
        ]);
    }

    public function test_user_can_delete_own_affirmation(): void
    {
        $affirmation = Affirmation::factory()->create([
            'user_id' => $this->user->id,
            'affirmation_category_id' => $this->category->id,
        ]);

        $response = $this->actingAs($this->user)->delete("/affirmations/{$affirmation->id}");

        $response->assertRedirect();
        $this->assertDatabaseMissing('affirmations', ['id' => $affirmation->id]);
    }

    public function test_user_cannot_delete_system_affirmation(): void
    {
        $affirmation = Affirmation::factory()->system()->create([
            'affirmation_category_id' => $this->category->id,
        ]);

        $response = $this->actingAs($this->user)->delete("/affirmations/{$affirmation->id}");

        $response->assertStatus(403);
    }

    public function test_affirmations_display_by_category(): void
    {
        $category2 = AffirmationCategory::factory()->create(['name' => 'Health']);
        Affirmation::factory()->create([
            'affirmation_category_id' => $category2->id,
            'is_system' => true,
        ]);

        $response = $this->actingAs($this->user)->get('/affirmations');

        $response->assertSee('Health');
    }
}
