<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Habit extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'frequency',
        'target_count',
        'icon',
        'color',
        'xp_per_completion',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function logs(): HasMany
    {
        return $this->hasMany(HabitLog::class);
    }

    public function logForDate($date = null)
    {
        $date = $date ?? today();

        return $this->logs()->where('log_date', $date)->first();
    }

    public function log($date = null): HabitLog
    {
        $date = $date ?? today();

        $log = $this->logs()->firstOrCreate(
            ['log_date' => $date],
            ['count' => 0]
        );

        if ($log->count < $this->target_count) {
            $log->increment('count');

            if ($log->count === $this->target_count) {
                $this->user->addXp($this->xp_per_completion, 'habit', $this->id, "Completed habit: {$this->name}");
            }
        }

        return $log;
    }

    public function getCompletedTodayAttribute(): bool
    {
        $log = $this->logForDate(today());

        return $log && $log->count >= $this->target_count;
    }

    public function getTodayProgressAttribute(): int
    {
        $log = $this->logForDate(today());

        return $log ? $log->count : 0;
    }

    public function getStreakAttribute(): int
    {
        $streak = 0;
        $date = today();

        while (true) {
            $log = $this->logForDate($date);

            if (!$log || $log->count < $this->target_count) {
                break;
            }

            $streak++;
            $date = $date->subDay();
        }

        return $streak;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
