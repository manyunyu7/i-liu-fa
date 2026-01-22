<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'level',
        'total_xp',
        'current_streak',
        'longest_streak',
        'last_activity_date',
        'timezone',
        'notification_preferences',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_activity_date' => 'date',
            'notification_preferences' => 'array',
        ];
    }

    public function affirmations(): HasMany
    {
        return $this->hasMany(Affirmation::class);
    }

    public function affirmationSessions(): HasMany
    {
        return $this->hasMany(AffirmationSession::class);
    }

    public function bucketListItems(): HasMany
    {
        return $this->hasMany(BucketListItem::class);
    }

    public function bucketListCategories(): HasMany
    {
        return $this->hasMany(BucketListCategory::class);
    }

    public function dreams(): HasMany
    {
        return $this->hasMany(Dream::class);
    }

    public function plannerTasks(): HasMany
    {
        return $this->hasMany(PlannerTask::class);
    }

    public function habits(): HasMany
    {
        return $this->hasMany(Habit::class);
    }

    public function achievements(): HasMany
    {
        return $this->hasMany(UserAchievement::class);
    }

    public function xpTransactions(): HasMany
    {
        return $this->hasMany(XpTransaction::class);
    }

    public function streaks(): HasMany
    {
        return $this->hasMany(Streak::class);
    }

    public function addXp(int $amount, string $sourceType, ?int $sourceId = null, ?string $description = null): void
    {
        $this->xpTransactions()->create([
            'amount' => $amount,
            'source_type' => $sourceType,
            'source_id' => $sourceId,
            'description' => $description,
        ]);

        $this->increment('total_xp', $amount);
        $this->updateLevel();
    }

    public function updateLevel(): void
    {
        $newLevel = Level::where('xp_required', '<=', $this->total_xp)
            ->orderBy('level_number', 'desc')
            ->first();

        if ($newLevel && $newLevel->level_number > $this->level) {
            $this->update(['level' => $newLevel->level_number]);
        }
    }

    public function getStreak(string $type): ?Streak
    {
        return $this->streaks()->where('streak_type', $type)->first();
    }

    public function updateStreak(string $type): Streak
    {
        $streak = $this->streaks()->firstOrCreate(
            ['streak_type' => $type],
            ['current_count' => 0, 'longest_count' => 0]
        );

        $today = now()->startOfDay();
        $yesterday = now()->subDay()->startOfDay();

        // Handle date comparison with Carbon
        $lastActivity = $streak->last_activity_date;
        if ($lastActivity && $lastActivity->startOfDay()->equalTo($today)) {
            return $streak;
        }

        if ($lastActivity && $lastActivity->startOfDay()->equalTo($yesterday)) {
            $streak->current_count++;
        } else {
            $streak->current_count = 1;
        }

        if ($streak->current_count > $streak->longest_count) {
            $streak->longest_count = $streak->current_count;
        }

        $streak->last_activity_date = $today;
        $streak->save();

        $this->update([
            'current_streak' => max($this->current_streak, $streak->current_count),
            'longest_streak' => max($this->longest_streak, $streak->longest_count),
            'last_activity_date' => $today,
        ]);

        return $streak;
    }

    public function getLevelProgressAttribute(): int
    {
        $currentLevel = Level::where('level_number', $this->level)->first();
        $nextLevel = Level::where('level_number', $this->level + 1)->first();

        if (!$nextLevel) {
            return 100;
        }

        $xpInCurrentLevel = $this->total_xp - ($currentLevel->xp_required ?? 0);
        $xpNeededForNext = $nextLevel->xp_required - ($currentLevel->xp_required ?? 0);

        return (int) min(100, ($xpInCurrentLevel / $xpNeededForNext) * 100);
    }

    public function getXpToNextLevelAttribute(): int
    {
        $nextLevel = Level::where('level_number', $this->level + 1)->first();

        if (!$nextLevel) {
            return 0;
        }

        return max(0, $nextLevel->xp_required - $this->total_xp);
    }
}
