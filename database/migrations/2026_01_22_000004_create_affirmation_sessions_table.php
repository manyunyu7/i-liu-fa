<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('affirmation_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('affirmation_id')->constrained()->onDelete('cascade');
            $table->timestamp('completed_at');
            $table->integer('xp_earned')->default(10);
            $table->integer('session_duration')->default(0); // in seconds
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('affirmation_sessions');
    }
};
