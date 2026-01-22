<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class DreamCategoryFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->words(2, true),
            'slug' => fake()->unique()->slug(2),
            'description' => fake()->sentence(),
            'icon' => fake()->randomElement(['💼', '❤️', '💰', '🏠', '🎓', '✈️']),
            'color' => fake()->hexColor(),
            'sort_order' => fake()->numberBetween(1, 100),
        ];
    }

    public function system(): static
    {
        return $this;
    }
}
