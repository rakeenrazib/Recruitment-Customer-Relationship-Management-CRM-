<section>
    <header>
        <h2 class="text-2xl font-black text-stone-900">Profile Settings</h2>
        <p class="mt-2 text-sm text-stone-500">Edit your public details.</p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="mt-8 space-y-6">
        @csrf
        @method('patch')

        <div class="grid gap-6 md:grid-cols-2">
            <div class="md:col-span-2">
                <x-input-label for="cover_photo" :value="__('Cover Photo')" />
                @if($user->cover_photo_path)
                    <img src="{{ asset('storage/' . $user->cover_photo_path) }}" alt="{{ $user->display_name }}" class="mt-3 h-32 w-full rounded-[1.5rem] object-cover">
                @endif
                <input id="cover_photo" name="cover_photo" type="file" class="mt-3 block w-full rounded-2xl border-stone-200 bg-stone-50 text-sm text-stone-700">
                <x-input-error class="mt-2" :messages="$errors->get('cover_photo')" />
            </div>

            <div class="md:col-span-2">
                <x-input-label for="profile_photo" :value="__('Photo')" />
                @if($user->profile_photo_path)
                    <img src="{{ asset('storage/' . $user->profile_photo_path) }}" alt="{{ $user->display_name }}" class="mt-3 h-20 w-20 rounded-[1.4rem] object-cover">
                @endif
                <input id="profile_photo" name="profile_photo" type="file" class="mt-3 block w-full rounded-2xl border-stone-200 bg-stone-50 text-sm text-stone-700">
                <x-input-error class="mt-2" :messages="$errors->get('profile_photo')" />
            </div>

            <div>
                <x-input-label for="name" :value="$user->isCompany() ? __('Company Name') : __('Name')" />
                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full rounded-2xl border-stone-200 bg-stone-50" :value="old('name', $user->display_name)" required autofocus />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>

            <div>
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" name="email" type="email" class="mt-1 block w-full rounded-2xl border-stone-200 bg-stone-50" :value="old('email', $user->email)" required />
                <x-input-error class="mt-2" :messages="$errors->get('email')" />
            </div>

            <div>
                <x-input-label for="phone" :value="__('Phone')" />
                <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full rounded-2xl border-stone-200 bg-stone-50" :value="old('phone', $user->candidate?->phone ?? $user->recruiter?->phone)" />
                <x-input-error class="mt-2" :messages="$errors->get('phone')" />
            </div>

            <div>
                <x-input-label for="location" :value="__('Location')" />
                <x-text-input id="location" name="location" type="text" class="mt-1 block w-full rounded-2xl border-stone-200 bg-stone-50" :value="old('location', $user->candidate?->location ?? $user->recruiter?->location ?? $user->company?->location)" />
                <x-input-error class="mt-2" :messages="$errors->get('location')" />
            </div>

            @if($user->isRecruiter())
                <div>
                    <x-input-label for="title" :value="__('Job Title')" />
                    <x-text-input id="title" name="title" type="text" class="mt-1 block w-full rounded-2xl border-stone-200 bg-stone-50" :value="old('title', $user->recruiter?->title)" />
                    <x-input-error class="mt-2" :messages="$errors->get('title')" />
                </div>

                <div>
                    <x-input-label for="department" :value="__('Department')" />
                    <x-text-input id="department" name="department" type="text" class="mt-1 block w-full rounded-2xl border-stone-200 bg-stone-50" :value="old('department', $user->recruiter?->department)" />
                    <x-input-error class="mt-2" :messages="$errors->get('department')" />
                </div>
            @endif

            @if($user->isCompany())
                <div>
                    <x-input-label for="industry" :value="__('Industry')" />
                    <x-text-input id="industry" name="industry" type="text" class="mt-1 block w-full rounded-2xl border-stone-200 bg-stone-50" :value="old('industry', $user->company?->industry)" />
                    <x-input-error class="mt-2" :messages="$errors->get('industry')" />
                </div>

                <div>
                    <x-input-label for="website" :value="__('Website')" />
                    <x-text-input id="website" name="website" type="url" class="mt-1 block w-full rounded-2xl border-stone-200 bg-stone-50" :value="old('website', $user->company?->website)" />
                    <x-input-error class="mt-2" :messages="$errors->get('website')" />
                </div>
            @endif

            <div class="md:col-span-2">
                <x-input-label for="bio" :value="$user->isCompany() ? __('Company Description') : __('Bio')" />
                <textarea id="bio" name="bio" rows="5" class="mt-1 block w-full rounded-2xl border-stone-200 bg-stone-50 text-sm text-stone-900">{{ old('bio', $user->candidate?->bio ?? $user->recruiter?->bio ?? $user->company?->description) }}</textarea>
                <x-input-error class="mt-2" :messages="$errors->get('bio')" />
            </div>

            @if($user->isCandidate())
                <div class="md:col-span-2">
                    <x-input-label for="portfolio" :value="__('Portfolio / Links')" />
                    <textarea id="portfolio" name="portfolio" rows="4" class="mt-1 block w-full rounded-2xl border-stone-200 bg-stone-50 text-sm text-stone-900">{{ old('portfolio', $user->candidate?->portfolio) }}</textarea>
                    <x-input-error class="mt-2" :messages="$errors->get('portfolio')" />
                </div>

                <div class="md:col-span-2">
                    <x-input-label for="details" :value="__('Public Details')" />
                    <textarea id="details" name="details" rows="4" class="mt-1 block w-full rounded-2xl border-stone-200 bg-stone-50 text-sm text-stone-900">{{ old('details', $user->candidate?->details) }}</textarea>
                    <x-input-error class="mt-2" :messages="$errors->get('details')" />
                </div>
            @endif
        </div>

        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
            <div>
                <p class="text-sm text-stone-700">
                    {{ __('Your email address is unverified.') }}
                    <button form="send-verification" class="font-semibold text-cyan-700 underline">
                        {{ __('Click here to re-send the verification email.') }}
                    </button>
                </p>
            </div>
        @endif

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>
            @if (session('status') === 'profile-updated')
                <p class="text-sm text-stone-500">{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
