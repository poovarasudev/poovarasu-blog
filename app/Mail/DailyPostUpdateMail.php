<?php

namespace App\Mail;

use App\Exports\PostExport;
use App\Post;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Facades\Excel;

class DailyPostUpdateMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $from = Carbon::now()->subDays(10);
        $to = now();
        $data = Post::get()->whereBetween('created_at', array($from, $to));
        $no_of_post = count($data);
        if ($no_of_post !=0){
            $pdf = PDF::loadview('layouts.pdf_excel', compact('data', 'from'))->setPaper('a4');
            return $this->markdown('emails.daily_post_update_mail_markdown', compact('no_of_post'))
                ->attachData($pdf->output(), 'post.pdf')
                ->attach(Excel::download(new PostExport, 'post.xlsx')->getFile(),['as' => 'post.xlsx']);
        }
        else {
            return $this->markdown('emails.daily_post_update_mail_markdown', compact('no_of_post'));
        }
    }
}
