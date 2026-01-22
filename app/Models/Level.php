<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    use HasFactory;

    protected $fillable = [
        'level_number',
        'xp_required',
        'title',
        'badge_icon',
    ];

    public static function forXp(int $xp): ?self
    {
        return static::where('xp_required', '<=', $xp)
            ->orderBy('level_number', 'desc')
            ->first();
    }
}
