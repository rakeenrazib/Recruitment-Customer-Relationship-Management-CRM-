<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-display text-2xl font-black text-slate-950">Notifications</h2>
            <a href="{{ route('dashboard') }}" class="text-sm font-bold text-slate-500 transition hover:text-slate-900">Back</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-4xl space-y-4 px-4 sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="panel border-emerald-200 bg-emerald-50/90 px-5 py-4 text-sm font-medium text-emerald-800">{{ session('success') }}</div>
            @endif

            @if($notifications->total() > 0)
                <div class="flex justify-end">
                    <form action="{{ route('notifications.mark-all-read') }}" method="POST">
                        @csrf
                        <button type="submit" class="rounded-2xl border border-slate-200 bg-white px-4 py-2 text-sm font-bold text-slate-700 transition hover:bg-slate-50">
                            Mark all read
                        </button>
                    </form>
                </div>
            @endif

            <div class="space-y-3">
                @forelse($notifications as $notif)
                    <div class="panel px-6 py-5 {{ $notif->is_read ? '' : 'border-sky-200 bg-sky-50/60' }}">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex items-start gap-3">
                                <div class="mt-1 h-2.5 w-2.5 rounded-full {{ $notif->is_read ? 'bg-slate-200' : 'bg-sky-500' }}"></div>
                                <div>
                                    @php
                                        $typeColors = [
                                            'shortlisted' => 'bg-blue-100 text-blue-700',
                                            'interview_scheduled' => 'bg-purple-100 text-purple-700',
                                            'hired' => 'bg-emerald-100 text-emerald-700',
                                            'rejected' => 'bg-red-100 text-red-700',
                                            'applied' => 'bg-amber-100 text-amber-700',
                                            'job-alert' => 'bg-cyan-100 text-cyan-700',
                                            'company-update' => 'bg-slate-200 text-slate-700',
                                        ];
                                        $tc = $typeColors[$notif->type] ?? 'bg-slate-100 text-slate-600';
                                    @endphp
                                    <span class="pill {{ $tc }}">{{ str_replace('_', ' ', $notif->type) }}</span>
                                    <p class="mt-3 text-sm font-semibold leading-6 text-slate-900">{{ $notif->message }}</p>
                                    <p class="mt-1 text-xs text-slate-400">{{ $notif->created_at->diffForHumans() }}</p>
                                </div>
                            </div>

                            <div class="flex items-center gap-3">
                                @if(!$notif->is_read)
                                    <form action="{{ route('notifications.mark-read', $notif) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="text-sm font-bold text-slate-500 transition hover:text-slate-900">Read</button>
                                    </form>
                                @endif
                                <form action="{{ route('notifications.destroy', $notif) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-sm font-bold text-rose-600 transition hover:text-rose-700">Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="panel p-12 text-center">
                        <p class="text-sm font-semibold text-slate-500">No notifications.</p>
                    </div>
                @endforelse
            </div>

            <div>
                {{ $notifications->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
