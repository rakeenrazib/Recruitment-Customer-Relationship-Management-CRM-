<?php

namespace App\Patterns\Strategy\InterviewEvaluation;

use App\Models\Application;
use InvalidArgumentException;

class InterviewEvaluationContext
{
    /**
     * Context is a key Strategy-pattern component.
     * It owns a reference to the selected strategy and delegates evaluation
     * work to that strategy through the shared interface.
     */
    public function __construct(
        private InterviewEvaluationStrategyInterface $strategy,
    ) {
    }

    /**
     * Allow the recruiter flow to replace strategies at runtime.
     */
    public function setStrategy(InterviewEvaluationStrategyInterface $strategy): void
    {
        $this->strategy = $strategy;
    }

    /**
     * This is the Context entry point used by the application.
     * It delegates to the concrete strategy, then normalizes the result into
     * the fields already stored on the Application model.
     */
    public function evaluate(Application $application, array $payload): array
    {
        $result = $this->strategy->evaluate($application, $payload);

        return [
            'assessment_type' => $this->strategy->key(),
            'general_score' => $result['general_score'],
            'final_score' => $result['score'],
            'comments' => $result['comments'],
            'strengths' => implode(', ', $result['strengths']),
            'weaknesses' => implode(', ', $result['weaknesses']),
            'recommendation' => $result['recommendation'],
            'rubrics' => $result['rubrics'],
        ];
    }

    /**
     * Build a Context from the request key so controllers do not need to know
     * the concrete classes directly.
     */
    public static function fromKey(string $strategyKey): self
    {
        return new self(match ($strategyKey) {
            'technical_assessment', 'scoring_rubric' => new TechnicalAssessmentStrategy(),
            'hr_feedback' => new HrFeedbackStrategy(),
            'behavioral_assessment' => new BehavioralAssessmentStrategy(),
            default => throw new InvalidArgumentException("Unknown evaluation strategy [{$strategyKey}]."),
        });
    }

}
