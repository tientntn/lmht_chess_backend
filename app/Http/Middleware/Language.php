<?php

namespace App\Http\Middleware;

use Closure, Session;
use Illuminate\Support\Facades\Auth;

class Language
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if(!Session::has('locale'))
        {
            Session::put('locale', 'vi');
        }

        app()->setLocale(Session::get('locale'));

        return $next($request);
    }
}
