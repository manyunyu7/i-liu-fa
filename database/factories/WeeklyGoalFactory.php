<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\WeeklyGoal;
use Illuminate\Database\Eloquent\Factories\Factory;

class WeeklyGoalFactory extends Factory
{
    protected $model = WeeklyGoal::class;

    public function definition(): array
    {
        $categories = array_keys(WeeklyGoal::getCategories());

        return [
            'user_id' => User::factory(),
            'title' => $this->faker->sentence(4),
            'description' => $this->faker->optional()->paragraph(),
            'category' => $this->faker->randomElement($categories),
            'week_start_date' => now()->startOfWeek(),
            'target_count' => $this->faker->numberBetween(1, 10),
            'current_count' => 0,
            'is_completed' => false,
            'xp_reward' => $this->faker->numberBetween(30, 100),
        ];
    }

    public function completed(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'current_count' => $attributes['target_count'],
                'is_completed' => true,
                'completed_at' => now(),
            ];
        });
    }

    public function forWeek($date): static
    {
        return $this->state(function (array $attributes) use ($date) {
            return [
                'week_start_date' => \Carbon\Carbon::parse($date)->startOfWeek(),
            ];
        });
    }
}
