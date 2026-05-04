<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Factories\UserFactory;
use App\Models\Company;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register', [
            'companies' => Company::orderBy('company_name')->get(),
        ]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'string', 'in:candidate,recruiter,company'],
            'name' => ['nullable', 'string', 'max:255'],
            'full_name' => ['nullable', 'string', 'max:255'],
            'company_name' => ['nullable', 'string', 'max:255'],
            'company_id' => ['nullable', 'integer', 'exists:companies,id'],
            'department' => ['nullable', 'string', 'max:255'],
            'title' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'bio' => ['nullable', 'string'],
            'website' => ['nullable', 'url', 'max:255'],
            'industry' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'verification_message' => ['nullable', 'string'],
        ]);

        if ($request->role === 'company' && ! $request->company_name) {
            throw ValidationException::withMessages([
                'company_name' => 'Company accounts require a company name.',
            ]);
        }

        if ($request->role === 'company' && ! $request->website) {
            throw ValidationException::withMessages([
                'website' => 'Company accounts require a website.',
            ]);
        }

        if (in_array($request->role, ['candidate', 'recruiter'], true) && ! ($request->full_name ?? $request->name)) {
            throw ValidationException::withMessages([
                'full_name' => 'This account type requires a full name.',
            ]);
        }

        $user = UserFactory::create($request->role, [
            'name' => $request->name,
            'full_name' => $request->full_name,
            'company_name' => $request->company_name,
            'email' => $request->email,
            'password' => Hash::make($request->string('password')),
            'company_id' => $request->company_id,
            'department' => $request->department,
            'title' => $request->title,
            'phone' => $request->phone,
            'bio' => $request->bio,
            'website' => $request->website,
            'industry' => $request->industry,
            'description' => $request->description ?? $request->bio,
            'verification_message' => $request->verification_message,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
