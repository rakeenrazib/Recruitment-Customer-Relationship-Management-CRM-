<?php

namespace App\States\Application;

class AppliedState extends ApplicationState
{
    public function name(): string
    {
        return 'applied';
    }

    protected function allowedTransitions(): array
    {
        return ['shortlisted', 'rejected'];
    }
}
