<?php

namespace App\Patterns\Strategy\StatusTransition;

class AppliedTransitionStrategy implements StatusTransitionStrategyInterface
{
    public function validateTransition(string $targetStatus): bool
    {
        return in_array($targetStatus, ['shortlisted', 'rejected'], true);
    }
}
