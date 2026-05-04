<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Support\Facades\Log;
// use Illuminate\Support\Facades\Mail; // Use simple placeholder as requested

class EmailNotificationDecorator extends NotificationDecorator
{
    /**
     * Call wrapped send(), then also send an email.
     */
    public function send(User $user, string $message, string $type): void
    {
        // First, run the wrapped notification
        parent::send($user, $message, $type);

        // Then send the email (Using Log as a placeholder for email sending logic)
        Log::info("EMAIL SENT to {$user->email}: [{$type}] {$message}");
        
        // Example of actual mail usage:
        // Mail::raw($message, function ($mail) use ($user) {
        //     $mail->to($user->email)->subject('New Notification');
        // });
    }
}
