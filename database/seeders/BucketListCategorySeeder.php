<?php

namespace Database\Seeders;

use App\Models\BucketListCategory;
use Illuminate\Database\Seeder;

class BucketListCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Travel & Adventure',
                'slug' => 'travel',
                'icon' => '✈️',
                'color' => '#1CB0F6',
                'is_system' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Career & Education',
                'slug' => 'career',
                'icon' => '💼',
                'color' => '#58CC02',
                'is_system' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Health & Fitness',
                'slug' => 'health',
                'icon' => '🏃',
                'color' => '#FF9600',
                'is_system' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Relationships',
                'slug' => 'relationships',
                'icon' => '❤️',
                'color' => '#FF86D0',
                'is_system' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'Financial Goals',
                'slug' => 'financial',
                'icon' => '💰',
                'color' => '#FFC800',
                'is_system' => true,
                'sort_order' => 5,
            ],
            [
                'name' => 'Personal Growth',
                'slug' => 'personal',
                'icon' => '🌱',
                'color' => '#CE82FF',
                'is_system' => true,
                'sort_order' => 6,
            ],
            [
                'name' => 'Creative & Hobbies',
                'slug' => 'creative',
                'icon' => '🎨',
                'color' => '#FF4B4B',
                'is_system' => true,
                'sort_order' => 7,
            ],
            [
                'name' => 'Experiences',
                'slug' => 'experiences',
                'icon' => '⭐',
                'color' => '#89E219',
                'is_system' => true,
                'sort_order' => 8,
            ],
        ];

        foreach ($categories as $category) {
            BucketListCategory::create($category);
        }
    }
}
