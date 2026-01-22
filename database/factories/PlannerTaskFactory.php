<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PlannerTaskFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'title' => fake()->sentence(4),
            'description' => fake()->optional()->sentence(),
            'task_date' => today(),
            'task_type' => fake()->randomElement(['task', 'intention', 'goal', 'habit']),
            'priority' => fake()->randomElement(['low', 'medium', 'high']),
            'is_completed' => false,
            'completed_at' => null,
            'xp_reward' => fake()->randomElement([5, 10, 15, 20]),
        ];
    }

    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_completed' => true,
            'completed_at' => now(),
        ]);
    }

    public function forDate(string $date): static
    {
        return $this->state(fn (array $attributes) => [
            'task_date' => $date,
        ]);
    }

    public function intention(): static
    {
        return $this->state(fn (array $attributes) => [
            'task_type' => 'intention',
        ]);
    }

    public function goal(): static
    {
        return $this->state(fn (array $attributes) => [
            'task_type' => 'goal',
        ]);
    }
}
