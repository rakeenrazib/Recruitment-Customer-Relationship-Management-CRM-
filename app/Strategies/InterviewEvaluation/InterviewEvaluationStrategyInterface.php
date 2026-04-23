<?php

namespace App\Strategies\InterviewEvaluation;

use App\Models\Application;

interface InterviewEvaluationStrategyInterface
{
    public function key(): string;

    public function evaluate(Application $application, array $payload): array;
}
