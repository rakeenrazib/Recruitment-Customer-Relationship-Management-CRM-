<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Talent Hub') }}</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800|space-grotesk:500,700&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="app-shell font-sans antialiased">
        <div class="pointer-events-none fixed inset-0 -z-10 overflow-hidden">
            <div class="site-backdrop-guest absolute inset-0"></div>
            <div class="ambient-orb left-[10%] top-[8%] h-72 w-72 bg-teal-200/35"></div>
            <div class="ambient-orb right-[10%] top-[18%] h-64 w-64 bg-amber-200/25"></div>
        </div>

        <div class="relative min-h-screen">
            <div class="mx-auto flex min-h-screen w-full max-w-7xl flex-col px-4 py-6 sm:px-6 lg:px-8">
                <div class="mb-6 flex items-center justify-between sm:mb-8">
                    <a href="/" class="glass-nav inline-flex items-center gap-3 rounded-full px-5 py-3 text-sm font-bold text-slate-900">
                        <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-slate-950 text-sm text-white">TH</span>
                        <span class="block font-display text-base font-black leading-none">Talent Hub</span>
                    </a>

                </div>

                <div class="flex flex-1 items-center justify-center">
                    {{ $slot }}
                </div>

                @include('layouts.support-footer')
            </div>
        </div>
    </body>
</html>
