<?php

namespace App\Http\Middleware;

use Closure;

class UserWizardCheck
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
        if(auth()->user()->faculty_id == null) {
            return redirect()->route('user.wizard');
        } else {
            return $next($request);
        }
    }
}
