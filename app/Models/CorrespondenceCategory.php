<?php

namespace App\Models;

use App\Traits\UserTracking;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class CorrespondenceCategory extends Model
{
    /** @use HasFactory<\Database\Factories\CorrespondenceCategoryFactory> */
    use HasFactory;

    use SoftDeletes;
    use UserTracking;

    protected $fillable = [
        'name',
        'code',
        'parent_id',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(CorrespondenceCategory::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(CorrespondenceCategory::class, 'parent_id');
    }

    public function correspondences(): HasMany
    {
        return $this->hasMany(Correspondence::class, 'category_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeRoots($query)
    {
        return $query->whereNull('parent_id');
    }
}
