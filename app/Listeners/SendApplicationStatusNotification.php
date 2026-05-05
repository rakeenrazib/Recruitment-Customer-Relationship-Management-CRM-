<?php

namespace App\Listeners;

use App\Events\ApplicationStatusUpdated;
use App\Notifications\BasicNotification;

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

        // Save the notification to the DB
        $notification = new BasicNotification();
        $notification->send($user, $message, $newStatus);

        // Explicitly log the notification instead of using a Decorator
        \Illuminate\Support\Facades\Log::info("Notification sent to {$user->email}: {$message}");
    }
}
