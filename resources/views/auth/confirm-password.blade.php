<x-guest-layout>
    <div class="panel mx-auto w-full max-w-xl p-8 sm:p-10">
        <p class="eyebrow">Secure Action</p>
        <h1 class="mt-3 font-display text-4xl font-black text-slate-950">Confirm your password</h1>
        <p class="mt-4 text-sm leading-6 text-slate-500">
            This area is protected. Please confirm your password before continuing.
        </p>

        <form method="POST" action="{{ route('password.confirm') }}" class="mt-8 space-y-5">
            @csrf

            <div>
                <x-input-label for="password" :value="__('Password')" />
                <x-text-input id="password" class="mt-1 block w-full" type="password" name="password" required autocomplete="current-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div class="flex justify-end">
                <x-primary-button>
                    {{ __('Confirm') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</x-guest-layout>
