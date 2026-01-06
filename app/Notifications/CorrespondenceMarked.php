<?php

namespace App\Notifications;

use App\Models\Correspondence;
use App\Models\CorrespondenceMovement;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class CorrespondenceMarked extends Notification
{
    use Queueable;

    public function __construct(
        public Correspondence $correspondence,
        public CorrespondenceMovement $movement
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'correspondence_id' => $this->correspondence->id,
            'register_number' => $this->correspondence->register_number,
            'subject' => $this->correspondence->subject,
            'movement_id' => $this->movement->id,
            'from_user' => $this->movement->fromUser?->name ?? 'System',
            'action' => $this->movement->action,
            'message' => "Correspondence #{$this->correspondence->register_number} has been marked to you for {$this->movement->action}.",
        ];
    }
}
