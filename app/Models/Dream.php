<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Dream extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'dream_category_id',
        'title',
        'description',
        'visualization_image',
        'status',
        'manifestation_date',
        'affirmation',
        'xp_reward',
    ];

    protected function casts(): array
    {
        return [
            'manifestation_date' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(DreamCategory::class, 'dream_category_id');
    }

    public function journalEntries(): HasMany
    {
        return $this->hasMany(DreamJournalEntry::class)->orderBy('created_at', 'desc');
    }

    public function manifest(): void
    {
        $this->update([
            'status' => 'manifested',
            'manifestation_date' => now(),
        ]);

        $this->user->addXp($this->xp_reward, 'dream', $this->id, "Manifested: {$this->title}");
    }

    public function scopeDreaming($query)
    {
        return $query->where('status', 'dreaming');
    }

    public function scopeManifesting($query)
    {
        return $query->where('status', 'manifesting');
    }

    public function scopeManifested($query)
    {
        return $query->where('status', 'manifested');
    }
}
