<?php

namespace App\Listeners;

use App\Events\PostCreated;
use App\Mail\PostOperationsWithMarkdown;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendPostCreatedMarkdownMail implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  PostCreated  $event
     * @return void
     */
    public function handle(PostCreated $event)
    {
        Mail::to($event->post->email)->send(
            new PostOperationsWithMarkdown($event->post, $event->auth, $event->button, $event->operation, $event->url));
    }
}
