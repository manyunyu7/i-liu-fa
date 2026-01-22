<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VisionBoard extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'theme',
        'background_color',
        'background_image',
        'is_public',
        'is_primary',
    ];

    protected function casts(): array
    {
        return [
            'is_public' => 'boolean',
            'is_primary' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(VisionBoardItem::class)->orderBy('z_index');
    }

    public function makePrimary(): void
    {
        // Unset other primary boards
        $this->user->visionBoards()->where('id', '!=', $this->id)->update(['is_primary' => false]);
        $this->update(['is_primary' => true]);
    }

    public function scopePrimary($query)
    {
        return $query->where('is_primary', true);
    }

    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }
}
