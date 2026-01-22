<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class QuoteFactory extends Factory
{
    public function definition(): array
    {
        $categories = ['general', 'motivation', 'success', 'happiness', 'gratitude', 'mindfulness'];

        return [
            'content' => fake()->sentence(12),
            'author' => fake()->name(),
            'source' => fake()->optional()->words(3, true),
            'category' => fake()->randomElement($categories),
            'is_active' => true,
            'is_featured' => false,
            'likes_count' => fake()->numberBetween(0, 100),
        ];
    }

    public function featured(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_featured' => true,
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }

    public function motivation(): static
    {
        return $this->state(fn (array $attributes) => [
            'category' => 'motivation',
        ]);
    }
}
