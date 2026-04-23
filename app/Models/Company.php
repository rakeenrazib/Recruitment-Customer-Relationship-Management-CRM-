<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Observers\CompanySubject;

class Company extends Model
{
    use CompanySubject;

    protected $fillable = [
        'user_id',
        'company_name',
        'industry',
        'website',
        'description',
        'location',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function recruiters()
    {
        return $this->hasMany(Recruiter::class);
    }

    public function verifiedRecruiters()
    {
        return $this->hasMany(Recruiter::class)->whereNotNull('verified_at');
    }

    public function jobs()
    {
        return $this->hasMany(Job::class);
    }

    public function openJobs()
    {
        return $this->hasMany(Job::class)->where('status', 'open');
    }

    public function followers()
    {
        return $this->belongsToMany(Candidate::class, 'company_follows');
    }

    public function followRecords()
    {
        return $this->hasMany(CompanyFollow::class);
    }

    public function verificationRequests()
    {
        return $this->hasMany(RecruiterVerificationRequest::class);
    }
}
