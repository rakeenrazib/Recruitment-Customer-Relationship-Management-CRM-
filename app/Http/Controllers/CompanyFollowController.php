<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\RedirectResponse;

class CompanyFollowController extends Controller
{
    public function store(Company $company): RedirectResponse
    {
        abort_unless(auth()->user()->isCandidate(), 403);

        auth()->user()->candidate->followedCompanies()->syncWithoutDetaching([$company->id]);

        return back()->with('success', "You're now following {$company->company_name}.");
    }

    public function destroy(Company $company): RedirectResponse
    {
        abort_unless(auth()->user()->isCandidate(), 403);

        auth()->user()->candidate->followedCompanies()->detach($company->id);

        return back()->with('success', "You unfollowed {$company->company_name}.");
    }
}
