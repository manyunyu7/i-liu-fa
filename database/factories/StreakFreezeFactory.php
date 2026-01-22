<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class StreakFreezeFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'freeze_date' => fake()->dateTimeBetween('-30 days', 'now'),
            'type' => 'manual',
            'is_used' => true,
        ];
    }

    public function unused(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_used' => false,
        ]);
    }

    public function purchased(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'purchased',
        ]);
    }
}
