<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class LevelFactory extends Factory
{
    public function definition(): array
    {
        return [
            'level_number' => fake()->unique()->numberBetween(1, 100),
            'title' => fake()->word(),
            'xp_required' => fake()->numberBetween(0, 10000),
            'badge_icon' => fake()->randomElement(['🌱', '🌿', '🌳', '⭐', '🌟']),
        ];
    }
}
