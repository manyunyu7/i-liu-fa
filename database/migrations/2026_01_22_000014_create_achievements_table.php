<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('achievements', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description');
            $table->string('icon')->nullable();
            $table->enum('category', ['streak', 'completion', 'milestone', 'special'])->default('milestone');
            $table->string('requirement_type'); // e.g., 'streak_days', 'total_affirmations', 'bucket_list_completed'
            $table->integer('requirement_value');
            $table->integer('xp_reward')->default(50);
            $table->string('badge_color')->default('#FFC800');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('achievements');
    }
};
