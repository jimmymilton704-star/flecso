<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DriverLocationUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $location;

    public function __construct($location)
    {
        $this->location = $location;
    }

    public function broadcastOn()
    {
        // IMPORTANT: each admin gets own channel
        return new PrivateChannel('admin.' . $this->location['admin_id']);
    }

    public function broadcastAs()
    {
        return 'driver.location.updated';
    }
}