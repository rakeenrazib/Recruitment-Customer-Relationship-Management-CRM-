<x-guest-layout>
    <div class="mx-auto w-full max-w-4xl">
        <section class="panel p-8 sm:p-10">
            <div x-data="{ role: '{{ old('role', 'candidate') }}' }">
                <div>
                    <p class="eyebrow">Create Account</p>
                    <h2 class="mt-3 font-display text-4xl font-black tracking-tight text-slate-950">Register</h2>
                </div>

                <form class="mt-8 space-y-6" method="POST" action="{{ route('register') }}">
                    @csrf

                    <div>
                        <label for="role" class="mb-2 block text-xs font-bold uppercase tracking-[0.25em] text-slate-400">Type</label>
                        <select id="role" name="role" x-model="role" required class="field-input font-semibold">
                            <option value="candidate" @selected(old('role') === 'candidate')>Candidate</option>
                            <option value="recruiter" @selected(old('role') === 'recruiter')>Recruiter</option>
                            <option value="company" @selected(old('role') === 'company')>Company</option>
                        </select>
                        <x-input-error :messages="$errors->get('role')" class="mt-2" />
                    </div>

                    <div class="grid gap-5 sm:grid-cols-2">
                        <div class="sm:col-span-2" x-show="role !== 'company'">
                            <label for="full_name" class="mb-2 block text-xs font-bold uppercase tracking-[0.25em] text-slate-400">Name</label>
                            <input id="full_name" name="full_name" type="text" value="{{ old('full_name') }}" class="field-input" placeholder="Alex Morgan">
                            <x-input-error :messages="$errors->get('full_name')" class="mt-2" />
                        </div>

                        <div class="sm:col-span-2" x-show="role === 'company'">
                            <label for="company_name" class="mb-2 block text-xs font-bold uppercase tracking-[0.25em] text-slate-400">Company</label>
                            <input id="company_name" name="company_name" type="text" value="{{ old('company_name') }}" class="field-input" placeholder="Northstar Labs">
                            <x-input-error :messages="$errors->get('company_name')" class="mt-2" />
                        </div>

                        <div>
                            <label for="email" class="mb-2 block text-xs font-bold uppercase tracking-[0.25em] text-slate-400">Email</label>
                            <input id="email" name="email" type="email" value="{{ old('email') }}" required class="field-input" placeholder="you@example.com">
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <div x-show="role === 'recruiter'">
                            <label for="department" class="mb-2 block text-xs font-bold uppercase tracking-[0.25em] text-slate-400">Dept.</label>
                            <input id="department" name="department" type="text" value="{{ old('department') }}" class="field-input" placeholder="Talent Acquisition">
                            <x-input-error :messages="$errors->get('department')" class="mt-2" />
                        </div>

                        <div x-show="role === 'recruiter'">
                            <label for="title" class="mb-2 block text-xs font-bold uppercase tracking-[0.25em] text-slate-400">Title</label>
                            <input id="title" name="title" type="text" value="{{ old('title') }}" class="field-input" placeholder="Senior Recruiter">
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>

                        <div class="sm:col-span-2" x-show="role === 'recruiter'">
                            <label for="company_id" class="mb-2 block text-xs font-bold uppercase tracking-[0.25em] text-slate-400">Company</label>
                            <select id="company_id" name="company_id" class="field-input">
                                <option value="">Select later</option>
                                @foreach($companies as $company)
                                    <option value="{{ $company->id }}" @selected(old('company_id') == $company->id)>{{ $company->company_name }}</option>
                                @endforeach
                            </select>
                            <p class="mt-2 text-xs text-slate-500">Optional during registration. Your recruiter account stays unverified until you request company verification from the dashboard.</p>
                            <x-input-error :messages="$errors->get('company_id')" class="mt-2" />
                        </div>

                        <div x-show="role === 'company'">
                            <label for="industry" class="mb-2 block text-xs font-bold uppercase tracking-[0.25em] text-slate-400">Industry</label>
                            <input id="industry" name="industry" type="text" value="{{ old('industry') }}" class="field-input" placeholder="Software">
                            <x-input-error :messages="$errors->get('industry')" class="mt-2" />
                        </div>

                        <div x-show="role === 'company'">
                            <label for="website" class="mb-2 block text-xs font-bold uppercase tracking-[0.25em] text-slate-400">Website</label>
                            <input id="website" name="website" type="url" value="{{ old('website') }}" class="field-input" placeholder="https://example.com" x-bind:required="role === 'company'">
                            <x-input-error :messages="$errors->get('website')" class="mt-2" />
                        </div>

                        <div>
                            <label for="phone" class="mb-2 block text-xs font-bold uppercase tracking-[0.25em] text-slate-400">Phone</label>
                            <input id="phone" name="phone" type="text" value="{{ old('phone') }}" class="field-input" placeholder="+8801XXXXXXXXX">
                            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                        </div>

                        <div>
                            <label for="password" class="mb-2 block text-xs font-bold uppercase tracking-[0.25em] text-slate-400">Password</label>
                            <input id="password" name="password" type="password" required class="field-input" placeholder="Password">
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <div class="sm:col-span-2">
                            <label for="password_confirmation" class="mb-2 block text-xs font-bold uppercase tracking-[0.25em] text-slate-400">Confirm</label>
                            <input id="password_confirmation" name="password_confirmation" type="password" required class="field-input" placeholder="Repeat password">
                        </div>

                        <div class="sm:col-span-2">
                            <label for="bio" class="mb-2 block text-xs font-bold uppercase tracking-[0.25em] text-slate-400">About</label>
                            <textarea id="bio" name="bio" rows="4" class="field-input min-h-[120px]" placeholder="Short intro">{{ old('bio') }}</textarea>
                            <x-input-error :messages="$errors->get('bio')" class="mt-2" />
                        </div>

                        <div class="sm:col-span-2" x-show="role === 'recruiter'">
                            <label for="verification_message" class="mb-2 block text-xs font-bold uppercase tracking-[0.25em] text-slate-400">Note</label>
                            <textarea id="verification_message" name="verification_message" rows="3" class="field-input min-h-[100px]" placeholder="Optional note">{{ old('verification_message') }}</textarea>
                            <x-input-error :messages="$errors->get('verification_message')" class="mt-2" />
                        </div>
                    </div>

                    <button type="submit" class="btn-primary w-full">
                        Register
                    </button>
                </form>

                <p class="mt-6 text-center text-sm text-slate-500">
                    Already have an account?
                    <a href="{{ route('login') }}" class="font-bold text-teal-700 transition hover:text-teal-900">Sign in</a>
                </p>
            </div>
        </section>
    </div>
</x-guest-layout>
