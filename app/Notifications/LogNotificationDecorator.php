<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Support\Facades\Log;

class LogNotificationDecorator extends NotificationDecorator
{
    /**
     * Call wrapped send(), then also log the notification.
     */
    public function send(User $user, string $message, string $type): void
    {
        // First, run the wrapped notification
        parent::send($user, $message, $type);

        // Then log it
        Log::info("Notification sent to user [{$user->id}] ({$user->email}) | Type: {$type} | Message: {$message}");
    }
}
