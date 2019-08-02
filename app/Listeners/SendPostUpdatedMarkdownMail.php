<?php

namespace App\Listeners;

use App\Events\PostUpdated;
use App\Mail\PostOperationsWithMarkdown;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendPostUpdatedMarkdownMail implements ShouldQueue
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
     * @param  PostUpdated  $event
     * @return void
     */
    public function handle(PostUpdated $event)
    {
        Mail::to($event->post->email)->send(
            new PostOperationsWithMarkdown($event->post, $event->auth, $event->button, $event->operation, $event->url));
    }
}
