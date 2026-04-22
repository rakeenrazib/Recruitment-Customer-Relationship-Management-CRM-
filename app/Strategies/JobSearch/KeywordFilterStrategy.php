<?php

namespace App\Strategies\JobSearch;

class KeywordFilterStrategy implements JobSearchStrategyInterface
{
    public function apply($query, $value)
    {
        return $query->where(function ($q) use ($value) {
            $q->where('title', 'like', "%{$value}%")
              ->orWhere('company', 'like', "%{$value}%");
        });
    }
}
