<?php

namespace App\Observers;

use App\Models\Company;
use App\Models\Job;
use App\Patterns\Observer\CompanyFollowers\CompanyFollowerNotificationObserver;
use App\Patterns\Observer\CompanyFollowers\CompanyFollowerSubject;

class JobObserver
{
    /**
     * This Laravel observer triggers the classroom-style Observer pattern when
     * a recruiter opens a job that followers should hear about.
     */
    public function created(Job $job): void
    {
        if ($job->status !== 'open') {
            return;
        }

        $company = Company::with('followers.user')->find($job->company_id);

        if (! $company) {
            return;
        }

        $subject = new CompanyFollowerSubject();
        $subject->setNotificationDetails(
            "New role: {$job->title} at {$company->company_name}.",
            'job-alert',
            'Job',
            $job->id,
        );

        foreach ($company->followers as $candidate) {
            new CompanyFollowerNotificationObserver($subject, $candidate);
        }

        $subject->notifyObservers();
    }

    public function updated(Job $job): void
    {
        if (! $job->wasChanged('status') || $job->status !== 'open') {
            return;
        }

        $this->created($job);
    }
}
