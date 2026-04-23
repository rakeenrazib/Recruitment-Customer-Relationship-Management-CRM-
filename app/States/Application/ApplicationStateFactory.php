<?php

namespace App\States\Application;

use App\Models\Application;
use InvalidArgumentException;

class ApplicationStateFactory
{
    public static function make(Application $application, ?string $status = null): ApplicationState
    {
        return match ($status ?? $application->status) {
            'applied', 'pending' => new AppliedState($application),
            'shortlisted' => new ShortlistedState($application),
            'interview', 'interview_scheduled' => new InterviewScheduledState($application),
            'hired' => new HiredState($application),
            'rejected' => new RejectedState($application),
            default => throw new InvalidArgumentException('Unsupported application state.'),
        };
    }
}
