<?php

namespace App\Strategies\JobSearch;

class LocationFilterStrategy implements JobSearchStrategyInterface
{
    public function apply($query, $value)
    {
        return $query->where('location', 'like', "%{$value}%");
    }
}
