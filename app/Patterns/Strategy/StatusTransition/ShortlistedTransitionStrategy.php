<?php

namespace App\Patterns\Strategy\StatusTransition;

class ShortlistedTransitionStrategy implements StatusTransitionStrategyInterface
{
    public function validateTransition(string $targetStatus): bool
    {
        return in_array($targetStatus, ['interview_scheduled', 'hired', 'rejected'], true);
    }
}
