<?php

namespace App\States\Application;

class HiredState extends ApplicationState
{
    public function name(): string
    {
        return 'hired';
    }

    protected function allowedTransitions(): array
    {
        return [];
    }
}
