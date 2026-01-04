<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MovementComment extends Model
{
    protected $fillable = [
        'movement_id',
        'user_id',
        'comment',
        'is_private',
    ];

    protected function casts(): array
    {
        return [
            'is_private' => 'boolean',
        ];
    }

    public function movement(): BelongsTo
    {
        return $this->belongsTo(CorrespondenceMovement::class, 'movement_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopePublic($query)
    {
        return $query->where('is_private', false);
    }
}
