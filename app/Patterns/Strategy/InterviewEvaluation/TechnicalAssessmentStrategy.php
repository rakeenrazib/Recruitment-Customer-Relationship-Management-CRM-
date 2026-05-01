<?php

namespace App\Patterns\Strategy\InterviewEvaluation;

use App\Models\Application;

class TechnicalAssessmentStrategy implements InterviewEvaluationStrategyInterface
{
    public function key(): string
    {
        return 'technical_assessment';
    }

    /**
     * Strategy pattern:
     * this concrete strategy contains only the rules for technical evaluation.
     * The recruiter flow still calls the same public method as every other
     * strategy: evaluate().
     */
    public function evaluate(Application $application, array $payload): array
    {
        $codingRubric = $this->gradeCodingTest($payload);
        $skillCompetency = $this->calculateSkillScore($application, $payload);
        $plagiarismDetected = $this->detectPlagiarism($payload);
        $generalScore = $this->generalScore($payload);
        $score = $generalScore ?? $this->averageScores([$codingRubric, $skillCompetency]);

        return [
            'general_score' => $generalScore,
            'score' => $score === null ? null : ($plagiarismDetected ? max($score - 0.5, 0) : $score),
            'comments' => $payload['comments'] ?? 'Technical assessment completed based on coding quality and role-specific skills.',
            'strengths' => $this->normalizeTextList($payload['strengths'] ?? 'Role-relevant technical fit, Coding quality reviewed'),
            'weaknesses' => $plagiarismDetected
                ? ['Possible plagiarism indicators were detected']
                : $this->normalizeTextList($payload['weaknesses'] ?? 'Needs continued hands-on validation during later stages'),
            'recommendation' => $payload['recommendation']
                ?? (($score !== null && $score >= 4.0) && ! $plagiarismDetected
                    ? 'Proceed to the next technical stage'
                    : 'Review carefully before progressing'),
            'rubrics' => [
                'coding_rubric' => $codingRubric,
                'skill_competency' => $skillCompetency,
                'plagiarism_detected' => $plagiarismDetected,
            ],
        ];
    }

    /**
     * Private helper methods are specific to this strategy.
     * They should not be called by the controller or the context directly.
     */
    private function gradeCodingTest(array $payload): ?float
    {
        return isset($payload['coding_rubric']) && $payload['coding_rubric'] !== null && $payload['coding_rubric'] !== ''
            ? round((float) $payload['coding_rubric'], 2)
            : null;
    }

    private function calculateSkillScore(Application $application, array $payload): ?float
    {
        return isset($payload['skill_competency']) && $payload['skill_competency'] !== null && $payload['skill_competency'] !== ''
            ? round((float) $payload['skill_competency'], 2)
            : null;
    }

    private function detectPlagiarism(array $payload): bool
    {
        return (bool) ($payload['plagiarism_detected'] ?? false);
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
