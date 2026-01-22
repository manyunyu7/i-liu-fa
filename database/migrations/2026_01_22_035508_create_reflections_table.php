<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reflections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('reflection_date');
            $table->enum('type', ['morning', 'evening', 'gratitude', 'general']);
            $table->string('mood')->nullable(); // happy, grateful, calm, energized, anxious, tired, etc.
            $table->integer('mood_score')->nullable(); // 1-10
            $table->text('gratitude_items')->nullable(); // JSON array of gratitude items
            $table->text('highlights')->nullable(); // What went well
            $table->text('challenges')->nullable(); // What was challenging
            $table->text('lessons')->nullable(); // What I learned
            $table->text('intentions')->nullable(); // Tomorrow's intentions
            $table->text('notes')->nullable(); // Free-form notes
            $table->integer('xp_earned')->default(0);
            $table->timestamps();

            $table->unique(['user_id', 'reflection_date', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reflections');
    }
};
