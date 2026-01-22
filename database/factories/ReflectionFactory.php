<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReflectionFactory extends Factory
{
    public function definition(): array
    {
        $moods = ['happy', 'grateful', 'calm', 'energized', 'motivated', 'peaceful', 'neutral'];

        return [
            'user_id' => User::factory(),
            'reflection_date' => fake()->dateTimeBetween('-30 days', 'now'),
            'type' => fake()->randomElement(['morning', 'evening', 'gratitude', 'general']),
            'mood' => fake()->randomElement($moods),
            'mood_score' => fake()->numberBetween(5, 10),
            'gratitude_items' => [
                fake()->sentence(),
                fake()->sentence(),
                fake()->sentence(),
            ],
            'highlights' => fake()->optional()->paragraph(),
            'challenges' => fake()->optional()->paragraph(),
            'lessons' => fake()->optional()->paragraph(),
            'intentions' => fake()->optional()->paragraph(),
            'notes' => fake()->optional()->paragraph(),
            'xp_earned' => fake()->randomElement([10, 15, 20]),
        ];
    }

    public function morning(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'morning',
            'xp_earned' => 15,
        ]);
    }

    public function evening(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'evening',
            'xp_earned' => 20,
        ]);
    }

    public function gratitude(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'gratitude',
            'xp_earned' => 10,
        ]);
    }
}
