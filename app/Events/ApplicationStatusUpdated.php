<?php

namespace App\Events;

use App\Models\Application;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ApplicationStatusUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $application;
    public $newStatus;

    /**
     * Create a new event instance.
     */
    public function __construct(Application $application, string $newStatus)
    {
        $this->application = $application;
        $this->newStatus = $newStatus;
    }
}
