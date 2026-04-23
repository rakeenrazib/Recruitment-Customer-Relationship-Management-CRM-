<nav x-data="{ open: false }" class="relative z-[70] pt-5">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="glass-nav rounded-[1.75rem] px-4 sm:px-6">
            <div class="flex min-h-[78px] justify-between gap-4">
                <div class="flex min-w-0 items-center gap-6">
                    <div class="flex shrink-0 items-center">
                        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 text-slate-900">
                            <span class="inline-flex h-11 w-11 items-center justify-center rounded-full bg-slate-950 text-sm font-black text-white shadow-lg shadow-slate-950/20">TH</span>
                            <span class="block font-display text-lg font-black leading-none tracking-tight">Talent Hub</span>
                        </a>
                    </div>

                    <div class="hidden flex-wrap gap-2 sm:flex">
                        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                            {{ __('Dashboard') }}
                        </x-nav-link>
                        <x-nav-link :href="route('jobs.index')" :active="request()->routeIs('jobs.*')">
                            {{ __('Jobs') }}
                        </x-nav-link>
                        @if(auth()->user()->isCandidate())
                            <x-nav-link :href="route('applications.index')" :active="request()->routeIs('applications.*')">
                                {{ __('Apps') }}
                            </x-nav-link>
                            <x-nav-link :href="route('companies.index')" :active="request()->routeIs('companies.index')">
                                {{ __('Companies') }}
                            </x-nav-link>
                        @endif
                        @if(auth()->user()->isRecruiter())
                            <x-nav-link :href="route('recruiter.applications')" :active="request()->routeIs('recruiter.*')">
                                {{ __('Applicants') }}
                            </x-nav-link>
                        @endif
                        @if(auth()->user()->isCompany() && auth()->user()->company)
                            <x-nav-link :href="route('companies.show', auth()->user()->company)" :active="request()->routeIs('companies.show')">
                                {{ __('Company') }}
                            </x-nav-link>
                        @endif
                    </div>
                </div>

                <div class="hidden items-center gap-3 sm:flex">
                    @if(auth()->user()->isCandidate())
                        @php
                            $navUnread = \App\Models\AppNotification::where('user_id', auth()->id())->where('is_read', false)->count();
                        @endphp
                        <a href="{{ route('notifications.index') }}" class="relative inline-flex h-11 w-11 items-center justify-center rounded-full border border-slate-200 bg-white/80 text-slate-500 transition hover:-translate-y-0.5 hover:border-teal-200 hover:bg-teal-50 hover:text-teal-700" title="Notifications">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                            @if($navUnread > 0)
                                <span class="absolute right-1.5 top-1.5 inline-flex h-2.5 w-2.5 rounded-full bg-rose-500 ring-4 ring-white/90"></span>
                            @endif
                        </a>
                    @endif

                    <x-dropdown align="right" width="56">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center gap-3 rounded-full border border-white/70 bg-white/80 px-3 py-2 text-sm font-medium text-slate-600 shadow-sm transition hover:-translate-y-0.5 hover:border-teal-200 hover:text-slate-900">
                                @if(Auth::user()->profile_photo_path)
                                    <img src="{{ asset('storage/' . Auth::user()->profile_photo_path) }}" alt="{{ Auth::user()->display_name }}" class="h-10 w-10 rounded-full object-cover ring-2 ring-white">
                                @else
                                    <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-slate-950 text-xs font-bold text-white shadow-md">
                                        {{ strtoupper(substr(Auth::user()->display_name, 0, 1)) }}
                                    </span>
                                @endif
                                <span class="text-left">
                                    <span class="block max-w-[11rem] truncate font-semibold text-slate-900">{{ Auth::user()->display_name }}</span>
                                    <span class="block text-[11px] font-bold uppercase tracking-[0.22em] text-slate-400">{{ auth()->user()->role }}</span>
                                </span>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Profile Settings') }}
                            </x-dropdown-link>

                            @if(auth()->user()->isCandidate() && auth()->user()->candidate)
                                <x-dropdown-link :href="route('candidates.show', auth()->user()->candidate)">
                                    {{ __('Public Profile') }}
                                </x-dropdown-link>
                            @endif
                            @if(auth()->user()->isRecruiter() && auth()->user()->recruiter)
                                <x-dropdown-link :href="route('recruiters.show', auth()->user()->recruiter)">
                                    {{ __('Public Profile') }}
                                </x-dropdown-link>
                            @endif
                            @if(auth()->user()->isCompany() && auth()->user()->company)
                                <x-dropdown-link :href="route('companies.show', auth()->user()->company)">
                                    {{ __('Public Profile') }}
                                </x-dropdown-link>
                            @endif

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>

                <div class="flex items-center sm:hidden">
                    <button @click="open = ! open" class="inline-flex h-11 w-11 items-center justify-center rounded-full border border-slate-200 bg-white/80 text-slate-500 transition hover:border-teal-200 hover:bg-teal-50 hover:text-teal-700">
                        <svg class="h-5 w-5" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden px-4 pt-3 sm:hidden">
        <div class="glass-nav space-y-1 rounded-[1.5rem] px-2 py-3">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('jobs.index')" :active="request()->routeIs('jobs.*')">
                {{ __('Jobs') }}
            </x-responsive-nav-link>
            @if(auth()->user()->isCandidate())
                <x-responsive-nav-link :href="route('applications.index')" :active="request()->routeIs('applications.*')">
                    {{ __('Apps') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('companies.index')" :active="request()->routeIs('companies.index')">
                    {{ __('Companies') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('notifications.index')" :active="request()->routeIs('notifications.*')">
                    {{ __('Notifications') }}
                </x-responsive-nav-link>
            @endif
            @if(auth()->user()->isRecruiter())
                <x-responsive-nav-link :href="route('recruiter.applications')" :active="request()->routeIs('recruiter.*')">
                    {{ __('Applicants') }}
                </x-responsive-nav-link>
            @endif
            @if(auth()->user()->isCompany() && auth()->user()->company)
                <x-responsive-nav-link :href="route('companies.show', auth()->user()->company)" :active="request()->routeIs('companies.show')">
                    {{ __('Company') }}
                </x-responsive-nav-link>
            @endif
            <x-responsive-nav-link :href="route('profile.edit')">
                {{ __('Profile Settings') }}
            </x-responsive-nav-link>

            <div class="mx-2 mt-4 rounded-[1.2rem] border border-white/70 bg-white/75 px-4 py-4">
                <div class="text-base font-semibold text-slate-900">{{ Auth::user()->display_name }}</div>
                <div class="mt-1 text-sm text-slate-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
