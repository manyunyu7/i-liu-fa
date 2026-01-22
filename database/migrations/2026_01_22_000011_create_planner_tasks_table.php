<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('planner_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->date('task_date');
            $table->enum('task_type', ['intention', 'goal', 'habit', 'task'])->default('task');
            $table->enum('priority', ['low', 'medium', 'high'])->default('medium');
            $table->boolean('is_recurring')->default(false);
            $table->json('recurrence_pattern')->nullable();
            $table->boolean('is_completed')->default(false);
            $table->timestamp('completed_at')->nullable();
            $table->integer('xp_reward')->default(15);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('planner_tasks');
    }
};
