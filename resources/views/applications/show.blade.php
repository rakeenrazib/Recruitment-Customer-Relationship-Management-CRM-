<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ auth()->user()->role === 'recruiter' ? 'Candidate Application Details' : 'My Application Details' }}
            </h2>
            @if(auth()->user()->role === 'candidate')
                <a href="{{ route('applications.index') }}" class="text-sm font-bold text-gray-500 hover:text-gray-800 transition">&larr; Back to My Applications</a>
            @else
                <a href="{{ route('recruiter.applications') }}" class="text-sm font-bold text-gray-500 hover:text-gray-800 transition">&larr; Back to Applications</a>
            @endif
        </div>
    </x-slot>

    <div class="py-10 bg-gray-50 min-h-screen">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="p-4 bg-green-50 border border-green-200 rounded-xl text-green-800 text-sm font-semibold">
                    ✓ {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="p-4 bg-red-50 border border-red-200 rounded-xl text-red-800 text-sm font-semibold">
                    {{ session('error') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- ============================================================ --}}
                {{-- LEFT COLUMN: Main Details (2/3)                              --}}
                {{-- ============================================================ --}}
                <div class="lg:col-span-2 space-y-6">

                    {{-- Job Info Card --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="px-8 py-6 border-b border-gray-50">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h3 class="text-2xl font-extrabold text-gray-900 mb-1">{{ $application->job->title }}</h3>
                                    <p class="text-base font-bold text-blue-600">{{ $application->job->company }}</p>
                                    <p class="text-sm text-gray-500 mt-1 flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                        {{ $application->job->location }}
                                    </p>
                                </div>
                                <div class="text-right">
                                    @php
                                        $statusColors = [
                                            'pending'     => 'bg-yellow-100 text-yellow-700',
                                            'shortlisted' => 'bg-blue-100 text-blue-700',
                                            'interview'   => 'bg-purple-100 text-purple-700',
                                            'rejected'    => 'bg-red-100 text-red-700',
                                        ];
                                        $color = $statusColors[$application->status] ?? 'bg-gray-100 text-gray-700';
                                    @endphp
                                    <span class="inline-flex px-4 py-1.5 rounded-xl text-xs font-bold uppercase tracking-wider {{ $color }}">
                                        {{ ucfirst($application->status) }}
                                    </span>
                                    <p class="text-xs text-gray-400 mt-2 font-medium">
                                        Applied on {{ $application->created_at->format('M d, Y') }}
                                    </p>
                                    <p class="text-xs text-gray-400 font-medium">{{ $application->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        </div>

                        {{-- Candidate / Recruiter Info Row --}}
                        @if(auth()->user()->role === 'recruiter')
                            <div class="px-8 py-6 bg-gray-50/50 border-b border-gray-50">
                                <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4">Candidate Information</h4>
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                                    <div>
                                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Name</p>
                                        <p class="text-sm font-bold text-gray-900">{{ $application->user->name }}</p>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Email</p>
                                        <p class="text-sm font-bold text-gray-900">{{ $application->user->email }}</p>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Skills</p>
                                        <p class="text-sm text-gray-700">{{ $application->user->skills ?? 'Not specified' }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- Resume --}}
                        <div class="px-8 py-6 border-b border-gray-50">
                            <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3">Resume / CV</h4>
                            @if($application->cv_path)
                                <div class="flex gap-3">
                                    <a href="{{ asset('storage/' . $application->cv_path) }}" target="_blank"
                                       class="inline-flex items-center gap-2 px-4 py-2 bg-blue-50 border border-blue-100 rounded-xl text-sm font-bold text-blue-700 hover:bg-blue-100 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        View Resume
                                    </a>
                                    <a href="{{ asset('storage/' . $application->cv_path) }}" download
                                       class="inline-flex items-center gap-2 px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm font-bold text-gray-700 hover:bg-gray-100 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        Download
                                    </a>
                                </div>
                            @else
                                <p class="text-sm text-gray-400 italic">No resume uploaded.</p>
                            @endif
                        </div>

                        {{-- Cover Letter --}}
                        <div class="px-8 py-6">
                            <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3">Cover Letter</h4>
                            @if($application->cover_letter)
                                <div class="p-5 bg-gray-50 rounded-xl border border-gray-100 text-gray-700 text-sm leading-relaxed whitespace-pre-line">
                                    {{ $application->cover_letter }}
                                </div>
                            @else
                                <p class="text-sm text-gray-400 italic">No cover letter provided.</p>
                            @endif
                        </div>
                    </div>

                    {{-- ============================================================ --}}
                    {{-- CANDIDATE ONLY: Visual Progress Step Indicator               --}}
                    {{-- ============================================================ --}}
                    @if(auth()->user()->role === 'candidate')
                        @php
                            $currentStatus = $application->status;
                            $isRejected = $currentStatus === 'rejected';

                            // Linear progress steps (rejection is a terminal state, not a "step forward")
                            $progressSteps = [
                                ['key' => 'applied',     'label' => 'Applied',        'desc' => 'Your application was submitted'],
                                ['key' => 'shortlisted', 'label' => 'Under Review',   'desc' => 'Recruiter is reviewing your profile'],
                                ['key' => 'interview',   'label' => 'Interview',       'desc' => 'You have been selected for interview'],
                                ['key' => 'decision',    'label' => 'Final Decision',  'desc' => 'Outcome of your application'],
                            ];

                            // Map DB status → which step index is "current"
                            $stepIndex = match($currentStatus) {
                                'pending'     => 0,
                                'shortlisted' => 1,
                                'interview'   => 2,
                                'rejected'    => 3,
                                default       => 0,
                            };

                            // Progress bar fill percentage
                            $fillPercent = match($currentStatus) {
                                'pending'     => 0,
                                'shortlisted' => 33,
                                'interview'   => 66,
                                'rejected'    => 100,
                                default       => 0,
                            };
                        @endphp

                        {{-- Rejected Banner --}}
                        @if($isRejected)
                            <div class="bg-red-50 border border-red-200 rounded-2xl px-8 py-5 flex items-start gap-4">
                                <div class="flex-shrink-0 w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-sm font-extrabold text-red-700 mb-1">Application Closed</h4>
                                    <p class="text-xs text-red-500 leading-relaxed">
                                        Unfortunately, this application has been closed. You are welcome to browse other open positions.
                                    </p>
                                    <a href="{{ route('jobs.index') }}" class="inline-block mt-3 text-xs font-bold text-red-700 underline underline-offset-2 hover:text-red-900">
                                        Browse Jobs &rarr;
                                    </a>
                                </div>
                            </div>
                        @endif

                        {{-- Step Indicator Card --}}
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 px-8 py-7">
                            <div class="flex items-center justify-between mb-6">
                                <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Application Progress</h4>
                                <span class="text-[10px] font-bold uppercase tracking-widest px-2.5 py-1 rounded-lg
                                    @if($isRejected) bg-red-100 text-red-600
                                    @elseif($currentStatus === 'interview') bg-purple-100 text-purple-600
                                    @elseif($currentStatus === 'shortlisted') bg-blue-100 text-blue-600
                                    @else bg-yellow-100 text-yellow-600 @endif">
                                    {{ ucfirst($currentStatus) }}
                                </span>
                            </div>

                            {{-- Progress Bar --}}
                            <div class="relative h-1.5 bg-gray-100 rounded-full mb-8 overflow-hidden">
                                <div class="absolute inset-y-0 left-0 rounded-full transition-all duration-500
                                    @if($isRejected) bg-red-400
                                    @elseif($currentStatus === 'interview') bg-purple-500
                                    @elseif($currentStatus === 'shortlisted') bg-blue-500
                                    @else bg-yellow-400 @endif"
                                     style="width: {{ $fillPercent }}%">
                                </div>
                            </div>

                            {{-- Steps Row --}}
                            <div class="grid grid-cols-4 gap-1">
                                @foreach($progressSteps as $i => $step)
                                    @php
                                        $isCurrent = $i === $stepIndex;
                                        $isDone    = $i < $stepIndex;
                                        $isPending = $i > $stepIndex;
                                    @endphp
                                    <div class="flex flex-col items-center text-center">

                                        {{-- Circle --}}
                                        <div class="relative mb-3">
                                            <div class="w-10 h-10 rounded-full flex items-center justify-center border-2
                                                @if($isCurrent && $isRejected) bg-red-100 border-red-400 text-red-600
                                                @elseif($isCurrent && $currentStatus === 'interview') bg-purple-500 border-purple-500 text-white
                                                @elseif($isCurrent && $currentStatus === 'shortlisted') bg-blue-500 border-blue-500 text-white
                                                @elseif($isCurrent) bg-yellow-400 border-yellow-400 text-white
                                                @elseif($isDone) bg-green-500 border-green-500 text-white
                                                @else bg-white border-gray-200 text-gray-300 @endif">

                                                @if($isCurrent && $isRejected)
                                                    {{-- X icon --}}
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                                                    </svg>
                                                @elseif($isDone)
                                                    {{-- Check icon --}}
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                                    </svg>
                                                @elseif($isCurrent)
                                                    {{-- Active pulse dot --}}
                                                    <span class="w-2.5 h-2.5 rounded-full bg-white block"></span>
                                                @else
                                                    {{-- Inactive dot --}}
                                                    <span class="w-2 h-2 rounded-full bg-gray-200 block"></span>
                                                @endif
                                            </div>

                                            {{-- Pulse ring for current active step --}}
                                            @if($isCurrent && !$isRejected)
                                                <span class="absolute inset-0 rounded-full animate-ping opacity-20
                                                    @if($currentStatus === 'interview') bg-purple-400
                                                    @elseif($currentStatus === 'shortlisted') bg-blue-400
                                                    @else bg-yellow-400 @endif">
                                                </span>
                                            @endif
                                        </div>

                                        {{-- Label --}}
                                        <p class="text-[10px] font-extrabold uppercase tracking-wide leading-tight mb-0.5
                                            @if($isCurrent && $isRejected) text-red-600
                                            @elseif($isCurrent && $currentStatus === 'interview') text-purple-600
                                            @elseif($isCurrent && $currentStatus === 'shortlisted') text-blue-600
                                            @elseif($isCurrent) text-yellow-600
                                            @elseif($isDone) text-green-600
                                            @else text-gray-300 @endif">
                                            {{ $step['label'] }}
                                        </p>

                                        {{-- Desc --}}
                                        <p class="text-[9px] text-gray-400 leading-tight hidden sm:block">
                                            {{ $step['desc'] }}
                                        </p>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Candidate Actions --}}
                        @if($application->status === 'pending')
                            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 px-8 py-6">
                                <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4">Actions</h4>
                                <form action="{{ route('applications.destroy', $application) }}" method="POST"
                                      onsubmit="return confirm('Are you sure you want to withdraw this application?');">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            class="inline-flex items-center gap-2 px-5 py-2.5 bg-red-50 border border-red-100 text-red-700 text-sm font-bold rounded-xl hover:bg-red-100 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                        Withdraw Application
                                    </button>
                                </form>
                            </div>
                        @endif
                    @endif

                </div>

                {{-- ============================================================ --}}
                {{-- RIGHT COLUMN: Sidebar                                         --}}
                {{-- ============================================================ --}}
                <div class="space-y-6">

                    {{-- RECRUITER ONLY: Status Control & Quick Actions --}}
                    @if(auth()->user()->role === 'recruiter')

                        {{-- Quick Action Buttons --}}
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 px-6 py-5">
                            <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4">Quick Actions</h4>
                            <div class="space-y-2">
                                <form action="{{ route('applications.update-status', $application) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status" value="shortlisted">
                                    <button type="submit" class="w-full py-2.5 bg-blue-50 text-blue-700 text-xs font-bold rounded-xl hover:bg-blue-100 transition border border-blue-100">
                                        ✓ Shortlist Candidate
                                    </button>
                                </form>
                                <form action="{{ route('applications.update-status', $application) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status" value="interview">
                                    <button type="submit" class="w-full py-2.5 bg-purple-50 text-purple-700 text-xs font-bold rounded-xl hover:bg-purple-100 transition border border-purple-100">
                                        📅 Move to Interview
                                    </button>
                                </form>
                                <form action="{{ route('applications.update-status', $application) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status" value="rejected">
                                    <button type="submit" class="w-full py-2.5 bg-red-50 text-red-700 text-xs font-bold rounded-xl hover:bg-red-100 transition border border-red-100">
                                        ✕ Reject Application
                                    </button>
                                </form>
                            </div>
                        </div>

                        {{-- Status Dropdown + Notes Form --}}
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 px-6 py-5">
                            <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4">Update Status & Notes</h4>
                            <form action="{{ route('applications.update-status', $application) }}" method="POST" class="space-y-4">
                                @csrf @method('PATCH')

                                <div>
                                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Status</label>
                                    <select name="status" class="w-full rounded-xl border-gray-200 text-sm font-bold text-gray-700 focus:ring-blue-500 focus:border-blue-500">
                                        <option value="pending"     @if($application->status === 'pending')     selected @endif>Pending</option>
                                        <option value="shortlisted" @if($application->status === 'shortlisted') selected @endif>Shortlisted</option>
                                        <option value="interview"   @if($application->status === 'interview')   selected @endif>Interview</option>
                                        <option value="rejected"    @if($application->status === 'rejected')    selected @endif>Rejected</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Recruiter Notes</label>
                                    <textarea name="notes" rows="4"
                                              class="w-full rounded-xl border-gray-200 text-sm text-gray-700 focus:ring-blue-500 focus:border-blue-500 placeholder-gray-300"
                                              placeholder="Add private feedback or notes...">{{ $application->notes }}</textarea>
                                </div>

                                <button type="submit" class="w-full py-2.5 bg-blue-600 text-white text-sm font-bold rounded-xl hover:bg-blue-700 transition">
                                    Save Changes
                                </button>
                            </form>
                        </div>

                    @endif

                    {{-- Metadata Card (both roles) --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 px-6 py-5">
                        <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4">Details</h4>
                        <div class="space-y-3">
                            <div class="flex justify-between text-xs">
                                <span class="text-gray-400 font-bold uppercase tracking-widest">App ID</span>
                                <span class="text-gray-900 font-bold">#{{ $application->id }}</span>
                            </div>
                            <div class="flex justify-between text-xs">
                                <span class="text-gray-400 font-bold uppercase tracking-widest">Applied</span>
                                <span class="text-gray-900 font-bold">{{ $application->created_at->format('M d, Y') }}</span>
                            </div>
                            <div class="flex justify-between text-xs">
                                <span class="text-gray-400 font-bold uppercase tracking-widest">Status</span>
                                <span class="font-bold {{ $color }}  px-2 py-0.5 rounded-lg text-[10px] uppercase tracking-wider">{{ ucfirst($application->status) }}</span>
                            </div>
                            <div class="flex justify-between text-xs">
                                <span class="text-gray-400 font-bold uppercase tracking-widest">Job</span>
                                <span class="text-gray-900 font-bold text-right">{{ Str::limit($application->job->title, 20) }}</span>
                            </div>
                            <div class="flex justify-between text-xs">
                                <span class="text-gray-400 font-bold uppercase tracking-widest">Source</span>
                                <span class="text-gray-900 font-bold">Direct Portal</span>
                            </div>
                        </div>
                    </div>

                    {{-- Notes (read-only for candidate if recruiter left notes) --}}
                    @if(auth()->user()->role === 'candidate' && $application->notes)
                        <div class="bg-blue-50 border border-blue-100 rounded-2xl px-6 py-5">
                            <h4 class="text-[10px] font-bold text-blue-400 uppercase tracking-widest mb-2">Feedback from Recruiter</h4>
                            <p class="text-sm text-blue-800 leading-relaxed">{{ $application->notes }}</p>
                        </div>
                    @endif

                </div>
            </div>

        </div>
    </div>
</x-app-layout>
