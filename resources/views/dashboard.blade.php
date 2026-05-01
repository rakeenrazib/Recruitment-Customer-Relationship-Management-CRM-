<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="eyebrow">Workspace</p>
                <div class="mt-2 flex flex-wrap items-center gap-3">
                    <h2 class="font-display text-3xl font-black text-slate-950">Dashboard</h2>
                    @if(auth()->user()->isRecruiter() && auth()->user()->recruiter?->isVerified())
                        <span class="pill bg-emerald-500/15 text-emerald-300 ring-1 ring-emerald-400/30">Verified</span>
                    @endif
                </div>
                <p class="mt-2 text-sm text-slate-500">{{ auth()->user()->display_name }} &bull; {{ ucfirst(auth()->user()->role) }}</p>
            </div>

            <div class="flex flex-wrap gap-3">
                <a href="{{ route('profile.edit') }}" class="btn-secondary">Edit profile</a>
                <a href="{{ route('jobs.index') }}" class="btn-primary">Jobs</a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="panel border-emerald-200 bg-emerald-50/90 px-5 py-4 text-sm font-medium text-emerald-800">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="panel border-rose-200 bg-rose-50/90 px-5 py-4 text-sm font-medium text-rose-800">{{ session('error') }}</div>
            @endif

            <section class="grid gap-4 md:grid-cols-4">
                @foreach($stats as $label => $value)
                    <div class="stat-card">
                        <p class="eyebrow">{{ str_replace('_', ' ', $label) }}</p>
                        <p class="mt-5 text-4xl font-black text-slate-950">{{ $value }}</p>
                        <div class="mt-5 h-1.5 w-16 rounded-full bg-gradient-to-r from-teal-500 via-sky-400 to-amber-300"></div>
                    </div>
                @endforeach
            </section>

            @if(auth()->user()->isRecruiter() && ! auth()->user()->canRecruit())
                <div class="panel border-amber-200 bg-[linear-gradient(135deg,rgba(255,251,235,0.96),rgba(255,243,199,0.88))] p-6">
                    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                        <div>
                            <p class="eyebrow text-amber-700">Action Needed</p>
                            <h3 class="mt-2 font-display text-xl font-black text-amber-950">Verification required</h3>
                            <p class="mt-2 max-w-2xl text-sm text-amber-900">
                                @if(auth()->user()->recruiter?->company)
                                    Linked to {{ auth()->user()->recruiter->company->company_name }}. Recruiter actions unlock after approval.
                                @else
                                    Choose a company and send a verification request to unlock recruiter actions.
                                @endif
                            </p>
                        </div>
                        <form method="POST" action="{{ route('recruiter.verification.request') }}" class="flex w-full max-w-xl flex-col gap-3 sm:flex-row">
                            @csrf
                            <select name="company_id" class="field-input min-w-0 flex-1 bg-white" required>
                                <option value="">Select company</option>
                                @foreach($companies as $company)
                                    <option value="{{ $company->id }}" @selected(old('company_id', auth()->user()->recruiter?->company_id) == $company->id)>{{ $company->company_name }}</option>
                                @endforeach
                            </select>
                            <input name="message" type="text" class="field-input min-w-0 flex-1 bg-white" placeholder="Optional note">
                            <button type="submit" class="btn-primary whitespace-nowrap bg-amber-900 hover:bg-amber-800 focus:ring-amber-500">Request</button>
                        </form>
                    </div>
                </div>
            @endif

            <div class="grid gap-6 lg:grid-cols-[1.2fr_0.8fr]">
                <div class="space-y-6">
                    @if(auth()->user()->isCandidate())
                        <div class="panel p-6">
                            <div class="mb-5 flex items-center justify-between">
                                <div>
                                    <p class="eyebrow">Activity</p>
                                    <h3 class="mt-2 font-display text-xl font-black text-slate-950">Recent Applications</h3>
                                </div>
                                <a href="{{ route('applications.index') }}" class="text-sm font-bold text-teal-700">All</a>
                            </div>
                            <div class="space-y-3">
                                @forelse($recentApplications as $application)
                                    <div class="panel-soft p-4">
                                        <div class="flex items-start justify-between gap-4">
                                            <div>
                                                <p class="font-bold text-slate-950">{{ $application->job->title }}</p>
                                                <p class="mt-1 text-sm text-slate-500">{{ $application->job->company }}</p>
                                            </div>
                                            <span class="pill bg-slate-950 text-white">{{ str_replace('_', ' ', $application->status) }}</span>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-sm text-slate-500">No applications yet.</p>
                                @endforelse
                            </div>
                        </div>

                        <div class="panel p-6">
                            <div class="mb-5 flex items-center justify-between">
                                <div>
                                    <p class="eyebrow">Following</p>
                                    <h3 class="mt-2 font-display text-xl font-black text-slate-950">Companies</h3>
                                </div>
                                <a href="{{ route('companies.index') }}" class="text-sm font-bold text-teal-700">Browse</a>
                            </div>
                            <div class="grid gap-3 sm:grid-cols-2">
                                @forelse(auth()->user()->candidate?->followedCompanies ?? [] as $company)
                                    <a href="{{ route('companies.show', $company) }}" class="panel-soft p-4 transition hover:-translate-y-0.5 hover:border-teal-200 hover:bg-teal-50/70">
                                        <p class="font-bold text-slate-950">{{ $company->company_name }}</p>
                                        <p class="mt-1 text-sm text-slate-500">{{ $company->industry ?: 'Company' }}</p>
                                    </a>
                                @empty
                                    <p class="text-sm text-slate-500">No companies yet.</p>
                                @endforelse
                            </div>
                        </div>
                    @elseif(auth()->user()->isRecruiter())
                        <div class="panel p-6">
                            <div class="mb-5 flex items-center justify-between">
                                <div>
                                    <p class="eyebrow">Jobs</p>
                                    <h3 class="mt-2 font-display text-xl font-black text-slate-950">Active Jobs</h3>
                                </div>
                                @if(auth()->user()->canRecruit())
                                    <a href="{{ route('jobs.create') }}" class="text-sm font-bold text-teal-700">New</a>
                                @endif
                            </div>
                            <div class="space-y-3">
                                @forelse($activeJobs as $job)
                                    <div class="panel-soft p-4">
                                        <div class="flex items-center justify-between gap-4">
                                            <div>
                                                <a href="{{ route('jobs.show', $job) }}" class="font-bold text-slate-950">{{ $job->title }}</a>
                                                <p class="mt-1 text-sm text-slate-500">{{ $job->company }}</p>
                                            </div>
                                            <span class="rounded-full bg-white px-3 py-2 text-sm font-semibold text-slate-500">{{ $job->applications->count() }}</span>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-sm text-slate-500">No active jobs.</p>
                                @endforelse
                            </div>
                        </div>

                        <div class="panel p-6">
                            <div class="mb-5 flex items-center justify-between">
                                <div>
                                    <p class="eyebrow">Archive</p>
                                    <h3 class="mt-2 font-display text-xl font-black text-slate-950">Closed Jobs</h3>
                                </div>
                                <a href="{{ route('jobs.index') }}" class="text-sm font-bold text-teal-700">All</a>
                            </div>
                            <div class="space-y-3">
                                @forelse($closedJobs as $job)
                                    <div class="panel-soft p-4">
                                        <div class="flex items-center justify-between gap-4">
                                            <div>
                                                <a href="{{ route('jobs.show', $job) }}" class="font-bold text-slate-950">{{ $job->title }}</a>
                                                <p class="mt-1 text-sm text-slate-500">{{ $job->company }}</p>
                                            </div>
                                            <span class="rounded-full bg-white px-3 py-2 text-sm font-semibold text-slate-500">Closed</span>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-sm text-slate-500">No closed jobs.</p>
                                @endforelse
                            </div>
                        </div>

                        <div class="grid gap-6 md:grid-cols-2">
                            <div class="panel p-6">
                                <p class="eyebrow text-teal-700">Pipeline</p>
                                <h3 class="mt-2 font-display text-xl font-black text-slate-950">New Candidates</h3>
                                <div class="mt-4 space-y-3">
                                    @forelse($pendingApplications as $application)
                                        <a href="{{ route('applications.show', $application) }}" class="panel-soft block p-4 transition hover:-translate-y-0.5">
                                            <p class="font-bold text-slate-950">{{ $application->candidate?->full_name ?? $application->user->name }}</p>
                                            <p class="mt-1 text-sm text-slate-500">{{ $application->job->title }}</p>
                                        </a>
                                    @empty
                                        <p class="text-sm text-slate-500">No new candidates.</p>
                                    @endforelse
                                </div>
                            </div>

                            <div class="panel p-6">
                                <p class="eyebrow text-amber-700">Pipeline</p>
                                <h3 class="mt-2 font-display text-xl font-black text-slate-950">Shortlisted</h3>
                                <div class="mt-4 space-y-3">
                                    @forelse($shortlistedApplications as $application)
                                        <a href="{{ route('applications.show', $application) }}" class="panel-soft block p-4 transition hover:-translate-y-0.5">
                                            <p class="font-bold text-slate-950">{{ $application->candidate?->full_name ?? $application->user->name }}</p>
                                            <p class="mt-1 text-sm text-slate-500">{{ $application->job->title }}</p>
                                        </a>
                                    @empty
                                        <p class="text-sm text-slate-500">No shortlisted candidates.</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    @elseif(auth()->user()->isCompany())
                        <div class="panel p-6">
                            <div class="mb-5 flex items-center justify-between">
                                <div>
                                    <p class="eyebrow">Company</p>
                                    <h3 class="mt-2 font-display text-xl font-black text-slate-950">Verification Queue</h3>
                                </div>
                                <a href="{{ route('companies.show', auth()->user()->company) }}" class="text-sm font-bold text-teal-700">Public</a>
                            </div>
                            <div class="space-y-3">
                                @forelse(auth()->user()->company->verificationRequests->where('status', 'pending') as $verificationRequest)
                                    <div class="panel-soft p-4">
                                        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                                            <div>
                                                <p class="font-bold text-slate-950">{{ $verificationRequest->recruiter->full_name }}</p>
                                                <p class="mt-1 text-sm text-slate-500">{{ $verificationRequest->recruiter->title ?: 'Recruiter' }}</p>
                                            </div>
                                            <form method="POST" action="{{ route('company.verification.approve', $verificationRequest) }}">
                                                @csrf
                                                <button type="submit" class="btn-primary px-4 py-3">Approve</button>
                                            </form>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-sm text-slate-500">No pending requests.</p>
                                @endforelse
                            </div>
                        </div>

                        <div class="panel p-6">
                            <p class="eyebrow">Publishing</p>
                            <h3 class="mt-2 font-display text-xl font-black text-slate-950">Recent Jobs</h3>
                            <div class="mt-4 space-y-3">
                                @forelse($recentJobs as $job)
                                    <div class="panel-soft p-4">
                                        <p class="font-bold text-slate-950">{{ $job->title }}</p>
                                        <p class="mt-1 text-sm text-slate-500">{{ $job->recruiter?->full_name ?? 'Recruiter' }}</p>
                                    </div>
                                @empty
                                    <p class="text-sm text-slate-500">No jobs yet.</p>
                                @endforelse
                            </div>
                        </div>
                    @endif
                </div>

                <div class="space-y-6">
                    @if(auth()->user()->isCandidate())
                        <div class="panel p-6">
                            <div class="mb-5 flex items-center justify-between">
                                <div>
                                    <p class="eyebrow">Inbox</p>
                                    <h3 class="mt-2 font-display text-xl font-black text-slate-950">Notifications</h3>
                                </div>
                                <a href="{{ route('notifications.index') }}" class="text-sm font-bold text-teal-700">All</a>
                            </div>
                            <div class="space-y-3">
                                @forelse($notifications as $notification)
                                    @php($targetUrl = $notification->targetUrl())
                                    <div class="panel-soft p-4">
                                        @if($targetUrl)
                                            <a href="{{ $targetUrl }}" class="block text-sm font-semibold text-slate-900 transition hover:text-teal-700">{{ $notification->message }}</a>
                                        @else
                                            <p class="text-sm font-semibold text-slate-900">{{ $notification->message }}</p>
                                        @endif
                                        <p class="mt-2 text-[11px] uppercase tracking-[0.22em] text-slate-400">{{ str_replace('_', ' ', $notification->type) }}</p>
                                    </div>
                                @empty
                                    <p class="text-sm text-slate-500">No notifications.</p>
                                @endforelse
                            </div>
                        </div>

                        <div class="panel p-6">
                            <div class="mb-5 flex items-center justify-between">
                                <div>
                                    <p class="eyebrow">Suggestions</p>
                                    <h3 class="mt-2 font-display text-xl font-black text-slate-950">Jobs</h3>
                                </div>
                                <a href="{{ route('jobs.index') }}" class="text-sm font-bold text-teal-700">Browse</a>
                            </div>
                            <div class="space-y-3">
                                @forelse($recommendedJobs as $job)
                                    <a href="{{ route('jobs.show', $job) }}" class="panel-soft block p-4 transition hover:-translate-y-0.5 hover:border-teal-200 hover:bg-teal-50/70">
                                        <p class="font-bold text-slate-950">{{ $job->title }}</p>
                                        <p class="mt-1 text-sm text-slate-500">{{ $job->company }}</p>
                                    </a>
                                @empty
                                    <p class="text-sm text-slate-500">No suggestions yet.</p>
                                @endforelse
                            </div>
                        </div>
                    @else
                        <div class="panel p-6">
                            <p class="eyebrow">Quick Access</p>
                            <h3 class="mt-2 font-display text-xl font-black text-slate-950">Useful Links</h3>
                            <div class="mt-4 space-y-3">
                                <a href="{{ route('profile.edit') }}" class="panel-soft block p-4 text-sm font-semibold text-slate-950 transition hover:-translate-y-0.5">Profile settings</a>
                                @if(auth()->user()->isRecruiter() && auth()->user()->canRecruit())
                                    <a href="{{ route('jobs.create') }}" class="panel-soft block p-4 text-sm font-semibold text-slate-950 transition hover:-translate-y-0.5">Add New Job</a>
                                @endif
                                @if(auth()->user()->isRecruiter() && auth()->user()->recruiter)
                                <a href="{{ route('recruiters.show', auth()->user()->recruiter) }}" class="panel-soft block p-4 text-sm font-semibold text-slate-950 transition hover:-translate-y-0.5">Recruiter profile</a>
                                @endif
                                @if(auth()->user()->isCompany() && auth()->user()->company)
                                    <a href="{{ route('companies.show', auth()->user()->company) }}" class="panel-soft block p-4 text-sm font-semibold text-slate-950 transition hover:-translate-y-0.5">Public profile</a>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
