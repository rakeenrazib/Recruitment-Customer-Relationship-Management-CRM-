<x-guest-layout>
    <div class="min-h-screen flex flex-col items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8 bg-white p-10 rounded-2xl shadow-xl border border-gray-100">
            <div>
                <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900 tracking-tight">
                    Welcome Back
                </h2>
                <p class="mt-2 text-center text-sm text-gray-500">
                    Please enter your details to sign in
                </p>
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form class="mt-8 space-y-6" method="POST" action="{{ route('login') }}">
                @csrf
                
                <div class="space-y-4">
                    <!-- Email Address -->
                    <div>
                        <label for="email" class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-1 ml-1">Email Address</label>
                        <input id="email" name="email" type="email" autocomplete="email" required 
                            class="appearance-none relative block w-full px-4 py-3 border border-gray-200 placeholder-gray-400 text-gray-900 rounded-xl focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm transition shadow-sm" 
                            placeholder="you@example.com" value="{{ old('email') }}" autofocus>
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Password -->
                    <div>
                        <div class="flex justify-between items-center mb-1 ml-1">
                            <label for="password" class="block text-xs font-bold text-gray-400 uppercase tracking-widest">Password</label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-[10px] font-bold text-blue-600 hover:text-blue-800 transition uppercase tracking-widest">
                                    Forgot?
                                </a>
                            @endif
                        </div>
                        <input id="password" name="password" type="password" autocomplete="current-password" required 
                            class="appearance-none relative block w-full px-4 py-3 border border-gray-200 placeholder-gray-400 text-gray-900 rounded-xl focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm transition shadow-sm" 
                            placeholder="••••••••">
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>
                </div>

                <div class="flex items-center">
                    <input id="remember_me" name="remember" type="checkbox" 
                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded transition cursor-pointer">
                    <label for="remember_me" class="ml-2 block text-sm text-gray-500 cursor-pointer select-none">
                        Remember me
                    </label>
                </div>

                <div>
                    <button type="submit" class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-bold rounded-xl text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition shadow-lg shadow-blue-200">
                        Sign In
                    </button>
                </div>
            </form>

            <div class="pt-6 border-t border-gray-50 text-center">
                <p class="text-sm text-gray-500">
                    Don't have an account? 
                    <a href="{{ route('register') }}" class="font-bold text-blue-600 hover:text-blue-800 transition">
                        Create an account
                    </a>
                </p>
            </div>
        </div>
    </div>
</x-guest-layout>
