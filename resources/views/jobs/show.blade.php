<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-black text-stone-900">Job</h2>
            <a href="{{ route('jobs.index') }}" class="text-sm font-bold text-stone-500">Back</a>
        </div>
    </x-slot>

    <div class="min-h-screen bg-stone-50 py-10">
        <div class="mx-auto max-w-5xl space-y-6 px-4 sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-medium text-emerald-800">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-sm font-medium text-red-800">{{ session('error') }}</div>
            @endif

            <div class="rounded-[2rem] border border-stone-200 bg-white shadow-sm">
                <div class="border-b border-stone-100 p-8">
                    <div class="flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">
                        <div>
                            <h1 class="text-4xl font-black text-stone-900">{{ $job->title }}</h1>
                            <div class="mt-4 flex flex-wrap items-center gap-4 text-sm text-stone-600">
                                @if($job->companyProfile)
                                    <a href="{{ route('companies.show', $job->companyProfile) }}" class="font-bold text-cyan-700">{{ $job->companyProfile->company_name }}</a>
                                @endif
                                <span>{{ $job->location }}</span>
                                @if($job->salary)
                                    <span>${{ number_format($job->salary) }}</span>
                                @endif
                            </div>
                            @if($job->recruiter)
                                <p class="mt-3 text-sm text-stone-500">
                                    Recruiter:
                                    <a href="{{ route('recruiters.show', $job->recruiter) }}" class="font-semibold text-stone-900">{{ $job->recruiter->full_name }}</a>
                                </p>
                            @endif
                        </div>
                        <span class="rounded-full bg-stone-900 px-4 py-2 text-sm font-bold uppercase tracking-[0.2em] text-white">{{ $job->status }}</span>
                    </div>
                </div>

                <div class="grid gap-6 p-8 lg:grid-cols-[1.25fr_0.75fr]">
                    <div class="space-y-6">
                        <div>
                            <h3 class="text-xs font-bold uppercase tracking-[0.28em] text-stone-400">Description</h3>
                            <p class="mt-4 whitespace-pre-line text-sm leading-7 text-stone-700">{{ $job->description }}</p>
                        </div>
                        @if($job->requirements)
                            <div>
                                <h3 class="text-xs font-bold uppercase tracking-[0.28em] text-stone-400">Requirements</h3>
                                <p class="mt-4 whitespace-pre-line text-sm leading-7 text-stone-700">{{ $job->requirements }}</p>
                            </div>
                        @endif
                    </div>

                    <div class="space-y-6">
                        @if($job->interviewPlan)
                            <div class="rounded-[1.75rem] border border-stone-200 bg-stone-50 p-5">
                                <h3 class="text-sm font-black text-stone-900">Interview Plan</h3>
                                <p class="mt-2 text-xs uppercase tracking-[0.2em] text-stone-400">Strategy: {{ str_replace('_', ' ', $job->interviewPlan->evaluation_strategy) }}</p>
                                <div class="mt-4 space-y-3">
                                    @foreach($job->interviewPlan->stages as $stage)
                                        <div class="rounded-2xl border border-white bg-white p-4">
                                            <p class="font-bold text-stone-900">{{ $stage['name'] }}</p>
                                            <p class="text-sm text-stone-500">{{ $stage['owner'] }}</p>
                                            <p class="mt-2 text-sm text-stone-600">{{ $stage['goal'] }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if(auth()->user()->isCandidate())
                            @php($alreadyApplied = $job->applications->where('user_id', auth()->id())->count() > 0)
                            @if($alreadyApplied)
                                <div class="rounded-[1.75rem] border border-emerald-200 bg-emerald-50 p-5">
                                    <p class="font-bold text-emerald-900">You already applied for this role.</p>
                                    <a href="{{ route('applications.index') }}" class="mt-3 inline-block text-sm font-bold text-emerald-700">View apps</a>
                                </div>
                            @elseif($job->status === 'open')
                                <div class="rounded-[1.75rem] border border-stone-200 bg-white p-5 shadow-sm">
                                    <h3 class="text-lg font-black text-stone-900">Apply</h3>
                                    <form method="POST" action="{{ route('applications.store', $job) }}" enctype="multipart/form-data" class="mt-5 space-y-4">
                                        @csrf
                                        <div>
                                            <label for="cv_file" class="mb-2 block text-xs font-bold uppercase tracking-[0.25em] text-stone-400">CV</label>
                                            <input type="file" id="cv_file" name="cv_file" accept=".pdf" class="block w-full rounded-2xl border border-stone-200 bg-stone-50 px-4 py-3 text-sm text-stone-700">
                                        </div>
                                        <div>
                                            <label for="cover_letter" class="mb-2 block text-xs font-bold uppercase tracking-[0.25em] text-stone-400">Cover Letter</label>
                                            <textarea id="cover_letter" name="cover_letter" rows="5" class="block w-full rounded-2xl border-stone-200 bg-stone-50 px-4 py-3 text-sm text-stone-900"></textarea>
                                        </div>
                                        <button type="submit" class="w-full rounded-2xl bg-stone-900 px-4 py-3 text-sm font-bold text-white">Apply</button>
                                    </form>
                                </div>
                            @endif
                        @elseif(auth()->user()->isRecruiter() && auth()->id() === $job->user_id)
                            <div class="rounded-[1.75rem] border border-stone-200 bg-white p-5 shadow-sm">
                                <div class="flex items-center justify-between gap-3">
                                    <h3 class="text-lg font-black text-stone-900">Applicants</h3>
                                    @if($job->status === 'open')
                                        <form method="POST" action="{{ route('jobs.close', $job) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="rounded-2xl border border-rose-200 px-4 py-2 text-sm font-bold text-rose-700">Close</button>
                                        </form>
                                    @endif
                                </div>
                                <div class="mt-4 space-y-3">
                                    @forelse($job->applications as $application)
                                        <div class="rounded-2xl border border-stone-100 bg-stone-50 p-4">
                                            <div class="flex items-center justify-between gap-4">
                                                <div>
                                                    @if($application->candidate)
                                                        <a href="{{ route('candidates.show', $application->candidate) }}" class="font-bold text-stone-900">{{ $application->candidate->full_name }}</a>
                                                    @else
                                                        <p class="font-bold text-stone-900">{{ $application->user->name }}</p>
                                                    @endif
                                                    <p class="text-sm text-stone-500">{{ $application->user->email }}</p>
                                                </div>
                                                <a href="{{ route('applications.show', $application) }}" class="text-sm font-bold text-cyan-700">Open</a>
                                            </div>
                                        </div>
                                    @empty
                                        <p class="text-sm text-stone-500">No applications yet.</p>
                                    @endforelse
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
