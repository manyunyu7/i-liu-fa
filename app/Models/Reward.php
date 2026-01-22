<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Reward extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'type',
        'cost_gems',
        'cost_xp',
        'metadata',
        'is_active',
        'is_purchasable',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'metadata' => 'array',
            'cost_gems' => 'integer',
            'cost_xp' => 'integer',
            'is_active' => 'boolean',
            'is_purchasable' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_rewards')
            ->withPivot(['quantity', 'purchased_at', 'used_at', 'expires_at', 'is_active', 'metadata'])
            ->withTimestamps();
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopePurchasable($query)
    {
        return $query->where('is_purchasable', true);
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('type', $type);
    }

    public static function getTypes(): array
    {
        return [
            'streak_freeze' => 'Streak Freeze',
            'xp_boost' => 'XP Boost',
            'gems' => 'Gems',
            'badge' => 'Badge',
            'custom' => 'Custom',
        ];
    }
}
