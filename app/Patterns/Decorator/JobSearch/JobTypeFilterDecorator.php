<?php

namespace App\Patterns\Decorator\JobSearch;

use Illuminate\Database\Eloquent\Builder;

/**
 * Class JobTypeFilterDecorator
 *
 * This is a Concrete Decorator in the Decorator pattern. It adds the capability
 * to filter the job search query by a specific job type (e.g., full-time, part-time).
 * It intercepts the query retrieval from the wrapped component and appends the
 * job type constraint.
 */
class JobTypeFilterDecorator extends JobSearchQueryDecorator
{
    /**
     * The type of job to filter by.
     *
     * @var string
     */
    protected string $jobType;

    /**
     * JobTypeFilterDecorator constructor.
     *
     * @param JobSearchQueryInterface $wrapped The component being decorated.
     * @param string                  $jobType The type of job (e.g., 'full-time').
     */
    public function __construct(JobSearchQueryInterface $wrapped, string $jobType)
    {
        parent::__construct($wrapped);
        $this->jobType = $jobType;
    }

    /**
     * Get the modified query builder.
     *
     * Obtains the query from the decorated component and appends an exact match
     * condition for the job type field.
     *
     * @return Builder
     */
    public function getQuery(): Builder
    {
        $query = parent::getQuery();
        
        return $query->where('job_type', $this->jobType);
    }
}
