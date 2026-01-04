<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CorrespondencePriority extends Model
{
    /** @use HasFactory<\Database\Factories\CorrespondencePriorityFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'color',
        'sla_hours',
        'sequence',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'sla_hours' => 'integer',
            'sequence' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function correspondences(): HasMany
    {
        return $this->hasMany(Correspondence::class, 'priority_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sequence');
    }
}
