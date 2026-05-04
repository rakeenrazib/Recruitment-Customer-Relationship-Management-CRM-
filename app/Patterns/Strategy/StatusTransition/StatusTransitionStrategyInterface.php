<?php

namespace App\Patterns\Strategy\StatusTransition;

interface StatusTransitionStrategyInterface
{
    /**
     * Define which statuses this current status can transition to.
     */
    public function validateTransition(string $targetStatus): bool;
}
