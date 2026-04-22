<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Job Details</h2>
            <a href="{{ route('jobs.index') }}" class="text-sm font-bold text-gray-500 hover:text-gray-800 transition">&larr; Back to Jobs</a>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('error'))
                <div class="p-4 bg-red-50 border border-red-200 rounded-xl text-red-800 text-sm font-medium">{{ session('error') }}</div>
            @endif
            @if(session('success'))
                <div class="p-4 bg-green-50 border border-green-200 rounded-xl text-green-800 text-sm font-medium">{{ session('success') }}</div>
            @endif

            <!-- Job Details Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-8 border-b border-gray-50">
                    <div class="flex justify-between items-start">
                        <div>
                            <h1 class="text-3xl font-extrabold text-gray-900 mb-2">{{ $job->title }}</h1>
                            <p class="text-lg font-bold text-blue-600">{{ $job->company }}</p>
                            <div class="flex items-center gap-4 mt-3 text-sm text-gray-500">
                                <span class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                                    {{ $job->location }}
                                </span>
                                @if($job->salary)
                                    <span class="flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        ${{ number_format($job->salary) }}
                                    </span>
                                @endif
                            </div>
                        </div>
                        <span class="px-4 py-2 rounded-xl text-sm font-bold uppercase tracking-wider {{ $job->status === 'open' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ $job->status }}
                        </span>
                    </div>
                </div>

                <div class="p-8">
                    <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Description</h4>
                    <p class="text-gray-700 leading-relaxed whitespace-pre-line">{{ $job->description }}</p>
                </div>
            </div>

            @auth
                @if(auth()->user()->role === 'candidate')
                    @php
                        $alreadyApplied = $job->applications->where('user_id', auth()->id())->count() > 0;
                    @endphp

                    @if($alreadyApplied)
                        <div class="bg-green-50 border border-green-200 rounded-2xl p-6 text-center">
                            <svg class="w-8 h-8 text-green-500 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <p class="text-sm font-bold text-green-700">You have already applied for this position.</p>
                            <a href="{{ route('applications.index') }}" class="mt-2 inline-block text-xs font-bold text-green-600 hover:underline">View My Applications &rarr;</a>
                        </div>
                    @elseif($job->status === 'open')
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
                            <h3 class="text-lg font-extrabold text-gray-900 mb-6">Apply for this Position</h3>
                            <form method="POST" action="{{ route('applications.store', $job) }}" enctype="multipart/form-data" class="space-y-6">
                                @csrf
                                <div>
                                    <label for="cv_file" class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Upload CV (PDF)</label>
                                    <input type="file" id="cv_file" name="cv_file" accept=".pdf"
                                        class="block w-full text-sm text-gray-700 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition">
                                    @error('cv_file')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="cover_letter" class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Cover Letter (Optional)</label>
                                    <textarea id="cover_letter" name="cover_letter" rows="5"
                                        class="w-full rounded-xl border-gray-200 text-sm text-gray-700 focus:ring-blue-500 focus:border-blue-500 placeholder-gray-300"
                                        placeholder="Tell the employer why you're the right fit..."></textarea>
                                </div>
                                <button type="submit" class="w-full py-3 bg-blue-600 text-white text-sm font-bold rounded-xl hover:bg-blue-700 transition shadow-lg shadow-blue-200">
                                    Submit Application
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="bg-gray-50 border border-gray-200 rounded-2xl p-6 text-center">
                            <p class="text-sm font-bold text-gray-500">This position is no longer accepting applications.</p>
                        </div>
                    @endif

                @elseif(auth()->user()->role === 'recruiter' && auth()->id() === $job->user_id)
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-lg font-extrabold text-gray-900">Applicants ({{ $job->applications->count() }})</h3>
                            <div class="flex gap-3">
                                <a href="{{ route('jobs.edit', $job) }}" class="text-xs font-bold text-blue-600 hover:text-blue-800 transition">Edit Job</a>
                                @if($job->status === 'open')
                                    <form action="{{ route('jobs.close', $job) }}" method="POST" class="inline">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="text-xs font-bold text-red-600 hover:text-red-800 transition">Close Job</button>
                                    </form>
                                @endif
                            </div>
                        </div>

                        @if($job->applications->count() > 0)
                            <div class="space-y-4">
                                @foreach($job->applications as $application)
                                    <div class="p-5 border border-gray-100 rounded-xl bg-gray-50/50 hover:bg-white hover:shadow-sm transition">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <p class="font-bold text-gray-900">{{ $application->user->name }}</p>
                                                <p class="text-xs text-gray-500">{{ $application->user->email }} &bull; Applied {{ $application->created_at->format('M d, Y') }}</p>
                                                <span class="mt-1 inline-flex px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider
                                                    {{ $application->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : ($application->status === 'shortlisted' || $application->status === 'interview' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600') }}">
                                                    {{ $application->status }}
                                                </span>
                                            </div>
                                            <div class="flex items-center gap-3">
                                                <a href="{{ route('applications.show', $application) }}" class="text-xs font-bold text-blue-600 hover:text-blue-800">View Details</a>
                                                @if($application->cv_path)
                                                    <a href="{{ asset('storage/' . $application->cv_path) }}" target="_blank" class="text-xs font-bold text-gray-500 hover:text-gray-800">View CV</a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-sm text-gray-400 italic text-center py-8">No applications received yet.</p>
                        @endif
                    </div>
                @endif
            @endauth

        </div>
    </div>
</x-app-layout>