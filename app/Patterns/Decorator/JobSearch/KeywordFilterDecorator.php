<?php

namespace App\Patterns\Decorator\JobSearch;

use Illuminate\Database\Eloquent\Builder;

/**
 * Class KeywordFilterDecorator
 *
 * This is a Concrete Decorator in the Decorator pattern. It adds the responsibility
 * of filtering the job search query by a specific keyword. It wraps around a
 * Component (BaseJobSearchQuery or another Decorator) and appends conditions to
 * search for the keyword within the job title, description, or company name.
 */
class KeywordFilterDecorator extends JobSearchQueryDecorator
{
    /**
     * The keyword to search for.
     *
     * @var string
     */
    protected string $keyword;

    /**
     * KeywordFilterDecorator constructor.
     *
     * @param JobSearchQueryInterface $wrapped The component being decorated.
     * @param string                  $keyword The search keyword.
     */
    public function __construct(JobSearchQueryInterface $wrapped, string $keyword)
    {
        parent::__construct($wrapped);
        $this->keyword = $keyword;
    }

    /**
     * Get the modified query builder.
     *
     * Fetches the query builder from the decorated component and appends the
     * keyword search conditions (title, description, or company matching).
     *
     * @return Builder
     */
    public function getQuery(): Builder
    {
        $query = parent::getQuery();
        
        return $query->where(function ($q) {
            $q->where('title', 'like', "%{$this->keyword}%")
              ->orWhere('description', 'like', "%{$this->keyword}%")
              ->orWhere('company', 'like', "%{$this->keyword}%");
        });
    }
}
