<?php

namespace App\Factories;

use App\Models\Candidate;
use App\Models\Company;
use App\Models\Recruiter;
use App\Models\RecruiterVerificationRequest;
use App\Models\User;
use InvalidArgumentException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class UserFactory
{
    /**
     * Create a new user based on the specified type.
     *
     * @param string $type
     * @param array $data
     * @return User
     * @throws InvalidArgumentException
     */
    public static function create(string $type, array $data): User
    {
        return DB::transaction(function () use ($type, $data) {
            $name = $data['name'] ?? $data['full_name'] ?? $data['company_name'] ?? 'User';

            $user = User::create([
                'name' => $name,
                'email' => $data['email'],
                'password' => $data['password'],
                'role' => $type,
                'headline' => Arr::get($data, 'headline'),
            ]);

            return match ($type) {
                'candidate' => tap($user, fn (User $createdUser) => Candidate::create([
                    'user_id' => $createdUser->id,
                    'full_name' => $data['full_name'] ?? $name,
                    'phone' => Arr::get($data, 'phone'),
                    'location' => Arr::get($data, 'location'),
                    'bio' => Arr::get($data, 'bio'),
                    'portfolio' => Arr::get($data, 'portfolio'),
                    'details' => Arr::get($data, 'details'),
                    'resume_link' => Arr::get($data, 'resume_link'),
                ])),
                'recruiter' => tap($user, function (User $createdUser) use ($data, $name) {
                    $recruiter = Recruiter::create([
                        'user_id' => $createdUser->id,
                        'company_id' => $data['company_id'],
                        'full_name' => $data['full_name'] ?? $name,
                        'phone' => Arr::get($data, 'phone'),
                        'department' => Arr::get($data, 'department'),
                        'title' => Arr::get($data, 'title'),
                        'bio' => Arr::get($data, 'bio'),
                    ]);

                    if ($recruiter->company_id) {
                        $recruiter->update(['verification_requested_at' => now()]);

                        RecruiterVerificationRequest::create([
                            'recruiter_id' => $recruiter->id,
                            'company_id' => $recruiter->company_id,
                            'message' => Arr::get($data, 'verification_message'),
                        ]);
                    }
                }),
                'company' => tap($user, fn (User $createdUser) => Company::create([
                    'user_id' => $createdUser->id,
                    'company_name' => $data['company_name'] ?? $name,
                    'industry' => Arr::get($data, 'industry'),
                    'website' => Arr::get($data, 'website'),
                    'description' => Arr::get($data, 'description'),
                    'location' => Arr::get($data, 'location'),
                ])),
                default => throw new InvalidArgumentException("Invalid user type: {$type}"),
            };
        });
    }
}
