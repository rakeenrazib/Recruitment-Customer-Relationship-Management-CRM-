<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Row 1: Summary Stats Section -->
            @if(isset($stats))
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @if(auth()->user()->role === 'candidate')
                        <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100 flex items-center transition hover:shadow-md">
                            <div class="mr-4 text-blue-500">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            </div>
                            <div>
                                <span class="text-xs font-bold text-gray-400 uppercase tracking-widest block mb-0.5">Total Applications</span>
                                <span class="text-2xl font-extrabold text-gray-900 leading-none">{{ $stats['total_applications'] }}</span>
                            </div>
                        </div>
                        <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100 flex items-center transition hover:shadow-md">
                            <div class="mr-4 text-gray-600">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <div>
                                <span class="text-xs font-bold text-gray-400 uppercase tracking-widest block mb-0.5">Pending</span>
                                <span class="text-2xl font-extrabold text-gray-900 leading-none">{{ $stats['pending_applications'] }}</span>
                            </div>
                        </div>
                        <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100 flex items-center transition hover:shadow-md">
                            <div class="mr-4 text-green-500">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <div>
                                <span class="text-xs font-bold text-gray-400 uppercase tracking-widest block mb-0.5">Shortlisted</span>
                                <span class="text-2xl font-extrabold text-gray-900 leading-none">{{ $stats['shortlisted_applications'] }}</span>
                            </div>
                        </div>
                    @elseif(auth()->user()->role === 'recruiter')
                        <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100 flex items-center transition hover:shadow-md">
                            <div class="mr-4 text-blue-500">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                            </div>
                            <div>
                                <span class="text-xs font-bold text-gray-400 uppercase tracking-widest block mb-0.5">Total Jobs</span>
                                <span class="text-2xl font-extrabold text-gray-900 leading-none">{{ $stats['total_jobs'] }}</span>
                            </div>
                        </div>
                        <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100 flex items-center transition hover:shadow-md">
                            <div class="mr-4 text-green-500">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            </div>
                            <div>
                                <span class="text-xs font-bold text-gray-400 uppercase tracking-widest block mb-0.5">Active Jobs</span>
                                <span class="text-2xl font-extrabold text-gray-900 leading-none">{{ $stats['active_jobs'] }}</span>
                            </div>
                        </div>
                        <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100 flex items-center transition hover:shadow-md">
                            <div class="mr-4 text-purple-500">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            </div>
                            <div>
                                <span class="text-xs font-bold text-gray-400 uppercase tracking-widest block mb-0.5">Applications</span>
                                <span class="text-2xl font-extrabold text-gray-900 leading-none">{{ $stats['total_applications_received'] ?? 0 }}</span>
                            </div>
                        </div>
                    @endif
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Main Content (2/3) -->
                <div class="md:col-span-2 space-y-6">
                    <!-- Quick Actions -->
                    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                        <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-4">Quick Actions</h3>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                            @if(auth()->user()->role === 'recruiter')
                                <a href="{{ route('jobs.create') }}" class="flex flex-col items-center justify-center p-4 bg-blue-50 text-blue-700 rounded-xl hover:bg-blue-100 transition border border-blue-100">
                                    <svg class="w-6 h-6 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                    <span class="text-xs font-bold">Post Job</span>
                                </a>
                                <a href="{{ route('jobs.index') }}" class="flex flex-col items-center justify-center p-4 bg-gray-50 text-gray-700 rounded-xl hover:bg-gray-100 transition border border-gray-200">
                                    <svg class="w-6 h-6 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                    <span class="text-xs font-bold">Manage</span>
                                </a>
                                <a href="{{ route('recruiter.applications') }}" class="flex flex-col items-center justify-center p-4 bg-gray-50 text-gray-700 rounded-xl hover:bg-gray-100 transition border border-gray-200">
                                    <svg class="w-6 h-6 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                                    <span class="text-xs font-bold">Applicants</span>
                                </a>
                                <a href="{{ route('candidates.search') }}" class="flex flex-col items-center justify-center p-4 bg-gray-50 text-gray-700 rounded-xl hover:bg-gray-100 transition border border-gray-200">
                                    <svg class="w-6 h-6 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                    <span class="text-xs font-bold">Search</span>
                                </a>
                                <a href="{{ route('interviews.create') }}" class="flex flex-col items-center justify-center p-4 bg-gray-50 text-gray-700 rounded-xl hover:bg-gray-100 transition border border-gray-200">
                                    <svg class="w-6 h-6 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    <span class="text-xs font-bold">Interview</span>
                                </a>
                            @else
                                <a href="{{ route('jobs.index') }}" class="flex flex-col items-center justify-center p-4 bg-blue-50 text-blue-700 rounded-xl hover:bg-blue-100 transition border border-blue-100">
                                    <svg class="w-6 h-6 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                    <span class="text-xs font-bold">Browse</span>
                                </a>
                                <a href="{{ route('applications.index') }}" class="flex flex-col items-center justify-center p-4 bg-gray-50 text-gray-700 rounded-xl hover:bg-gray-100 transition border border-gray-200">
                                    <svg class="w-6 h-6 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                                    <span class="text-xs font-bold">My Apps</span>
                                </a>
                                <a href="{{ route('jobs.saved') }}" class="flex flex-col items-center justify-center p-4 bg-gray-50 text-gray-700 rounded-xl hover:bg-gray-100 transition border border-gray-200">
                                    <svg class="w-6 h-6 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path></svg>
                                    <span class="text-xs font-bold">Saved</span>
                                </a>
                            @endif
                        </div>
                    </div>

                    <!-- Recent Section -->
                    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider">
                                @if(auth()->user()->role === 'candidate') Recent Applications @else Recent Jobs @endif
                            </h3>
                            <a href="@if(auth()->user()->role === 'candidate') {{ route('applications.index') }} @else {{ route('jobs.index') }} @endif" class="text-xs font-bold text-blue-600 hover:underline transition">View All</a>
                        </div>

                        <div class="space-y-3">
                            @if(auth()->user()->role === 'candidate')
                                @forelse($recentApplications as $app)
                                    <div class="p-4 border border-gray-50 rounded-xl bg-gray-50/50 hover:bg-white hover:shadow-sm transition">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <h4 class="text-base font-bold text-gray-900">{{ $app->job->title }}</h4>
                                                <p class="text-xs text-gray-500 font-medium">{{ $app->job->company }}</p>
                                            </div>
                                            <span class="px-2 py-1 rounded text-[10px] font-bold uppercase @if($app->status === 'pending') bg-yellow-100 text-yellow-700 @elseif($app->status === 'shortlisted') bg-green-100 text-green-700 @else bg-gray-100 text-gray-700 @endif">
                                                {{ $app->status }}
                                            </span>
                                        </div>
                                        <div class="flex items-center space-x-4 mt-3">
                                            <a href="{{ route('applications.show', $app) }}" class="text-xs font-bold text-blue-600 hover:text-blue-800">View</a>
                                            @if($app->status === 'pending')
                                                <form action="{{ route('applications.destroy', $app) }}" method="POST" class="inline">
                                                    @csrf @method('DELETE')
                                                    <button class="text-xs font-bold text-red-600 hover:text-red-800">Withdraw</button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-center text-xs text-gray-400 py-4">No recent activity.</p>
                                @endforelse
                            @else
                                @forelse($recentJobs as $job)
                                    <div class="p-4 border border-gray-50 rounded-xl bg-gray-50/50 hover:bg-white hover:shadow-sm transition">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <h4 class="text-base font-bold text-gray-900">{{ $job->title }}</h4>
                                                <p class="text-xs text-gray-500 font-medium">{{ $job->location }}</p>
                                            </div>
                                            <span class="text-xs font-bold text-blue-600">{{ $job->applications->count() }} Apps</span>
                                        </div>
                                        <div class="flex items-center space-x-4 mt-3">
                                            <a href="{{ route('jobs.edit', $job) }}" class="text-xs font-bold text-blue-600">Edit</a>
                                            <a href="{{ route('jobs.show', $job) }}" class="text-xs font-bold text-gray-600">Applicants</a>
                                            <a href="{{ route('jobs.pipeline', $job) }}" class="text-xs font-bold text-purple-600 hover:text-purple-800">Pipeline</a>
                                            @if($job->status === 'open')
                                                <form action="{{ route('jobs.close', $job) }}" method="POST" class="inline">
                                                    @csrf @method('PATCH')
                                                    <button class="text-xs font-bold text-red-600">Close</button>
                                                </form>
                                            @endif
                                            <form action="{{ route('jobs.destroy', $job) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this job?');">
                                                @csrf @method('DELETE')
                                                <button class="text-xs font-bold text-red-800">Delete</button>
                                            </form>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-center text-xs text-gray-400 py-4">No jobs posted yet.</p>
                                @endforelse
                            @endif
                        </div>
                    </div>

                    <!-- Additional Context Sections -->
                    @if(auth()->user()->role === 'recruiter')
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            @if($pendingApplications->count() > 0)
                                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                                    <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-4">New Applicants</h3>
                                    <div class="space-y-3">
                                        @foreach($pendingApplications as $app)
                                            <div class="p-4 border border-gray-50 rounded-xl bg-gray-50/50">
                                                <div class="flex justify-between items-center">
                                                    <div>
                                                        <h4 class="text-sm font-bold text-gray-900">{{ $app->user->name }}</h4>
                                                        <p class="text-[11px] text-gray-500">For: {{ $app->job->title }}</p>
                                                    </div>
                                                    <div class="flex space-x-2">
                                                        <form action="{{ route('applications.update-status', $app) }}" method="POST">
                                                            @csrf @method('PATCH')
                                                            <input type="hidden" name="status" value="shortlisted">
                                                            <button class="p-1.5 bg-green-50 text-green-600 rounded-lg hover:bg-green-100">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                            </button>
                                                        </form>
                                                        <form action="{{ route('applications.update-status', $app) }}" method="POST">
                                                            @csrf @method('PATCH')
                                                            <input type="hidden" name="status" value="rejected">
                                                            <button class="p-1.5 bg-red-50 text-red-600 rounded-lg hover:bg-red-100">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            @if($shortlistedApplications->count() > 0)
                                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                                    <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-4">Shortlisted</h3>
                                    <div class="space-y-3">
                                        @foreach($shortlistedApplications as $app)
                                            <div class="p-4 border border-green-50 rounded-xl bg-green-50/30">
                                                <div class="flex justify-between items-center">
                                                    <div>
                                                        <h4 class="text-sm font-bold text-gray-900">{{ $app->user->name }}</h4>
                                                        <p class="text-[11px] text-gray-500">For: {{ $app->job->title }}</p>
                                                    </div>
                                                    <div class="flex space-x-2">
                                                        <a href="{{ route('applications.show', $app) }}" class="p-1.5 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 text-xs font-bold px-3">
                                                            View
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>

                <!-- Sidebar (1/3) -->
                <div class="space-y-6">

                    {{-- ── Notifications Panel (candidates only) ── --}}
                    @if(auth()->user()->role === 'candidate')
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                            {{-- Header --}}
                            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                    </svg>
                                    <h3 class="text-sm font-bold text-gray-700">Notifications</h3>
                                    @if($unreadCount > 0)
                                        <span class="inline-flex items-center justify-center w-5 h-5 text-[10px] font-bold text-white bg-blue-500 rounded-full">
                                            {{ $unreadCount }}
                                        </span>
                                    @endif
                                </div>
                                @if($notifications->count() > 0)
                                    <form action="{{ route('notifications.mark-all-read') }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                                class="text-[10px] font-bold text-blue-500 hover:text-blue-700 transition whitespace-nowrap">
                                            Mark all read
                                        </button>
                                    </form>
                                @endif
                            </div>

                            {{-- List --}}
                            <div class="divide-y divide-gray-50">
                                @forelse($notifications as $notif)
                                    @php
                                        $typeColors = [
                                            'shortlisted' => ['bg' => 'bg-green-100', 'text' => 'text-green-700'],
                                            'interview'   => ['bg' => 'bg-purple-100', 'text' => 'text-purple-700'],
                                            'rejected'    => ['bg' => 'bg-red-100',  'text' => 'text-red-700'],
                                            'pending'     => ['bg' => 'bg-yellow-100','text' => 'text-yellow-700'],
                                        ];
                                        $tc = $typeColors[$notif->type] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-600'];
                                    @endphp
                                    <div class="px-5 py-3 flex items-start gap-3 {{ $notif->is_read ? '' : 'bg-blue-50/40' }} hover:bg-gray-50 transition">
                                        {{-- Unread dot --}}
                                        <div class="mt-1.5 flex-shrink-0">
                                            @if(!$notif->is_read)
                                                <div class="w-2 h-2 rounded-full bg-blue-500"></div>
                                            @else
                                                <div class="w-2 h-2 rounded-full bg-transparent"></div>
                                            @endif
                                        </div>

                                        <div class="flex-1 min-w-0">
                                            <span class="inline-block text-[9px] font-bold uppercase tracking-wider px-1.5 py-0.5 rounded {{ $tc['bg'] }} {{ $tc['text'] }} mb-0.5">
                                                {{ ucfirst($notif->type) }}
                                            </span>
                                            <p class="text-xs text-gray-800 font-medium leading-snug">{{ $notif->message }}</p>
                                            <p class="text-[10px] text-gray-400 mt-0.5">{{ $notif->created_at->diffForHumans() }}</p>
                                        </div>

                                        {{-- Mark as read --}}
                                        @if(!$notif->is_read)
                                            <form action="{{ route('notifications.mark-read', $notif) }}" method="POST" class="flex-shrink-0">
                                                @csrf
                                                <button type="submit"
                                                        title="Mark as read"
                                                        class="text-gray-300 hover:text-blue-500 transition mt-1">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                @empty
                                    <div class="px-5 py-8 text-center">
                                        <svg class="w-8 h-8 text-gray-200 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                  d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                        </svg>
                                        <p class="text-xs text-gray-400 font-medium">No notifications yet</p>
                                    </div>
                                @endforelse
                            </div>

                            {{-- Footer: View All --}}
                            <div class="px-5 py-3 border-t border-gray-100 bg-gray-50/50">
                                <a href="{{ route('notifications.index') }}"
                                   class="flex items-center justify-center gap-1 text-xs font-bold text-blue-600 hover:text-blue-800 transition">
                                    View All Notifications
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    @endif

                    {{-- Insights --}}
                    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                        <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-6">Insights</h3>
                        <div class="space-y-4">
                            <div class="flex justify-between items-center">
                                <span class="text-xs text-gray-600 font-medium">Activity (Week)</span>
                                <span class="text-xs font-bold text-blue-600">0</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-xs text-gray-600 font-medium">Interviews</span>
                                <span class="text-xs font-bold text-gray-900">0</span>
                            </div>
                            <div class="h-1.5 w-full bg-gray-100 rounded-full overflow-hidden">
                                <div class="h-full bg-blue-500 w-[10%]"></div>
                            </div>
                            <p class="text-[10px] text-gray-400 leading-relaxed">System is tracking your engagement patterns.</p>
                        </div>
                    </div>

                    @if(auth()->user()->role === 'candidate' && $recommendedJobs->count() > 0)
                        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                            <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-4">Recommended</h3>
                            <div class="space-y-4">
                                @foreach($recommendedJobs as $job)
                                    <div class="space-y-1">
                                        <h4 class="text-xs font-bold text-gray-900 truncate">{{ $job->title }}</h4>
                                        <p class="text-[10px] text-gray-500">{{ $job->company }} • {{ $job->location }}</p>
                                        <a href="{{ route('jobs.show', $job) }}" class="text-[10px] font-bold text-blue-600 hover:underline">Apply Now</a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
