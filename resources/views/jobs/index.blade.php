<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-black text-stone-900">Jobs</h2>
            @if(auth()->user()->canRecruit())
                <a href="{{ route('jobs.create') }}" class="rounded-full bg-stone-900 px-4 py-2 text-sm font-bold text-white">Post</a>
            @endif
        </div>
    </x-slot>

    <div class="min-h-screen bg-stone-50 py-10">
        <div class="mx-auto max-w-6xl space-y-6 px-4 sm:px-6 lg:px-8">
            <div class="rounded-[2rem] border border-stone-200 bg-white p-5 shadow-sm">
                <form method="GET" action="{{ route('jobs.index') }}" class="grid gap-4 lg:grid-cols-[1.4fr_1fr_1fr_auto_auto_auto]">
                    <input type="text" name="search" value="{{ $search }}" placeholder="Search title or company" class="rounded-2xl border-stone-200 bg-stone-50 px-4 py-3 text-sm text-stone-900">
                    <select name="location" class="rounded-2xl border-stone-200 bg-stone-50 px-4 py-3 text-sm text-stone-900">
                        <option value="">All locations</option>
                        @foreach($locations as $loc)
                            <option value="{{ $loc }}" @selected($location === $loc)>{{ $loc }}</option>
                        @endforeach
                    </select>
                    <select name="job_type" class="rounded-2xl border-stone-200 bg-stone-50 px-4 py-3 text-sm text-stone-900">
                        <option value="">All job types</option>
                        <option value="full-time" @selected($jobType === 'full-time')>Full-time</option>
                        <option value="part-time" @selected($jobType === 'part-time')>Part-time</option>
                        <option value="remote" @selected($jobType === 'remote')>Remote</option>
                    </select>
                    @if(auth()->user()->isRecruiter())
                        <select name="status" class="rounded-2xl border-stone-200 bg-stone-50 px-4 py-3 text-sm text-stone-900">
                            <option value="">All statuses</option>
                            <option value="active" @selected($status === 'active')>Active</option>
                            <option value="closed" @selected($status === 'closed')>Closed</option>
                        </select>
                    @endif
                    <button type="submit" class="rounded-2xl bg-stone-900 px-5 py-3 text-sm font-bold text-white">Search</button>
                    <a href="{{ route('jobs.index') }}" class="rounded-2xl border border-stone-200 px-5 py-3 text-center text-sm font-bold text-stone-700">Clear</a>
                </form>
            </div>

            <div class="space-y-4">
                @forelse($jobs as $job)
                    <div class="rounded-[2rem] border border-stone-200 bg-white p-6 shadow-sm">
                        <div class="flex flex-col gap-5 lg:flex-row lg:items-start lg:justify-between">
                            <div class="space-y-3">
                                <div class="flex flex-wrap items-center gap-3">
                                    <h3 class="text-2xl font-black text-stone-900">{{ $job->title }}</h3>
                                    <span class="rounded-full bg-stone-900 px-3 py-1 text-[11px] font-bold uppercase tracking-[0.2em] text-white">{{ $job->status }}</span>
                                    @if($job->job_type)
                                        <span class="rounded-full border border-cyan-200 bg-cyan-50 px-3 py-1 text-[11px] font-bold uppercase tracking-[0.2em] text-cyan-800">{{ str_replace('-', ' ', $job->job_type) }}</span>
                                    @endif
                                </div>
                                <div class="flex flex-wrap gap-3 text-sm text-stone-600">
                                    @if($job->companyProfile)
                                        <a href="{{ route('companies.show', $job->companyProfile) }}" class="font-bold text-cyan-700">{{ $job->companyProfile->company_name }}</a>
                                    @else
                                        <span class="font-bold text-cyan-700">{{ $job->company }}</span>
                                    @endif
                                    <span>{{ $job->location }}</span>
                                    @if($job->salary)
                                        <span>${{ number_format($job->salary) }}</span>
                                    @endif
                                </div>
                                @if($job->recruiter)
                                    <p class="text-sm text-stone-500">
                                        Posted by
                                        <a href="{{ route('recruiters.show', $job->recruiter) }}" class="font-semibold text-stone-900">{{ $job->recruiter->full_name }}</a>
                                    </p>
                                @endif
                                <p class="max-w-3xl text-sm leading-7 text-stone-600">{{ \Illuminate\Support\Str::limit($job->description, 220) }}</p>
                            </div>

                            <div class="flex flex-wrap items-center gap-3">
                                <a href="{{ route('jobs.show', $job) }}" class="text-sm font-bold text-cyan-700">View</a>
                                @if(auth()->user()->isRecruiter() && auth()->id() === $job->user_id)
                                    <a href="{{ route('jobs.pipeline', $job) }}" class="text-sm font-bold text-amber-700">Pipeline</a>
                                    <a href="{{ route('jobs.edit', $job) }}" class="text-sm font-bold text-stone-700">Edit</a>
                                    @if($job->status === 'open')
                                        <form method="POST" action="{{ route('jobs.close', $job) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-sm font-bold text-rose-700">Close</button>
                                        </form>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="rounded-[2rem] border border-stone-200 bg-white p-12 text-center shadow-sm">
                        <p class="text-sm font-semibold text-stone-500">No jobs found for the current filters.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
