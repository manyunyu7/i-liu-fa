<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WelcomePageTest extends TestCase
{
    use RefreshDatabase;

    public function test_welcome_page_is_accessible(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_welcome_page_contains_app_name(): void
    {
        $response = $this->get('/');

        $response->assertSee('DuoManifest');
    }

    public function test_welcome_page_has_login_link(): void
    {
        $response = $this->get('/');

        $response->assertSee('Log in');
    }

    public function test_welcome_page_has_register_link(): void
    {
        $response = $this->get('/');

        $response->assertSee('Get Started');
    }

    public function test_welcome_page_displays_features(): void
    {
        $response = $this->get('/');

        $response->assertSee('Daily Affirmations');
        $response->assertSee('Bucket List Tracker');
        $response->assertSee('Dream Manifestation');
        $response->assertSee('Daily Planner');
        $response->assertSee('Habit Tracker');
        $response->assertSee('Gamification');
    }
}
