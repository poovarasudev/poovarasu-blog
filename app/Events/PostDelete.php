<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class PostDelete
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $post, $auth, $url, $button = "Laravel",  $operation = "deleted";

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($post, $auth)
    {
        $this->post = $post;
        $this->auth = $auth;
        $this->url = config('app.url') . "/post";
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
