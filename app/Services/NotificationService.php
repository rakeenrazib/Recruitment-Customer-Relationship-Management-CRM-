<?php

namespace App\Services;

use App\Models\AppNotification;
use App\Models\User;
use LogicException;

class NotificationService
{
    private static ?self $instance = null;

    private function __construct()
    {
    }

    public static function getInstance(): self
    {
        return self::$instance ??= new self();
    }

    public function __clone(): void
    {
        throw new LogicException('NotificationService is a singleton and cannot be cloned.');
    }

    public function __wakeup(): void
    {
        throw new LogicException('NotificationService is a singleton and cannot be unserialized.');
    }

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
