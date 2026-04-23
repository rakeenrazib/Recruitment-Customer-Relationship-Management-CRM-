<x-app-layout>
    <x-slot name="header">
        <h2 class="font-display text-2xl font-black text-slate-950">Recruiter</h2>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-5xl space-y-6 px-4 sm:px-6 lg:px-8">
            <div class="panel overflow-visible p-0">
                <div class="relative h-56 overflow-hidden rounded-t-[1.9rem] sm:h-72">
                    @if($recruiter->user?->cover_photo_path)
                        <img src="{{ asset('storage/' . $recruiter->user->cover_photo_path) }}" alt="{{ $recruiter->full_name }}" class="h-full w-full object-cover">
                    @else
                        <div class="h-full w-full bg-[linear-gradient(135deg,#111827,#0f766e,#2563eb)]"></div>
                    @endif
                    <div class="absolute inset-0 bg-slate-950/20"></div>
                </div>

                <div class="relative z-10 px-6 pb-10 pt-0 sm:px-8">
                    <div class="-mt-10 flex flex-col gap-4 sm:-mt-12 sm:flex-row sm:items-start sm:justify-between">
                        <div class="flex items-start gap-4">
                            @if($recruiter->user?->profile_photo_path)
                                <img src="{{ asset('storage/' . $recruiter->user->profile_photo_path) }}" alt="{{ $recruiter->full_name }}" class="h-24 w-24 rounded-[1.6rem] border-4 border-white object-cover shadow-xl">
                            @else
                                <div class="flex h-24 w-24 items-center justify-center rounded-[1.6rem] border-4 border-white bg-slate-950 text-3xl font-black text-white shadow-xl">
                                    {{ strtoupper(substr($recruiter->full_name, 0, 1)) }}
                                </div>
                            @endif

                            <div class="pt-2">
                                <h1 class="font-display text-4xl font-black text-slate-950">{{ $recruiter->full_name }}</h1>
                                <p class="mt-2 break-words text-sm text-slate-500">
                                    {{ $recruiter->title ?: 'Recruiter' }}@if($recruiter->department) &bull; {{ $recruiter->department }}@endif
                                </p>
                                <p class="mt-2 text-sm text-slate-600">
                                    @if($recruiter->location)
                                        <span>{{ $recruiter->location }}</span>
                                    @endif
                                    @if($recruiter->location && $recruiter->company)
                                        <span> &bull; </span>
                                    @endif
                                    @if($recruiter->company)
                                        <a href="{{ route('companies.show', $recruiter->company) }}" class="font-bold text-teal-700">{{ $recruiter->company->company_name }}</a>
                                    @endif
                                </p>
                            </div>
                        </div>

                        <span class="pill shrink-0 self-start sm:self-auto {{ $recruiter->isVerified() ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700' }}">
                            {{ $recruiter->isVerified() ? 'Verified' : 'Pending' }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="grid gap-6 lg:grid-cols-[1fr_0.9fr]">
                <div class="panel p-6">
                    <p class="eyebrow">Bio</p>
                    <p class="mt-4 text-sm leading-7 text-slate-600">{{ $recruiter->bio ?: 'No bio yet.' }}</p>
                </div>

                <div class="panel p-6">
                    <p class="eyebrow">Open roles</p>
                    <div class="mt-4 space-y-3">
                        @forelse($recruiter->jobs as $job)
                            <a href="{{ route('jobs.show', $job) }}" class="panel-soft block p-4 transition hover:-translate-y-0.5">
                                <p class="font-bold text-slate-950">{{ $job->title }}</p>
                                <p class="mt-1 text-sm text-slate-500">{{ $job->location }}</p>
                            </a>
                        @empty
                            <p class="text-sm text-slate-500">No open jobs.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
