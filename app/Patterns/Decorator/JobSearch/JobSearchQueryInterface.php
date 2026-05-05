<?php

namespace App\Patterns\Decorator\JobSearch;

use Illuminate\Database\Eloquent\Builder;

/**
 * Interface JobSearchQueryInterface
 *
 * This is the Component interface for the Decorator pattern used in Job Search Filtering.
 * It defines the common operation that both the base component (BaseJobSearchQuery)
 * and all decorators (filters) must implement. By depending on this interface,
 * the client code can dynamically compose queries without knowing the specific
 * combination of filters applied.
 */
interface JobSearchQueryInterface
{
    /**
     * Get the Eloquent query builder instance.
     * 
     * This method applies any relevant filters or conditions to the query
     * and returns the resulting Builder object for further chaining or execution.
     *
     * @return Builder The Eloquent query builder instance.
     */
    public function getQuery(): Builder;
}
