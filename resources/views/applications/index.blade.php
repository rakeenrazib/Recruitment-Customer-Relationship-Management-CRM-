<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-black text-stone-900">My Applications</h2>
    </x-slot>

    <div class="min-h-screen bg-stone-50 py-10">
        <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
            <div class="overflow-hidden rounded-[2rem] border border-stone-200 bg-white shadow-sm">
                <div class="border-b border-stone-100 px-8 py-6">
                    <h3 class="text-2xl font-black text-stone-900">Application History</h3>
                    <p class="mt-2 text-sm text-stone-500">Track every role, company, and status transition in one place.</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-stone-50">
                            <tr>
                                <th class="px-8 py-4 text-left text-[11px] font-bold uppercase tracking-[0.28em] text-stone-400">Role</th>
                                <th class="px-8 py-4 text-left text-[11px] font-bold uppercase tracking-[0.28em] text-stone-400">Company</th>
                                <th class="px-8 py-4 text-left text-[11px] font-bold uppercase tracking-[0.28em] text-stone-400">Status</th>
                                <th class="px-8 py-4 text-left text-[11px] font-bold uppercase tracking-[0.28em] text-stone-400">Applied</th>
                                <th class="px-8 py-4 text-left text-[11px] font-bold uppercase tracking-[0.28em] text-stone-400">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-100">
                            @forelse($applications as $application)
                                <tr class="bg-white">
                                    <td class="px-8 py-6">
                                        <p class="font-bold text-stone-900">{{ $application->job->title }}</p>
                                        <p class="mt-1 text-sm text-stone-500">{{ $application->job->location }}</p>
                                    </td>
                                    <td class="px-8 py-6">
                                        @if($application->job->companyProfile)
                                            <a href="{{ route('companies.show', $application->job->companyProfile) }}" class="font-bold text-cyan-700">{{ $application->job->companyProfile->company_name }}</a>
                                        @else
                                            <span class="font-bold text-cyan-700">{{ $application->job->company }}</span>
                                        @endif
                                    </td>
                                    <td class="px-8 py-6">
                                        <span class="rounded-full bg-stone-900 px-3 py-1 text-[11px] font-bold uppercase tracking-[0.2em] text-white">{{ str_replace('_', ' ', $application->status) }}</span>
                                    </td>
                                    <td class="px-8 py-6 text-sm text-stone-600">{{ $application->created_at->format('M d, Y') }}</td>
                                    <td class="px-8 py-6">
                                        <a href="{{ route('applications.show', $application) }}" class="text-sm font-bold text-cyan-700">Details</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-8 py-16 text-center">
                                        <p class="text-sm font-semibold text-stone-500">You haven’t applied to any jobs yet.</p>
                                        <a href="{{ route('jobs.index') }}" class="mt-3 inline-block text-sm font-bold text-cyan-700">Browse jobs</a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
