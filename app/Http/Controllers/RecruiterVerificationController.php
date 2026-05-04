<?php

namespace App\Http\Controllers;

use App\Models\RecruiterVerificationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class RecruiterVerificationController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        abort_unless(auth()->user()->isRecruiter(), 403);

        $recruiter = auth()->user()->recruiter;
        $validated = $request->validate([
            'company_id' => ['required', 'integer', 'exists:companies,id'],
            'message' => ['nullable', 'string'],
        ]);

        $companyId = (int) $validated['company_id'];

        $existingPendingRequest = RecruiterVerificationRequest::query()
            ->where('recruiter_id', $recruiter->id)
            ->where('company_id', $companyId)
            ->where('status', 'pending')
            ->exists();

        if ($existingPendingRequest) {
            return back()->with('error', 'A verification request for that company is already pending.');
        }

        RecruiterVerificationRequest::create([
            'recruiter_id' => $recruiter->id,
            'company_id' => $companyId,
            'message' => $validated['message'] ?? null,
        ]);

        $recruiter->update([
            'company_id' => $companyId,
            'verification_requested_at' => now(),
        ]);

        return back()->with('success', 'Verification request sent to the selected company.');
    }

    public function approve(RecruiterVerificationRequest $verificationRequest): RedirectResponse
    {
        abort_unless(auth()->user()->isCompany(), 403);
        abort_unless(auth()->user()->company?->id === $verificationRequest->company_id, 403);

        $verificationRequest->update([
            'status' => 'approved',
            'reviewed_at' => now(),
        ]);

        $verificationRequest->recruiter->update([
            'verified_at' => now(),
        ]);

        return back()->with('success', 'Recruiter verified successfully.');
    }
}
