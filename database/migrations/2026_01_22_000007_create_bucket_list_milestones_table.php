<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bucket_list_milestones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bucket_list_item_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->boolean('is_completed')->default(false);
            $table->timestamp('completed_at')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bucket_list_milestones');
    }
};
