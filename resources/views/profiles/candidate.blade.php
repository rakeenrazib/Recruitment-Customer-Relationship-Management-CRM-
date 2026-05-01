<x-app-layout>
    <x-slot name="header">
        <h2 class="font-display text-2xl font-black text-slate-950">Profile</h2>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-5xl space-y-6 px-4 sm:px-6 lg:px-8">
            <div class="panel overflow-visible p-0">
                <div class="relative h-56 overflow-hidden rounded-t-[1.9rem] sm:h-72">
                    @if($candidate->user?->cover_photo_path)
                        <img src="{{ asset('storage/' . $candidate->user->cover_photo_path) }}" alt="{{ $candidate->full_name }}" class="h-full w-full object-cover">
                    @else
                        <div class="h-full w-full bg-[linear-gradient(135deg,#0f172a,#0f766e,#38bdf8)]"></div>
                    @endif
                    <div class="absolute inset-0 bg-slate-950/15"></div>
                </div>

                <div class="relative z-10 px-6 pb-10 pt-0 sm:px-8">
                    <div class="-mt-10 flex flex-col gap-4 sm:-mt-12 sm:flex-row sm:items-start">
                        @if($candidate->user->profile_photo_path)
                            <img src="{{ asset('storage/' . $candidate->user->profile_photo_path) }}" alt="{{ $candidate->full_name }}" class="h-24 w-24 rounded-[1.6rem] border-4 border-white object-cover shadow-xl">
                        @else
                            <div class="flex h-24 w-24 items-center justify-center rounded-[1.6rem] border-4 border-white bg-slate-950 text-3xl font-black text-white shadow-xl">
                                {{ strtoupper(substr($candidate->full_name, 0, 1)) }}
                            </div>
                        @endif

                        <div class="pt-2">
                            <h1 class="font-display text-4xl font-black text-slate-950">{{ $candidate->full_name }}</h1>
                            @if($candidate->location)
                                <p class="mt-2 text-sm text-slate-500">{{ $candidate->location }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid gap-6 lg:grid-cols-2">
                <div class="panel p-6">
                    <p class="eyebrow">Bio</p>
                    <p class="mt-4 text-sm leading-7 text-slate-600">{{ $candidate->bio ?: 'No bio yet.' }}</p>
                </div>

                <div class="panel p-6">
                    <p class="eyebrow">Links</p>
                    <p class="mt-4 text-sm leading-7 text-slate-600">{{ $candidate->portfolio ?: 'No links yet.' }}</p>
                </div>

                <div class="panel p-6 lg:col-span-2">
                    <p class="eyebrow">Details</p>
                    <p class="mt-4 text-sm leading-7 text-slate-600">{{ $candidate->details ?: 'No details yet.' }}</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
