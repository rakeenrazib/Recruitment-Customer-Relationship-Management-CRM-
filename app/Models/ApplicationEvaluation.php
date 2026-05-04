<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicationEvaluation extends Model
{
    protected $fillable = [
        'application_id',
        'recruiter_id',
        'assessment_type',
        'general_score',
        'final_score',
        'comments',
        'strengths',
        'weaknesses',
        'recommendation',
        'rubrics',
    ];

    protected function casts(): array
    {
        return [
            'general_score' => 'decimal:2',
            'final_score' => 'decimal:2',
            'rubrics' => 'array',
        ];
    }

    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    public function recruiter()
    {
        return $this->belongsTo(Recruiter::class);
    }
}
