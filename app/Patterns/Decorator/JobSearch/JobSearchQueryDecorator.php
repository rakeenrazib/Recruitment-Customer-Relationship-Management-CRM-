<?php

namespace App\Patterns\Decorator\JobSearch;

use Illuminate\Database\Eloquent\Builder;

/**
 * Class JobSearchQueryDecorator
 *
 * This is the Base Decorator class in the Decorator pattern. It implements the
 * same JobSearchQueryInterface as the Concrete Component and maintains a reference
 * to a wrapped JobSearchQueryInterface object. It delegates all operations to this
 * wrapped object, allowing concrete decorators to add their specific behaviors
 * before or after the delegated operation.
 */
abstract class JobSearchQueryDecorator implements JobSearchQueryInterface
{
    /**
     * The component being decorated.
     *
     * @var JobSearchQueryInterface
     */
    protected JobSearchQueryInterface $wrapped;

    /**
     * JobSearchQueryDecorator constructor.
     *
     * @param JobSearchQueryInterface $wrapped The component to be decorated.
     */
    public function __construct(JobSearchQueryInterface $wrapped)
    {
        $this->wrapped = $wrapped;
    }

    /**
     * Get the base query from the wrapped component.
     *
     * Concrete decorators will override this method, call this parent implementation
     * to get the builder, and then apply their specific query constraints.
     *
     * @return Builder
     */
    public function getQuery(): Builder
    {
        return $this->wrapped->getQuery();
    }
}
