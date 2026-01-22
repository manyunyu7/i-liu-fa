<?php

namespace Tests\Feature;

use App\Models\Level;
use App\Models\Quote;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QuoteTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        Level::factory()->create(['level_number' => 1, 'title' => 'Dreamer', 'xp_required' => 0]);
        $this->user = User::factory()->create();
    }

    public function test_guest_cannot_access_quotes(): void
    {
        $response = $this->get('/quotes');

        $response->assertRedirect('/login');
    }

    public function test_user_can_view_quotes_index(): void
    {
        Quote::factory()->count(3)->create();

        $response = $this->actingAs($this->user)->get('/quotes');

        $response->assertStatus(200);
        $response->assertSee('Motivational Quotes');
    }

    public function test_user_can_view_daily_quote(): void
    {
        Quote::factory()->create();

        $response = $this->actingAs($this->user)->get('/quotes/daily');

        $response->assertStatus(200);
        $response->assertSee('Your Daily Inspiration');
    }

    public function test_daily_quote_is_consistent_for_same_day(): void
    {
        Quote::factory()->count(5)->create();

        $this->actingAs($this->user)->get('/quotes/daily');
        $dailyQuote1 = $this->user->dailyQuotes()->whereDate('shown_date', today())->first();

        $this->actingAs($this->user)->get('/quotes/daily');
        $dailyQuote2 = $this->user->dailyQuotes()->whereDate('shown_date', today())->first();

        $this->assertEquals($dailyQuote1->quote_id, $dailyQuote2->quote_id);
    }

    public function test_user_can_view_random_quote(): void
    {
        Quote::factory()->create();

        $response = $this->actingAs($this->user)->get('/quotes/random');

        $response->assertStatus(200);
        $response->assertSee('Random Inspiration');
    }

    public function test_user_can_toggle_favorite(): void
    {
        $quote = Quote::factory()->create();

        $response = $this->actingAs($this->user)->post("/quotes/{$quote->id}/favorite");

        $response->assertRedirect();
        $this->assertTrue($this->user->favoriteQuotes()->where('quote_id', $quote->id)->exists());
    }

    public function test_user_can_unfavorite_quote(): void
    {
        $quote = Quote::factory()->create();
        $this->user->favoriteQuotes()->attach($quote->id);

        $response = $this->actingAs($this->user)->post("/quotes/{$quote->id}/favorite");

        $response->assertRedirect();
        $this->assertFalse($this->user->favoriteQuotes()->where('quote_id', $quote->id)->exists());
    }

    public function test_user_can_view_favorites(): void
    {
        $quote = Quote::factory()->create();
        $this->user->favoriteQuotes()->attach($quote->id);

        $response = $this->actingAs($this->user)->get('/quotes/favorites');

        $response->assertStatus(200);
        $response->assertSee($quote->content);
    }

    public function test_quotes_filter_by_category(): void
    {
        Quote::factory()->create(['category' => 'motivation']);
        Quote::factory()->create(['category' => 'gratitude']);

        $response = $this->actingAs($this->user)->get('/quotes?category=motivation');

        $response->assertStatus(200);
    }

    public function test_favoriting_increments_likes_count(): void
    {
        $quote = Quote::factory()->create(['likes_count' => 5]);

        $this->actingAs($this->user)->post("/quotes/{$quote->id}/favorite");

        $this->assertEquals(6, $quote->fresh()->likes_count);
    }

    public function test_unfavoriting_decrements_likes_count(): void
    {
        $quote = Quote::factory()->create(['likes_count' => 5]);
        $this->user->favoriteQuotes()->attach($quote->id);

        $this->actingAs($this->user)->post("/quotes/{$quote->id}/favorite");

        $this->assertEquals(4, $quote->fresh()->likes_count);
    }
}
