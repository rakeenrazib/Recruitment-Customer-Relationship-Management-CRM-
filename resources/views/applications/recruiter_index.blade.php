<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-black text-stone-900">Applications</h2>
    </x-slot>

    <div class="min-h-screen bg-stone-50 py-10">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
            <div class="rounded-[2rem] border border-stone-200 bg-white p-5 shadow-sm">
                <form method="GET" action="{{ route('recruiter.applications') }}" class="grid gap-4 lg:grid-cols-[1.2fr_0.9fr_1fr_auto_auto]">
                    <input type="text" name="search" value="{{ $search }}" placeholder="Search candidate name, email, or portfolio" class="rounded-2xl border-stone-200 bg-stone-50 px-4 py-3 text-sm text-stone-900">
                    <select name="status" class="rounded-2xl border-stone-200 bg-stone-50 px-4 py-3 text-sm text-stone-900">
                        <option value="">All statuses</option>
                        <option value="applied" @selected($status === 'applied')>Applied</option>
                        <option value="shortlisted" @selected($status === 'shortlisted')>Shortlisted</option>
                        <option value="interview_scheduled" @selected($status === 'interview_scheduled')>Interview Scheduled</option>
                        <option value="hired" @selected($status === 'hired')>Hired</option>
                        <option value="rejected" @selected($status === 'rejected')>Rejected</option>
                    </select>
                    <select name="job_id" class="rounded-2xl border-stone-200 bg-stone-50 px-4 py-3 text-sm text-stone-900">
                        <option value="">All jobs</option>
                        @foreach($recruiterJobs as $recruiterJob)
                            <option value="{{ $recruiterJob->id }}" @selected($jobId == $recruiterJob->id)>{{ $recruiterJob->title }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="rounded-2xl bg-stone-900 px-5 py-3 text-sm font-bold text-white">Filter</button>
                    <a href="{{ route('recruiter.applications') }}" class="rounded-2xl border border-stone-200 px-5 py-3 text-center text-sm font-bold text-stone-700">Clear</a>
                </form>
            </div>

            <div class="rounded-[2rem] border border-stone-200 bg-white p-6 shadow-sm">
                <div class="space-y-6">
                    @forelse($jobs as $job)
                        <div class="overflow-hidden rounded-[1.75rem] border border-stone-200">
                            <div class="flex flex-col gap-4 bg-stone-50 px-6 py-5 sm:flex-row sm:items-center sm:justify-between">
                                <div>
                                    <h3 class="text-lg font-black text-stone-900">{{ $job->title }}</h3>
                                    <p class="text-sm text-stone-500">{{ $job->company }}</p>
                                </div>
                                <div class="flex gap-3">
                                    <a href="{{ route('jobs.pipeline', $job) }}" class="text-sm font-bold text-amber-700">Pipeline</a>
                                    <a href="{{ route('jobs.show', $job) }}" class="text-sm font-bold text-cyan-700">View job</a>
                                </div>
                            </div>
                            <div class="space-y-4 p-6">
                                @forelse($job->applications as $application)
                                    <div class="rounded-2xl border border-stone-100 bg-stone-50 p-4">
                                        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                                            <div>
                                                @if($application->candidate)
                                                    <a href="{{ route('candidates.show', $application->candidate) }}" class="font-bold text-stone-900">{{ $application->candidate->full_name }}</a>
                                                @else
                                                    <p class="font-bold text-stone-900">{{ $application->user->name }}</p>
                                                @endif
                                                <p class="text-sm text-stone-500">{{ $application->user->email }}</p>
                                                <p class="mt-1 text-sm text-stone-600">{{ \Illuminate\Support\Str::limit($application->candidate?->portfolio ?? $application->candidate?->details, 100) }}</p>
                                            </div>
                                            <div class="flex items-center gap-4">
                                                <span class="rounded-full bg-stone-900 px-3 py-1 text-[11px] font-bold uppercase tracking-[0.2em] text-white">{{ str_replace('_', ' ', $application->status) }}</span>
                                                <a href="{{ route('applications.show', $application) }}" class="text-sm font-bold text-cyan-700">Review</a>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-sm text-stone-500">No matching applications for this job.</p>
                                @endforelse
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-stone-500">No applications found for the selected filters.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
