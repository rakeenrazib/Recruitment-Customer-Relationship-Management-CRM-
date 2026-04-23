<?php

namespace App\Notifications;

use App\Models\User;
use App\Services\NotificationService;

class BasicNotification implements NotificationInterface
{
    public function __construct(private readonly NotificationService $notifications = new NotificationService())
    {
    }

    /**
     * Save the notification to the database.
     */
    public function send(User $user, string $message, string $type): void
    {
        $this->notifications->send($user, $message, $type);
    }
}
