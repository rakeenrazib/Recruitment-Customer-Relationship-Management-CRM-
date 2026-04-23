<?php

namespace App\Strategies\InterviewEvaluation;

use App\Models\Application;

class HrFeedbackStrategy implements InterviewEvaluationStrategyInterface
{
    public function key(): string
    {
        return 'hr_feedback';
    }

    public function evaluate(Application $application, array $payload): array
    {
        return [
            'evaluation_method' => $this->key(),
            'evaluation_score' => isset($payload['score']) ? round((float) $payload['score'], 2) : null,
            'evaluation_summary' => $payload['summary'] ?? 'HR feedback captured.',
        ];
    }
}
