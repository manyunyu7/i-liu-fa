<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class AchievementFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->words(3, true),
            'slug' => fake()->unique()->slug(3),
            'description' => fake()->sentence(),
            'icon' => fake()->randomElement(['🏆', '⭐', '🎯', '🔥', '💪']),
            'badge_color' => fake()->hexColor(),
            'category' => fake()->randomElement(['streak', 'completion', 'milestone', 'special']),
            'requirement_type' => 'xp_total',
            'requirement_value' => fake()->numberBetween(1, 100),
            'xp_reward' => fake()->randomElement([50, 100, 200, 500]),
        ];
    }

    public function streak(): static
    {
        return $this->state(fn (array $attributes) => [
            'category' => 'streak',
            'requirement_type' => 'streak',
        ]);
    }

    public function secret(): static
    {
        return $this;
    }
}
