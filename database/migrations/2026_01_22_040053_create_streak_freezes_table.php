<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('streak_freezes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('freeze_date');
            $table->string('type')->default('manual'); // manual, purchased, reward
            $table->boolean('is_used')->default(false);
            $table->timestamps();

            $table->unique(['user_id', 'freeze_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('streak_freezes');
    }
};
