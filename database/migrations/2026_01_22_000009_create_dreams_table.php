<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dreams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('dream_category_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('visualization_image')->nullable();
            $table->enum('status', ['dreaming', 'manifesting', 'manifested'])->default('dreaming');
            $table->date('manifestation_date')->nullable();
            $table->text('affirmation')->nullable();
            $table->integer('xp_reward')->default(200);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dreams');
    }
};
