<?php

namespace Database\Seeders;

use App\Models\AffirmationCategory;
use Illuminate\Database\Seeder;

class AffirmationCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Wealth & Abundance',
                'slug' => 'wealth',
                'icon' => '💰',
                'color' => '#FFC800',
                'description' => 'Affirmations for financial prosperity and abundance',
                'sort_order' => 1,
            ],
            [
                'name' => 'Health & Vitality',
                'slug' => 'health',
                'icon' => '💚',
                'color' => '#58CC02',
                'description' => 'Affirmations for physical and mental well-being',
                'sort_order' => 2,
            ],
            [
                'name' => 'Love & Relationships',
                'slug' => 'love',
                'icon' => '💕',
                'color' => '#FF86D0',
                'description' => 'Affirmations for love, connection, and relationships',
                'sort_order' => 3,
            ],
            [
                'name' => 'Success & Career',
                'slug' => 'success',
                'icon' => '🚀',
                'color' => '#1CB0F6',
                'description' => 'Affirmations for career growth and success',
                'sort_order' => 4,
            ],
            [
                'name' => 'Confidence & Self-Love',
                'slug' => 'confidence',
                'icon' => '💪',
                'color' => '#FF9600',
                'description' => 'Affirmations for self-esteem and confidence',
                'sort_order' => 5,
            ],
            [
                'name' => 'Gratitude & Joy',
                'slug' => 'gratitude',
                'icon' => '🙏',
                'color' => '#CE82FF',
                'description' => 'Affirmations for gratitude and happiness',
                'sort_order' => 6,
            ],
        ];

        foreach ($categories as $category) {
            AffirmationCategory::create($category);
        }
    }
}
