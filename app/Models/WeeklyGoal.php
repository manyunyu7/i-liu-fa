<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WeeklyGoal extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'category',
        'week_start_date',
        'target_count',
        'current_count',
        'is_completed',
        'completed_at',
        'xp_reward',
    ];

    protected function casts(): array
    {
        return [
            'week_start_date' => 'date',
            'completed_at' => 'datetime',
            'is_completed' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeForWeek($query, $date = null)
    {
        $weekStart = $date ? \Carbon\Carbon::parse($date)->startOfWeek() : now()->startOfWeek();
        return $query->where('week_start_date', $weekStart);
    }

    public function scopeCurrentWeek($query)
    {
        return $query->forWeek(now());
    }

    public function scopeCompleted($query)
    {
        return $query->where('is_completed', true);
    }

    public function scopePending($query)
    {
        return $query->where('is_completed', false);
    }

    public function scopeByCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    public function getProgressPercentageAttribute(): int
    {
        if ($this->target_count <= 0) {
            return 0;
        }
        return (int) min(100, ($this->current_count / $this->target_count) * 100);
    }

    public function incrementProgress(int $amount = 1): void
    {
        $this->increment('current_count', $amount);

        if ($this->current_count >= $this->target_count && !$this->is_completed) {
            $this->complete();
        }
    }

    public function complete(): void
    {
        if ($this->is_completed) {
            return;
        }

        $this->update([
            'is_completed' => true,
            'completed_at' => now(),
        ]);

        // Award XP
        $this->user->addXp(
            $this->xp_reward,
            'weekly_goal',
            $this->id,
            "Completed weekly goal: {$this->title}"
        );
    }

    public static function getCategories(): array
    {
        return [
            'general' => ['label' => 'General', 'icon' => '🎯', 'color' => 'blue'],
            'health' => ['label' => 'Health & Fitness', 'icon' => '💪', 'color' => 'green'],
            'career' => ['label' => 'Career & Work', 'icon' => '💼', 'color' => 'purple'],
            'personal' => ['label' => 'Personal Growth', 'icon' => '🌱', 'color' => 'yellow'],
            'learning' => ['label' => 'Learning', 'icon' => '📚', 'color' => 'orange'],
            'relationships' => ['label' => 'Relationships', 'icon' => '❤️', 'color' => 'red'],
            'finance' => ['label' => 'Finance', 'icon' => '💰', 'color' => 'green'],
            'creativity' => ['label' => 'Creativity', 'icon' => '🎨', 'color' => 'purple'],
        ];
    }
}
