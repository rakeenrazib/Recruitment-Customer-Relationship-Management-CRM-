<?php

use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CompanyFollowController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicProfileController;
use App\Http\Controllers\RecruiterVerificationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }

    return view('welcome');
});

Route::get('/dashboard', DashboardController::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::get('/companies/{company}', [PublicProfileController::class, 'company'])->name('companies.show');
    Route::get('/companies', [CompanyController::class, 'index'])->name('companies.index');
    Route::get('/recruiters/{recruiter}', [PublicProfileController::class, 'recruiter'])->name('recruiters.show');
    Route::get('/candidates/{candidate}', [PublicProfileController::class, 'candidate'])->name('candidates.show');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('jobs', JobController::class);
    Route::patch('/jobs/{job}/close', [JobController::class, 'close'])->name('jobs.close');
    Route::get('/jobs/{job}/pipeline', [JobController::class, 'pipeline'])->name('jobs.pipeline');

    Route::post('/jobs/{job}/apply', [ApplicationController::class, 'store'])->name('applications.store');
    Route::get('/my-applications', [ApplicationController::class, 'index'])->name('applications.index');
    Route::get('/applications/{application}', [ApplicationController::class, 'show'])->name('applications.show');
    Route::delete('/applications/{application}', [ApplicationController::class, 'destroy'])->name('applications.destroy');
    Route::patch('/applications/{application}/status', [ApplicationController::class, 'updateStatus'])->name('applications.update-status');
    Route::get('/recruiter/applications', [ApplicationController::class, 'recruiterIndex'])->name('recruiter.applications');

    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'markRead'])->name('notifications.mark-read');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllRead'])->name('notifications.mark-all-read');
    Route::delete('/notifications/{notification}', [NotificationController::class, 'destroy'])->name('notifications.destroy');

    Route::post('/companies/{company}/follow', [CompanyFollowController::class, 'store'])->name('companies.follow');
    Route::delete('/companies/{company}/follow', [CompanyFollowController::class, 'destroy'])->name('companies.unfollow');

    Route::post('/recruiter/verification-requests', [RecruiterVerificationController::class, 'store'])->name('recruiter.verification.request');
    Route::post('/company/verification-requests/{verificationRequest}/approve', [RecruiterVerificationController::class, 'approve'])->name('company.verification.approve');

    Route::get('/saved-jobs', function () {
        return 'Saved Jobs - Coming Soon';
    })->name('jobs.saved');
    Route::get('/search-candidates', function () {
        return 'Search Candidates - Coming Soon';
    })->name('candidates.search');
    Route::get('/create-interview', function () {
        return 'Create Interview - Coming Soon';
    })->name('interviews.create');
});

require __DIR__.'/auth.php';
