<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('View Applications') }}</h2>
    </x-slot>

    <div class="py-10 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- ── Search & Filters ─────────────────────────────── --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                <form method="GET" action="{{ route('recruiter.applications') }}" class="flex flex-wrap gap-3 items-end">

                    {{-- Search --}}
                    <div class="flex-1 min-w-[200px]">
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Search Candidates</label>
                        <input type="text" name="search" value="{{ $search }}"
                               placeholder="Name, email or skills..."
                               class="w-full rounded-xl border-gray-200 text-sm text-gray-700 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    {{-- Status Filter --}}
                    <div class="min-w-[150px]">
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Status</label>
                        <select name="status" class="w-full rounded-xl border-gray-200 text-sm font-bold text-gray-700 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">All Statuses</option>
                            <option value="pending"     @if($status === 'pending')     selected @endif>Applied</option>
                            <option value="shortlisted" @if($status === 'shortlisted') selected @endif>Shortlisted</option>
                            <option value="interview"   @if($status === 'interview')   selected @endif>Interview</option>
                            <option value="rejected"    @if($status === 'rejected')    selected @endif>Rejected</option>
                        </select>
                    </div>

                    {{-- Job Filter --}}
                    <div class="min-w-[180px]">
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Job</label>
                        <select name="job_id" class="w-full rounded-xl border-gray-200 text-sm font-bold text-gray-700 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">All Jobs</option>
                            @foreach($recruiterJobs as $rj)
                                <option value="{{ $rj->id }}" @if($jobId == $rj->id) selected @endif>
                                    {{ Str::limit($rj->title, 30) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Submit --}}
                    <button type="submit"
                            class="px-5 py-2.5 bg-blue-600 text-white text-sm font-bold rounded-xl hover:bg-blue-700 transition">
                        Filter
                    </button>

                    @if($search || $status || $jobId)
                        <a href="{{ route('recruiter.applications') }}"
                           class="px-5 py-2.5 bg-gray-100 text-gray-600 text-sm font-bold rounded-xl hover:bg-gray-200 transition">
                            Clear
                        </a>
                    @endif
                </form>
            </div>

            {{-- ── Results ──────────────────────────────────────── --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-extrabold text-gray-900">Applications by Job</h2>
                    @if($search || $status || $jobId)
                        <span class="text-xs text-gray-400 font-bold">
                            Showing filtered results
                        </span>
                    @endif
                </div>

                @if($jobs->count() > 0)
                    @foreach($jobs as $job)
                        <div class="mb-8 border border-gray-100 rounded-xl overflow-hidden">
                            <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                                <div>
                                    <h3 class="font-extrabold text-gray-900">{{ $job->title }}</h3>
                                    <p class="text-xs text-gray-500 mt-0.5">{{ $job->company }} &bull; {{ $job->location }}</p>
                                </div>
                                <div class="flex items-center gap-3">
                                    <a href="{{ route('jobs.pipeline', $job) }}" class="text-xs font-bold text-purple-600 hover:underline">Pipeline</a>
                                    <a href="{{ route('jobs.show', $job) }}" class="text-xs font-bold text-blue-600 hover:underline">View Job &rarr;</a>
                                </div>
                            </div>

                            <div class="p-6">
                                @if($job->applications->count() > 0)
                                    <div class="space-y-4">
                                        @foreach($job->applications as $application)
                                            <div class="p-4 border border-gray-100 rounded-xl bg-white shadow-sm flex justify-between items-start">
                                                <div>
                                                    <p class="font-extrabold text-gray-900">{{ $application->user->name }}</p>
                                                    <p class="text-xs text-gray-500">{{ $application->user->email }}</p>
                                                    @if($application->user->skills)
                                                        <p class="text-xs text-gray-400 mt-0.5 italic">{{ Str::limit($application->user->skills, 60) }}</p>
                                                    @endif
                                                    <p class="text-xs text-gray-400 mt-1">Applied {{ $application->created_at->format('M d, Y') }}</p>
                                                </div>
                                                <div class="flex items-center gap-3 ml-4 flex-shrink-0">
                                                    @php
                                                        $sc = ['pending'=>'bg-yellow-100 text-yellow-700','shortlisted'=>'bg-blue-100 text-blue-700','interview'=>'bg-purple-100 text-purple-700','rejected'=>'bg-red-100 text-red-700'];
                                                    @endphp
                                                    <span class="text-[10px] font-bold uppercase px-2 py-1 rounded-lg {{ $sc[$application->status] ?? 'bg-gray-100 text-gray-700' }}">
                                                        {{ ucfirst($application->status) }}
                                                    </span>
                                                    <a href="{{ route('applications.show', $application) }}"
                                                       class="text-xs font-bold text-blue-600 hover:text-blue-800 underline underline-offset-2">
                                                        View Details
                                                    </a>
                                                    @if($application->cv_path)
                                                        <a href="{{ asset('storage/' . $application->cv_path) }}" target="_blank"
                                                           class="text-xs font-bold text-gray-500 hover:text-gray-800">CV</a>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-gray-400 text-sm italic">No matching applications for this job.</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="text-center py-12">
                        <p class="text-sm font-bold text-gray-400">No applications found matching your filters.</p>
                        <a href="{{ route('recruiter.applications') }}" class="text-xs text-blue-600 font-bold hover:underline mt-2 inline-block">Clear filters</a>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
