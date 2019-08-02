<?php

namespace App\Listeners;

use App\Events\PostDeleted;
use App\Mail\PostOperationsWithMarkdown;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendPostDeletedMarkdownMail implements ShouldQueue
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
     * @param  PostDeleted  $event
     * @return void
     */
    public function handle(PostDeleted $event)
    {
        Mail::to($event->post->email)->send(
            new PostOperationsWithMarkdown($event->post, $event->auth, $event->button, $event->operation, $event->url));
    }
}
