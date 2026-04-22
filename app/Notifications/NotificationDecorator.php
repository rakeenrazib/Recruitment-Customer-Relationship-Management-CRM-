<?php

namespace App\Notifications;

use App\Models\User;

abstract class NotificationDecorator implements NotificationInterface
{
    protected NotificationInterface $wrapped;

    public function __construct(NotificationInterface $wrapped)
    {
        $this->wrapped = $wrapped;
    }

    /**
     * Delegate to the wrapped notification by default.
     */
    public function send(User $user, string $message, string $type): void
    {
        $this->wrapped->send($user, $message, $type);
    }
}
