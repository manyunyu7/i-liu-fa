<?php

namespace Database\Seeders;

use App\Models\Level;
use Illuminate\Database\Seeder;

class LevelSeeder extends Seeder
{
    public function run(): void
    {
        $levels = [
            ['level_number' => 1, 'xp_required' => 0, 'title' => 'Dreamer', 'badge_icon' => '🌱'],
            ['level_number' => 2, 'xp_required' => 100, 'title' => 'Believer', 'badge_icon' => '🌿'],
            ['level_number' => 3, 'xp_required' => 250, 'title' => 'Seeker', 'badge_icon' => '🌳'],
            ['level_number' => 4, 'xp_required' => 500, 'title' => 'Manifester', 'badge_icon' => '⭐'],
            ['level_number' => 5, 'xp_required' => 1000, 'title' => 'Achiever', 'badge_icon' => '🌟'],
            ['level_number' => 6, 'xp_required' => 2000, 'title' => 'Transformer', 'badge_icon' => '💫'],
            ['level_number' => 7, 'xp_required' => 3500, 'title' => 'Visionary', 'badge_icon' => '🔮'],
            ['level_number' => 8, 'xp_required' => 5500, 'title' => 'Creator', 'badge_icon' => '🎯'],
            ['level_number' => 9, 'xp_required' => 8000, 'title' => 'Master', 'badge_icon' => '👑'],
            ['level_number' => 10, 'xp_required' => 12000, 'title' => 'Legend', 'badge_icon' => '🏆'],
        ];

        foreach ($levels as $level) {
            Level::create($level);
        }
    }
}
