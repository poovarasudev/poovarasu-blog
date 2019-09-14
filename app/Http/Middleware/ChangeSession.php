<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Config;
use Spatie\Permission\Traits\HasRoles;

class ChangeSession
{
    use HasRoles;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (auth()->user()) {
            if (auth()->user()->hasRole('admin')) {
                Config::set('session.lifetime', 120);
            } elseif (auth()->user()->hasRole('family')) {
                Config::set('session.lifetime', 120);
            } elseif (auth()->user()->hasRole('friend')) {
                Config::set('session.lifetime', 120);
            }
        }

        return $next($request);
    }
}
