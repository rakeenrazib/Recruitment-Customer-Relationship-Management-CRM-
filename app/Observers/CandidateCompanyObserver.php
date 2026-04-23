<?php

namespace App\Observers;

use App\Models\Candidate;
use App\Services\NotificationService;

class CandidateCompanyObserver implements CandidateObserverInterface
{
    public function __construct(
        private Candidate $candidate,
        private readonly NotificationService $notifications = new NotificationService(),
    )
    {
    }

    public function candidate(): Candidate
    {
        return $this->candidate;
    }

    public function update(object $subject, string $message, string $type): ?\App\Models\AppNotification
    {
        return $this->notifications->send(
            $this->candidate->user,
            $message,
            $type,
            class_basename($subject),
            $subject->id ?? null,
        );
    }
}
