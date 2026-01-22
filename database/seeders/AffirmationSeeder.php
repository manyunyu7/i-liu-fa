<?php

namespace Database\Seeders;

use App\Models\Affirmation;
use App\Models\AffirmationCategory;
use Illuminate\Database\Seeder;

class AffirmationSeeder extends Seeder
{
    public function run(): void
    {
        $affirmations = [
            'wealth' => [
                'I am a magnet for wealth and abundance.',
                'Money flows to me easily and effortlessly.',
                'I am worthy of financial success.',
                'My income is constantly increasing.',
                'I attract lucrative opportunities.',
                'I am grateful for the abundance in my life.',
                'Wealth comes to me from multiple sources.',
                'I manage my money wisely.',
            ],
            'health' => [
                'My body is healthy, strong, and vibrant.',
                'I nourish my body with healthy choices.',
                'Every cell in my body radiates with energy.',
                'I am grateful for my health and vitality.',
                'My mind is calm and peaceful.',
                'I choose foods that heal and energize me.',
                'I listen to my body and give it what it needs.',
                'Health and wellness flow through me.',
            ],
            'love' => [
                'I am worthy of deep, genuine love.',
                'I attract loving and supportive relationships.',
                'Love flows to me and through me.',
                'I am surrounded by loving energy.',
                'My relationships are healthy and fulfilling.',
                'I give and receive love freely.',
                'I am open to giving and receiving love.',
                'Love is always available to me.',
            ],
            'success' => [
                'I am destined for success.',
                'Every step I take leads me to success.',
                'I am confident in my abilities.',
                'Success comes naturally to me.',
                'I overcome challenges with ease.',
                'My work brings value to others.',
                'I am growing and improving every day.',
                'Opportunities are everywhere around me.',
            ],
            'confidence' => [
                'I believe in myself completely.',
                'I am worthy of all good things.',
                'I love and accept myself unconditionally.',
                'My confidence grows stronger every day.',
                'I am proud of who I am becoming.',
                'I trust my instincts and decisions.',
                'I am enough, just as I am.',
                'I radiate confidence and positivity.',
            ],
            'gratitude' => [
                'I am grateful for this beautiful day.',
                'I appreciate all the blessings in my life.',
                'Gratitude fills my heart with joy.',
                'I see the good in every situation.',
                'Thank you for all that I have.',
                'I am grateful for my journey.',
                'Joy and happiness are my natural state.',
                'I choose to focus on the positive.',
            ],
        ];

        foreach ($affirmations as $slug => $contents) {
            $category = AffirmationCategory::where('slug', $slug)->first();

            foreach ($contents as $content) {
                Affirmation::create([
                    'affirmation_category_id' => $category->id,
                    'content' => $content,
                    'is_system' => true,
                    'is_active' => true,
                ]);
            }
        }
    }
}
