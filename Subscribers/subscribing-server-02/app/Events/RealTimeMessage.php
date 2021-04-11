<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class RealTimeMessage implements ShouldBroadcast
{
    use SerializesModels;

    // declare message
    protected  $message;

    public function __construct($message)
    {

        $this->message = $message;
    }

    public function broadcastOn(): Channel
    {
        // trigger notification
        return new PrivateChannel('events');
    }
}
