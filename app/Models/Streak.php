<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Streak extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'streak_type',
        'current_count',
        'longest_count',
        'last_activity_date',
    ];

    protected function casts(): array
    {
        return [
            'last_activity_date' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isActiveToday(): bool
    {
        return $this->last_activity_date?->isToday() ?? false;
    }

    public function isAtRisk(): bool
    {
        if (!$this->last_activity_date) {
            return false;
        }

        return $this->last_activity_date->isYesterday() && !$this->isActiveToday();
    }
}
