<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StreakFreeze extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'freeze_date',
        'type',
        'is_used',
    ];

    protected function casts(): array
    {
        return [
            'freeze_date' => 'date',
            'is_used' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeUnused($query)
    {
        return $query->where('is_used', false);
    }

    public function scopeForDate($query, $date)
    {
        return $query->whereDate('freeze_date', $date);
    }
}
