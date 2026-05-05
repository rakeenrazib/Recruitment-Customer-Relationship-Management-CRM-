<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-black text-slate-950">Companies</h2>
            <span class="text-sm text-slate-500">{{ $companies->count() }} results</span>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
            <div class="panel p-4">
                <form method="GET" action="{{ route('companies.index') }}" class="grid gap-3 md:grid-cols-[1fr_auto_auto]">
                    <input
                        type="text"
                        name="search"
                        value="{{ $search }}"
                        placeholder="Search company, industry, or location"
                        class="rounded-2xl border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 focus:border-sky-500 focus:ring-sky-500"
                    >
                    <button type="submit" class="btn-primary">
                        Search
                    </button>
                    <a href="{{ route('companies.index') }}" class="btn-secondary">
                        Clear
                    </a>
                </form>
            </div>

            <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
                @forelse($companies as $company)
                    <div class="panel p-6 transition hover:-translate-y-1">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <h3 class="text-xl font-black text-slate-950">{{ $company->company_name }}</h3>
                                <p class="mt-1 text-sm text-slate-500">{{ $company->industry ?: 'Company' }}</p>
                            </div>
                            @if(in_array($company->id, $followedCompanyIds, true))
                                <span class="rounded-full bg-emerald-50 px-3 py-1 text-[11px] font-bold uppercase tracking-[0.22em] text-emerald-700">Following</span>
                            @endif
                        </div>

                        <div class="mt-5 grid grid-cols-3 gap-3 text-center">
                            <div class="panel-soft px-3 py-3">
                                <p class="text-lg font-black text-slate-900">{{ $company->open_jobs_count }}</p>
                                <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-slate-400">Jobs</p>
                            </div>
                            <div class="panel-soft px-3 py-3">
                                <p class="text-lg font-black text-slate-900">{{ $company->verified_recruiters_count }}</p>
                                <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-slate-400">Recruiters</p>
                            </div>
                            <div class="panel-soft px-3 py-3">
                                <p class="text-lg font-black text-slate-900">{{ $company->followers_count }}</p>
                                <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-slate-400">Followers</p>
                            </div>
                        </div>

                        <p class="mt-5 text-sm leading-6 text-slate-600">{{ \Illuminate\Support\Str::limit($company->description ?: 'No summary yet.', 96) }}</p>

                        <div class="mt-5 flex items-center justify-between">
                            <span class="text-sm text-slate-500">{{ $company->location ?: 'Location not listed' }}</span>
                            <a href="{{ route('companies.show', $company) }}" class="text-sm font-bold text-sky-700 transition hover:text-sky-900">
                                View
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full panel border-dashed border-slate-300 bg-white/80 p-12 text-center shadow-none">
                        <p class="text-sm font-semibold text-slate-500">No companies matched your search.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
