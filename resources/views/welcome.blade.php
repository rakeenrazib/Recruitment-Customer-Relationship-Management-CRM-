<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'JobBoard CRM') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Scripts/Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-slate-50 text-slate-900 font-sans selection:bg-blue-500 selection:text-white">
    <div class="relative min-h-screen flex flex-col">
        <!-- Subtle Background Pattern -->
        <div class="absolute inset-0 z-0 pointer-events-none bg-[radial-gradient(#e5e7eb_1px,transparent_1px)] [background-size:16px_16px] opacity-60"></div>
        
        <!-- Decorative Glow -->
        <div class="absolute top-0 inset-x-0 h-[500px] z-0 pointer-events-none overflow-hidden">
            <div class="absolute -top-40 left-1/2 -translate-x-1/2 w-[800px] h-[800px] bg-blue-100 rounded-full blur-3xl opacity-50"></div>
        </div>

        <!-- Navigation -->
        <header class="relative z-10 border-b border-slate-200/50 bg-white/50 backdrop-blur-md">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-20">
                    <div class="flex-shrink-0 flex items-center gap-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-xl flex items-center justify-center text-white font-bold text-xl shadow-lg shadow-blue-600/30">
                            J
                        </div>
                        <span class="font-bold text-xl tracking-tight text-slate-900">JobBoard</span>
                    </div>
                    
                    <nav class="flex items-center gap-6">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="text-sm font-medium text-slate-600 hover:text-slate-900 transition">Dashboard</a>
                            <a href="{{ route('jobs.index') }}" class="text-sm font-medium text-slate-600 hover:text-slate-900 transition">Browse Jobs</a>
                        @else
                            <a href="{{ route('login') }}" class="text-sm font-medium text-slate-600 hover:text-slate-900 transition">Log in</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-medium text-white bg-slate-900 rounded-lg hover:bg-slate-800 transition shadow-sm">Sign up free</a>
                            @endif
                        @endauth
                    </nav>
                </div>
            </div>
        </header>

        <!-- Hero Section -->
        <main class="flex-1 flex items-center relative z-10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 text-center">
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-blue-50 border border-blue-100 text-blue-700 text-sm font-medium mb-8 shadow-sm">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-blue-600"></span>
                    </span>
                    Now hiring in tech & design
                </div>
                
                <h1 class="text-5xl md:text-7xl font-extrabold tracking-tight text-slate-900 mb-8 max-w-4xl mx-auto leading-tight">
                    Find the perfect job that <br class="hidden md:block" />
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600">matches your skills</span>
                </h1>
                
                <p class="text-xl text-slate-600 mb-10 max-w-2xl mx-auto leading-relaxed">
                    Connect with top companies, apply seamlessly, and track your applications all in one place. Your next career move starts right here.
                </p>
                
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                    @auth
                        <a href="{{ route('jobs.index') }}" class="inline-flex items-center justify-center px-8 py-4 text-base font-semibold text-white bg-blue-600 rounded-xl hover:bg-blue-700 transition-all shadow-lg shadow-blue-600/30 hover:shadow-blue-600/40 hover:-translate-y-0.5 w-full sm:w-auto">
                            Explore Opportunities
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-8 py-4 text-base font-semibold text-white bg-blue-600 rounded-xl hover:bg-blue-700 transition-all shadow-lg shadow-blue-600/30 hover:shadow-blue-600/40 hover:-translate-y-0.5 w-full sm:w-auto">
                            Create Your Profile
                        </a>
                        <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-8 py-4 text-base font-semibold text-slate-700 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 hover:border-slate-300 transition-all shadow-sm hover:shadow w-full sm:w-auto">
                            Sign In to Apply
                        </a>
                    @endauth
                </div>
                
                <!-- Footer area of hero -->
                <div class="mt-32 pt-10 border-t border-slate-200/60 text-slate-500 text-sm flex flex-col md:flex-row items-center justify-between">
                    <p>&copy; {{ date('Y') }} {{ config('app.name', 'JobBoard CRM') }}. All rights reserved.</p>
                    <div class="flex gap-6 mt-4 md:mt-0 font-medium">
                        <a href="#" class="hover:text-slate-900 transition">Privacy Policy</a>
                        <a href="#" class="hover:text-slate-900 transition">Terms of Service</a>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
