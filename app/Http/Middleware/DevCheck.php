<?php

namespace App\Http\Middleware;

use Closure;

class DevCheck
{
    /**
     * Handle an incoming request.
     *Auth::user()->isStaff ||
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (auth()->check()) {
            if(auth()->user()->role == 4) {
                return $next($request);
            } else {
                return redirect('home')->withErrors(['You are not authorized to access this page.']);
            }
        }
        return redirect('login');
        //return $next($request);
    }
}
