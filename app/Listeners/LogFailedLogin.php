<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Failed;

class LogFailedLogin
{
    /**
     * Handle the event.
     */
    public function handle(Failed $event): void
    {
        $userData = $event->user ? [
            'id' => $event->user->id,
            'email' => $event->user->email,
        ] : [
            'email' => $event->credentials['email'] ?? 'N/A',
        ];

        activity('auth')
            ->withProperties([
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'credentials' => $event->credentials,
            ])
            ->event('failed_login')
            ->log('Failed login attempt for email: ' . ($event->credentials['email'] ?? 'N/A'));
            
        \Illuminate\Support\Facades\Log::warning('Failed login attempt detected.', [
            'ip' => request()->ip(),
            'credentials' => $event->credentials,
        ]);
    }
}
