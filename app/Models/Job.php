<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Observers\CompanySubject;

class Job extends Model
{
    use CompanySubject;

    protected $fillable = [
        'user_id',
        'recruiter_id',
        'company_id',
        'title',
        'company',
        'location',
        'salary',
        'description',
        'requirements',
        'status',
        'job_type',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function recruiter()
    {
        return $this->belongsTo(Recruiter::class);
    }

    public function companyProfile()
    {
        return $this->belongsTo(Company::class);
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    public function interviewPlan()
    {
        return $this->hasOne(InterviewPlan::class);
    }

    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }
}
