<?php

namespace Database\Seeders;

use App\Models\Achievement;
use Illuminate\Database\Seeder;

class AchievementSeeder extends Seeder
{
    public function run(): void
    {
        $achievements = [
            // Streak achievements
            [
                'name' => 'First Steps',
                'slug' => 'first-steps',
                'description' => 'Complete your first day of practice',
                'icon' => '👣',
                'category' => 'streak',
                'requirement_type' => 'streak_days',
                'requirement_value' => 1,
                'xp_reward' => 25,
                'badge_color' => '#58CC02',
            ],
            [
                'name' => 'Week Warrior',
                'slug' => 'week-warrior',
                'description' => 'Maintain a 7-day streak',
                'icon' => '🔥',
                'category' => 'streak',
                'requirement_type' => 'streak_days',
                'requirement_value' => 7,
                'xp_reward' => 100,
                'badge_color' => '#FF9600',
            ],
            [
                'name' => 'Month Master',
                'slug' => 'month-master',
                'description' => 'Maintain a 30-day streak',
                'icon' => '⚡',
                'category' => 'streak',
                'requirement_type' => 'streak_days',
                'requirement_value' => 30,
                'xp_reward' => 500,
                'badge_color' => '#FFC800',
            ],
            [
                'name' => 'Century Club',
                'slug' => 'century-club',
                'description' => 'Maintain a 100-day streak',
                'icon' => '💯',
                'category' => 'streak',
                'requirement_type' => 'streak_days',
                'requirement_value' => 100,
                'xp_reward' => 1000,
                'badge_color' => '#CE82FF',
            ],

            // Affirmation achievements
            [
                'name' => 'Believer',
                'slug' => 'believer',
                'description' => 'Complete 10 affirmation sessions',
                'icon' => '🌟',
                'category' => 'completion',
                'requirement_type' => 'affirmations_completed',
                'requirement_value' => 10,
                'xp_reward' => 50,
                'badge_color' => '#1CB0F6',
            ],
            [
                'name' => 'Manifestor',
                'slug' => 'manifestor',
                'description' => 'Complete 50 affirmation sessions',
                'icon' => '✨',
                'category' => 'completion',
                'requirement_type' => 'affirmations_completed',
                'requirement_value' => 50,
                'xp_reward' => 200,
                'badge_color' => '#CE82FF',
            ],
            [
                'name' => 'Affirmation Master',
                'slug' => 'affirmation-master',
                'description' => 'Complete 200 affirmation sessions',
                'icon' => '🧘',
                'category' => 'completion',
                'requirement_type' => 'affirmations_completed',
                'requirement_value' => 200,
                'xp_reward' => 500,
                'badge_color' => '#FFC800',
            ],

            // Bucket list achievements
            [
                'name' => 'Goal Getter',
                'slug' => 'goal-getter',
                'description' => 'Complete your first bucket list item',
                'icon' => '✅',
                'category' => 'milestone',
                'requirement_type' => 'bucket_list_completed',
                'requirement_value' => 1,
                'xp_reward' => 50,
                'badge_color' => '#58CC02',
            ],
            [
                'name' => 'Dream Chaser',
                'slug' => 'dream-chaser',
                'description' => 'Complete 5 bucket list items',
                'icon' => '🎯',
                'category' => 'milestone',
                'requirement_type' => 'bucket_list_completed',
                'requirement_value' => 5,
                'xp_reward' => 150,
                'badge_color' => '#1CB0F6',
            ],
            [
                'name' => 'Life Liver',
                'slug' => 'life-liver',
                'description' => 'Complete 25 bucket list items',
                'icon' => '🏆',
                'category' => 'milestone',
                'requirement_type' => 'bucket_list_completed',
                'requirement_value' => 25,
                'xp_reward' => 500,
                'badge_color' => '#FFC800',
            ],

            // Dreams achievements
            [
                'name' => 'Dream Come True',
                'slug' => 'dream-come-true',
                'description' => 'Manifest your first dream',
                'icon' => '💫',
                'category' => 'special',
                'requirement_type' => 'dreams_manifested',
                'requirement_value' => 1,
                'xp_reward' => 100,
                'badge_color' => '#CE82FF',
            ],
            [
                'name' => 'Reality Creator',
                'slug' => 'reality-creator',
                'description' => 'Manifest 5 dreams',
                'icon' => '🌈',
                'category' => 'special',
                'requirement_type' => 'dreams_manifested',
                'requirement_value' => 5,
                'xp_reward' => 300,
                'badge_color' => '#FF86D0',
            ],

            // Level achievements
            [
                'name' => 'Rising Star',
                'slug' => 'rising-star',
                'description' => 'Reach level 5',
                'icon' => '⭐',
                'category' => 'milestone',
                'requirement_type' => 'level',
                'requirement_value' => 5,
                'xp_reward' => 200,
                'badge_color' => '#FFC800',
            ],
            [
                'name' => 'Superstar',
                'slug' => 'superstar',
                'description' => 'Reach level 10',
                'icon' => '🌟',
                'category' => 'milestone',
                'requirement_type' => 'level',
                'requirement_value' => 10,
                'xp_reward' => 500,
                'badge_color' => '#FF9600',
            ],

            // XP achievements
            [
                'name' => 'XP Collector',
                'slug' => 'xp-collector',
                'description' => 'Earn 1,000 XP',
                'icon' => '💎',
                'category' => 'milestone',
                'requirement_type' => 'total_xp',
                'requirement_value' => 1000,
                'xp_reward' => 100,
                'badge_color' => '#1CB0F6',
            ],
            [
                'name' => 'XP Champion',
                'slug' => 'xp-champion',
                'description' => 'Earn 10,000 XP',
                'icon' => '👑',
                'category' => 'milestone',
                'requirement_type' => 'total_xp',
                'requirement_value' => 10000,
                'xp_reward' => 500,
                'badge_color' => '#FFC800',
            ],

            // Planner achievements
            [
                'name' => 'Organized',
                'slug' => 'organized',
                'description' => 'Complete 10 planner tasks',
                'icon' => '📋',
                'category' => 'completion',
                'requirement_type' => 'planner_tasks_completed',
                'requirement_value' => 10,
                'xp_reward' => 50,
                'badge_color' => '#58CC02',
            ],
            [
                'name' => 'Productivity Pro',
                'slug' => 'productivity-pro',
                'description' => 'Complete 100 planner tasks',
                'icon' => '🚀',
                'category' => 'completion',
                'requirement_type' => 'planner_tasks_completed',
                'requirement_value' => 100,
                'xp_reward' => 300,
                'badge_color' => '#1CB0F6',
            ],
        ];

        foreach ($achievements as $achievement) {
            Achievement::create($achievement);
        }
    }
}
