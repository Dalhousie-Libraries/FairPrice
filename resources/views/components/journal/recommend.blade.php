<div class="panel panel-default">
  <div class="panel-heading"><h4><b>Recommend</b><a style='float:right' data-toggle='collapse' data-target='#recommend-panel-body'>Show/Hide</a></h4></div>
  <div id='recommend-panel-body' class="panel-body collapse-in">
                        <?php   
                            $election = App\Election::whereDate('end_date', '>=', Carbon\Carbon::now()->toDateString())->orWhere('end_date', null)->orderBy('start_date', 'desc')->first();
                            if($election) {
                                $audit = App\ElectionAudit::where('election_id', $election->id)->where('banner_id', Auth::user()->email)->first();
                                $url = redirect()->back()->withInput()->getTargetUrl();
                                $vote = App\Vote::where("election_id", $election->id)->where("user_id", auth()->user()->id)->where("journal_id", $journal->id);
                            } else {
                                $audit = null;
                                $vote = null;
                            }
                            
                        ?>
                        @if($election)
                            @if(!$audit)
                                <h4>Consultation: {{$election->election_name}}</h4>
                                @if($vote->exists())
                                    <?php
                                        $myvote = $vote->first();
                                        $truncated_comments = substr($myvote->comments, 0, 200); 
                                        if(strlen($myvote->comments) >= 200) {
                                            $truncated_comments .= "...";
                                        }
                                    ?>
                                    <p><b>My Vote</b>: {{$myvote->hrvote}}</p>
                                    <p><b>Comments</b>: {{$truncated_comments}}</p>
                                @endif
                                <votecontrols add_url='{{route('vote.add')}}' delete_url='{{route('vote.remove')}}'
                                    ballot_url='{{route('vote', ['election' => $election->id])}}' journal_id='{{$journal->id}}'
                                    election_id='{{$election->id}}' voted='{{$vote->exists()}}'></votecontrols>
                            @endif
                        @endif
                    </ul>
  </div>
</div>