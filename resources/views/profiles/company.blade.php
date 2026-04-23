<x-app-layout>
    <x-slot name="header">
        <h2 class="font-display text-2xl font-black text-slate-950">Company</h2>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-6xl space-y-6 px-4 sm:px-6 lg:px-8">
            <div class="panel overflow-visible p-0">
                <div class="relative h-60 overflow-hidden rounded-t-[1.9rem] sm:h-80">
                    @if($company->user?->cover_photo_path)
                        <img src="{{ asset('storage/' . $company->user->cover_photo_path) }}" alt="{{ $company->company_name }}" class="h-full w-full object-cover">
                    @else
                        <div class="h-full w-full bg-[linear-gradient(135deg,#0f172a,#115e59,#f59e0b)]"></div>
                    @endif
                    <div class="absolute inset-0 bg-slate-950/20"></div>
                </div>

                <div class="relative z-10 px-6 pb-10 pt-0 sm:px-8">
                    <div class="-mt-10 flex flex-col gap-5 sm:-mt-12 sm:flex-row sm:items-start sm:justify-between">
                        <div class="flex items-start gap-4">
                            @if($company->user?->profile_photo_path)
                                <img src="{{ asset('storage/' . $company->user->profile_photo_path) }}" alt="{{ $company->company_name }}" class="h-24 w-24 rounded-[1.6rem] border-4 border-white object-cover shadow-xl">
                            @else
                                <div class="flex h-24 w-24 items-center justify-center rounded-[1.6rem] border-4 border-white bg-slate-950 text-3xl font-black text-white shadow-xl">
                                    {{ strtoupper(substr($company->company_name, 0, 1)) }}
                                </div>
                            @endif

                            <div class="pt-2">
                                <h1 class="font-display text-4xl font-black text-slate-950">{{ $company->company_name }}</h1>
                                <p class="mt-2 break-words text-sm text-slate-500">
                                    {{ $company->industry ?: 'Company' }}@if($company->location) &bull; {{ $company->location }}@endif
                                </p>
                                @if($company->website)
                                    <a href="{{ $company->website }}" target="_blank" class="mt-2 inline-block break-all text-sm font-bold text-teal-700">{{ $company->website }}</a>
                                @endif
                            </div>
                        </div>

                        @auth
                            @if(auth()->user()->isCandidate())
                                @if($isFollowing)
                                    <form method="POST" action="{{ route('companies.unfollow', $company) }}" class="shrink-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-secondary">Unfollow</button>
                                    </form>
                                @else
                                    <form method="POST" action="{{ route('companies.follow', $company) }}" class="shrink-0">
                                        @csrf
                                        <button type="submit" class="btn-primary">Follow</button>
                                    </form>
                                @endif
                            @endif
                        @endauth
                    </div>

                    <div class="mt-8 grid gap-4 sm:grid-cols-3">
                        <div class="metric-tile">
                            <p class="eyebrow">Open jobs</p>
                            <p class="mt-3 text-3xl font-black text-slate-950">{{ $openJobs->count() }}</p>
                        </div>
                        <div class="metric-tile">
                            <p class="eyebrow">Recruiters</p>
                            <p class="mt-3 text-3xl font-black text-slate-950">{{ $company->verifiedRecruiters->count() }}</p>
                        </div>
                        <div class="metric-tile">
                            <p class="eyebrow">Followers</p>
                            <p class="mt-3 text-3xl font-black text-slate-950">{{ $company->followers()->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid gap-6 lg:grid-cols-[1fr_0.95fr]">
                <div class="panel p-6">
                    <p class="eyebrow">About</p>
                    <p class="mt-4 text-sm leading-7 text-slate-600">{{ $company->description ?: 'No description yet.' }}</p>
                </div>

                <div class="panel p-6">
                    <p class="eyebrow">Recruiters</p>
                    <div class="mt-4 space-y-3">
                        @forelse($company->verifiedRecruiters as $recruiter)
                            <a href="{{ route('recruiters.show', $recruiter) }}" class="panel-soft block p-4 transition hover:-translate-y-0.5">
                                <p class="font-bold text-slate-950">{{ $recruiter->full_name }}</p>
                                <p class="mt-1 text-sm text-slate-500">
                                    {{ $recruiter->title ?: 'Recruiter' }}@if($recruiter->department) &bull; {{ $recruiter->department }}@endif
                                </p>
                            </a>
                        @empty
                            <p class="text-sm text-slate-500">No verified recruiters yet.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="panel p-6">
                <div class="flex items-center justify-between">
                    <h3 class="font-display text-2xl font-black text-slate-950">Open jobs</h3>
                    <span class="text-sm text-slate-500">{{ $openJobs->count() }}</span>
                </div>

                <div class="mt-6 grid gap-4 md:grid-cols-2">
                    @forelse($openJobs as $job)
                        <a href="{{ route('jobs.show', $job) }}" class="panel-soft block p-5 transition hover:-translate-y-0.5">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <p class="text-lg font-black text-slate-950">{{ $job->title }}</p>
                                    <p class="mt-1 text-sm text-slate-500">{{ $job->location }}</p>
                                </div>
                                @if($job->job_type)
                                    <span class="pill bg-teal-50 text-teal-700">{{ str_replace('-', ' ', $job->job_type) }}</span>
                                @endif
                            </div>
                            @if($job->recruiter)
                                <p class="mt-3 text-sm text-slate-600">By {{ $job->recruiter->full_name }}</p>
                            @endif
                        </a>
                    @empty
                        <p class="text-sm text-slate-500">No open jobs.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
