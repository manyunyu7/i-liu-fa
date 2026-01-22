<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rewards', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('icon')->default('🎁');
            $table->string('type'); // streak_freeze, xp_boost, gems, badge, custom
            $table->unsignedInteger('cost_gems')->default(0);
            $table->unsignedInteger('cost_xp')->default(0);
            $table->json('metadata')->nullable(); // Extra data like amount, duration, etc.
            $table->boolean('is_active')->default(true);
            $table->boolean('is_purchasable')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rewards');
    }
};
