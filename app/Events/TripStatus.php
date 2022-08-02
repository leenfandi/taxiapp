<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TripStatus implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */

    public $user_id;
    public $trip_id;
    public $trip_status;

    public function __construct($data)
    {
        $this->user_id = $data['user_id'];
        $this->trip_id = $data['trip_id'];
        $this->trip_status = $data['trip_status'];

    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return  new PrivateChannel('user'.$this->user_id);
    }

    public function broadcastAs()
    {
        return 'Trip-Status';
    }
}
