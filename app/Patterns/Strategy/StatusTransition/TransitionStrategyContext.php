<?php

namespace App\Patterns\Strategy\StatusTransition;

use InvalidArgumentException;

class TransitionStrategyContext
{
    public static function getStrategy(string $currentStatus): StatusTransitionStrategyInterface
    {
        return match ($currentStatus) {
            'applied', 'pending' => new AppliedTransitionStrategy(),
            'shortlisted' => new ShortlistedTransitionStrategy(),
            'interview', 'interview_scheduled' => new InterviewScheduledTransitionStrategy(),
            'hired' => new HiredTransitionStrategy(),
            'rejected' => new RejectedTransitionStrategy(),
            default => throw new InvalidArgumentException("No transition strategy found for {$currentStatus}"),
        };
    }
}
