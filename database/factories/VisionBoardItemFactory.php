<?php

namespace Database\Factories;

use App\Models\VisionBoard;
use Illuminate\Database\Eloquent\Factories\Factory;

class VisionBoardItemFactory extends Factory
{
    public function definition(): array
    {
        return [
            'vision_board_id' => VisionBoard::factory(),
            'type' => fake()->randomElement(['image', 'text', 'quote', 'goal', 'affirmation']),
            'title' => fake()->optional()->sentence(3),
            'content' => fake()->optional()->paragraph(),
            'position_x' => fake()->numberBetween(50, 500),
            'position_y' => fake()->numberBetween(50, 400),
            'width' => fake()->randomElement([150, 200, 250, 300]),
            'height' => fake()->randomElement([150, 200, 250, 300]),
            'rotation' => fake()->numberBetween(-15, 15),
            'z_index' => fake()->numberBetween(1, 10),
            'text_color' => '#1f2937',
            'background_color' => '#ffffff',
        ];
    }

    public function image(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'image',
            'image_url' => fake()->imageUrl(400, 400),
        ]);
    }

    public function quote(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'quote',
            'content' => fake()->sentence(10),
        ]);
    }

    public function affirmation(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'affirmation',
            'content' => 'I am ' . fake()->randomElement(['worthy', 'capable', 'strong', 'confident', 'blessed']),
        ]);
    }
}
