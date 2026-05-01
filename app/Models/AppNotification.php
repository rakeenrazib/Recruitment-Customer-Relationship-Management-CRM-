<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppNotification extends Model
{
    protected $table = 'app_notifications';

    protected $fillable = [
        'user_id',
        'type',
        'message',
        'subject_type',
        'subject_id',
        'is_read',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function targetUrl(): ?string
    {
        if ($this->subject_type === ApplicationEvaluation::class && $this->subject_id) {
            return route('notifications.open', $this);
        }

        if ($this->subject_type === Application::class && $this->subject_id) {
            return route('notifications.open', $this);
        }

        if (str_starts_with($this->type, 'evaluation-')) {
            return route('notifications.open', $this);
        }

        return null;
    }
}
