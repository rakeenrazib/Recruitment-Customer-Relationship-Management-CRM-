<x-guest-layout>
    <div class="panel mx-auto w-full max-w-2xl p-8 sm:p-10">
        <p class="eyebrow">Verify Email</p>
        <h1 class="mt-3 font-display text-4xl font-black text-slate-950">Confirm your email address</h1>
        <p class="mt-4 text-sm leading-6 text-slate-500">
            Before getting started, please verify your email by clicking the link we just sent. If it did not arrive, we can send another one.
        </p>

        @if (session('status') == 'verification-link-sent')
            <div class="mt-6 rounded-[1.15rem] border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700">
                {{ __('A new verification link has been sent to the email address you provided during registration.') }}
            </div>
        @endif

        <div class="mt-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <x-primary-button>
                    {{ __('Resend Verification Email') }}
                </x-primary-button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-sm font-semibold text-slate-500 transition hover:text-slate-900 focus:outline-none">
                    {{ __('Log Out') }}
                </button>
            </form>
        </div>
    </div>
</x-guest-layout>
