<?php

namespace App\Notifications;

use App\Models\User;
use App\Services\NotificationService;

class BasicNotification implements NotificationInterface
{
    public function __construct(?NotificationService $notifications = null)
    {
        // Notifications no longer use Singleton. Each collaborator may receive
        // a regular NotificationService instance through construction.
        $this->notifications = $notifications ?? new NotificationService();
    }

    private readonly NotificationService $notifications;

    /**
     * Save the notification to the database.
     */
    public function send(User $user, string $message, string $type): void
    {
        $this->notifications->send($user, $message, $type);
    }
}
