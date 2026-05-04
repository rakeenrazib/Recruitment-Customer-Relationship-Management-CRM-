<?php

namespace App\Patterns\Strategy\StatusTransition;

class HiredTransitionStrategy implements StatusTransitionStrategyInterface
{
    public function validateTransition(string $targetStatus): bool
    {
        return false; // No transitions allowed from Hired
    }
}
