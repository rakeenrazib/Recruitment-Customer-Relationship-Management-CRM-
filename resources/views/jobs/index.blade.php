<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Browse Jobs</h2>
            @if(auth()->user()->role === 'recruiter')
                <a href="{{ route('jobs.create') }}"
                   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-bold rounded-xl hover:bg-blue-700 transition shadow-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Post a Job
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-10 bg-gray-50 min-h-screen">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="p-4 bg-green-50 border border-green-200 rounded-xl text-green-800 text-sm font-medium">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="p-4 bg-red-50 border border-red-200 rounded-xl text-red-800 text-sm font-medium">
                    {{ session('error') }}
                </div>
            @endif

            {{-- ── Search & Filters ─────────────────────────────── --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                <form method="GET" action="{{ route('jobs.index') }}" class="flex flex-wrap gap-3 items-end">

                    {{-- Search --}}
                    <div class="flex-1 min-w-[200px]">
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Search</label>
                        <input type="text" name="search" value="{{ $search }}"
                               placeholder="Job title or company..."
                               class="w-full rounded-xl border-gray-200 text-sm text-gray-700 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    {{-- Location Filter --}}
                    <div class="min-w-[160px]">
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Location</label>
                        <select name="location" class="w-full rounded-xl border-gray-200 text-sm font-bold text-gray-700 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">All Locations</option>
                            @foreach($locations as $loc)
                                <option value="{{ $loc }}" @if($location === $loc) selected @endif>{{ $loc }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Job Type Filter --}}
                    <div class="min-w-[150px]">
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Job Type</label>
                        <select name="job_type" class="w-full rounded-xl border-gray-200 text-sm font-bold text-gray-700 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">All Types</option>
                            <option value="full-time" @if($jobType === 'full-time') selected @endif>Full-time</option>
                            <option value="part-time" @if($jobType === 'part-time') selected @endif>Part-time</option>
                            <option value="remote"    @if($jobType === 'remote')    selected @endif>Remote</option>
                        </select>
                    </div>

                    <button type="submit"
                            class="px-5 py-2.5 bg-blue-600 text-white text-sm font-bold rounded-xl hover:bg-blue-700 transition">
                        Search
                    </button>

                    @if($search || $location || $jobType)
                        <a href="{{ route('jobs.index') }}"
                           class="px-5 py-2.5 bg-gray-100 text-gray-600 text-sm font-bold rounded-xl hover:bg-gray-200 transition">
                            Clear
                        </a>
                    @endif
                </form>
            </div>

            {{-- ── Results count ────────────────────────────────── --}}
            @if($search || $location || $jobType)
                <p class="text-xs text-gray-500 font-bold px-1">
                    {{ $jobs->count() }} {{ Str::plural('result', $jobs->count()) }} found
                </p>
            @endif

            {{-- ── Job Listings ─────────────────────────────────── --}}
            <div class="space-y-4">
                @forelse($jobs as $job)
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition group">
                        <div class="flex justify-between items-start">
                            <div class="space-y-1 flex-1">
                                <div class="flex items-center gap-3 flex-wrap">
                                    <h3 class="text-lg font-extrabold text-gray-900 group-hover:text-blue-600 transition">
                                        {{ $job->title }}
                                    </h3>
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider {{ $job->status === 'open' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                        {{ $job->status }}
                                    </span>
                                    @if($job->job_type)
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-blue-50 text-blue-600 border border-blue-100">
                                            {{ str_replace('-', ' ', $job->job_type) }}
                                        </span>
                                    @endif
                                </div>
                                <p class="text-sm font-bold text-blue-600">{{ $job->company }}</p>
                                <div class="flex items-center gap-4 text-xs text-gray-500 pt-1 flex-wrap">
                                    <span class="flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        </svg>
                                        {{ $job->location }}
                                    </span>
                                    @if($job->salary)
                                        <span class="flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            ${{ number_format($job->salary) }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="flex items-center gap-3 ml-4 flex-shrink-0">
                                <a href="{{ route('jobs.show', $job) }}"
                                   class="text-xs font-bold text-blue-600 hover:text-blue-800 transition whitespace-nowrap underline underline-offset-4">
                                    View Details
                                </a>
                                @if(auth()->user()->role === 'recruiter' && auth()->id() === $job->user_id)
                                    <a href="{{ route('jobs.pipeline', $job) }}"
                                       class="text-xs font-bold text-purple-600 hover:text-purple-800 transition whitespace-nowrap">
                                        Pipeline
                                    </a>
                                    <a href="{{ route('jobs.edit', $job) }}"
                                       class="text-xs font-bold text-gray-500 hover:text-gray-800 transition">
                                        Edit
                                    </a>
                                    <form action="{{ route('jobs.destroy', $job) }}" method="POST" class="inline"
                                          onsubmit="return confirm('Are you sure you want to delete this job?');">
                                        @csrf @method('DELETE')
                                        <button class="text-xs font-bold text-red-500 hover:text-red-800 transition">Delete</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
                        <svg class="w-12 h-12 text-gray-200 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                        <p class="text-sm font-bold text-gray-400">
                            @if($search || $location || $jobType)
                                No jobs match your search. <a href="{{ route('jobs.index') }}" class="text-blue-600 hover:underline">Clear filters</a>
                            @else
                                No jobs available yet.
                            @endif
                        </p>
                    </div>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>