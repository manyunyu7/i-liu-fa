<?php

namespace Database\Factories;

use App\Models\AffirmationCategory;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AffirmationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => null,
            'affirmation_category_id' => AffirmationCategory::factory(),
            'content' => fake()->sentence(),
            'is_favorite' => false,
            'is_active' => true,
            'is_system' => false,
        ];
    }

    public function forUser(User $user): static
    {
        return $this->state(fn (array $attributes) => [
            'user_id' => $user->id,
        ]);
    }

    public function system(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_system' => true,
            'user_id' => null,
        ]);
    }

    public function favorite(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_favorite' => true,
        ]);
    }
}
