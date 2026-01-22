<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlannerTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'task_date',
        'task_type',
        'priority',
        'is_recurring',
        'recurrence_pattern',
        'is_completed',
        'completed_at',
        'xp_reward',
    ];

    protected function casts(): array
    {
        return [
            'task_date' => 'date',
            'is_recurring' => 'boolean',
            'recurrence_pattern' => 'array',
            'is_completed' => 'boolean',
            'completed_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function complete(): void
    {
        $this->update([
            'is_completed' => true,
            'completed_at' => now(),
        ]);

        $this->user->addXp($this->xp_reward, 'planner', $this->id, "Completed: {$this->title}");
        $this->user->updateStreak('planner');
    }

    public function uncomplete(): void
    {
        $this->update([
            'is_completed' => false,
            'completed_at' => null,
        ]);
    }

    public function scopeForDate($query, $date)
    {
        return $query->whereDate('task_date', $date);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('task_date', today());
    }

    public function scopeThisWeek($query)
    {
        return $query->whereBetween('task_date', [now()->startOfWeek(), now()->endOfWeek()]);
    }

    public function scopeCompleted($query)
    {
        return $query->where('is_completed', true);
    }

    public function scopePending($query)
    {
        return $query->where('is_completed', false);
    }

    public function scopeIntentions($query)
    {
        return $query->where('task_type', 'intention');
    }

    public function scopeGoals($query)
    {
        return $query->where('task_type', 'goal');
    }
}
