<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $recentApplications = collect();
    $recentJobs = collect();
    $recommendedJobs = collect();
    $stats = [];
    $pendingApplications = collect();
    $shortlistedApplications = collect();
    $notifications = collect();
    $unreadCount = 0;

    if (auth()->user()) {
        if (auth()->user()->role === 'candidate') {
            $recentApplications = \App\Models\Application::with('job')
                ->where('user_id', auth()->id())
                ->latest()
                ->take(5)
                ->get();

            $recommendedJobs = \App\Models\Job::where('status', 'open')
                ->latest()
                ->take(5)
                ->get();

            $stats = [
                'total_applications'     => \App\Models\Application::where('user_id', auth()->id())->count(),
                'pending_applications'   => \App\Models\Application::where('user_id', auth()->id())->where('status', 'pending')->count(),
                'shortlisted_applications' => \App\Models\Application::where('user_id', auth()->id())->where('status', 'shortlisted')->count(),
            ];

            $notifications = \App\Models\AppNotification::where('user_id', auth()->id())
                ->latest()
                ->take(8)
                ->get();

            $unreadCount = \App\Models\AppNotification::where('user_id', auth()->id())
                ->where('is_read', false)
                ->count();

        } elseif (auth()->user()->role === 'recruiter') {
            $recentJobs = \App\Models\Job::where('user_id', auth()->id())
                ->latest()
                ->take(5)
                ->get();

            $pendingApplications = \App\Models\Application::with(['job', 'user'])
                ->whereHas('job', function ($query) {
                    $query->where('user_id', auth()->id());
                })
                ->where('status', 'pending')
                ->latest()
                ->take(5)
                ->get();

            $shortlistedApplications = \App\Models\Application::with(['job', 'user'])
                ->whereHas('job', function ($query) {
                    $query->where('user_id', auth()->id());
                })
                ->where('status', 'shortlisted')
                ->latest()
                ->take(5)
                ->get();

            $stats = [
                'total_jobs'                  => \App\Models\Job::where('user_id', auth()->id())->count(),
                'active_jobs'                 => \App\Models\Job::where('user_id', auth()->id())->where('status', 'open')->count(),
                'total_applications_received' => \App\Models\Application::whereHas('job', function ($query) {
                    $query->where('user_id', auth()->id());
                })->count(),
            ];
        }
    }

    return view('dashboard', compact('recentApplications', 'recentJobs', 'recommendedJobs', 'stats', 'pendingApplications', 'shortlistedApplications', 'notifications', 'unreadCount'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth'])->group(function () {
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Jobs (resource + extras)
    Route::resource('jobs', JobController::class);
    Route::patch('/jobs/{job}/close', [JobController::class, 'close'])->name('jobs.close');
    Route::get('/jobs/{job}/pipeline', [JobController::class, 'pipeline'])->name('jobs.pipeline');

    // Applications
    Route::post('/jobs/{job}/apply', [ApplicationController::class, 'store'])->name('applications.store');
    Route::get('/my-applications', [ApplicationController::class, 'index'])->name('applications.index');
    Route::get('/applications/{application}', [ApplicationController::class, 'show'])->name('applications.show');
    Route::delete('/applications/{application}', [ApplicationController::class, 'destroy'])->name('applications.destroy');
    Route::patch('/applications/{application}/status', [ApplicationController::class, 'updateStatus'])->name('applications.update-status');

    // Recruiter
    Route::get('/recruiter/applications', [ApplicationController::class, 'recruiterIndex'])->name('recruiter.applications');

    // Notifications (candidates)
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'markRead'])->name('notifications.mark-read');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllRead'])->name('notifications.mark-all-read');

    // Placeholders
    Route::get('/saved-jobs', function () { return 'Saved Jobs – Coming Soon'; })->name('jobs.saved');
    Route::get('/search-candidates', function () { return 'Search Candidates – Coming Soon'; })->name('candidates.search');
    Route::get('/create-interview', function () { return 'Create Interview – Coming Soon'; })->name('interviews.create');
});

require __DIR__ . '/auth.php';