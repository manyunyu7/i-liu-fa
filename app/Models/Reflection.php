<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reflection extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'reflection_date',
        'type',
        'mood',
        'mood_score',
        'gratitude_items',
        'highlights',
        'challenges',
        'lessons',
        'intentions',
        'notes',
        'xp_earned',
    ];

    protected function casts(): array
    {
        return [
            'reflection_date' => 'date',
            'gratitude_items' => 'array',
            'mood_score' => 'integer',
            'xp_earned' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeForDate($query, $date)
    {
        return $query->whereDate('reflection_date', $date);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('reflection_date', today());
    }

    public function scopeMorning($query)
    {
        return $query->where('type', 'morning');
    }

    public function scopeEvening($query)
    {
        return $query->where('type', 'evening');
    }

    public function scopeGratitude($query)
    {
        return $query->where('type', 'gratitude');
    }

    public static function getMoodEmoji(string $mood): string
    {
        return match($mood) {
            'happy' => '😊',
            'grateful' => '🙏',
            'calm' => '😌',
            'energized' => '⚡',
            'motivated' => '💪',
            'peaceful' => '☮️',
            'anxious' => '😰',
            'tired' => '😴',
            'sad' => '😢',
            'stressed' => '😫',
            'neutral' => '😐',
            default => '🤔',
        };
    }
}
