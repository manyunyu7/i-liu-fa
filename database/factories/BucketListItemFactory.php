<?php

namespace Database\Factories;

use App\Models\BucketListCategory;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class BucketListItemFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'category_id' => BucketListCategory::factory(),
            'title' => fake()->sentence(4),
            'description' => fake()->paragraph(),
            'priority' => fake()->randomElement(['low', 'medium', 'high']),
            'target_date' => fake()->optional()->dateTimeBetween('now', '+2 years'),
            'completed_at' => null,
            'progress' => 0,
            'notes' => fake()->optional()->paragraph(),
            'is_public' => false,
            'xp_reward' => fake()->randomElement([50, 100, 150, 200]),
        ];
    }

    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'completed_at' => now(),
            'progress' => 100,
        ]);
    }

    public function highPriority(): static
    {
        return $this->state(fn (array $attributes) => [
            'priority' => 'high',
        ]);
    }
}
