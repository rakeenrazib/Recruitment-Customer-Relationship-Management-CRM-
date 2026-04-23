<?php

namespace App\States\Application;

class RejectedState extends ApplicationState
{
    public function name(): string
    {
        return 'rejected';
    }

    protected function allowedTransitions(): array
    {
        return [];
    }
}
