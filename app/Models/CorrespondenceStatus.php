<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CorrespondenceStatus extends Model
{
    /** @use HasFactory<\Database\Factories\CorrespondenceStatusFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'color',
        'type',
        'is_initial',
        'is_final',
        'sequence',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_initial' => 'boolean',
            'is_final' => 'boolean',
            'sequence' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function correspondences(): HasMany
    {
        return $this->hasMany(Correspondence::class, 'status_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForReceipt($query)
    {
        return $query->whereIn('type', ['Receipt', 'Both']);
    }

    public function scopeForDispatch($query)
    {
        return $query->whereIn('type', ['Dispatch', 'Both']);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sequence');
    }

    public function scopeInitial($query)
    {
        return $query->where('is_initial', true);
    }
}
