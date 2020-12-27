<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cookie;

class NotifyAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        notifyAdmin("Above");
        if (env('NOTIFY_ADMIN_WEBHOOK') && isAppEnvProduction() && Cookie::has('notify')) {
            Cookie::queue('notify', 'yes', 15);
            notifyAdmin();
        }
        notifyAdmin("Below");

        return $next($request);
    }
}
