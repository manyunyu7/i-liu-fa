<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AffirmationSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'affirmation_id',
        'completed_at',
        'xp_earned',
        'session_duration',
    ];

    protected function casts(): array
    {
        return [
            'completed_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function affirmation(): BelongsTo
    {
        return $this->belongsTo(Affirmation::class);
    }
}
