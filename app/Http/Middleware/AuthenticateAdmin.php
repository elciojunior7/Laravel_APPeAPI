<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AuthenticateAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    const ADMIN = 100;
    public function handle($request, Closure $next, $guard = null)
    {
        if (!Auth::guard($guard)->check())
        {
            return redirect('/');
        }
        elseif(Auth::guard($guard)->check() && Auth::user()->role != self::ADMIN) 
        {
            return redirect('/home');
        }
        return $next($request);
    }
}
