<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\Company;
use App\Models\Recruiter;
use Illuminate\View\View;

class PublicProfileController extends Controller
{
    public function candidate(Candidate $candidate): View
    {
        $candidate->load('user');

        return view('profiles.candidate', compact('candidate'));
    }

    public function recruiter(Recruiter $recruiter): View
    {
        $recruiter->load([
            'user',
            'company.user',
            'jobs' => fn ($query) => $query->open()->latest(),
        ]);

        return view('profiles.recruiter', compact('recruiter'));
    }

    public function company(Company $company): View
    {
        $company->load([
            'user',
            'verifiedRecruiters.user',
            'openJobs' => fn ($query) => $query->with('recruiter.user')->latest(),
        ]);

        $isFollowing = auth()->check()
            && auth()->user()->candidate
            && auth()->user()->candidate->followedCompanies()->where('company_id', $company->id)->exists();

        $openJobs = $company->openJobs;

        return view('profiles.company', compact('company', 'isFollowing', 'openJobs'));
    }
}
