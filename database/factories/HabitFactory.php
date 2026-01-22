<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class HabitFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => fake()->sentence(3),
            'description' => fake()->optional()->sentence(),
            'icon' => fake()->randomElement(['✓', '💧', '🏃', '📚', '🧘', '💪']),
            'color' => fake()->hexColor(),
            'frequency' => fake()->randomElement(['daily', 'weekly']),
            'target_count' => fake()->numberBetween(1, 5),
            'is_active' => true,
            'xp_per_completion' => fake()->randomElement([5, 10, 15]),
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }

    public function weekly(): static
    {
        return $this->state(fn (array $attributes) => [
            'frequency' => 'weekly',
        ]);
    }
}
