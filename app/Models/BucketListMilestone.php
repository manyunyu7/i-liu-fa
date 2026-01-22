<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BucketListMilestone extends Model
{
    use HasFactory;

    protected $fillable = [
        'bucket_list_item_id',
        'title',
        'is_completed',
        'completed_at',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_completed' => 'boolean',
            'completed_at' => 'datetime',
        ];
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(BucketListItem::class, 'bucket_list_item_id');
    }

    public function toggle(): void
    {
        $this->update([
            'is_completed' => !$this->is_completed,
            'completed_at' => !$this->is_completed ? now() : null,
        ]);

        $this->item->updateProgress();
    }
}
