<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quotes', function (Blueprint $table) {
            $table->id();
            $table->text('content');
            $table->string('author')->nullable();
            $table->string('source')->nullable();
            $table->string('category')->default('general'); // motivation, success, happiness, gratitude, etc.
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->unsignedInteger('likes_count')->default(0);
            $table->timestamps();
        });

        // User's favorite quotes
        Schema::create('quote_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('quote_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            $table->unique(['user_id', 'quote_id']);
        });

        // Track daily quote for users
        Schema::create('daily_quotes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('quote_id')->constrained()->onDelete('cascade');
            $table->date('shown_date');
            $table->timestamps();

            $table->unique(['user_id', 'shown_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('daily_quotes');
        Schema::dropIfExists('quote_user');
        Schema::dropIfExists('quotes');
    }
};
