<?php

namespace App\Strategies\InterviewEvaluation;

use App\Models\Application;

class ScoringRubricStrategy implements InterviewEvaluationStrategyInterface
{
    public function key(): string
    {
        return 'scoring_rubric';
    }

    public function evaluate(Application $application, array $payload): array
    {
        $scores = collect($payload['scores'] ?? [])->filter(fn ($score) => is_numeric($score))->map(fn ($score) => (float) $score);
        $score = $scores->count() ? round($scores->avg(), 2) : null;

        return [
            'evaluation_method' => $this->key(),
            'evaluation_score' => $score,
            'evaluation_summary' => $payload['summary'] ?? 'Scoring rubric recorded.',
        ];
    }
}
