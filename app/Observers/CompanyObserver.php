<?php

namespace App\Observers;

use App\Models\Company;

class CompanyObserver
{
    public function updated(Company $company): void
    {
        if (! $company->wasChanged(['description', 'location', 'website', 'industry'])) {
            return;
        }

        $company->loadMissing('followers.user');

        foreach ($company->followers as $candidate) {
            $company->attach(new CandidateCompanyObserver($candidate));
        }

        $company->notifyObservers("{$company->company_name} updated its company profile.", 'company-update');
    }
}
