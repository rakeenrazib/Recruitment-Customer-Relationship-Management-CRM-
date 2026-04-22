<?php

namespace App\Notifications;

use App\Models\AppNotification;
use App\Models\User;

class BasicNotification implements NotificationInterface
{
    /**
     * Save the notification to the database.
     */
    public function send(User $user, string $message, string $type): void
    {
        AppNotification::create([
            'user_id' => $user->id,
            'type'    => $type,
            'message' => $message,
            'is_read' => false,
        ]);
    }
}
