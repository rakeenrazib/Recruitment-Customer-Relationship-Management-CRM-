<x-guest-layout>
    <div class="mx-auto w-full max-w-xl">
        <section class="panel mx-auto w-full p-8 sm:p-10">
            <div>
                <p class="eyebrow">Welcome Back</p>
                <h2 class="mt-3 font-display text-4xl font-black tracking-tight text-slate-950">Sign in</h2>
            </div>

            <x-auth-session-status class="mt-6" :status="session('status')" />

            <form class="mt-8 space-y-6" method="POST" action="{{ route('login') }}">
                @csrf

                <div class="space-y-4">
                    <div>
                        <label for="email" class="mb-2 block text-xs font-bold uppercase tracking-[0.25em] text-slate-400">Email</label>
                        <input id="email" name="email" type="email" autocomplete="email" required class="field-input" placeholder="you@example.com" value="{{ old('email') }}" autofocus>
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div>
                        <div class="mb-2 flex items-center justify-between">
                            <label for="password" class="text-xs font-bold uppercase tracking-[0.25em] text-slate-400">Password</label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-[11px] font-bold uppercase tracking-[0.22em] text-teal-700 transition hover:text-teal-900">
                                    Forgot?
                                </a>
                            @endif
                        </div>
                        <input id="password" name="password" type="password" autocomplete="current-password" required class="field-input" placeholder="Password">
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>
                </div>

                <div class="flex items-center rounded-[1.25rem] border border-slate-200/80 bg-slate-50/90 px-4 py-3 dark:border-slate-700 dark:bg-slate-800/90">
                    <input id="remember_me" name="remember" type="checkbox" class="h-4 w-4 rounded border-slate-300 text-teal-700 focus:ring-teal-500">
                    <label for="remember_me" class="ml-3 cursor-pointer select-none text-sm font-medium text-slate-700 dark:text-slate-200">
                        Remember me
                    </label>
                </div>

                <div>
                    <button type="submit" class="btn-primary w-full">
                        Sign in
                    </button>
                </div>
            </form>

            <div class="mt-8 border-t border-slate-100 pt-6 text-center">
                <p class="text-sm text-slate-500">
                    New here?
                    <a href="{{ route('register') }}" class="font-bold text-teal-700 transition hover:text-teal-900">
                        Register
                    </a>
                </p>
            </div>
        </section>
    </div>
</x-guest-layout>
