<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CompanyController extends Controller
{
    public function index(Request $request): View
    {
        abort_unless(auth()->user()->isCandidate(), 403);

        $search = trim((string) $request->input('search', ''));

        $companies = Company::withCount(['verifiedRecruiters', 'followers', 'openJobs'])
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($scoped) use ($search) {
                    $scoped->where('company_name', 'like', "%{$search}%")
                        ->orWhere('industry', 'like', "%{$search}%")
                        ->orWhere('location', 'like', "%{$search}%");
                });
            })
            ->orderBy('company_name')
            ->get();

        $followedCompanyIds = auth()->user()->candidate
            ? auth()->user()->candidate->followedCompanies()->pluck('companies.id')->all()
            : [];

        return view('companies.index', compact('companies', 'search', 'followedCompanyIds'));
    }
}
