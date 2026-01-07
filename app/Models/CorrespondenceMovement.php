<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class CorrespondenceMovement extends Model implements HasMedia
{
    /** @use HasFactory<\Database\Factories\CorrespondenceMovementFactory> */
    use HasFactory;

    use InteractsWithMedia;
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(fn (string $eventName) => "Correspondence Movement has been {$eventName}");
    }

    protected $fillable = [
        'correspondence_id',
        'sequence',
        'from_user_id',
        'from_designation',
        'to_user_id',
        'to_designation',
        'to_division_id',
        'action',
        'instructions',
        'is_urgent',
        'expected_response_date',
        'received_at',
        'reviewed_at',
        'action_taken',
        'action_taken_at',
        'status',
        'remarks',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'is_urgent' => 'boolean',
            'expected_response_date' => 'date',
            'received_at' => 'datetime',
            'reviewed_at' => 'datetime',
            'action_taken_at' => 'datetime',
            'sequence' => 'integer',
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
    public function correspondence(): BelongsTo
    {
        return $this->belongsTo(Correspondence::class);
    }

    public function fromUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    public function toUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'to_user_id');
    }

    public function toDivision(): BelongsTo
    {
        return $this->belongsTo(Division::class, 'to_division_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(MovementComment::class, 'movement_id');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'Pending');
    }

    public function scopeForUser($query, int $userId)
    {
        return $query->where('to_user_id', $userId);
    }

    // Helper methods
    public function isPending(): bool
    {
        return $this->status === 'Pending';
    }

    public function markAsReceived(): self
    {
        $this->update([
            'status' => 'Received',
            'received_at' => now(),
        ]);

        return $this;
    }

    public function markAsReviewed(): self
    {
        $this->update([
            'status' => 'Reviewed',
            'reviewed_at' => now(),
        ]);

        return $this;
    }

    public function markAsActioned(string $actionTaken): self
    {
        $this->update([
            'status' => 'Actioned',
            'action_taken' => $actionTaken,
            'action_taken_at' => now(),
        ]);

        return $this;
    }

    public function getTimeToReceiveAttribute(): ?int
    {
        if (! $this->received_at) {
            return null;
        }

        return $this->created_at->diffInMinutes($this->received_at);
    }

    public function getTimeToActionAttribute(): ?int
    {
        if (! $this->action_taken_at || ! $this->received_at) {
            return null;
        }

        return $this->received_at->diffInMinutes($this->action_taken_at);
    }
}
