<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Recruitment Pipeline
                </h2>
                <p class="text-sm text-gray-500 mt-0.5">
                    {{ $job->title }} &mdash; {{ $job->company }}
                </p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('jobs.show', $job) }}"
                   class="text-sm font-bold text-gray-500 hover:text-gray-800 transition">
                    View Job Details
                </a>
                <a href="{{ route('dashboard') }}"
                   class="text-sm font-bold text-gray-500 hover:text-gray-800 transition">
                    &larr; Dashboard
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-xl text-green-800 text-sm font-semibold">
                    ✓ {{ session('success') }}
                </div>
            @endif

            {{-- Search bar --}}
            <form method="GET" action="{{ route('jobs.pipeline', $job) }}" class="mb-5 flex gap-3 items-center">
                <div class="flex-1">
                    <input type="text" name="search" value="{{ $search }}"
                           placeholder="Search candidates by name, email or skills..."
                           class="w-full rounded-xl border-gray-200 text-sm text-gray-700 focus:ring-purple-500 focus:border-purple-500">
                </div>
                <button type="submit"
                        class="px-5 py-2.5 bg-purple-600 text-white text-sm font-bold rounded-xl hover:bg-purple-700 transition">
                    Search
                </button>
                @if($search)
                    <a href="{{ route('jobs.pipeline', $job) }}"
                       class="px-5 py-2.5 bg-gray-100 text-gray-600 text-sm font-bold rounded-xl hover:bg-gray-200 transition">
                        Clear
                    </a>
                @endif
            </form>

            {{-- Summary bar --}}
            <div class="grid grid-cols-4 gap-4 mb-6">
                @php
                    $colMeta = [
                        'pending'     => ['label' => 'Applied',      'color' => 'text-yellow-600', 'bg' => 'bg-yellow-50',  'border' => 'border-yellow-200'],
                        'shortlisted' => ['label' => 'Shortlisted',  'color' => 'text-blue-600',   'bg' => 'bg-blue-50',    'border' => 'border-blue-200'],
                        'interview'   => ['label' => 'Interview',     'color' => 'text-purple-600', 'bg' => 'bg-purple-50',  'border' => 'border-purple-200'],
                        'rejected'    => ['label' => 'Rejected',      'color' => 'text-red-600',    'bg' => 'bg-red-50',     'border' => 'border-red-200'],
                    ];
                @endphp
                @foreach($colMeta as $key => $meta)
                    <div class="bg-white rounded-xl border border-gray-100 p-4 flex items-center gap-3 shadow-sm">
                        <div class="text-2xl font-extrabold {{ $meta['color'] }}">
                            {{ $columns[$key]->count() }}
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-500 uppercase tracking-widest">{{ $meta['label'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Kanban Board --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-5">

                @foreach($colMeta as $statusKey => $meta)
                    <div class="flex flex-col">

                        {{-- Column Header --}}
                        <div class="{{ $meta['bg'] }} {{ $meta['border'] }} border rounded-xl px-4 py-3 mb-3 flex items-center justify-between">
                            <span class="text-xs font-extrabold {{ $meta['color'] }} uppercase tracking-widest">
                                {{ $meta['label'] }}
                            </span>
                            <span class="text-xs font-bold text-gray-400 bg-white rounded-full px-2 py-0.5 border border-gray-100">
                                {{ $columns[$statusKey]->count() }}
                            </span>
                        </div>

                        {{-- Cards --}}
                        <div class="space-y-3 flex-1">
                            @forelse($columns[$statusKey] as $application)
                                <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 hover:shadow-md transition">

                                    {{-- Candidate info --}}
                                    <div class="mb-3">
                                        <h4 class="text-sm font-extrabold text-gray-900 leading-tight">
                                            {{ $application->user->name }}
                                        </h4>
                                        <p class="text-xs text-gray-500 mt-0.5">{{ $application->user->email }}</p>
                                        @if($application->user->skills)
                                            <p class="text-xs text-gray-400 mt-1 line-clamp-2 italic">
                                                {{ Str::limit($application->user->skills, 60) }}
                                            </p>
                                        @endif
                                        <p class="text-[10px] text-gray-300 mt-1 font-medium">
                                            Applied {{ $application->created_at->format('M d, Y') }}
                                        </p>
                                    </div>

                                    {{-- View Details --}}
                                    <a href="{{ route('applications.show', $application) }}"
                                       class="block w-full text-center text-xs font-bold py-1.5 mb-3 rounded-lg bg-gray-50 text-gray-700 hover:bg-gray-100 border border-gray-100 transition">
                                        View Details
                                    </a>

                                    {{-- Move buttons (exclude current status) --}}
                                    <div class="space-y-1.5">
                                        @if($statusKey !== 'shortlisted')
                                            <form action="{{ route('applications.update-status', $application) }}" method="POST">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="status" value="shortlisted">
                                                <input type="hidden" name="_pipeline_redirect" value="{{ route('jobs.pipeline', $job) }}">
                                                <button type="submit"
                                                        class="w-full text-[10px] font-bold py-1.5 rounded-lg bg-blue-50 text-blue-700 hover:bg-blue-100 border border-blue-100 transition">
                                                    → Shortlist
                                                </button>
                                            </form>
                                        @endif
                                        @if($statusKey !== 'interview')
                                            <form action="{{ route('applications.update-status', $application) }}" method="POST">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="status" value="interview">
                                                <input type="hidden" name="_pipeline_redirect" value="{{ route('jobs.pipeline', $job) }}">
                                                <button type="submit"
                                                        class="w-full text-[10px] font-bold py-1.5 rounded-lg bg-purple-50 text-purple-700 hover:bg-purple-100 border border-purple-100 transition">
                                                    → Interview
                                                </button>
                                            </form>
                                        @endif
                                        @if($statusKey !== 'rejected')
                                            <form action="{{ route('applications.update-status', $application) }}" method="POST">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="status" value="rejected">
                                                <input type="hidden" name="_pipeline_redirect" value="{{ route('jobs.pipeline', $job) }}">
                                                <button type="submit"
                                                        class="w-full text-[10px] font-bold py-1.5 rounded-lg bg-red-50 text-red-700 hover:bg-red-100 border border-red-100 transition">
                                                    → Reject
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="bg-white rounded-xl border border-dashed border-gray-200 p-6 text-center">
                                    <p class="text-xs text-gray-300 font-bold uppercase tracking-widest">Empty</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </div>
</x-app-layout>
