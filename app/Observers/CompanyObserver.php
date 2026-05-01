<?php

namespace App\Observers;

use App\Models\Company;
use App\Patterns\Observer\CompanyFollowers\CompanyFollowerNotificationObserver;
use App\Patterns\Observer\CompanyFollowers\CompanyFollowerSubject;

class CompanyObserver
{
    /**
     * Laravel model observer:
     * this file is not the "design-pattern Subject" itself.
     * Instead, it bridges the Company model lifecycle to the custom Observer
     * pattern classes stored in app/Patterns/Observer/CompanyFollowers.
     */
    public function updated(Company $company): void
    {
        if (! $company->wasChanged(['description', 'location', 'website', 'industry'])) {
            return;
        }

        $company->loadMissing('followers.user');
        $subject = new CompanyFollowerSubject();
        $subject->setNotificationDetails(
            "{$company->company_name} updated its company profile.",
            'company-update',
            'Company',
            $company->id,
        );

        foreach ($company->followers as $candidate) {
            new CompanyFollowerNotificationObserver($subject, $candidate);
        }

        $subject->notifyObservers();
    }
}
