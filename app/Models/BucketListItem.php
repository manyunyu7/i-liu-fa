<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BucketListItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'bucket_list_category_id',
        'title',
        'description',
        'target_date',
        'progress',
        'priority',
        'status',
        'completed_at',
        'cover_image',
        'xp_reward',
    ];

    protected function casts(): array
    {
        return [
            'target_date' => 'date',
            'completed_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(BucketListCategory::class, 'bucket_list_category_id');
    }

    public function milestones(): HasMany
    {
        return $this->hasMany(BucketListMilestone::class)->orderBy('sort_order');
    }

    public function markAsCompleted(): void
    {
        $this->update([
            'status' => 'completed',
            'progress' => 100,
            'completed_at' => now(),
        ]);

        $this->user->addXp($this->xp_reward, 'bucket_list', $this->id, "Completed: {$this->title}");
    }

    public function updateProgress(): void
    {
        $totalMilestones = $this->milestones()->count();

        if ($totalMilestones === 0) {
            return;
        }

        $completedMilestones = $this->milestones()->where('is_completed', true)->count();
        $progress = (int) (($completedMilestones / $totalMilestones) * 100);

        $this->update(['progress' => $progress]);

        if ($progress === 100 && $this->status !== 'completed') {
            $this->markAsCompleted();
        }
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }
}
