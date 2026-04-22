<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\Http\Request;

class JobController extends Controller
{
    public function index(\Illuminate\Http\Request $request)
    {
        $search   = $request->input('search', '');
        $location = $request->input('location', '');
        $jobType  = $request->input('job_type', '');

        $query = Job::latest();

        $strategies = [
            'search'   => \App\Strategies\JobSearch\KeywordFilterStrategy::class,
            'location' => \App\Strategies\JobSearch\LocationFilterStrategy::class,
            'job_type' => \App\Strategies\JobSearch\JobTypeFilterStrategy::class,
        ];

        foreach ($strategies as $key => $strategyClass) {
            if ($request->filled($key)) {
                $strategy = new $strategyClass();
                $query = $strategy->apply($query, $request->input($key));
            }
        }

        $jobs = $query->get();

        // Distinct locations for filter dropdown
        $locations = Job::select('location')->distinct()->orderBy('location')->pluck('location');

        return view('jobs.index', compact('jobs', 'search', 'location', 'jobType', 'locations'));
    }

    public function create()
    {
        if (auth()->user()->role !== 'recruiter') {
            abort(403, 'Only recruiters can post jobs.');
        }

        return view('jobs.create');
    }

    public function store(Request $request)
    {
        if (auth()->user()->role !== 'recruiter') {
            abort(403, 'Only recruiters can post jobs.');
        }

        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'company'     => 'required|string|max:255',
            'location'    => 'required|string|max:255',
            'salary'      => 'nullable|numeric',
            'description' => 'required|string',
            'job_type'    => 'nullable|in:full-time,part-time,remote',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['status']  = 'open';

        Job::create($validated);

        return redirect()->route('jobs.index')->with('success', 'Job posted successfully.');
    }

    public function show(Job $job)
    {
        $job->load('applications.user');
        return view('jobs.show', compact('job'));
    }

    public function edit(Job $job)
    {
        if ($job->user_id !== auth()->id()) {
            abort(403);
        }
        return view('jobs.edit', compact('job'));
    }

    public function update(Request $request, Job $job)
    {
        if ($job->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'company'     => 'required|string|max:255',
            'location'    => 'required|string|max:255',
            'salary'      => 'nullable|numeric',
            'description' => 'required|string',
            'job_type'    => 'nullable|in:full-time,part-time,remote',
        ]);

        $job->update($validated);

        return redirect()->route('dashboard')->with('success', 'Job updated successfully.');
    }

    public function close(Job $job)
    {
        if ($job->user_id !== auth()->id()) {
            abort(403);
        }

        $job->update(['status' => 'closed']);

        return back()->with('success', 'Job closed successfully.');
    }

    public function pipeline(Job $job, \Illuminate\Http\Request $request)
    {
        if ($job->user_id !== auth()->id()) {
            abort(403);
        }

        $search = $request->input('search', '');

        $query = $job->applications()->with('user');

        if ($search) {
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('skills', 'like', "%{$search}%");
            });
        }

        $applications = $query->get();

        $columns = [
            'pending'     => $applications->where('status', 'pending')->values(),
            'shortlisted' => $applications->where('status', 'shortlisted')->values(),
            'interview'   => $applications->where('status', 'interview')->values(),
            'rejected'    => $applications->where('status', 'rejected')->values(),
        ];

        return view('jobs.pipeline', compact('job', 'columns', 'search'));
    }

    public function destroy(Job $job)
    {
        if ($job->user_id !== auth()->id()) {
            abort(403);
        }
        $job->delete();
        return redirect()->route('dashboard')->with('success', 'Job deleted successfully.');
    }
}