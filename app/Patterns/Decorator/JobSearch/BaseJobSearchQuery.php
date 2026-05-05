<?php

namespace App\Patterns\Decorator\JobSearch;

use Illuminate\Database\Eloquent\Builder;

/**
 * Class BaseJobSearchQuery
 *
 * This is the Concrete Component in the Decorator pattern. It represents the initial,
 * unfiltered job search query. It wraps the base Eloquent Builder instance and serves
 * as the foundation upon which various filter decorators will be stacked dynamically.
 */
class BaseJobSearchQuery implements JobSearchQueryInterface
{
    /**
     * The underlying Eloquent query builder.
     *
     * @var Builder
     */
    protected Builder $query;

    /**
     * BaseJobSearchQuery constructor.
     *
     * @param Builder $query The initial Eloquent query builder for the Job model.
     */
    public function __construct(Builder $query)
    {
        $this->query = $query;
    }

    /**
     * Get the base query builder.
     *
     * In this base component, it simply returns the unmodified query builder.
     * Subsequent decorators will wrap this query and append their own conditions.
     *
     * @return Builder
     */
    public function getQuery(): Builder
    {
        return $this->query;
    }
}
