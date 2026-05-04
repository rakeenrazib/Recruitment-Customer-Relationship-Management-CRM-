<?php

namespace App\Factories;

use App\Patterns\Strategy\InterviewEvaluation\BehavioralAssessmentStrategy;
use App\Patterns\Strategy\InterviewEvaluation\HrFeedbackStrategy;
use App\Patterns\Strategy\InterviewEvaluation\InterviewEvaluationStrategyInterface;
use App\Patterns\Strategy\InterviewEvaluation\TechnicalAssessmentStrategy;
use InvalidArgumentException;

/**
 * InterviewFactory
 *
 * Factory Pattern: centralises the creation of InterviewEvaluationStrategy
 * objects so that callers never need to know which concrete class to
 * instantiate — they only supply a human-readable type key.
 *
 * NON-INVASIVE: nothing outside this file is modified.
 * Existing InterviewPlanFactory, UserFactory, controllers, and all pattern
 * classes (Observer, Strategy, Singleton, Decorator) are completely untouched.
 */
class InterviewFactory
{
    /**
     * Instantiate and return the correct evaluation strategy for the given type.
     *
     * Supported types:
     *   'technical'  → TechnicalAssessmentStrategy
     *   'hr'         → HrFeedbackStrategy
     *   'behavioral' → BehavioralAssessmentStrategy
     *
     * @param  string $type  One of: 'technical', 'hr', 'behavioral'
     * @return InterviewEvaluationStrategyInterface
     *
     * @throws InvalidArgumentException  When an unknown type is supplied.
     *
     * Example usage in a controller:
     *   $strategy = InterviewFactory::create($request->evaluation_method);
     *   $result   = $strategy->evaluate($application, $request->all());
     */
    public static function create(string $type): InterviewEvaluationStrategyInterface
    {
        return match ($type) {
            'technical'  => new TechnicalAssessmentStrategy(),
            'hr'         => new HrFeedbackStrategy(),
            'behavioral' => new BehavioralAssessmentStrategy(),
            default      => throw new InvalidArgumentException(
                "InterviewFactory: unknown evaluation type [{$type}]. " .
                "Supported types are: 'technical', 'hr', 'behavioral'."
            ),
        };
    }
}
