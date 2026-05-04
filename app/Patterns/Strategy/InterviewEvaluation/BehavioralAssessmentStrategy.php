<?php

namespace App\Patterns\Strategy\InterviewEvaluation;

use App\Models\Application;

class BehavioralAssessmentStrategy implements InterviewEvaluationStrategyInterface
{
    public function key(): string
    {
        return 'behavioral_assessment';
    }

    public function evaluate(Application $application, array $payload): array
    {
        $generalScore = $this->generalScore($payload);
        $leadership = $this->assessLeadership($payload);
        $teamwork = $this->measureTeamwork($payload);
        $adaptability = $this->scoreAdaptability($payload);
        $conflictHandling = $this->evaluateConflictHandling($payload);
        $score = $generalScore ?? $this->averageScores([$leadership, $teamwork, $adaptability, $conflictHandling]);

        return [
            'general_score' => $generalScore,
            'score' => $score,
            'comments' => $payload['comments'] ?? 'Behavioral assessment recorded for teamwork, adaptability, and culture fit.',
            'strengths' => $this->normalizeTextList($payload['strengths'] ?? 'Interpersonal fit reviewed, Organizational behavior assessed'),
            'weaknesses' => $this->normalizeTextList($payload['weaknesses'] ?? (($score !== null && $score >= 4.0)
                ? 'Behavioral observations should be confirmed in the final stage'
                : 'Behavioral fit needs more discussion with the hiring team')),
            'recommendation' => $payload['recommendation']
                ?? (($score !== null && $score >= 4.0)
                    ? 'Proceed with team-fit discussions'
                    : 'Investigate team and culture fit more deeply'),
            'rubrics' => [
                'leadership_score' => $leadership,
                'teamwork_score' => $teamwork,
                'adaptability_score' => $adaptability,
                'conflict_handling_score' => $conflictHandling,
            ],
        ];
    }

    private function assessLeadership(array $payload): ?float
    {
        return isset($payload['leadership_score']) && $payload['leadership_score'] !== null && $payload['leadership_score'] !== ''
            ? round((float) $payload['leadership_score'], 2)
            : null;
    }

    private function measureTeamwork(array $payload): ?float
    {
        if (isset($payload['teamwork_score']) && $payload['teamwork_score'] !== null && $payload['teamwork_score'] !== '') {
            return round((float) $payload['teamwork_score'], 2);
        }

        return null;
    }

    private function scoreAdaptability(array $payload): ?float
    {
        if (isset($payload['adaptability_score']) && $payload['adaptability_score'] !== null && $payload['adaptability_score'] !== '') {
            return round((float) $payload['adaptability_score'], 2);
        }

        return null;
    }

    private function evaluateConflictHandling(array $payload): ?float
    {
        if (isset($payload['conflict_handling_score']) && $payload['conflict_handling_score'] !== null && $payload['conflict_handling_score'] !== '') {
            return round((float) $payload['conflict_handling_score'], 2);
        }

        return null;
    }

    private function generalScore(array $payload): ?float
    {
        return isset($payload['general_score']) && $payload['general_score'] !== null && $payload['general_score'] !== ''
            ? round((float) $payload['general_score'], 2)
            : null;
    }

    private function normalizeTextList(string $value): array
    {
        return array_values(array_filter(array_map('trim', explode(',', $value))));
    }

    private function averageScores(array $scores): ?float
    {
        $numericScores = array_values(array_filter($scores, fn ($score) => $score !== null));

        if ($numericScores === []) {
            return null;
        }

        return round(array_sum($numericScores) / count($numericScores), 2);
    }
}
