<?php

use App\Models\User;
use App\Services\NotificationService;

test('notification service deduplicates matching recent notifications', function () {
    $user = User::factory()->create();
    $service = new NotificationService();

    $first = $service->send($user, 'New job listing available.', 'job-alert', 'Job', 1);
    $second = $service->send($user, 'New job listing available.', 'job-alert', 'Job', 1);

    expect($first->id)->toBe($second->id);
    expect(\App\Models\AppNotification::count())->toBe(1);
});
