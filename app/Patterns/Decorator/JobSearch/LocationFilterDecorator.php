<?php

namespace App\Patterns\Decorator\JobSearch;

use Illuminate\Database\Eloquent\Builder;

/**
 * Class LocationFilterDecorator
 *
 * This is a Concrete Decorator in the Decorator pattern. It dynamically adds
 * location-based filtering to the job search query. It wraps around the component
 * and appends a condition to match jobs within the specified location.
 */
class LocationFilterDecorator extends JobSearchQueryDecorator
{
    /**
     * The location string to filter by.
     *
     * @var string
     */
    protected string $location;

    /**
     * LocationFilterDecorator constructor.
     *
     * @param JobSearchQueryInterface $wrapped  The component being decorated.
     * @param string                  $location The location to search for.
     */
    public function __construct(JobSearchQueryInterface $wrapped, string $location)
    {
        parent::__construct($wrapped);
        $this->location = $location;
    }

    /**
     * Get the modified query builder.
     *
     * Retrieves the builder from the wrapped component and applies the location
     * search constraint using a 'LIKE' clause.
     *
     * @return Builder
     */
    public function getQuery(): Builder
    {
        $query = parent::getQuery();
        
        return $query->where('location', 'like', "%{$this->location}%");
    }
}
