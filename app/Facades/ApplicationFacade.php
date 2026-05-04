<?php

namespace App\Facades;

use App\Events\ApplicationStatusUpdated;
use App\Factories\InterviewFactory;
use App\Models\Application;
use InvalidArgumentException;

/**
 * ApplicationFacade
 *
 * Facade Pattern: provides a single, simplified entry point that orchestrates
 * the full application-processing workflow by delegating to the system's
 * already-existing building blocks:
 *
 *   • Application model       — persistence
 *   • InterviewFactory        — strategy selection
 *   • ApplicationStatusUpdated event — status notifications (Observer handles
 *                                       the rest automatically, untouched)
 *
 * NON-INVASIVE: no existing file is modified.
 * Controllers, Observer, Strategy, Singleton, Decorator, and both existing
 * factories (InterviewPlanFactory, UserFactory) are completely untouched.
 */
class ApplicationFacade
{
    /**
     * Process an application submission end-to-end.
     *
     * Steps performed:
     *   1. Persist the application via the existing Application model.
     *   2. Optionally run an evaluation strategy (via InterviewFactory)
     *      when 'evaluation_method' is present in $data.
     *   3. Fire ApplicationStatusUpdated so the existing Listener/Observer
     *      pipeline (SendApplicationStatusNotification, etc.) handles
     *      notifications automatically — this facade never touches them.
     *
     * @param  array{
     *     job_id: int,
     *     user_id: int,
     *     candidate_id: int,
     *     status: string,
     *     cover_letter?: string,
     *     cv_path?: string,
     *     notes?: string,
     *     evaluation_method?: string,
     *     evaluation_payload?: array,
     * } $data
     *
     * @return Application  The freshly created (and optionally evaluated) application.
     *
     * @throws InvalidArgumentException  Propagated from InterviewFactory when an
     *                                   unknown evaluation_method is supplied.
     *
     * Example usage (no integration into controllers required):
     *
     *   use App\Facades\ApplicationFacade;
     *
     *   $application = ApplicationFacade::process([
     *       'job_id'            => 1,
     *       'user_id'           => 42,
     *       'candidate_id'      => 7,
     *       'status'            => 'pending',
     *       'cover_letter'      => 'I am excited to apply...',
     *       'evaluation_method' => 'technical',   // optional
     *       'evaluation_payload'=> $request->all(), // optional
     *   ]);
     */
    public static function process(array $data): Application
    {
        // ── Step 1: Persist the application ─────────────────────────────────
        // Delegates entirely to the existing Application model.
        // Only fields present in $fillable are accepted; extras are ignored.
        $application = Application::create($data);

        // ── Step 2: Optional evaluation strategy via InterviewFactory ────────
        // InterviewFactory (app/Factories/InterviewFactory.php) is called only
        // when the caller explicitly provides an evaluation_method key.
        // The result is returned but NOT stored here — persistence is the
        // controller's or a dedicated service's concern.
        if (isset($data['evaluation_method'])) {
            $strategy = InterviewFactory::create($data['evaluation_method']);
            $strategy->evaluate($application, $data['evaluation_payload'] ?? $data);
        }

        // ── Step 3: Fire the existing status event ───────────────────────────
        // Dispatching ApplicationStatusUpdated triggers the full existing
        // listener chain (SendApplicationStatusNotification → Decorator stack →
        // Observer) without this facade knowing or caring about those details.
        event(new ApplicationStatusUpdated(
            $application->fresh(['job', 'user']),
            $application->status
        ));

        return $application;
    }
}
