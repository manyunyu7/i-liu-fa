<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('avatar')->nullable()->after('email');
            $table->integer('level')->default(1)->after('avatar');
            $table->integer('total_xp')->default(0)->after('level');
            $table->integer('current_streak')->default(0)->after('total_xp');
            $table->integer('longest_streak')->default(0)->after('current_streak');
            $table->date('last_activity_date')->nullable()->after('longest_streak');
            $table->string('timezone')->default('UTC')->after('last_activity_date');
            $table->json('notification_preferences')->nullable()->after('timezone');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'avatar',
                'level',
                'total_xp',
                'current_streak',
                'longest_streak',
                'last_activity_date',
                'timezone',
                'notification_preferences',
            ]);
        });
    }
};
