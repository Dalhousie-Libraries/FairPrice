<?php

namespace App\Http\Middleware;

use Closure;
use App\ElectionAudit;
use App\Election;

class HasVoted
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
        $election = $request->input('election_id', Election::orderBy('end_date', 'desc')->first()->id);
        $ea = ElectionAudit::where('election_id', $election)->where('banner_id', auth()->user()->email)
                ->first();
        if($ea) {
            return redirect('home')->withErrors(['You have already cast your vote in this election. Your vote was cast on: ' . explode(" ", $ea->created_at)[0]]);
        }
        
        return $next($request);
    }
}
