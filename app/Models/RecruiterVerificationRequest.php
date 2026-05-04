<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecruiterVerificationRequest extends Model
{
    protected $fillable = [
        'recruiter_id',
        'company_id',
        'status',
        'message',
        'reviewed_at',
    ];

    protected function casts(): array
    {
        return [
            'reviewed_at' => 'datetime',
        ];
    }

    public function recruiter()
    {
        return $this->belongsTo(Recruiter::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
