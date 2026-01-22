<?php

namespace Database\Seeders;

use App\Models\Reward;
use Illuminate\Database\Seeder;

class RewardSeeder extends Seeder
{
    public function run(): void
    {
        $rewards = [
            // Streak Freezes
            [
                'name' => 'Streak Freeze',
                'slug' => 'streak-freeze',
                'description' => 'Protect your streak for one day when you can\'t practice.',
                'icon' => '🧊',
                'type' => 'streak_freeze',
                'cost_gems' => 200,
                'sort_order' => 1,
            ],
            [
                'name' => 'Streak Freeze Pack',
                'slug' => 'streak-freeze-pack',
                'description' => 'Get 3 streak freezes at a discount!',
                'icon' => '🧊',
                'type' => 'streak_freeze',
                'cost_gems' => 500,
                'metadata' => ['quantity' => 3],
                'sort_order' => 2,
            ],

            // XP Boosts
            [
                'name' => 'XP Boost (Small)',
                'slug' => 'xp-boost-small',
                'description' => 'Instantly gain 50 XP.',
                'icon' => '⚡',
                'type' => 'xp_boost',
                'cost_gems' => 50,
                'metadata' => ['amount' => 50],
                'sort_order' => 1,
            ],
            [
                'name' => 'XP Boost (Medium)',
                'slug' => 'xp-boost-medium',
                'description' => 'Instantly gain 150 XP.',
                'icon' => '⚡',
                'type' => 'xp_boost',
                'cost_gems' => 125,
                'metadata' => ['amount' => 150],
                'sort_order' => 2,
            ],
            [
                'name' => 'XP Boost (Large)',
                'slug' => 'xp-boost-large',
                'description' => 'Instantly gain 500 XP!',
                'icon' => '💥',
                'type' => 'xp_boost',
                'cost_gems' => 400,
                'metadata' => ['amount' => 500],
                'sort_order' => 3,
            ],

            // Badges
            [
                'name' => 'Early Bird Badge',
                'slug' => 'early-bird-badge',
                'description' => 'Show off your dedication with this exclusive badge.',
                'icon' => '🌅',
                'type' => 'badge',
                'cost_gems' => 300,
                'sort_order' => 1,
            ],
            [
                'name' => 'Night Owl Badge',
                'slug' => 'night-owl-badge',
                'description' => 'For those who manifest under the stars.',
                'icon' => '🦉',
                'type' => 'badge',
                'cost_gems' => 300,
                'sort_order' => 2,
            ],
            [
                'name' => 'Zen Master Badge',
                'slug' => 'zen-master-badge',
                'description' => 'Achieve inner peace with this premium badge.',
                'icon' => '🧘',
                'type' => 'badge',
                'cost_gems' => 500,
                'sort_order' => 3,
            ],
        ];

        foreach ($rewards as $reward) {
            Reward::updateOrCreate(
                ['slug' => $reward['slug']],
                $reward
            );
        }
    }
}
