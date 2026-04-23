<x-guest-layout>
    <div class="panel mx-auto w-full max-w-xl p-8 sm:p-10">
        <p class="eyebrow">Password Recovery</p>
        <h1 class="mt-3 font-display text-4xl font-black text-slate-950">Reset your password</h1>
        <p class="mt-4 text-sm leading-6 text-slate-500">
            Forgot your password? Enter your email address and we will send you a reset link so you can get back into Talent Hub.
        </p>

        <x-auth-session-status class="mt-6" :status="session('status')" />

        <form method="POST" action="{{ route('password.email') }}" class="mt-8 space-y-5">
            @csrf

            <div>
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" class="mt-1 block w-full" type="email" name="email" :value="old('email')" required autofocus />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div class="flex justify-end">
                <x-primary-button>
                    {{ __('Email Password Reset Link') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</x-guest-layout>
