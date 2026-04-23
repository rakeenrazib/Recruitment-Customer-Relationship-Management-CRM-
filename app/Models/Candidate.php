<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    protected $fillable = [
        'user_id',
        'full_name',
        'phone',
        'location',
        'bio',
        'portfolio',
        'details',
        'resume_link',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    public function follows()
    {
        return $this->hasMany(CompanyFollow::class);
    }

    public function followedCompanies()
    {
        return $this->belongsToMany(Company::class, 'company_follows');
    }
}
