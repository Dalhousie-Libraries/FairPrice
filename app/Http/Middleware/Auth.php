<?php

namespace App\Http\Middleware;

use Closure;

class Auth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $role_required)
    {
        if (auth()->check() && auth()->user()->role() >= $role_required) {
            if(auth()->user()->faculty_id == null && Route::currentRouteName() != 'user.wizard' && Route::currentRouteName() != 'user.wizard.submit') {
                return redirect()->route('user.wizard');        
            }
            return $next($request);
        }
        return redirect('login');
    }
}
