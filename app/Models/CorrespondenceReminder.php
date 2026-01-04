<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CorrespondenceReminder extends Model
{
    protected $fillable = [
        'correspondence_id',
        'user_id',
        'remind_at',
        'message',
        'is_sent',
        'sent_at',
        'is_acknowledged',
        'acknowledged_at',
    ];

    protected function casts(): array
    {
        return [
            'remind_at' => 'datetime',
            'is_sent' => 'boolean',
            'sent_at' => 'datetime',
            'is_acknowledged' => 'boolean',
            'acknowledged_at' => 'datetime',
        ];
    }

    public function correspondence(): BelongsTo
    {
        return $this->belongsTo(Correspondence::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopePending($query)
    {
        return $query->where('is_sent', false)
            ->where('remind_at', '<=', now());
    }

    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function markAsSent(): self
    {
        $this->update([
            'is_sent' => true,
            'sent_at' => now(),
        ]);

        return $this;
    }

    public function acknowledge(): self
    {
        $this->update([
            'is_acknowledged' => true,
            'acknowledged_at' => now(),
        ]);

        return $this;
    }
}
