<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'level' => 1,
            'total_xp' => 0,
            'current_streak' => 0,
            'longest_streak' => 0,
            'last_activity_date' => null,
            'timezone' => 'UTC',
            'notification_preferences' => [],
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    public function withXp(int $xp): static
    {
        return $this->state(fn (array $attributes) => [
            'total_xp' => $xp,
        ]);
    }

    public function withStreak(int $streak): static
    {
        return $this->state(fn (array $attributes) => [
            'current_streak' => $streak,
            'longest_streak' => $streak,
            'last_activity_date' => now()->toDateString(),
        ]);
    }

    public function withLevel(int $level): static
    {
        return $this->state(fn (array $attributes) => [
            'level' => $level,
        ]);
    }
}
