<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">All Notifications</h2>
            <a href="{{ route('dashboard') }}" class="text-sm font-bold text-gray-500 hover:text-gray-800 transition">
                &larr; Back to Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-10 bg-gray-50 min-h-screen">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-4">

            @if(session('success'))
                <div class="p-4 bg-green-50 border border-green-200 rounded-xl text-green-800 text-sm font-semibold">
                    ✓ {{ session('success') }}
                </div>
            @endif

            {{-- Mark all read --}}
            @if($notifications->total() > 0)
                <div class="flex justify-end">
                    <form action="{{ route('notifications.mark-all-read') }}" method="POST">
                        @csrf
                        <button type="submit"
                                class="text-xs font-bold text-blue-600 hover:text-blue-800 transition underline underline-offset-2">
                            Mark all as read
                        </button>
                    </form>
                </div>
            @endif

            {{-- Notification list --}}
            <div class="space-y-3">
                @forelse($notifications as $notif)
                    <div class="bg-white rounded-xl border shadow-sm px-6 py-4 flex items-start justify-between gap-4
                        {{ $notif->is_read ? 'border-gray-100' : 'border-blue-200 bg-blue-50/30' }}">

                        <div class="flex items-start gap-3 flex-1">
                            {{-- Unread dot --}}
                            @if(!$notif->is_read)
                                <div class="mt-1.5 w-2 h-2 rounded-full bg-blue-500 flex-shrink-0"></div>
                            @else
                                <div class="mt-1.5 w-2 h-2 rounded-full bg-transparent flex-shrink-0"></div>
                            @endif

                            <div>
                                {{-- Type badge --}}
                                @php
                                    $typeColors = [
                                        'shortlisted' => 'bg-blue-100 text-blue-700',
                                        'interview'   => 'bg-purple-100 text-purple-700',
                                        'rejected'    => 'bg-red-100 text-red-700',
                                        'pending'     => 'bg-yellow-100 text-yellow-700',
                                    ];
                                    $tc = $typeColors[$notif->type] ?? 'bg-gray-100 text-gray-600';
                                @endphp
                                <span class="inline-block text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-lg {{ $tc }} mb-1">
                                    {{ ucfirst($notif->type) }}
                                </span>
                                <p class="text-sm text-gray-800 font-medium">{{ $notif->message }}</p>
                                <p class="text-xs text-gray-400 mt-1">{{ $notif->created_at->diffForHumans() }}</p>
                            </div>
                        </div>

                        {{-- Mark as read --}}
                        @if(!$notif->is_read)
                            <form action="{{ route('notifications.mark-read', $notif) }}" method="POST" class="flex-shrink-0">
                                @csrf
                                <button type="submit"
                                        class="text-xs font-bold text-gray-400 hover:text-gray-700 transition underline underline-offset-2 whitespace-nowrap">
                                    Mark read
                                </button>
                            </form>
                        @endif
                    </div>
                @empty
                    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-12 text-center">
                        <p class="text-sm font-bold text-gray-400">No notifications yet.</p>
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            <div>
                {{ $notifications->links() }}
            </div>

        </div>
    </div>
</x-app-layout>
