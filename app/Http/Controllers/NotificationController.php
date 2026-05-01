<?php

namespace App\Http\Controllers;

use App\Models\AppNotification;
use App\Models\Application;
use App\Models\ApplicationEvaluation;

class NotificationController extends Controller
{
    /**
     * Show all notifications for the authenticated candidate.
     */
    public function index()
    {
        $notifications = AppNotification::where('user_id', auth()->id())
            ->latest()
            ->paginate(15);

        // Mark all as read when viewing the full list
        AppNotification::where('user_id', auth()->id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return view('notifications.index', compact('notifications'));
    }

    /**
     * Mark a single notification as read via AJAX or form POST.
     */
    public function markRead(AppNotification $notification)
    {
        if ($notification->user_id !== auth()->id()) {
            abort(403);
        }

        $notification->update(['is_read' => true]);

        return back()->with('success', 'Notification marked as read.');
    }

    public function open(AppNotification $notification)
    {
        if ($notification->user_id !== auth()->id()) {
            abort(403);
        }

        $notification->update(['is_read' => true]);

        $evaluation = $this->resolveEvaluationTarget($notification);

        if ($evaluation) {
            return redirect(route('applications.show', $evaluation->application).'#evaluation-'.$evaluation->id);
        }

        if ($notification->subject_type === Application::class) {
            $application = Application::find($notification->subject_id);

            if ($application && $application->user_id === auth()->id()) {
                return redirect()->route('applications.show', $application);
            }
        }

        return redirect()->route('notifications.index')->with('error', 'This notification is no longer linked to an available record.');
    }

    private function resolveEvaluationTarget(AppNotification $notification): ?ApplicationEvaluation
    {
        if ($notification->subject_type === ApplicationEvaluation::class) {
            $evaluation = ApplicationEvaluation::with('application')->find($notification->subject_id);

            if ($evaluation && $evaluation->application?->user_id === auth()->id()) {
                return $evaluation;
            }
        }

        if (! str_starts_with($notification->type, 'evaluation-')) {
            return null;
        }

        $assessmentType = collect([
            'technical_assessment' => 'technical assessment',
            'hr_feedback' => 'hr feedback',
            'behavioral_assessment' => 'behavioral assessment',
        ])->search(fn ($label) => str_contains(strtolower($notification->message), $label));

        $assessmentType = $assessmentType === false ? null : $assessmentType;

        return ApplicationEvaluation::with('application')
            ->whereHas('application', fn ($query) => $query->where('user_id', auth()->id()))
            ->when($assessmentType, fn ($query) => $query->where('assessment_type', $assessmentType))
            ->latest('updated_at')
            ->first();
    }

    /**
     * Mark ALL notifications as read.
     */
    public function markAllRead()
    {
        AppNotification::where('user_id', auth()->id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return back()->with('success', 'All notifications marked as read.');
    }

    public function destroy(AppNotification $notification)
    {
        if ($notification->user_id !== auth()->id()) {
            abort(403);
        }

        $notification->delete();

        return back()->with('success', 'Notification deleted.');
    }
}
