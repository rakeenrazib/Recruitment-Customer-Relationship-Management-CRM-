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
}
