<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'company',
        'location',
        'salary',
        'description',
        'status',
        'job_type',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function applications()
    {
    return $this->hasMany(Application::class);
    }
}

