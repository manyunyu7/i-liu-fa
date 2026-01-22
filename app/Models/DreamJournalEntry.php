<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DreamJournalEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'dream_id',
        'content',
        'mood',
    ];

    public function dream(): BelongsTo
    {
        return $this->belongsTo(Dream::class);
    }
}
