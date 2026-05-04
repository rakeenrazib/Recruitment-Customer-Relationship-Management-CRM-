<?php

namespace App\Patterns\Observer\CompanyFollowers;

use App\Models\Candidate;
use App\Services\NotificationService;

class CompanyFollowerNotificationObserver implements ObserverInterface
{
    private NotificationService $notificationService;

    /**
     * The concrete observer keeps a reference to the Subject, just like the
     * classroom pseudocode, and registers itself immediately.
     */
    public function __construct(
        private CompanyFollowerSubject $subject,
        private Candidate $candidate,
        ?NotificationService $notificationService = null,
    ) {
        $this->notificationService = $notificationService ?? new NotificationService();
        $this->subject->registerObserver($this);
    }

    /**
     * notify() is the Observer callback. It pulls the current Subject state
     * and turns that state into a candidate notification.
     */
    public function notify(): void
    {
        // 1. Start with the base notification
        $notification = new \App\Notifications\BasicNotification($this->notificationService);

        // 2. Wrap it with Log Decorator
        $notification = new \App\Notifications\LogNotificationDecorator($notification);

        // 3. Wrap it with Email Decorator
        $notification = new \App\Notifications\EmailNotificationDecorator($notification);

        // 4. Send the notification
        $notification->send(
            $this->candidate->user,
            $this->subject->getMessage(),
            $this->subject->getNotificationType()
        );
    }
}
