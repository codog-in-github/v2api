<?php

namespace App\Events;

use App\Models\OrderNode;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderNodeChange
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public OrderNode $node;

    /**
     * @param OrderNode | array $node
     */
    public function __construct($node)
    {
        if(is_array($node)){
            $node = new OrderNode($node);
        }
        $this->node = $node;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
