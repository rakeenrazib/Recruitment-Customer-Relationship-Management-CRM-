<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-black text-stone-900">Recruitment Pipeline</h2>
                <p class="mt-1 text-sm text-stone-500">{{ $job->title }} &middot; {{ $job->company }}</p>
            </div>
            <a href="{{ route('jobs.show', $job) }}" class="text-sm font-bold text-stone-500">Back to Job</a>
        </div>
    </x-slot>

    @php
        $labels = [
            'applied' => 'Applied',
            'shortlisted' => 'Shortlisted',
            'interview_scheduled' => 'Interview Scheduled',
            'hired' => 'Hired',
            'rejected' => 'Rejected',
        ];
    @endphp

    <div class="min-h-screen bg-stone-50 py-8">
        <div class="mx-auto max-w-6xl space-y-6 px-4 sm:px-6 lg:px-8">
            <div class="panel p-5">
                <form method="GET" action="{{ route('jobs.pipeline', $job) }}" class="flex flex-col gap-4 lg:flex-row lg:items-center">
                    <input type="text" name="search" value="{{ $search }}" placeholder="Search by candidate name, email, or portfolio" class="field-input min-w-[280px] flex-1">
                    <input type="hidden" name="stage" value="{{ $stage }}">
                    <button type="submit" class="btn-primary px-5 py-3">Search</button>
                </form>

                <div class="mt-5 flex flex-wrap gap-3">
                    @foreach($labels as $statusKey => $label)
                        <a
                            href="{{ route('jobs.pipeline', ['job' => $job, 'stage' => $statusKey, 'search' => $search]) }}"
                            class="{{ $stage === $statusKey ? 'border-slate-950 bg-slate-950 text-white' : 'border-slate-700/70 bg-slate-900/10 text-slate-300 hover:bg-slate-800 hover:text-white' }} inline-flex items-center gap-2 rounded-full border px-4 py-2 text-sm font-bold transition"
                        >
                            <span>{{ $label }}</span>
                            <span class="{{ $stage === $statusKey ? 'bg-white/15 text-white' : 'bg-slate-800 text-slate-100' }} rounded-full px-2 py-0.5 text-xs">
                                {{ $columns[$statusKey]->count() }}
                            </span>
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="panel p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="eyebrow">{{ $labels[$stage] }}</p>
                        <h3 class="mt-2 text-2xl font-black text-slate-950">{{ $columns[$stage]->count() }} candidate{{ $columns[$stage]->count() === 1 ? '' : 's' }}</h3>
                    </div>
                    <span class="pill bg-slate-950 text-white">{{ $job->title }}</span>
                </div>

                <div class="mt-6 space-y-4">
                    @forelse($columns[$stage] as $application)
                        <div class="panel-soft p-5">
                            <div class="flex flex-col gap-5 lg:flex-row lg:items-start lg:justify-between">
                                <div class="flex items-start gap-4">
                                    @if($application->candidate?->user?->profile_photo_path)
                                        <img src="{{ asset('storage/' . $application->candidate->user->profile_photo_path) }}" alt="{{ $application->candidate?->full_name ?? $application->user->name }}" class="h-16 w-16 rounded-2xl object-cover">
                                    @else
                                        <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-slate-950 text-lg font-black text-white">
                                            {{ strtoupper(substr($application->candidate?->full_name ?? $application->user->name, 0, 1)) }}
                                        </div>
                                    @endif

                                    <div class="min-w-0">
                                        <div class="flex flex-wrap items-center gap-3">
                                            <p class="text-lg font-black text-slate-950">{{ $application->candidate?->full_name ?? $application->user->name }}</p>
                                            <span class="pill bg-slate-950 text-white">{{ str_replace('_', ' ', $application->status) }}</span>
                                        </div>
                                        <p class="mt-1 text-sm text-slate-500">{{ $application->user->email }}</p>
                                        @if($application->candidate?->portfolio)
                                            <p class="mt-2 text-sm text-slate-400">{{ \Illuminate\Support\Str::limit($application->candidate->portfolio, 120) }}</p>
                                        @endif
                                        <div class="mt-3 flex flex-wrap gap-4 text-sm font-bold">
                                            @if($application->candidate)
                                                <a href="{{ route('candidates.show', $application->candidate) }}" class="text-teal-400">Public profile</a>
                                            @endif
                                            <a href="{{ route('applications.show', $application) }}" class="text-cyan-400">View application</a>
                                        </div>
                                    </div>
                                </div>

                                <div class="grid gap-2 sm:grid-cols-2 lg:w-[21rem]">
                                    @foreach($labels as $moveStatus => $moveLabel)
                                        @if($moveStatus !== $stage)
                                            <form method="POST" action="{{ route('applications.update-status', $application) }}">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="{{ $moveStatus }}">
                                                <input type="hidden" name="_pipeline_redirect" value="{{ route('jobs.pipeline', ['job' => $job, 'stage' => $stage, 'search' => $search]) }}">
                                                <button type="submit" class="w-full rounded-xl border border-slate-700 bg-slate-900 px-3 py-2 text-xs font-bold text-slate-100 transition hover:border-teal-400 hover:text-white">
                                                    Move to {{ $moveLabel }}
                                                </button>
                                            </form>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="rounded-2xl border border-dashed border-slate-700 p-10 text-center text-sm text-slate-400">No candidates in this stage.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
