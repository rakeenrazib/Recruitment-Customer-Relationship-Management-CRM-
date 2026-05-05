<?php

namespace App\Http\Controllers;

use App\Factories\InterviewPlanFactory;
use App\Models\Job;
use App\Patterns\Decorator\JobSearch\JobTypeFilterDecorator;
use App\Patterns\Decorator\JobSearch\KeywordFilterDecorator;
use App\Patterns\Decorator\JobSearch\LocationFilterDecorator;
use App\Patterns\Decorator\JobSearch\BaseJobSearchQuery;
use Illuminate\Http\Request;

class JobController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $location = $request->input('location', '');
        $jobType = $request->input('job_type', '');
        $status = $request->input('status', '');

        $query = Job::with(['companyProfile', 'recruiter.user'])->latest();

        $jobQuery = new BaseJobSearchQuery($query);

        if ($request->filled('search')) {
            $jobQuery = new KeywordFilterDecorator($jobQuery, $request->input('search'));
        }
        if ($request->filled('location')) {
            $jobQuery = new LocationFilterDecorator($jobQuery, $request->input('location'));
        }
        if ($request->filled('job_type')) {
            $jobQuery = new JobTypeFilterDecorator($jobQuery, $request->input('job_type'));
        }

        $query = $jobQuery->getQuery();

        if (auth()->user()->isRecruiter()) {
            $query->where('user_id', auth()->id());

            if ($status === 'active') {
                $query->where('status', 'open');
            } elseif ($status === 'closed') {
                $query->where('status', 'closed');
            }
        } elseif (auth()->user()->isCompany()) {
            $query->where('company_id', auth()->user()->company?->id);
        } elseif (! auth()->user()->canRecruit()) {
            $query->open();
        }

        $jobs = $query->get();
        $locations = (clone $query)->select('location')->distinct()->orderBy('location')->pluck('location');

        return view('jobs.index', compact('jobs', 'search', 'location', 'jobType', 'status', 'locations'));
    }

    public function create()
    {
        abort_unless(auth()->user()->canRecruit(), 403, 'Only verified recruiters can post jobs.');

        return view('jobs.create', [
            'company' => auth()->user()->recruiter->company,
        ]);
    }

    public function store(Request $request)
    {
        abort_unless(auth()->user()->canRecruit(), 403, 'Only verified recruiters can post jobs.');

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'salary' => 'nullable|numeric',
            'description' => 'required|string',
            'requirements' => 'nullable|string',
            'job_type' => 'nullable|in:full-time,part-time,remote',
        ]);

        $recruiter = auth()->user()->recruiter()->with('company')->firstOrFail();

        $job = Job::create([
            ...$validated,
            'user_id' => auth()->id(),
            'recruiter_id' => $recruiter->id,
            'company_id' => $recruiter->company_id,
            'company' => $recruiter->company->company_name,
            'status' => 'open',
        ]);

        InterviewPlanFactory::createForJob($job);

        return redirect()->route('jobs.index')->with('success', 'Job posted successfully.');
    }

    public function show(Job $job)
    {
        if (auth()->user()->isRecruiter()) {
            abort_unless($job->user_id === auth()->id(), 403);
        }

        if (auth()->user()->isCompany()) {
            abort_unless($job->company_id === auth()->user()->company?->id, 403);
        }

        $job->load([
            'companyProfile.user',
            'recruiter.user',
            'interviewPlan',
            'applications.candidate.user',
            'applications.user',
        ]);

        return view('jobs.show', compact('job'));
    }

    public function edit(Job $job)
    {
        abort_unless($job->user_id === auth()->id(), 403);

        return view('jobs.edit', compact('job'));
    }

    public function update(Request $request, Job $job)
    {
        abort_unless($job->user_id === auth()->id(), 403);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'salary' => 'nullable|numeric',
            'description' => 'required|string',
            'requirements' => 'nullable|string',
            'job_type' => 'nullable|in:full-time,part-time,remote',
        ]);

        $job->update($validated + ['company' => $job->companyProfile?->company_name ?? $job->company]);
        $strategy = $job->interviewPlan?->evaluation_strategy;
        if ($strategy === 'scoring_rubric') {
            $strategy = 'technical_assessment';
        }

        InterviewPlanFactory::createForJob($job, $strategy ?? 'technical_assessment');

        return redirect()->route('dashboard')->with('success', 'Job updated successfully.');
    }

    public function close(Job $job)
    {
        abort_unless(auth()->user()->canRecruit() && $job->user_id === auth()->id(), 403);

        $job->update(['status' => 'closed']);

        return back()->with('success', 'Job closed.');
    }

    public function pipeline(Job $job, Request $request)
    {
        abort_unless($job->user_id === auth()->id(), 403);

        $search = $request->input('search', '');
        $stage = $request->input('stage', 'applied');

        $query = $job->applications()->with(['candidate.user', 'user']);

        if ($search) {
            $query->where(function ($scoped) use ($search) {
                $scoped->whereHas('candidate', function ($q) use ($search) {
                    $q->where('full_name', 'like', "%{$search}%")
                        ->orWhere('bio', 'like', "%{$search}%")
                        ->orWhere('portfolio', 'like', "%{$search}%");
                })->orWhereHas('user', function ($q) use ($search) {
                    $q->where('email', 'like', "%{$search}%");
                });
            });
        }

        $applications = $query->get();

        $columns = [
            'applied' => $applications->whereIn('status', ['applied', 'pending'])->values(),
            'shortlisted' => $applications->where('status', 'shortlisted')->values(),
            'interview_scheduled' => $applications->whereIn('status', ['interview_scheduled', 'interview'])->values(),
            'hired' => $applications->where('status', 'hired')->values(),
            'rejected' => $applications->where('status', 'rejected')->values(),
        ];

        if (! array_key_exists($stage, $columns)) {
            $stage = 'applied';
        }

        return view('jobs.pipeline', compact('job', 'columns', 'search', 'stage'));
    }

    public function destroy(Job $job)
    {
        abort_unless($job->user_id === auth()->id(), 403);
        $job->delete();

        return redirect()->route('dashboard')->with('success', 'Job deleted successfully.');
    }
}
