<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vision_board_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vision_board_id')->constrained()->onDelete('cascade');
            $table->foreignId('dream_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('type', ['image', 'text', 'quote', 'goal', 'affirmation']);
            $table->string('title')->nullable();
            $table->text('content')->nullable();
            $table->string('image_url')->nullable();
            $table->integer('position_x')->default(0);
            $table->integer('position_y')->default(0);
            $table->integer('width')->default(200);
            $table->integer('height')->default(200);
            $table->integer('rotation')->default(0);
            $table->integer('z_index')->default(1);
            $table->string('font_family')->nullable();
            $table->string('font_size')->nullable();
            $table->string('text_color')->nullable();
            $table->string('background_color')->nullable();
            $table->string('border_style')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vision_board_items');
    }
};
