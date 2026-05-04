<?php

namespace App\Http\Controllers;

use App\Events\ApplicationStatusUpdated;
use App\Models\Application;
use App\Models\ApplicationEvaluation;
use App\Models\Job;
use App\Patterns\Strategy\InterviewEvaluation\InterviewEvaluationContext;
use App\Services\NotificationService;
use App\Patterns\Strategy\StatusTransition\TransitionStrategyContext;
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
        $application->load(['job.companyProfile', 'candidate.user', 'job.recruiter.user', 'user', 'evaluations.recruiter.user']);

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
        ]);

        $transitionStrategy = TransitionStrategyContext::getStrategy($application->status);
        $targetStatus = $validated['status'];

        if ($targetStatus !== $application->status && ! $transitionStrategy->validateTransition($targetStatus)) {
            return back()->with('error', "Cannot move an application from {$application->status} to {$targetStatus}.");
        }

        $oldStatus = $application->status;

        $application->update([
            'status' => $targetStatus,
            'status_updated_at' => now(),
            'notes' => $validated['notes'] ?? $application->notes,
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

    /**
     * Create a new evaluation record for one assessment type.
     * Each application can have one technical, one HR, and one behavioral
     * evaluation, which is why the records live in their own table now.
     */
    public function storeEvaluation(Request $request, Application $application)
    {
        abort_unless($application->job->user_id === auth()->id(), 403);

        $validated = $this->validateEvaluationRequest($request, $application, false);
        $evaluation = $this->buildEvaluationPayload($request, $application, $validated);

        $record = ApplicationEvaluation::create([
            ...$evaluation,
            'application_id' => $application->id,
            'recruiter_id' => auth()->user()->recruiter->id,
        ]);

        $this->sendEvaluationNotification($application, $record, 'published');

        return back()->with('success', 'Evaluation published.');
    }

    public function updateEvaluation(Request $request, Application $application, ApplicationEvaluation $evaluation)
    {
        abort_unless($application->job->user_id === auth()->id(), 403);
        abort_unless($evaluation->application_id === $application->id, 404);

        $validated = $this->validateEvaluationRequest($request, $application, true, $evaluation);
        $payload = $this->buildEvaluationPayload($request, $application, $validated);

        $evaluation->update($payload);

        $this->sendEvaluationNotification($application, $evaluation, 'updated');

        return back()->with('success', 'Evaluation updated.');
    }

    public function destroyEvaluation(Application $application, ApplicationEvaluation $evaluation)
    {
        abort_unless($application->job->user_id === auth()->id(), 403);
        abort_unless($evaluation->application_id === $application->id, 404);

        $evaluation->delete();

        return back()->with('success', 'Evaluation deleted.');
    }

    private function validateEvaluationRequest(Request $request, Application $application, bool $isUpdate, ?ApplicationEvaluation $evaluation = null): array
    {
        $uniqueRule = function ($attribute, $value, $fail) use ($application, $isUpdate, $evaluation) {
            $query = ApplicationEvaluation::where('application_id', $application->id)
                ->where('assessment_type', $value);

            if ($isUpdate && $evaluation) {
                $query->where('id', '!=', $evaluation->id);
            }

            if ($query->exists()) {
                $fail('This assessment type already exists for the candidate.');
            }
        };

        return $request->validate([
            'assessment_type' => ['required', 'in:technical_assessment,hr_feedback,behavioral_assessment', $uniqueRule],
            'general_score' => 'nullable|numeric|min:0|max:5',
            'comments' => 'nullable|string',
            'strengths' => 'nullable|string',
            'weaknesses' => 'nullable|string',
            'recommendation' => 'nullable|string',
            'coding_rubric' => 'nullable|numeric|min:0|max:5',
            'skill_competency' => 'nullable|numeric|min:0|max:5',
            'plagiarism_detected' => 'nullable|boolean',
            'communication_score' => 'nullable|numeric|min:0|max:5',
            'salary_fit_score' => 'nullable|numeric|min:0|max:5',
            'availability_score' => 'nullable|numeric|min:0|max:5',
            'professionalism_score' => 'nullable|numeric|min:0|max:5',
            'leadership_score' => 'nullable|numeric|min:0|max:5',
            'teamwork_score' => 'nullable|numeric|min:0|max:5',
            'adaptability_score' => 'nullable|numeric|min:0|max:5',
            'conflict_handling_score' => 'nullable|numeric|min:0|max:5',
        ]);
    }

    private function buildEvaluationPayload(Request $request, Application $application, array $validated): array
    {
        $context = InterviewEvaluationContext::fromKey($validated['assessment_type']);

        return $context->evaluate($application, [
            'general_score' => $validated['general_score'] ?? null,
            'comments' => $validated['comments'] ?? null,
            'summary' => $validated['comments'] ?? null,
            'strengths' => $validated['strengths'] ?? null,
            'weaknesses' => $validated['weaknesses'] ?? null,
            'recommendation' => $validated['recommendation'] ?? null,
            'coding_rubric' => $validated['coding_rubric'] ?? null,
            'skill_competency' => $validated['skill_competency'] ?? null,
            'plagiarism_detected' => $request->boolean('plagiarism_detected'),
            'communication_score' => $validated['communication_score'] ?? null,
            'salary_fit_score' => $validated['salary_fit_score'] ?? null,
            'availability_score' => $validated['availability_score'] ?? null,
            'professionalism_score' => $validated['professionalism_score'] ?? null,
            'leadership_score' => $validated['leadership_score'] ?? null,
            'teamwork_score' => $validated['teamwork_score'] ?? null,
            'adaptability_score' => $validated['adaptability_score'] ?? null,
            'conflict_handling_score' => $validated['conflict_handling_score'] ?? null,
        ]);
    }

    private function sendEvaluationNotification(Application $application, ApplicationEvaluation $evaluation, string $action): void
    {
        $label = str_replace('_', ' ', $evaluation->assessment_type);
        $message = $action === 'published'
            ? "A {$label} evaluation has been published for your application to \"{$application->job->title}\"."
            : "A {$label} evaluation has been updated for your application to \"{$application->job->title}\".";

        (new NotificationService())->send(
            $application->user,
            $message,
            'evaluation-'.$action,
            ApplicationEvaluation::class,
            $evaluation->id
        );
    }
}
