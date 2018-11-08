@component('components.common.listfiltercontrol', ['filter_subject' => $filter_subject, 'filter_term' => $filter_term, 'filter_platform' => $filter_platform,
    'src_page' => "", 'report' => "", 'alpha' => $alpha, 'filter_faculty' => $filter_faculty, 'filter_department' => $filter_department])
@endcomponent

<?php   
    $election = App\Election::whereDate('end_date', '>=', Carbon\Carbon::now()->toDateString())->orWhere('end_date', null)->orderBy('start_date', 'desc')->first();
    if($election) {
        $audit = App\ElectionAudit::where('election_id', $election->id)->where('banner_id', Auth::user()->email)->first();
    } else {
        $audit = null;
    }
    if($election) {
        $vote = App\Vote::where("election_id", $election->id)->where("user_id", auth()->user()->id)->get();
    }
?>

<div class="col-xs-12">
<div class="panel panel-default" style='width:100%;float:left'>
    <div class="panel-heading">
            <h4>
            <b>List of Journals - Page {{$journals->currentPage()}} of {{$journals->lastPage()}}</b>
            
            <a class="btn btn-primary btn-sm" style='float:right' href='{{$journals->url($journals->lastPage())}}'>>>|</a>
            <a class="btn btn-primary btn-sm" style='float:right' href='{{$journals->nextPageUrl()}}'>>></a>
            <a class="btn btn-primary btn-sm" style='float:right' href='{{$journals->previousPageUrl()}}'><<</a>
            <a class="btn btn-primary btn-sm" style='float:right' href='{{$journals->url(1)}}'>|<<</a>
            @if(isset($vote))
                @if($vote->count() == 0)
                    <a class="btn btn-primary btn-sm finalize hidden" style='float:right;margin-right:5px' href='{{ route('vote', ['election' => $election->id, 'finalize' => 'true'])}}'>Finalize and Submit</a>
                    <a class="btn btn-primary btn-sm finalize hidden" style='float:right;margin-right:5px' href='{{ route('vote', ['election' => $election->id])}}'>Review my Recommendations</a>
                @else
                    <a class="btn btn-primary btn-sm finalize" style='float:right;margin-right:5px' href='{{ route('vote', ['election' => $election->id, 'finalize' => 'true'])}}'>Finalize and Submit</a>
                    <a class="btn btn-primary btn-sm finalize" style='float:right;margin-right:5px' href='{{ route('vote', ['election' => $election->id])}}'>Review my Recommendations</a>
                @endif
            @endif
            </h4>
    </div>
    <div class="panel-body">
        <div class='row'>
            @component('components.common.downloadbar')
            @endcomponent
        </div>
        <div class='row' style='text-align:center'>
            @component('components.common.letterpagination', ['alpha' => $alpha])
            @endcomponent
        </div>
        <div class='row'>
            
                
                @if($election && !$audit)
                    <!-- Show the vote controls -->
                    <div class='col-md-1'><h4 style='text-align:left'>Electronic ISSN</h4></div>
                    <div class='col-md-1'><h4 style='text-align:left'>Print ISSN</h4></div>
                    <div class='col-md-4'><h4 style='text-align:left'>Name</h4></div>
                    <div class='col-md-4'><h4 style='text-align:center'>Quick Recommend</h4></div>
                    <div class='col-md-2'><h4 style='text-align:left'>Recommendation</h4></div>
                @else
                    <div class='col-md-1'><h4 style='text-align:left'>Electronic ISSN</h4></div>
                    <div class='col-md-1'><h4 style='text-align:left'>Print ISSN</h4></div>
                    <div class='col-md-4'><h4 style='text-align:left'>Name</h4></div>
					<div class='col-md-4'><h4 style='text-align:center'>Quick Recommend</h4></div>
                    <div class='col-md-2'><h4 style='text-align:left'>Recommendation</h4></div>
                @endif
        </div>
        <HR WIDTH="100%">
            @foreach ($journals as $journal)
                @if($loop->iteration % 2 == 0)
                    <div class='row' style='text-align:left;margin-left:0px; margin-right:0px'>
                @else
                    <div class='row' style='background-color:#f9f9f9;text-align:left;margin-left:0px; margin-right:0px'>
                @endif

                @if($election && !$audit)
                    <?php
                    if(isset($vote)) {
                        if($vote) {
                            if($vote->contains('journal_id', $journal->id)) {
                                $vote_val=$vote->where('journal_id', $journal->id)->first()->vote;
                            } else {
                                $vote_val = -1;
                            }
                        } else {
                            $vote_val = -1;
                        }
                    } else {
                        $vote_val = -1;
                    }
                    ?>
                    <reportrow add_url='{{route('vote.add')}}' delete_url='{{route('vote.remove')}}'
                        ballot_url='{{route('vote')}}' journal_id='{{$journal->id}}'
                        election_id='{{$election->id}}' voted='{{$vote_val}}' election=1 prop_journal='{{$journal->toJson()}}'></reportrow>
                @else
                    <reportrow add_url='{{route('vote.add')}}' delete_url='{{route('vote.remove')}}'
                        ballot_url='{{route('vote')}}' journal_id='{{$journal->id}}'
                        election_id='' voted='-1' election='' prop_journal='{{$journal->toJson()}}'></reportrow>                
                @endif
                </div>
                <HR WIDTH="100%" style='margin:2px'>
            @endforeach
            <h4>
                <a class="btn btn-primary btn-sm" style='float:right' href='{{$journals->url($journals->lastPage())}}'>>>|</a>
                <a class="btn btn-primary btn-sm" style='float:right' href='{{$journals->nextPageUrl()}}'>>></a>
                <a class="btn btn-primary btn-sm" style='float:right' href='{{$journals->previousPageUrl()}}'><<</a>
                <a class="btn btn-primary btn-sm" style='float:right' href='{{$journals->url(1)}}'>|<<</a>
                @if(isset($vote)) 
                    @if($vote->count() == 0)
                        <a class="btn btn-primary btn-sm finalize hidden" style='float:right;margin-right:5px' href='{{ route('vote', ['election' => $election->id, 'finalize' => 'true'])}}'>Finalize and Submit</a>
                        <a class="btn btn-primary btn-sm finalize hidden" style='float:right;margin-right:5px' href='{{ route('vote', ['election' => $election->id])}}'>Review my Recommendations</a>
                    @else
                        <a class="btn btn-primary btn-sm finalize" style='float:right;margin-right:5px' href='{{ route('vote', ['election' => $election->id, 'finalize' => 'true'])}}'>Finalize and Submit</a>
                        <a class="btn btn-primary btn-sm finalize" style='float:right;margin-right:5px' href='{{ route('vote', ['election' => $election->id])}}'>Review my Recommendations</a>
                    @endif
                @endif
            </h4>
    </div>
</div>
</div>
