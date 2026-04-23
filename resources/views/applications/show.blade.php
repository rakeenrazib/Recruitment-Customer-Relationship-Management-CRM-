<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-black text-stone-900">{{ auth()->user()->isRecruiter() ? 'Candidate Application' : 'Application Details' }}</h2>
            <a href="{{ auth()->user()->isRecruiter() ? route('recruiter.applications') : route('applications.index') }}" class="text-sm font-bold text-stone-500">Back</a>
        </div>
    </x-slot>

    <div class="min-h-screen bg-stone-50 py-10">
        <div class="mx-auto max-w-6xl space-y-6 px-4 sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-medium text-emerald-800">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-sm font-medium text-red-800">{{ session('error') }}</div>
            @endif

            <div class="grid gap-6 lg:grid-cols-[1.2fr_0.8fr]">
                <div class="rounded-[2rem] border border-stone-200 bg-white p-8 shadow-sm">
                    <div class="flex flex-col gap-5 border-b border-stone-100 pb-6 sm:flex-row sm:items-start sm:justify-between">
                        <div>
                            <h3 class="text-3xl font-black text-stone-900">{{ $application->job->title }}</h3>
                            @if($application->job->companyProfile)
                                <a href="{{ route('companies.show', $application->job->companyProfile) }}" class="mt-2 inline-block text-sm font-bold text-cyan-700">{{ $application->job->companyProfile->company_name }}</a>
                            @endif
                            <p class="mt-2 text-sm text-stone-500">{{ $application->job->location }}</p>
                        </div>
                        <span class="rounded-full bg-stone-900 px-4 py-2 text-sm font-bold uppercase tracking-[0.2em] text-white">{{ str_replace('_', ' ', $application->status) }}</span>
                    </div>

                    @if($application->candidate)
                        <div class="mt-6 rounded-[1.75rem] border border-stone-200 bg-stone-50 p-5">
                            <div class="flex items-center justify-between gap-4">
                                <div>
                                    <p class="text-xs font-bold uppercase tracking-[0.28em] text-stone-400">Candidate</p>
                                    <a href="{{ route('candidates.show', $application->candidate) }}" class="mt-2 inline-block text-xl font-black text-stone-900">{{ $application->candidate->full_name }}</a>
                                    <p class="mt-1 text-sm text-stone-500">{{ $application->user->email }}</p>
                                </div>
                            </div>
                            @if($application->candidate->bio)
                                <p class="mt-4 text-sm leading-7 text-stone-700">{{ $application->candidate->bio }}</p>
                            @endif
                            @if($application->candidate->portfolio)
                                <p class="mt-4 text-sm text-stone-700"><span class="font-bold">Portfolio:</span> {{ $application->candidate->portfolio }}</p>
                            @endif
                        </div>
                    @endif

                    <div class="mt-6">
                        <h4 class="text-xs font-bold uppercase tracking-[0.28em] text-stone-400">Cover Letter</h4>
                        <p class="mt-4 whitespace-pre-line text-sm leading-7 text-stone-700">{{ $application->cover_letter ?: 'No cover letter provided.' }}</p>
                    </div>

                    @if($application->cv_path)
                        <div class="mt-6">
                            <a href="{{ asset('storage/' . $application->cv_path) }}" target="_blank" class="inline-flex rounded-2xl bg-stone-900 px-4 py-3 text-sm font-bold text-white">Open CV</a>
                        </div>
                    @endif

                    @if($application->evaluation_summary)
                        <div class="mt-6 rounded-[1.75rem] border border-cyan-200 bg-cyan-50 p-5">
                            <p class="text-xs font-bold uppercase tracking-[0.28em] text-cyan-700">Evaluation</p>
                            <p class="mt-2 text-sm font-semibold text-cyan-900">{{ str_replace('_', ' ', $application->evaluation_method) }} @if($application->evaluation_score) · Score {{ $application->evaluation_score }} @endif</p>
                            <p class="mt-3 text-sm leading-7 text-cyan-900">{{ $application->evaluation_summary }}</p>
                        </div>
                    @endif
                </div>

                <div class="space-y-6">
                    <div class="rounded-[2rem] border border-stone-200 bg-white p-6 shadow-sm">
                        <h4 class="text-sm font-black text-stone-900">Application Details</h4>
                        <div class="mt-5 space-y-3 text-sm text-stone-600">
                            <div class="flex justify-between gap-4"><span>Applied</span><span>{{ $application->created_at->format('M d, Y') }}</span></div>
                            <div class="flex justify-between gap-4"><span>Last status update</span><span>{{ optional($application->status_updated_at)->format('M d, Y') ?: 'Not updated yet' }}</span></div>
                            @if($application->job->recruiter)
                                <div class="flex justify-between gap-4"><span>Recruiter</span><a href="{{ route('recruiters.show', $application->job->recruiter) }}" class="font-bold text-cyan-700">{{ $application->job->recruiter->full_name }}</a></div>
                            @endif
                        </div>
                    </div>

                    @if(auth()->user()->isCandidate() && $application->status === 'applied')
                        <div class="rounded-[2rem] border border-red-200 bg-red-50 p-6">
                            <form action="{{ route('applications.destroy', $application) }}" method="POST" onsubmit="return confirm('Withdraw this application?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full rounded-2xl bg-red-700 px-4 py-3 text-sm font-bold text-white">Withdraw Application</button>
                            </form>
                        </div>
                    @endif

                    @if(auth()->user()->isRecruiter())
                        <div class="rounded-[2rem] border border-stone-200 bg-white p-6 shadow-sm">
                            <h4 class="text-sm font-black text-stone-900">Update Status</h4>
                            <form action="{{ route('applications.update-status', $application) }}" method="POST" class="mt-5 space-y-4">
                                @csrf
                                @method('PATCH')
                                <div>
                                    <label class="mb-2 block text-xs font-bold uppercase tracking-[0.25em] text-stone-400">Status</label>
                                    <select name="status" class="block w-full rounded-2xl border-stone-200 bg-stone-50 px-4 py-3 text-sm text-stone-900">
                                        <option value="applied" @selected($application->status === 'applied')>Applied</option>
                                        <option value="shortlisted" @selected($application->status === 'shortlisted')>Shortlisted</option>
                                        <option value="interview_scheduled" @selected($application->status === 'interview_scheduled')>Interview Scheduled</option>
                                        <option value="hired" @selected($application->status === 'hired')>Hired</option>
                                        <option value="rejected" @selected($application->status === 'rejected')>Rejected</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="mb-2 block text-xs font-bold uppercase tracking-[0.25em] text-stone-400">Evaluation Method</label>
                                    <select name="evaluation_strategy" class="block w-full rounded-2xl border-stone-200 bg-stone-50 px-4 py-3 text-sm text-stone-900">
                                        <option value="">Skip evaluation update</option>
                                        <option value="scoring_rubric">Scoring Rubric</option>
                                        <option value="technical_assessment">Technical Assessment</option>
                                        <option value="hr_feedback">HR Feedback</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="mb-2 block text-xs font-bold uppercase tracking-[0.25em] text-stone-400">Score</label>
                                    <input type="number" step="0.01" name="evaluation_score" value="{{ old('evaluation_score', $application->evaluation_score) }}" class="block w-full rounded-2xl border-stone-200 bg-stone-50 px-4 py-3 text-sm text-stone-900">
                                </div>
                                <div>
                                    <label class="mb-2 block text-xs font-bold uppercase tracking-[0.25em] text-stone-400">Recruiter Notes</label>
                                    <textarea name="notes" rows="4" class="block w-full rounded-2xl border-stone-200 bg-stone-50 px-4 py-3 text-sm text-stone-900">{{ old('notes', $application->notes) }}</textarea>
                                </div>
                                <div>
                                    <label class="mb-2 block text-xs font-bold uppercase tracking-[0.25em] text-stone-400">Evaluation Summary</label>
                                    <textarea name="evaluation_summary" rows="4" class="block w-full rounded-2xl border-stone-200 bg-stone-50 px-4 py-3 text-sm text-stone-900">{{ old('evaluation_summary', $application->evaluation_summary) }}</textarea>
                                </div>
                                <button type="submit" class="w-full rounded-2xl bg-stone-900 px-4 py-3 text-sm font-bold text-white">Save Changes</button>
                            </form>
                        </div>
                    @elseif($application->notes)
                        <div class="rounded-[2rem] border border-cyan-200 bg-cyan-50 p-6">
                            <h4 class="text-sm font-black text-cyan-900">Recruiter Notes</h4>
                            <p class="mt-3 text-sm leading-7 text-cyan-900">{{ $application->notes }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
