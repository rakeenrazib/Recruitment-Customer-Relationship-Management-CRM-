<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role', 'skills', 'headline', 'profile_photo_path', 'cover_photo_path'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected function displayName(): Attribute
    {
        return Attribute::get(function () {
            return $this->candidate?->full_name
                ?? $this->recruiter?->full_name
                ?? $this->company?->company_name
                ?? $this->name;
        });
    }

    public function jobs()
    {
        return $this->hasMany(Job::class);
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    public function candidate()
    {
        return $this->hasOne(Candidate::class);
    }

    public function recruiter()
    {
        return $this->hasOne(Recruiter::class);
    }

    public function company()
    {
        return $this->hasOne(Company::class);
    }

    public function notifications()
    {
        return $this->hasMany(AppNotification::class);
    }

    public function isCandidate(): bool
    {
        return $this->role === 'candidate';
    }

    public function isRecruiter(): bool
    {
        return $this->role === 'recruiter';
    }

    public function isCompany(): bool
    {
        return $this->role === 'company';
    }

    public function canRecruit(): bool
    {
        return $this->isRecruiter() && $this->recruiter?->isVerified();
    }
}
