<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class PostOperationsWithoutMarkdown extends Mailable
{
    use Queueable, SerializesModels;

    public $post, $auth, $operation, $button, $btn_link;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($post, $auth, $button, $operation, $url)
    {
        $this->post = $post;
        $this->auth = $auth;
        $this->button = $button;
        $this->operation = $operation;
        $this->btn_link = $url;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.post_operations_custom');
    }
}
