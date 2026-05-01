<?php

namespace App\States\Application;

use App\Models\Application;

abstract class ApplicationState
{
    public function __construct(protected Application $application)
    {
    }

    abstract public function name(): string;

    public function canTransitionTo(string $target): bool
    {
        return in_array($target, $this->allowedTransitions(), true);
    }

    abstract protected function allowedTransitions(): array;
}
