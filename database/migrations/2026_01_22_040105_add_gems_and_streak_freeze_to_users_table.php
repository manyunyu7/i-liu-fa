<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedInteger('gems')->default(0)->after('total_xp');
            $table->unsignedInteger('streak_freezes_available')->default(0)->after('gems');
            $table->date('last_streak_freeze_used')->nullable()->after('streak_freezes_available');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['gems', 'streak_freezes_available', 'last_streak_freeze_used']);
        });
    }
};
