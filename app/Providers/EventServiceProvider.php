<?php

namespace App\Providers;

use App\Events\PostCreate;
use App\Events\PostDelete;
use App\Events\PostEvent;
use App\Events\PostUpdate;
use App\Listeners\SendPostCreatedMarkdownMail;
use App\Listeners\SendPostCreatedViewMail;
use App\Listeners\SendPostDeletedMarkdownMail;
use App\Listeners\SendPostDeletedViewMail;
use App\Listeners\SendPostNotifications;
use App\Listeners\SendPostUpdatedMarkdownMail;
use App\Listeners\SendPostUpdatedViewMail;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        PostCreate::class => [
            SendPostCreatedMarkdownMail::class,
            SendPostCreatedViewMail::class,
        ],
        PostUpdate::class => [
            SendPostUpdatedMarkdownMail::class,
            SendPostUpdatedViewMail::class,
        ],
        PostDelete::class => [
            SendPostDeletedMarkdownMail::class,
            SendPostDeletedViewMail::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
