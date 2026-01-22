<?php

namespace Database\Factories;

use App\Models\DreamCategory;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class DreamFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'category_id' => DreamCategory::factory(),
            'title' => fake()->sentence(4),
            'description' => fake()->paragraph(),
            'visualization' => fake()->optional()->paragraph(),
            'target_date' => fake()->optional()->dateTimeBetween('now', '+5 years'),
            'manifested_at' => null,
            'priority' => fake()->randomElement(['low', 'medium', 'high']),
            'status' => 'active',
            'xp_reward' => fake()->randomElement([100, 200, 300, 500]),
        ];
    }

    public function manifested(): static
    {
        return $this->state(fn (array $attributes) => [
            'manifested_at' => now(),
            'status' => 'manifested',
        ]);
    }

    public function paused(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'paused',
        ]);
    }
}
