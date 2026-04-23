<?php

namespace App\Factories;

use App\Models\InterviewPlan;
use App\Models\Job;

class InterviewPlanFactory
{
    public static function createForJob(Job $job, string $strategy = 'scoring_rubric'): InterviewPlan
    {
        $stages = [
            ['name' => 'Screening', 'owner' => 'Recruiter', 'goal' => 'Validate fit and availability'],
            ['name' => 'Technical', 'owner' => 'Hiring Team', 'goal' => 'Assess role-specific capability'],
            ['name' => 'Final', 'owner' => 'Leadership', 'goal' => 'Confirm long-term fit'],
        ];

        if (($job->job_type ?? null) === 'part-time') {
            $stages = [
                ['name' => 'Screening', 'owner' => 'Recruiter', 'goal' => 'Confirm role fit'],
                ['name' => 'Final', 'owner' => 'Hiring Manager', 'goal' => 'Finalize offer decision'],
            ];
        }

        return InterviewPlan::updateOrCreate(
            ['job_id' => $job->id],
            [
                'plan_type' => $job->job_type ?? 'standard',
                'evaluation_strategy' => $strategy,
                'stages' => $stages,
            ],
        );
    }
}
