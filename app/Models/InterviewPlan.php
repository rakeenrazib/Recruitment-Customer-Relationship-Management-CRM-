<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InterviewPlan extends Model
{
    protected $fillable = [
        'job_id',
        'plan_type',
        'evaluation_strategy',
        'stages',
    ];

    protected function casts(): array
    {
        return [
            'stages' => 'array',
        ];
    }

    public function job()
    {
        return $this->belongsTo(Job::class);
    }
}
