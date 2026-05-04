<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Talent Hub') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800|space-grotesk:500,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased">
    <div class="pointer-events-none fixed inset-0 -z-10 overflow-hidden">
        <div class="site-backdrop-welcome absolute inset-0"></div>
        <div class="ambient-orb left-[8%] top-[10%] h-72 w-72 bg-teal-200/35"></div>
        <div class="ambient-orb bottom-[10%] right-[8%] h-96 w-96 bg-amber-200/25"></div>
    </div>

    <div class="relative">
        <header class="mx-auto max-w-7xl px-6 pt-6 sm:px-10">
            <div class="glass-nav flex items-center justify-between rounded-[1.8rem] px-6 py-4">
                <div class="flex items-center gap-3">
                    <span class="inline-flex h-11 w-11 items-center justify-center rounded-full bg-slate-950 text-sm font-black text-white">TH</span>
                    <p class="font-display text-lg font-black tracking-tight text-slate-950 dark:text-white">Talent Hub</p>
                </div>

                <nav class="flex items-center gap-3">
                    @auth
                        <a href="{{ route('dashboard') }}" class="btn-secondary">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="btn-secondary">Log in</a>
                        <a href="{{ route('register') }}" class="btn-primary">Create account</a>
                    @endauth
                </nav>
            </div>
        </header>

        <main class="mx-auto max-w-7xl px-6 pb-20 pt-10 sm:px-10 lg:pb-24 lg:pt-14">
            <section class="grid items-center gap-8 lg:grid-cols-[1.1fr_0.9fr]">
                <div>
                    <p class="inline-flex rounded-full border border-teal-200 bg-teal-50/80 px-4 py-2 text-xs font-bold uppercase tracking-[0.32em] text-teal-800">
                        Hiring CRM
                    </p>
                    <h1 class="hero-title mt-8 max-w-5xl font-display text-5xl font-black leading-[0.92] text-slate-950 dark:text-white sm:text-6xl lg:text-7xl">
                        Better hiring, less noise.
                    </h1>
                    <p class="mt-6 max-w-2xl text-lg leading-8 text-slate-700 dark:text-slate-200">
                        Hiring teams and candidates can stay organized with clearer profiles, company follows, and streamlined job management.
                    </p>

                    <div class="mt-8 flex flex-wrap gap-4">
                        <a href="{{ route('register') }}" class="btn-primary">Get started</a>
                    </div>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div class="hero-card">
                        <p class="eyebrow text-teal-700">Companies</p>
                        <p class="mt-3 text-base font-semibold text-slate-950 dark:text-white">Public profiles and open roles.</p>
                    </div>
                    <div class="hero-card">
                        <p class="eyebrow text-amber-700">Recruiters</p>
                        <p class="mt-3 text-base font-semibold text-slate-950 dark:text-white">Verified before posting.</p>
                    </div>
                    <div class="hero-card">
                        <p class="eyebrow text-sky-700">Candidates</p>
                        <p class="mt-3 text-base font-semibold text-slate-950 dark:text-white">Follow companies and track apps.</p>
                    </div>
                    <div class="hero-card bg-[linear-gradient(135deg,rgba(15,23,42,0.95),rgba(18,117,136,0.92))] text-white">
                        <p class="eyebrow text-white/70">Jobs</p>
                        <p class="mt-3 text-base font-semibold">Open, clear, and easy to manage.</p>
                    </div>
                </div>
            </section>
        </main>

        @include('layouts.support-footer')
    </div>
</body>
</html>
