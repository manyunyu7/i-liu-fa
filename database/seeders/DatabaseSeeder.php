<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call([
            LevelSeeder::class,
            AffirmationCategorySeeder::class,
            AffirmationSeeder::class,
            BucketListCategorySeeder::class,
            DreamCategorySeeder::class,
            AchievementSeeder::class,
        ]);

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
}
