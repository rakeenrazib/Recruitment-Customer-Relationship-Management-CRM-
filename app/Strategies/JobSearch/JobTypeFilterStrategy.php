<?php

namespace App\Strategies\JobSearch;

class JobTypeFilterStrategy implements JobSearchStrategyInterface
{
    public function apply($query, $value)
    {
        return $query->where('job_type', $value);
    }
}
