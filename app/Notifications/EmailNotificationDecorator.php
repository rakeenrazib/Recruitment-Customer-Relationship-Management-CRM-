<?php

namespace App\Notifications;

use App\Mail\InterviewScheduledMail;
use App\Models\Application;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class EmailNotificationDecorator extends NotificationDecorator
{
    protected Application $application;

    public function __construct(NotificationInterface $wrapped, Application $application)
    {
        parent::__construct($wrapped);
        $this->application = $application;
    }

    /**
     * Call wrapped send(), then also send an email.
     */
    public function send(User $user, string $message, string $type): void
    {
        // First, run the wrapped notification (saves to DB)
        parent::send($user, $message, $type);

        // Then send the email
        try {
            Mail::to($user->email)->send(new InterviewScheduledMail($this->application));
        } catch (\Exception $e) {
            // Silently fail if mail not configured — DB notification still saved
        }
    }
}
