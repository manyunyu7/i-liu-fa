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
            'dream_category_id' => DreamCategory::factory(),
            'title' => fake()->sentence(4),
            'description' => fake()->paragraph(),
            'affirmation' => fake()->optional()->sentence(),
            'manifestation_date' => null,
            'status' => 'dreaming',
            'xp_reward' => fake()->randomElement([100, 200, 300, 500]),
        ];
    }

    public function manifested(): static
    {
        return $this->state(fn (array $attributes) => [
            'manifestation_date' => now(),
            'status' => 'manifested',
        ]);
    }

    public function manifesting(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'manifesting',
        ]);
    }
}
