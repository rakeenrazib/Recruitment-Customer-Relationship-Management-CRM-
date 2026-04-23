<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recruiter extends Model
{
    protected $fillable = [
        'user_id',
        'company_id',
        'full_name',
        'phone',
        'location',
        'department',
        'title',
        'bio',
        'verification_requested_at',
        'verified_at',
    ];

    protected function casts(): array
    {
        return [
            'verification_requested_at' => 'datetime',
            'verified_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function jobs()
    {
        return $this->hasMany(Job::class);
    }

    public function verificationRequests()
    {
        return $this->hasMany(RecruiterVerificationRequest::class);
    }

    public function isVerified(): bool
    {
        return $this->verified_at !== null;
    }
}
