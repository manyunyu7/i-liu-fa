<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
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
        'gems',
        'streak_freezes_available',
        'last_streak_freeze_used',
        'current_streak',
        'longest_streak',
        'last_activity_date',
        'timezone',
        'notification_preferences',
        'preferences',
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
            'last_streak_freeze_used' => 'date',
            'notification_preferences' => 'array',
            'preferences' => 'array',
            'gems' => 'integer',
            'streak_freezes_available' => 'integer',
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

    public function visionBoards(): HasMany
    {
        return $this->hasMany(VisionBoard::class);
    }

    public function primaryVisionBoard(): ?VisionBoard
    {
        return $this->visionBoards()->primary()->first();
    }

    public function reflections(): HasMany
    {
        return $this->hasMany(Reflection::class);
    }

    public function streakFreezes(): HasMany
    {
        return $this->hasMany(StreakFreeze::class);
    }

    public function rewards(): BelongsToMany
    {
        return $this->belongsToMany(Reward::class, 'user_rewards')
            ->withPivot(['quantity', 'purchased_at', 'used_at', 'expires_at', 'is_active', 'metadata'])
            ->withTimestamps();
    }

    public function userRewards(): HasMany
    {
        return $this->hasMany(UserReward::class);
    }

    public function favoriteQuotes(): BelongsToMany
    {
        return $this->belongsToMany(Quote::class, 'quote_user')
            ->withTimestamps();
    }

    public function dailyQuotes(): HasMany
    {
        return $this->hasMany(DailyQuote::class);
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

    public function addGems(int $amount): void
    {
        $this->increment('gems', $amount);
    }

    public function spendGems(int $amount): bool
    {
        if ($this->gems < $amount) {
            return false;
        }

        $this->decrement('gems', $amount);
        return true;
    }

    public function purchaseStreakFreeze(): bool
    {
        $reward = Reward::where('type', 'streak_freeze')->active()->first();

        if (!$reward || $this->gems < $reward->cost_gems) {
            return false;
        }

        if (!$this->spendGems($reward->cost_gems)) {
            return false;
        }

        $this->increment('streak_freezes_available');

        $this->userRewards()->create([
            'reward_id' => $reward->id,
            'quantity' => 1,
            'purchased_at' => now(),
        ]);

        return true;
    }

    public function useStreakFreeze(?string $date = null): bool
    {
        if ($this->streak_freezes_available <= 0) {
            return false;
        }

        $freezeDate = $date ? \Carbon\Carbon::parse($date) : today();

        // Check if already used for this date
        if ($this->streakFreezes()->forDate($freezeDate)->exists()) {
            return false;
        }

        $this->streakFreezes()->create([
            'freeze_date' => $freezeDate,
            'type' => 'manual',
            'is_used' => true,
        ]);

        $this->decrement('streak_freezes_available');
        $this->update(['last_streak_freeze_used' => $freezeDate]);

        return true;
    }

    public function hasStreakFreezeForDate($date): bool
    {
        return $this->streakFreezes()->forDate($date)->exists();
    }

    public function canAfford(Reward $reward): bool
    {
        if ($reward->cost_gems > 0 && $this->gems < $reward->cost_gems) {
            return false;
        }

        if ($reward->cost_xp > 0 && $this->total_xp < $reward->cost_xp) {
            return false;
        }

        return true;
    }

    public function purchaseReward(Reward $reward): ?UserReward
    {
        if (!$this->canAfford($reward)) {
            return null;
        }

        if ($reward->cost_gems > 0) {
            $this->spendGems($reward->cost_gems);
        }

        return $this->userRewards()->create([
            'reward_id' => $reward->id,
            'quantity' => 1,
            'purchased_at' => now(),
            'metadata' => $reward->metadata,
        ]);
    }
}
