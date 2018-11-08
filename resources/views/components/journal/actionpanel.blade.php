<div class="panel panel-default">
  <div class="panel-heading"><h4><b>Menu</b><a style='float:right' data-toggle='collapse' data-target='#actionpanel-panel-body'>Show/Hide</a></h4></div>
  <div id='actionpanel-panel-body' class="panel-body collapse-in">
                    <h4>I would like to...</h4>
                    <ul  class="custom-bullet" style="padding-left: 20px;">
                        <?php   
                            $election = App\Election::whereDate('end_date', '>=', Carbon\Carbon::now()->toDateString())->orWhere('end_date', null)->orderBy('start_date', 'desc')->first();
                            if($election) {
                                $audit = App\ElectionAudit::where('election_id', $election->id)->where('banner_id', Auth::user()->email)->first();
                            } else {
                                $audit = null;
                            }
                            $url = request()->session()->get('last_search_url', route('home'));
                        ?>
                        @if(strpos($url, 'journals') !== false)
                            <li><a href='{{$url}}'>Back to Search</a></li>
                        @else
                            <li><a href='{{route("home")}}'>Back to Home</a></li>
                        @endif
                        <li><a target="_blank" href='{{$journal->fullurl}}'>View This Journal</a></li>
                        @if(Auth::user()->role > 1)
                            <li><a href='{{route('journal.edit', ['journal_id' => $journal->id])}}'>Edit This Journal</a></li>
                            <li><a href='{{route('journal.pricelist', [$journal->id])}}'>View Historical Journal Prices</a></li>
                        @endif
                    </ul>
  </div>
</div>