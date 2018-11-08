<?php

namespace App\Http\Middleware;

use Closure;
use App\Election;

class ElectionFilter
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
        //Determine the current election.
        $election = App\Election::whereDate('start_date' < Carbon\Carbon::now()->toDateString())->whereDate('end_date', '>=', Carbon\Carbon::now()->toDateString())->orWhere('end_date', null)->orderBy('start_date', 'desc')->first();
        if($election) {
            $request->attributes->add(['filter_election' => $election]);
            $packages = explode(',', $election->packages);
            $list = "";
            foreach($packages as $package) {
                $list = array_merge(\DB::table('platform_journal')->where('platform_id', $package)->pluck('journal_id')->all(), $list);
            }

            $request->attributes->add(['limit_to_journals' => $filtered_journals]);
        } else {
            return $next($request);
        }
        return $next($request);
    }
}
