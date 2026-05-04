<?php

namespace App\Http\Controllers;

use App\Models\AppNotification;
use App\Models\Application;
use App\Models\Company;
use App\Models\Job;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $user = auth()->user()->loadMissing(['candidate.followedCompanies', 'recruiter.company', 'company.verificationRequests.recruiter.user']);

        $recentApplications = collect();
        $recentJobs = collect();
        $recommendedJobs = collect();
        $stats = [];
        $pendingApplications = collect();
        $shortlistedApplications = collect();
        $activeJobs = collect();
        $closedJobs = collect();
        $notifications = collect();
        $unreadCount = 0;
        $companies = collect();

        if ($user->isCandidate()) {
            $recentApplications = Application::with('job.companyProfile')
                ->where('user_id', $user->id)
                ->latest()
                ->take(5)
                ->get();

            $recommendedJobs = Job::with('companyProfile')
                ->where('status', 'open')
                ->latest()
                ->take(5)
                ->get();

            $stats = [
                'total_applications' => Application::where('user_id', $user->id)->count(),
                'pending_applications' => Application::where('user_id', $user->id)->whereIn('status', ['applied', 'pending'])->count(),
                'shortlisted_applications' => Application::where('user_id', $user->id)->where('status', 'shortlisted')->count(),
                'followed_companies' => $user->candidate?->followedCompanies?->count() ?? 0,
            ];

            $notifications = AppNotification::where('user_id', $user->id)->latest()->take(8)->get();
            $unreadCount = AppNotification::where('user_id', $user->id)->where('is_read', false)->count();
        } elseif ($user->isRecruiter()) {
            $companies = Company::orderBy('company_name')->get(['id', 'company_name']);

            $recentJobs = Job::with('applications')
                ->where('user_id', $user->id)
                ->latest()
                ->take(5)
                ->get();

            $activeJobs = Job::with('applications')
                ->where('user_id', $user->id)
                ->where('status', 'open')
                ->latest()
                ->take(5)
                ->get();

            $closedJobs = Job::with('applications')
                ->where('user_id', $user->id)
                ->where('status', 'closed')
                ->latest()
                ->take(5)
                ->get();

            $pendingApplications = Application::with(['job', 'candidate.user'])
                ->whereHas('job', fn ($query) => $query->where('user_id', $user->id))
                ->whereIn('status', ['applied', 'pending'])
                ->latest()
                ->take(5)
                ->get();

            $shortlistedApplications = Application::with(['job', 'candidate.user'])
                ->whereHas('job', fn ($query) => $query->where('user_id', $user->id))
                ->where('status', 'shortlisted')
                ->latest()
                ->take(5)
                ->get();

            $stats = [
                'total_jobs' => Job::where('user_id', $user->id)->count(),
                'active_jobs' => Job::where('user_id', $user->id)->where('status', 'open')->count(),
                'total_applications_received' => Application::whereHas('job', fn ($query) => $query->where('user_id', $user->id))->count(),
                'verified_status' => $user->recruiter?->isVerified() ? 'Verified' : 'Unverified',
            ];
        } elseif ($user->isCompany()) {
            $recentJobs = Job::with('recruiter.user')
                ->where('company_id', $user->company?->id)
                ->latest()
                ->take(5)
                ->get();

            $stats = [
                'total_recruiters' => $user->company?->recruiters()->count() ?? 0,
                'verified_recruiters' => $user->company?->verifiedRecruiters()->count() ?? 0,
                'followers' => $user->company?->followers()->count() ?? 0,
                'open_jobs' => $user->company?->jobs()->where('status', 'open')->count() ?? 0,
            ];
        }

        return view('dashboard', compact(
            'recentApplications',
            'recentJobs',
            'recommendedJobs',
            'stats',
            'pendingApplications',
            'shortlistedApplications',
            'activeJobs',
            'closedJobs',
            'notifications',
            'unreadCount',
            'user',
            'companies',
        ));
    }
}
