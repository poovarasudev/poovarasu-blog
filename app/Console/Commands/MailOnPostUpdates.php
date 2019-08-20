<?php

namespace App\Console\Commands;

use App\Mail\DailyPostUpdateMail;
use App\Post;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade as PDF;

class MailOnPostUpdates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:post-updates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send daily update email for post';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Mail::to(config('admin_mail_config.admin_mail'))->send(new DailyPostUpdateMail());
    }
}
