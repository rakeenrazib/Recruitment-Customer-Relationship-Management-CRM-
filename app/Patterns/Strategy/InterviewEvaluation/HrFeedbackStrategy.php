<?php

namespace App\Patterns\Strategy\InterviewEvaluation;

use App\Models\Application;

class HrFeedbackStrategy implements InterviewEvaluationStrategyInterface
{
    public function key(): string
    {
        return 'hr_feedback';
    }

    public function evaluate(Application $application, array $payload): array
    {
        $generalScore = $this->generalScore($payload);
        $communication = $this->scoreCommunication($payload);
        $salaryFit = $this->checkSalaryExpectationFit($application, $payload);
        $availability = $this->verifyAvailability($payload);
        $professionalism = $this->assessProfessionalism($payload);
        $score = $generalScore ?? $this->averageScores([$communication, $salaryFit, $availability, $professionalism]);

        return [
            'general_score' => $generalScore,
            'score' => $score,
            'comments' => $payload['comments'] ?? 'HR feedback recorded for communication, professionalism, and administrative suitability.',
            'strengths' => $this->normalizeTextList($payload['strengths'] ?? 'Administrative screening completed, Professional expectations reviewed'),
            'weaknesses' => $this->normalizeTextList($payload['weaknesses'] ?? (($score !== null && $score >= 4.0)
                ? 'Minor HR follow-up may still be needed'
                : 'Candidate requires closer HR review before progressing')),
            'recommendation' => $payload['recommendation']
                ?? (($score !== null && $score >= 4.0)
                    ? 'Suitable for recruiter approval'
                    : 'Reassess HR fit before moving forward'),
            'rubrics' => [
                'communication_score' => $communication,
                'salary_fit_score' => $salaryFit,
                'availability_score' => $availability,
                'professionalism_score' => $professionalism,
            ],
        ];
    }

    private function scoreCommunication(array $payload): ?float
    {
        return isset($payload['communication_score']) && $payload['communication_score'] !== null && $payload['communication_score'] !== ''
            ? round((float) $payload['communication_score'], 2)
            : null;
    }

    private function checkSalaryExpectationFit(Application $application, array $payload): ?float
    {
        return isset($payload['salary_fit_score']) && $payload['salary_fit_score'] !== null && $payload['salary_fit_score'] !== ''
            ? round((float) $payload['salary_fit_score'], 2)
            : null;
    }

    private function verifyAvailability(array $payload): ?float
    {
        if (isset($payload['availability_score']) && $payload['availability_score'] !== null && $payload['availability_score'] !== '') {
            return round((float) $payload['availability_score'], 2);
        }

        return null;
    }

    private function assessProfessionalism(array $payload): ?float
    {
        if (isset($payload['professionalism_score']) && $payload['professionalism_score'] !== null && $payload['professionalism_score'] !== '') {
            return round((float) $payload['professionalism_score'], 2);
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
