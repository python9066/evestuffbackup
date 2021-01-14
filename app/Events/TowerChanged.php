<?php

namespace App\Events;

use App\Models\NotificationRecords;
use App\Models\TowerRecord;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TowerChanged implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


    /**
     * Message details
     *
     * @var User
     */
    public $towers;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(TowerRecord $towers)
    {
        $this->towers = $towers;
    }

    // public function __construct($notifications)
    // {
    //     $this->notifications = $notifications;

    // }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('towers');
    }
}
