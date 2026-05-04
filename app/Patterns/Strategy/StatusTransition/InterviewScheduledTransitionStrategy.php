<?php

namespace App\Patterns\Strategy\StatusTransition;

class InterviewScheduledTransitionStrategy implements StatusTransitionStrategyInterface
{
    public function validateTransition(string $targetStatus): bool
    {
        return in_array($targetStatus, ['hired', 'rejected'], true);
    }
}
