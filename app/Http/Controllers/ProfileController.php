<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        $user = $request->user()->load(['candidate.followedCompanies', 'recruiter.company', 'company.verificationRequests.recruiter.user']);

        return view('profile.edit', [
            'user' => $user,
        ]);
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $validated = $request->validated();

        $user->fill([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        if ($request->hasFile('profile_photo')) {
            $user->profile_photo_path = $request->file('profile_photo')->store('profile-photos', 'public');
        }

        if ($request->hasFile('cover_photo')) {
            $user->cover_photo_path = $request->file('cover_photo')->store('cover-photos', 'public');
        }

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        if ($user->isCandidate() && $user->candidate) {
            $user->candidate->update([
                'full_name' => $validated['name'],
                'phone' => $validated['phone'] ?? null,
                'location' => $validated['location'] ?? null,
                'bio' => $validated['bio'] ?? null,
                'portfolio' => $validated['portfolio'] ?? null,
                'details' => $validated['details'] ?? null,
            ]);
        }

        if ($user->isRecruiter() && $user->recruiter) {
            $recruiterData = [
                'full_name' => $validated['name'],
                'phone' => $validated['phone'] ?? null,
                'department' => $validated['department'] ?? null,
                'title' => $validated['title'] ?? null,
                'bio' => $validated['bio'] ?? null,
            ];

            if (Schema::hasColumn('recruiters', 'location')) {
                $recruiterData['location'] = $validated['location'] ?? null;
            }

            $user->recruiter->update($recruiterData);
        }

        if ($user->isCompany() && $user->company) {
            $user->company->update([
                'company_name' => $validated['name'],
                'industry' => $validated['industry'] ?? null,
                'website' => $validated['website'] ?? null,
                'description' => $validated['bio'] ?? null,
                'location' => $validated['location'] ?? null,
            ]);
        }

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
