<?php

namespace App\Strategies\InterviewEvaluation;

use App\Models\Application;

class TechnicalAssessmentStrategy implements InterviewEvaluationStrategyInterface
{
    public function key(): string
    {
        return 'technical_assessment';
    }

    public function evaluate(Application $application, array $payload): array
    {
        $score = isset($payload['score']) ? round((float) $payload['score'], 2) : null;

        return [
            'evaluation_method' => $this->key(),
            'evaluation_score' => $score,
            'evaluation_summary' => $payload['summary'] ?? 'Technical assessment submitted.',
        ];
    }
}
