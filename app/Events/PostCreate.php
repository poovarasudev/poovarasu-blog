<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class PostCreate
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $post, $auth, $button = "View Post",  $operation = "created", $url;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($post, $auth)
    {
        $this->post = $post;
        $this->auth = $auth;
        $this->url = config('app.url') . "/post/" . $post->id;
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
