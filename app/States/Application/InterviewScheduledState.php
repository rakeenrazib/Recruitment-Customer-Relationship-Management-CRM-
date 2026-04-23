<?php

namespace App\States\Application;

class InterviewScheduledState extends ApplicationState
{
    public function name(): string
    {
        return 'interview_scheduled';
    }

    protected function allowedTransitions(): array
    {
        return ['hired', 'rejected'];
    }
}
