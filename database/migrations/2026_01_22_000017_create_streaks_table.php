<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('streaks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('streak_type'); // daily_login, affirmation, planner
            $table->integer('current_count')->default(0);
            $table->integer('longest_count')->default(0);
            $table->date('last_activity_date')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'streak_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('streaks');
    }
};
