<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Affirmation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'affirmation_category_id',
        'content',
        'is_favorite',
        'is_system',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_favorite' => 'boolean',
            'is_system' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(AffirmationCategory::class, 'affirmation_category_id');
    }

    public function sessions(): HasMany
    {
        return $this->hasMany(AffirmationSession::class);
    }

    public function scopeSystem($query)
    {
        return $query->where('is_system', true);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where(function ($q) use ($userId) {
            $q->where('user_id', $userId)
              ->orWhere('is_system', true);
        });
    }
}
