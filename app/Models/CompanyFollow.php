<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyFollow extends Model
{
    protected $fillable = [
        'candidate_id',
        'company_id',
    ];

    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
