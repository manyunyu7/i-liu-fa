<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VisionBoardItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'vision_board_id',
        'dream_id',
        'type',
        'title',
        'content',
        'image_url',
        'position_x',
        'position_y',
        'width',
        'height',
        'rotation',
        'z_index',
        'font_family',
        'font_size',
        'text_color',
        'background_color',
        'border_style',
    ];

    protected function casts(): array
    {
        return [
            'position_x' => 'integer',
            'position_y' => 'integer',
            'width' => 'integer',
            'height' => 'integer',
            'rotation' => 'integer',
            'z_index' => 'integer',
        ];
    }

    public function visionBoard(): BelongsTo
    {
        return $this->belongsTo(VisionBoard::class);
    }

    public function dream(): BelongsTo
    {
        return $this->belongsTo(Dream::class);
    }

    public function bringToFront(): void
    {
        $maxZIndex = $this->visionBoard->items()->max('z_index') ?? 0;
        $this->update(['z_index' => $maxZIndex + 1]);
    }

    public function sendToBack(): void
    {
        $this->visionBoard->items()->where('id', '!=', $this->id)->increment('z_index');
        $this->update(['z_index' => 1]);
    }
}
