<?php

namespace App\Patterns\Strategy\StatusTransition;

class RejectedTransitionStrategy implements StatusTransitionStrategyInterface
{
    public function validateTransition(string $targetStatus): bool
    {
        return false; // No transitions allowed from Rejected
    }
}
