<?php

namespace App\Http\Middleware;

use Closure;

class GroupCheck
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
            if(auth()->user()->type > 0) {
                return $next($request);
            } else {
                return redirect('home')->withErrors(['This page is only available to users who are presently staff, faculty, or students. If you feel you should belong to one of these groups, please contact a system administrator.']);
            }
        
        }
        return redirect('login');
        //return $next($request);
    }
}
