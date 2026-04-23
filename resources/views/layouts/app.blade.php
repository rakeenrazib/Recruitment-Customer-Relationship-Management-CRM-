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
            <div class="site-backdrop absolute inset-0"></div>
            <div class="ambient-orb left-[6%] top-[8%] h-64 w-64 bg-teal-400/20"></div>
            <div class="ambient-orb bottom-[12%] right-[6%] h-80 w-80 bg-amber-400/15"></div>
            <div class="ambient-orb left-[42%] top-[22%] h-52 w-52 bg-sky-400/15"></div>
        </div>

        <div class="min-h-screen">
            @include('layouts.navigation')

            @isset($header)
                <header class="relative z-10">
                    <div class="mx-auto max-w-7xl px-4 pt-6 sm:px-6 lg:px-8">
                        <div class="glass-nav rounded-[2rem] px-6 py-5">
                        {{ $header }}
                        </div>
                    </div>
                </header>
            @endisset

            <main class="pb-12 sm:pb-16">
                {{ $slot }}
            </main>

            @include('layouts.support-footer')
        </div>
    </body>
</html>
