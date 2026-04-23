<?php

namespace App\Observers;

use App\Models\AppNotification;
use App\Models\Candidate;

interface CandidateObserverInterface
{
    public function update(object $subject, string $message, string $type): ?AppNotification;

    public function candidate(): Candidate;
}
