<?php

namespace Database\Seeders;

use App\Models\DreamCategory;
use Illuminate\Database\Seeder;

class DreamCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Lifestyle',
                'slug' => 'lifestyle',
                'icon' => '🏠',
                'color' => '#1CB0F6',
                'description' => 'Dream home, car, lifestyle',
                'sort_order' => 1,
            ],
            [
                'name' => 'Career',
                'slug' => 'career',
                'icon' => '🎯',
                'color' => '#58CC02',
                'description' => 'Dream job, business, professional success',
                'sort_order' => 2,
            ],
            [
                'name' => 'Relationships',
                'slug' => 'relationships',
                'icon' => '💑',
                'color' => '#FF86D0',
                'description' => 'Soulmate, family, friendships',
                'sort_order' => 3,
            ],
            [
                'name' => 'Health',
                'slug' => 'health',
                'icon' => '💪',
                'color' => '#FF9600',
                'description' => 'Ideal body, health goals',
                'sort_order' => 4,
            ],
            [
                'name' => 'Wealth',
                'slug' => 'wealth',
                'icon' => '💎',
                'color' => '#FFC800',
                'description' => 'Financial freedom, abundance',
                'sort_order' => 5,
            ],
            [
                'name' => 'Personal',
                'slug' => 'personal',
                'icon' => '✨',
                'color' => '#CE82FF',
                'description' => 'Self-improvement, inner growth',
                'sort_order' => 6,
            ],
            [
                'name' => 'Adventure',
                'slug' => 'adventure',
                'icon' => '🌍',
                'color' => '#89E219',
                'description' => 'Travel, experiences, exploration',
                'sort_order' => 7,
            ],
        ];

        foreach ($categories as $category) {
            DreamCategory::create($category);
        }
    }
}
