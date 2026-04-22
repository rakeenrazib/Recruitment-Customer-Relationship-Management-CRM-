<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Application;
use App\Models\AppNotification;
use App\Models\Job;
use App\Mail\InterviewScheduledMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class ApplicationController extends Controller
{
    public function recruiterIndex(Request $request)
    {
        if (auth()->user()->role !== 'recruiter') {
            abort(403);
        }

        $search   = $request->input('search', '');
        $status   = $request->input('status', '');
        $jobId    = $request->input('job_id', '');

        // All jobs posted by this recruiter (for the job filter dropdown)
        $recruiterJobs = Job::where('user_id', auth()->id())->latest()->get();

        $query = \App\Models\Application::with(['job', 'user'])
            ->whereHas('job', fn($q) => $q->where('user_id', auth()->id()));

        if ($search) {
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('skills', 'like', "%{$search}%");
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($jobId) {
            $query->where('job_id', $jobId);
        }

        $applications = $query->latest()->get();

        // Group applications under their jobs for the grouped view
        $jobs = $recruiterJobs->map(function ($job) use ($applications) {
            $job->setRelation('applications', $applications->where('job_id', $job->id)->values());
            return $job;
        })->filter(fn($job) => $job->applications->count() > 0 || !request()->anyFilled(['search','status','job_id']));

        return view('applications.recruiter_index', compact('jobs', 'recruiterJobs', 'search', 'status', 'jobId'));
    }

    public function index()
    {
        $applications = Application::with('job')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('applications.index', compact('applications'));
    }

    public function store(Request $request, Job $job)
    {
        if (auth()->user()->role !== 'candidate') {
            abort(403, 'Only candidates can apply.');
        }

        $request->validate([
            'cover_letter' => 'nullable|string',
            'cv_file' => 'nullable|file|mimes:pdf|max:2048',
        ]);

        $alreadyApplied = Application::where('job_id', $job->id)
            ->where('user_id', auth()->id())
            ->exists();

        if ($alreadyApplied) {
            return redirect()->route('jobs.show', $job)->with('error', 'You already applied to this job.');
        }

        $cvPath = null;
        if ($request->hasFile('cv_file')) {
            $cvPath = $request->file('cv_file')->store('cvs', 'public');
        }

        Application::create([
            'job_id' => $job->id,
            'user_id' => auth()->id(),
            'cover_letter' => $request->cover_letter,
            'cv_path' => $cvPath,
            'status' => 'pending',
        ]);

        return redirect()->route('applications.index')->with('success', 'Application submitted successfully.');
    }
    public function show(Application $application)
    {
        $application->load(['job', 'user']);
        if ($application->user_id !== auth()->id() && $application->job->user_id !== auth()->id()) {
            abort(403);
        }
        return view('applications.show', compact('application'));
    }

    public function destroy(Application $application)
    {
        if ($application->user_id !== auth()->id()) {
            abort(403);
        }

        if ($application->status !== 'pending') {
            return back()->with('error', 'Only pending applications can be withdrawn.');
        }

        $application->delete();
        return redirect()->route('dashboard')->with('success', 'Application withdrawn successfully.');
    }

    public function updateStatus(Request $request, Application $application)
    {
        if ($application->job->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:pending,shortlisted,interview,rejected',
            'notes'  => 'nullable|string',
        ]);

        $oldStatus = $application->status;
        $newStatus = $request->status;

        $application->update([
            'status' => $newStatus,
            'notes'  => $request->notes ?? $application->notes,
        ]);

        if ($oldStatus !== $newStatus) {
            event(new \App\Events\ApplicationStatusUpdated($application, $newStatus));
        }

        $redirect = $request->input('_pipeline_redirect');
        if ($redirect) {
            return redirect($redirect)->with('success', 'Status updated.');
        }

        return back()->with('success', 'Application updated.');
    }
}