<?php

namespace App\Http\Controllers;

use App\Events\ApplicationStatusUpdated;
use App\Models\Application;
use App\Models\Job;
use App\States\Application\ApplicationStateFactory;
use App\Strategies\InterviewEvaluation\HrFeedbackStrategy;
use App\Strategies\InterviewEvaluation\ScoringRubricStrategy;
use App\Strategies\InterviewEvaluation\TechnicalAssessmentStrategy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApplicationController extends Controller
{
    public function recruiterIndex(Request $request)
    {
        abort_unless(auth()->user()->canRecruit(), 403);

        $search = $request->input('search', '');
        $status = $request->input('status', '');
        $jobId = $request->input('job_id', '');

        $recruiterJobs = Job::where('user_id', auth()->id())->with('companyProfile')->latest()->get();

        $query = Application::with(['job.companyProfile', 'candidate.user', 'user'])
            ->whereHas('job', fn ($q) => $q->where('user_id', auth()->id()));

        if ($search) {
            $query->where(function ($scoped) use ($search) {
                $scoped->whereHas('candidate', function ($q) use ($search) {
                    $q->where('full_name', 'like', "%{$search}%")
                        ->orWhere('portfolio', 'like', "%{$search}%")
                        ->orWhere('details', 'like', "%{$search}%");
                })->orWhereHas('user', function ($q) use ($search) {
                    $q->where('email', 'like', "%{$search}%");
                });
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($jobId) {
            $query->where('job_id', $jobId);
        }

        $applications = $query->latest()->get();

        $jobs = $recruiterJobs->map(function ($job) use ($applications) {
            $job->setRelation('applications', $applications->where('job_id', $job->id)->values());

            return $job;
        })->filter(fn ($job) => $job->applications->count() > 0 || ! request()->anyFilled(['search', 'status', 'job_id']));

        return view('applications.recruiter_index', compact('jobs', 'recruiterJobs', 'search', 'status', 'jobId'));
    }

    public function index()
    {
        $applications = Application::with(['job.companyProfile', 'candidate'])
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('applications.index', compact('applications'));
    }

    public function store(Request $request, Job $job)
    {
        abort_unless(auth()->user()->isCandidate(), 403, 'Only candidates can apply.');

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

        $cvPath = $request->hasFile('cv_file')
            ? $request->file('cv_file')->store('cvs', 'public')
            : null;

        Application::create([
            'job_id' => $job->id,
            'user_id' => auth()->id(),
            'candidate_id' => auth()->user()->candidate?->id,
            'cover_letter' => $request->cover_letter,
            'cv_path' => $cvPath,
            'status' => 'applied',
            'status_updated_at' => now(),
        ]);

        return redirect()->route('applications.index')->with('success', 'Application submitted successfully.');
    }

    public function show(Application $application)
    {
        $application->load(['job.companyProfile', 'candidate.user', 'job.recruiter.user', 'user']);

        if ($application->user_id !== auth()->id() && $application->job->user_id !== auth()->id()) {
            abort(403);
        }

        return view('applications.show', compact('application'));
    }

    public function destroy(Application $application)
    {
        abort_unless($application->user_id === auth()->id(), 403);

        if ($application->status !== 'applied') {
            return back()->with('error', 'Only applied applications can be withdrawn.');
        }

        $application->delete();

        return redirect()->route('dashboard')->with('success', 'Application withdrawn successfully.');
    }

    public function updateStatus(Request $request, Application $application)
    {
        abort_unless($application->job->user_id === auth()->id(), 403);

        $validated = $request->validate([
            'status' => 'required|in:applied,shortlisted,interview_scheduled,hired,rejected',
            'notes' => 'nullable|string',
            'evaluation_strategy' => 'nullable|in:scoring_rubric,technical_assessment,hr_feedback',
            'evaluation_score' => 'nullable|numeric',
            'evaluation_summary' => 'nullable|string',
        ]);

        $currentState = ApplicationStateFactory::make($application);
        $targetStatus = $validated['status'];

        if ($targetStatus !== $application->status && ! $currentState->canTransitionTo($targetStatus)) {
            return back()->with('error', "Cannot move an application from {$application->status} to {$targetStatus}.");
        }

        $evaluation = [];
        $strategyKey = $validated['evaluation_strategy'] ?? null;

        if ($strategyKey) {
            $strategy = match ($strategyKey) {
                'technical_assessment' => new TechnicalAssessmentStrategy(),
                'hr_feedback' => new HrFeedbackStrategy(),
                default => new ScoringRubricStrategy(),
            };

            $evaluation = $strategy->evaluate($application, [
                'score' => $validated['evaluation_score'] ?? null,
                'scores' => [$validated['evaluation_score'] ?? null],
                'summary' => $validated['evaluation_summary'] ?? null,
            ]);
        }

        $oldStatus = $application->status;

        $application->update([
            'status' => $targetStatus,
            'status_updated_at' => now(),
            'notes' => $validated['notes'] ?? $application->notes,
            ...$evaluation,
        ]);

        if ($oldStatus !== $targetStatus) {
            event(new ApplicationStatusUpdated($application->fresh(['job', 'user']), $targetStatus));
        }

        $redirect = $request->input('_pipeline_redirect');
        if ($redirect) {
            return redirect($redirect)->with('success', 'Status updated.');
        }

        return back()->with('success', 'Application updated.');
    }
}
