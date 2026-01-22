<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class RewardFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->words(3, true),
            'slug' => fake()->unique()->slug(),
            'description' => fake()->sentence(),
            'icon' => fake()->randomElement(['🎁', '💎', '🧊', '⚡', '🏅']),
            'type' => fake()->randomElement(['streak_freeze', 'xp_boost', 'gems', 'badge']),
            'cost_gems' => fake()->randomElement([50, 100, 200, 500]),
            'cost_xp' => 0,
            'metadata' => null,
            'is_active' => true,
            'is_purchasable' => true,
            'sort_order' => 0,
        ];
    }

    public function streakFreeze(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'Streak Freeze',
            'slug' => 'streak-freeze',
            'description' => 'Protect your streak for one day',
            'icon' => '🧊',
            'type' => 'streak_freeze',
            'cost_gems' => 200,
        ]);
    }

    public function xpBoost(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'XP Boost',
            'description' => 'Get bonus XP instantly',
            'icon' => '⚡',
            'type' => 'xp_boost',
            'cost_gems' => 100,
            'metadata' => ['amount' => 50],
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}
