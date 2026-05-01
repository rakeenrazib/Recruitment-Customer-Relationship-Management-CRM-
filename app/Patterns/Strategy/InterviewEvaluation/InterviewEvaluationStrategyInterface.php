<?php

namespace App\Patterns\Strategy\InterviewEvaluation;

use App\Models\Application;

interface InterviewEvaluationStrategyInterface
{
    /**
     * Every concrete strategy exposes a machine-friendly key so the Context
     * can switch between implementations without knowing internal details.
     */
    public function key(): string;

    /**
     * This is the single public operation shared by all strategies.
     * The recruiter workflow can call evaluate() regardless of whether the
     * chosen assessment is technical, HR-focused, or behavioral.
     *
     * The returned structure stays consistent across all strategies:
     * score, comments, strengths, weaknesses, recommendation.
     */
    public function evaluate(Application $application, array $payload): array;
}
