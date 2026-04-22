<x-guest-layout>
    <div class="min-h-screen flex flex-col items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8 bg-white p-10 rounded-2xl shadow-xl border border-gray-100">
            <div>
                <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900 tracking-tight">
                    Create Account
                </h2>
                <p class="mt-2 text-center text-sm text-gray-500">
                    Join our community and start your journey
                </p>
            </div>

            <form class="mt-8 space-y-6" method="POST" action="{{ route('register') }}">
                @csrf
                
                <div class="space-y-4">
                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-1 ml-1">Full Name</label>
                        <input id="name" name="name" type="text" autocomplete="name" required 
                            class="appearance-none relative block w-full px-4 py-3 border border-gray-200 placeholder-gray-400 text-gray-900 rounded-xl focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm transition shadow-sm" 
                            placeholder="John Doe" value="{{ old('name') }}" autofocus>
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <!-- Email Address -->
                    <div>
                        <label for="email" class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-1 ml-1">Email Address</label>
                        <input id="email" name="email" type="email" autocomplete="email" required 
                            class="appearance-none relative block w-full px-4 py-3 border border-gray-200 placeholder-gray-400 text-gray-900 rounded-xl focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm transition shadow-sm" 
                            placeholder="you@example.com" value="{{ old('email') }}">
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Account Type -->
                    <div>
                        <label for="role" class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-1 ml-1">I am a</label>
                        <select id="role" name="role" required
                            class="appearance-none relative block w-full px-4 py-3 border border-gray-200 text-gray-900 rounded-xl focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm transition shadow-sm bg-white">
                            <option value="candidate" {{ old('role') == 'candidate' ? 'selected' : '' }}>Candidate (Looking for jobs)</option>
                            <option value="recruiter" {{ old('role') == 'recruiter' ? 'selected' : '' }}>Recruiter (Hiring)</option>
                        </select>
                        <x-input-error :messages="$errors->get('role')" class="mt-2" />
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-1 ml-1">Password</label>
                            <input id="password" name="password" type="password" autocomplete="new-password" required 
                                class="appearance-none relative block w-full px-4 py-3 border border-gray-200 placeholder-gray-400 text-gray-900 rounded-xl focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm transition shadow-sm" 
                                placeholder="••••••••">
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label for="password_confirmation" class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-1 ml-1">Confirm</label>
                            <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required 
                                class="appearance-none relative block w-full px-4 py-3 border border-gray-200 placeholder-gray-400 text-gray-900 rounded-xl focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm transition shadow-sm" 
                                placeholder="••••••••">
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                        </div>
                    </div>
                </div>

                <div>
                    <button type="submit" class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-bold rounded-xl text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition shadow-lg shadow-blue-200">
                        Create Account
                    </button>
                </div>
            </form>

            <div class="pt-6 border-t border-gray-50 text-center">
                <p class="text-sm text-gray-500">
                    Already have an account? 
                    <a href="{{ route('login') }}" class="font-bold text-blue-600 hover:text-blue-800 transition">
                        Sign In
                    </a>
                </p>
            </div>
        </div>
    </div>
</x-guest-layout>
