<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Applications') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-8 border-b border-gray-50 bg-white">
                    <h3 class="text-xl font-extrabold text-gray-900">Application History</h3>
                    <p class="text-sm text-gray-500 mt-1">Track and manage all your job applications in one place.</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50/50">
                                <th class="px-8 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Job Title</th>
                                <th class="px-8 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Company</th>
                                <th class="px-8 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Status</th>
                                <th class="px-8 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Applied Date</th>
                                <th class="px-8 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($applications as $application)
                                <tr class="hover:bg-gray-50/50 transition">
                                    <td class="px-8 py-6">
                                        <div class="font-bold text-gray-900">{{ $application->job->title }}</div>
                                        <div class="text-xs text-gray-500 mt-0.5">{{ $application->job->location }}</div>
                                    </td>
                                    <td class="px-8 py-6">
                                        <div class="text-sm font-bold text-blue-600">{{ $application->job->company }}</div>
                                    </td>
                                    <td class="px-8 py-6">
                                        <span class="inline-flex px-3 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider 
                                            @if($application->status === 'pending') bg-yellow-100 text-yellow-700 
                                            @elseif($application->status === 'shortlisted' || $application->status === 'interview') bg-green-100 text-green-700 
                                            @else bg-gray-100 text-gray-700 @endif">
                                            {{ $application->status }}
                                        </span>
                                    </td>
                                    <td class="px-8 py-6">
                                        <div class="text-sm text-gray-600">{{ $application->created_at->format('M d, Y') }}</div>
                                    </td>
                                    <td class="px-8 py-6">
                                        <a href="{{ route('applications.show', $application) }}" class="text-xs font-bold text-blue-600 hover:text-blue-800 transition underline underline-offset-4">Details</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-8 py-12 text-center">
                                        <div class="flex flex-col items-center">
                                            <svg class="w-12 h-12 text-gray-200 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                            <p class="text-sm font-bold text-gray-400">You haven't applied to any jobs yet.</p>
                                            <a href="{{ route('jobs.index') }}" class="mt-4 text-xs font-bold text-blue-600 hover:underline">Browse available jobs &rarr;</a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="flex justify-start">
                <a href="{{ route('dashboard') }}" class="text-sm font-bold text-gray-500 hover:text-gray-800 transition">&larr; Back to Dashboard</a>
            </div>

        </div>
    </div>
</x-app-layout>