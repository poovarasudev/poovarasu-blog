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
        if (isAppEnvProduction()) {
            if (Cookie::has('notify')) {
                notifyAdmin();
            } else {
                Cookie::queue('notify', 'yes', 15);
                notifyAdmin();
            }
        }

        return $next($request);
    }
}
