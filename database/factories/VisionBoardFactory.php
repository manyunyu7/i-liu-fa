<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class VisionBoardFactory extends Factory
{
    public function definition(): array
    {
        $themes = ['default', 'cosmic', 'nature', 'sunset', 'ocean'];
        $theme = fake()->randomElement($themes);

        $backgroundColors = [
            'default' => '#f8fafc',
            'cosmic' => '#1a1a2e',
            'nature' => '#ecfdf5',
            'sunset' => '#fff7ed',
            'ocean' => '#eff6ff',
        ];

        return [
            'user_id' => User::factory(),
            'title' => fake()->sentence(3),
            'description' => fake()->optional()->paragraph(),
            'theme' => $theme,
            'background_color' => $backgroundColors[$theme],
            'is_public' => false,
            'is_primary' => false,
        ];
    }

    public function primary(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_primary' => true,
        ]);
    }

    public function public(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_public' => true,
        ]);
    }
}
