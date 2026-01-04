<?php

namespace App\Models;

use App\Traits\UserTracking;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class LetterType extends Model
{
    /** @use HasFactory<\Database\Factories\LetterTypeFactory> */
    use HasFactory;

    use SoftDeletes;
    use UserTracking;

    protected $fillable = [
        'name',
        'code',
        'requires_reply',
        'default_days_to_reply',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'requires_reply' => 'boolean',
            'is_active' => 'boolean',
            'default_days_to_reply' => 'integer',
        ];
    }

    public function correspondences(): HasMany
    {
        return $this->hasMany(Correspondence::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
