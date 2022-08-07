<?php

namespace App\Events;

use Carbon\Carbon;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;


class NewNotification implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $name ;
    public $from;
    public  $to;
    public $notes;
    public $date;
    public $time;
    public $number;
    public $trip_id;
    public $driver_id;


    public function __construct($data)
    {
        $this->name = $data['username'];
        $this->from = $data['from'];
        $this->to = $data['to'];
        $this->notes = $data['notes'];
        $this->trip_id = $data['trip_id'];
        $this->number = $data['number'];
        $this->driver_id = $data['driver_id'];
        $this->date = date("Y M d" , strtotime(Carbon::now()));
        $this->time = date("h:i A" , strtotime(Carbon::now()));
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return  new PrivateChannel('driver'.$this->driver_id);
    }

    public function broadcastAs()
  {
      return 'new-notification';
  }
}
