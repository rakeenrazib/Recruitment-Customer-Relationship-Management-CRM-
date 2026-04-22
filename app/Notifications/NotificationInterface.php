<?php

namespace App\Notifications;

use App\Models\User;

interface NotificationInterface
{
    /**
     * Send a notification to the given user with the given message.
     *
     * @param User $user
     * @param string $message
     * @param string $type
     * @return void
     */
    public function send(User $user, string $message, string $type): void;
}
