<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class BucketListCategoryFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => null,
            'name' => fake()->words(2, true),
            'slug' => fake()->unique()->slug(2),
            'description' => fake()->sentence(),
            'icon' => fake()->randomElement(['✈️', '🎓', '💪', '🎯', '🏠', '💰']),
            'color' => fake()->hexColor(),
            'is_system' => false,
            'sort_order' => fake()->numberBetween(1, 100),
        ];
    }

    public function forUser(User $user): static
    {
        return $this->state(fn (array $attributes) => [
            'user_id' => $user->id,
        ]);
    }

    public function system(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_system' => true,
            'user_id' => null,
        ]);
    }
}
