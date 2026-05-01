<?php

namespace App\Listeners;

use App\Events\ApplicationStatusUpdated;
use App\Notifications\BasicNotification;
use App\Notifications\EmailNotificationDecorator;
use App\Notifications\LogNotificationDecorator;

class SendApplicationStatusNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ApplicationStatusUpdated $event): void
    {
        $application = $event->application;
        $newStatus   = $event->newStatus;
        $jobTitle    = $application->job->title;

        $messages = [
            'shortlisted' => "You have been shortlisted for \"{$jobTitle}\".",
            'interview_scheduled' => "An interview has been scheduled for \"{$jobTitle}\". We will contact you with details shortly.",
            'hired' => "Congratulations. You have been marked hired for \"{$jobTitle}\".",
            'rejected'    => "Your application for \"{$jobTitle}\" was not successful at this time.",
            'applied'     => "Your application for \"{$jobTitle}\" is under review.",
        ];

        if (!isset($messages[$newStatus])) {
            return;
        }

        $application->load('user');
        $user    = $application->user;
        $message = $messages[$newStatus];

        // Start with the base notification (saves to DB)
        $notification = new BasicNotification();

        // Wrap with log decorator (always log)
        $notification = new LogNotificationDecorator($notification);

        // Wrap with email decorator only for interview status
        if ($newStatus === 'interview_scheduled') {
            $notification = new EmailNotificationDecorator($notification, $application);
        }

        $notification->send($user, $message, $newStatus);
    }
}
