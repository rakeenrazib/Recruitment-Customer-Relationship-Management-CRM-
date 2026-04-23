<?php

namespace App\Observers;

use App\Models\Company;
use App\Models\Job;

class JobObserver
{
    public function created(Job $job): void
    {
        if ($job->status !== 'open') {
            return;
        }

        $company = Company::with('followers.user')->find($job->company_id);

        if (! $company) {
            return;
        }

        foreach ($company->followers as $candidate) {
            $job->attach(new CandidateCompanyObserver($candidate));
        }

        $job->notifyObservers("New role: {$job->title} at {$company->company_name}.", 'job-alert');
    }

    public function updated(Job $job): void
    {
        if (! $job->wasChanged('status') || $job->status !== 'open') {
            return;
        }

        $this->created($job);
    }
}
