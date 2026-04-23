<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-black text-stone-900">Profile Settings</h2>
    </x-slot>

    <div class="min-h-screen bg-stone-50 py-12">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
            <div class="grid gap-6 lg:grid-cols-[1.15fr_0.85fr]">
                <div class="rounded-[2rem] border border-stone-200 bg-white p-6 shadow-sm">
                    @include('profile.partials.update-profile-information-form')
                </div>

                <div class="space-y-6">
                    <div class="rounded-[2rem] border border-stone-200 bg-white p-6 shadow-sm">
                        @include('profile.partials.update-password-form')
                    </div>

                    <div class="rounded-[2rem] border border-stone-200 bg-white p-6 shadow-sm">
                        @include('profile.partials.delete-user-form')
                    </div>

                    @if($user->isCompany() && $user->company)
                        <div class="rounded-[2rem] border border-stone-200 bg-white p-6 shadow-sm">
                            <h3 class="text-lg font-black text-stone-900">Pending Recruiter Requests</h3>
                            <div class="mt-4 space-y-3">
                                @forelse($user->company->verificationRequests->where('status', 'pending') as $verificationRequest)
                                    <div class="rounded-2xl border border-stone-100 bg-stone-50 p-4">
                                        <p class="font-bold text-stone-900">{{ $verificationRequest->recruiter->full_name }}</p>
                                        <p class="text-sm text-stone-500">{{ $verificationRequest->message ?: 'No message attached.' }}</p>
                                        <form method="POST" action="{{ route('company.verification.approve', $verificationRequest) }}" class="mt-3">
                                            @csrf
                                            <button type="submit" class="rounded-2xl bg-stone-900 px-4 py-2 text-sm font-bold text-white">Approve</button>
                                        </form>
                                    </div>
                                @empty
                                    <p class="text-sm text-stone-500">No pending recruiter verification requests.</p>
                                @endforelse
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
