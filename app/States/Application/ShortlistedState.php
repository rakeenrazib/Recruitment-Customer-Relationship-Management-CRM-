<?php

namespace App\States\Application;

class ShortlistedState extends ApplicationState
{
    public function name(): string
    {
        return 'shortlisted';
    }

    protected function allowedTransitions(): array
    {
        return ['interview_scheduled', 'hired', 'rejected'];
    }
}
