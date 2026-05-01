<?php

namespace App\Services;

use App\Models\AppNotification;
use App\Models\User;

class NotificationService
{
    // This service is intentionally a regular service class now.
    // The professor asked us to move the Singleton pattern away from
    // notifications and re-implement it for database connection handling.
    public function send(User $user, string $message, string $type, ?string $subjectType = null, ?int $subjectId = null): AppNotification
    {
        $subjectType = $subjectType ?: null;

        $existing = AppNotification::query()
            ->where('user_id', $user->id)
            ->where('type', $type)
            ->where('message', $message)
            ->where(function ($query) use ($subjectType, $subjectId) {
                if ($subjectType === null) {
                    $query->whereNull('subject_type')->whereNull('subject_id');
                } else {
                    $query->where('subject_type', $subjectType)->where('subject_id', $subjectId);
                }
            })
            ->where('created_at', '>=', now()->subMinutes(2))
            ->latest()
            ->first();

        if ($existing) {
            if ($existing->is_read) {
                $existing->update(['is_read' => false]);
            }

            return $existing;
        }

        return AppNotification::create([
            'user_id' => $user->id,
            'type' => $type,
            'message' => $message,
            'subject_type' => $subjectType,
            'subject_id' => $subjectId,
            'is_read' => false,
        ]);
    }
}
