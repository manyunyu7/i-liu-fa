<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Achievement extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'category',
        'requirement_type',
        'requirement_value',
        'xp_reward',
        'badge_color',
    ];

    public function userAchievements(): HasMany
    {
        return $this->hasMany(UserAchievement::class);
    }

    public function isUnlockedBy(User $user): bool
    {
        return $this->userAchievements()->where('user_id', $user->id)->exists();
    }

    public function checkAndUnlock(User $user): bool
    {
        if ($this->isUnlockedBy($user)) {
            return false;
        }

        $unlocked = match ($this->requirement_type) {
            'streak_days' => $user->longest_streak >= $this->requirement_value,
            'total_xp' => $user->total_xp >= $this->requirement_value,
            'level' => $user->level >= $this->requirement_value,
            'affirmations_completed' => $user->affirmationSessions()->count() >= $this->requirement_value,
            'bucket_list_completed' => $user->bucketListItems()->completed()->count() >= $this->requirement_value,
            'dreams_manifested' => $user->dreams()->manifested()->count() >= $this->requirement_value,
            'planner_tasks_completed' => $user->plannerTasks()->completed()->count() >= $this->requirement_value,
            default => false,
        };

        if ($unlocked) {
            UserAchievement::create([
                'user_id' => $user->id,
                'achievement_id' => $this->id,
                'unlocked_at' => now(),
            ]);

            $user->addXp($this->xp_reward, 'achievement', $this->id, "Achievement unlocked: {$this->name}");

            return true;
        }

        return false;
    }
}
