<?php

namespace Database\Factories;

use App\Models\Habit;
use Illuminate\Database\Eloquent\Factories\Factory;

class HabitLogFactory extends Factory
{
    public function definition(): array
    {
        return [
            'habit_id' => Habit::factory(),
            'log_date' => now()->toDateString(),
            'count' => 1,
            'notes' => fake()->optional()->sentence(),
        ];
    }

    public function forDate(string $date): static
    {
        return $this->state(fn (array $attributes) => [
            'log_date' => $date,
        ]);
    }
}
