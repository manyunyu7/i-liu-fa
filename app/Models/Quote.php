<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Quote extends Model
{
    use HasFactory;

    protected $fillable = [
        'content',
        'author',
        'source',
        'category',
        'is_active',
        'is_featured',
        'likes_count',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'likes_count' => 'integer',
        ];
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'quote_user')
            ->withTimestamps();
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeByCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    public static function getCategories(): array
    {
        return [
            'general' => 'General',
            'motivation' => 'Motivation',
            'success' => 'Success',
            'happiness' => 'Happiness',
            'gratitude' => 'Gratitude',
            'mindfulness' => 'Mindfulness',
            'courage' => 'Courage',
            'perseverance' => 'Perseverance',
            'love' => 'Love',
            'wisdom' => 'Wisdom',
        ];
    }

    public static function random(?string $category = null): ?self
    {
        $query = static::active();

        if ($category) {
            $query->byCategory($category);
        }

        return $query->inRandomOrder()->first();
    }

    public static function getDailyQuote(?User $user = null): self
    {
        if ($user) {
            $daily = DailyQuote::where('user_id', $user->id)
                ->whereDate('shown_date', today())
                ->first();

            if ($daily) {
                return $daily->quote;
            }

            $quote = static::active()->inRandomOrder()->first();

            if ($quote) {
                DailyQuote::create([
                    'user_id' => $user->id,
                    'quote_id' => $quote->id,
                    'shown_date' => today(),
                ]);
            }

            return $quote;
        }

        return static::active()->inRandomOrder()->first() ?? new self([
            'content' => 'Believe you can and you\'re halfway there.',
            'author' => 'Theodore Roosevelt',
        ]);
    }
}
