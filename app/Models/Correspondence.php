<?php

namespace App\Models;

use App\Traits\UserTracking;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Correspondence extends Model implements HasMedia
{
    /** @use HasFactory<\Database\Factories\CorrespondenceFactory> */
    use HasFactory;

    use HasUuids;
    use InteractsWithMedia;
    use SoftDeletes;
    use UserTracking;

    protected $fillable = [
        'type',
        'register_number',
        'year',
        'serial_number',
        'letter_type_id',
        'category_id',
        'reference_number',
        'letter_date',
        'received_date',
        'dispatch_date',
        'subject',
        'description',
        'sender_name',
        'from_division_id',
        'to_division_id',
        'region_id',
        'branch_id',
        'addressed_to_user_id',
        'initial_action',
        'status_id',
        'priority_id',
        'confidentiality',
        'due_date',
        'closed_at',
        'delivery_mode',
        'courier_name',
        'courier_tracking',
        'parent_id',
        'related_correspondence_id',
        'current_holder_id',
        'current_holder_since',
        'is_replied',
        'reply_date',
        'reply_reference',
        'remarks',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'letter_date' => 'date',
            'received_date' => 'date',
            'dispatch_date' => 'date',
            'due_date' => 'date',
            'closed_at' => 'datetime',
            'current_holder_since' => 'datetime',
            'is_replied' => 'boolean',
            'reply_date' => 'date',
            'year' => 'integer',
            'serial_number' => 'integer',
            'metadata' => 'array',
        ];
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('attachments')
            ->useDisk('public');
    }

    public function registerMediaConversions(?\Spatie\MediaLibrary\MediaCollections\Models\Media $media = null): void
    {
        //
    }

    // Relationships
    public function letterType(): BelongsTo
    {
        return $this->belongsTo(LetterType::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(CorrespondenceCategory::class, 'category_id');
    }

    public function fromDivision(): BelongsTo
    {
        return $this->belongsTo(Division::class, 'from_division_id');
    }

    public function toDivision(): BelongsTo
    {
        return $this->belongsTo(Division::class, 'to_division_id');
    }

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function addressedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'addressed_to_user_id');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(CorrespondenceStatus::class, 'status_id');
    }

    public function priority(): BelongsTo
    {
        return $this->belongsTo(CorrespondencePriority::class, 'priority_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Correspondence::class, 'parent_id');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(Correspondence::class, 'parent_id');
    }

    public function relatedCorrespondence(): BelongsTo
    {
        return $this->belongsTo(Correspondence::class, 'related_correspondence_id');
    }

    public function currentHolder(): BelongsTo
    {
        return $this->belongsTo(User::class, 'current_holder_id');
    }

    public function movements(): HasMany
    {
        return $this->hasMany(CorrespondenceMovement::class)->orderBy('sequence');
    }

    public function reminders(): HasMany
    {
        return $this->hasMany(CorrespondenceReminder::class);
    }

    // Scopes
    public function scopeReceipts($query)
    {
        return $query->where('type', 'Receipt');
    }

    public function scopeDispatches($query)
    {
        return $query->where('type', 'Dispatch');
    }

    public function scopePending($query)
    {
        return $query->whereHas('status', fn ($q) => $q->where('is_final', false));
    }

    public function scopeOverdue($query)
    {
        return $query->whereNotNull('due_date')
            ->where('due_date', '<', now())
            ->whereNull('closed_at');
    }

    public function scopeForYear($query, int $year)
    {
        return $query->where('year', $year);
    }

    public function scopeHeldBy($query, int $userId)
    {
        return $query->where('current_holder_id', $userId);
    }

    public function scopeVisibleTo($query, User $user)
    {
        if ($user->is_super_admin === 'Yes' || $user->hasAnyRole(['super-admin', 'admin'])) {
            return $query;
        }

        return $query->where(function ($q) use ($user) {
            $q->where('created_by', $user->id)
                ->orWhere('current_holder_id', $user->id)
                ->orWhere('addressed_to_user_id', $user->id)
                ->orWhereHas('movements', function ($mq) use ($user) {
                    $mq->where('from_user_id', $user->id)
                        ->orWhere('to_user_id', $user->id);
                });
        });
    }

    // Helper methods
    public function isReceipt(): bool
    {
        return $this->type === 'Receipt';
    }

    public function isDispatch(): bool
    {
        return $this->type === 'Dispatch';
    }

    public function isClosed(): bool
    {
        return $this->closed_at !== null;
    }

    public function isOverdue(): bool
    {
        return $this->due_date && $this->due_date->isPast() && ! $this->isClosed();
    }

    public function getDaysOpenAttribute(): int
    {
        $start = $this->received_date ?? $this->created_at;
        $end = $this->closed_at ?? now();

        return $start->diffInDays($end);
    }

    public function getLatestMovement(): ?CorrespondenceMovement
    {
        return $this->movements()->latest('sequence')->first();
    }

    public function markTo(User $user, string $action = 'Mark', ?string $instructions = null): CorrespondenceMovement
    {
        $lastSequence = $this->movements()->max('sequence') ?? 0;

        $movement = $this->movements()->create([
            'sequence' => $lastSequence + 1,
            'from_user_id' => auth()->id(),
            'from_designation' => auth()->user()?->designation ?? null,
            'to_user_id' => $user->id,
            'to_designation' => $user->designation ?? null,
            'action' => $action,
            'instructions' => $instructions,
            'status' => 'Pending',
            'created_by' => auth()->id(),
        ]);

        $this->update([
            'current_holder_id' => $user->id,
            'current_holder_since' => now(),
        ]);

        return $movement;
    }

    protected static function booted(): void
    {
        static::creating(function (Correspondence $correspondence) {
            if (empty($correspondence->register_number)) {
                $division = $correspondence->to_division_id
                    ? Division::find($correspondence->to_division_id)
                    : null;

                $correspondence->year = $correspondence->year ?? now()->year;
                $correspondence->register_number = CorrespondenceSequence::generateRegisterNumber(
                    $correspondence->type,
                    $correspondence->year,
                    $correspondence->to_division_id,
                    $division?->short_name
                );
                $correspondence->serial_number = CorrespondenceSequence::where([
                    'type' => $correspondence->type,
                    'year' => $correspondence->year,
                    'division_id' => $correspondence->to_division_id,
                ])->value('last_number') ?? 1;
            }
        });
    }
}
