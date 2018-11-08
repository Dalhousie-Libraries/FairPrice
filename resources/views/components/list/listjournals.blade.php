@component('components.common.listfiltercontrol', ['filter_subject' => $filter_subject, 'filter_term' => $filter_term, 'filter_platform' => $filter_platform,
    'src_page' => "", 'report' => "", 'alpha' => $alpha])
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

<div class="panel panel-default" style='width:100%;float:left'>
    <div class="panel-heading">
            <h4>
            <b>List of Journals - Page {{$journals->currentPage()}} of {{$journals->lastPage()}}</b>
            <a class="btn btn-primary btn-sm" style='float:right' href='{{$journals->url($journals->lastPage())}}'>>>|</a>
            <a class="btn btn-primary btn-sm" style='float:right' href='{{$journals->nextPageUrl()}}'>>></a>
            <a class="btn btn-primary btn-sm" style='float:right' href='{{$journals->previousPageUrl()}}'><<</a>
            <a class="btn btn-primary btn-sm" style='float:right' href='{{$journals->url(1)}}'>|<<</a>
            
            </h4>
    </div>
    <div class="panel-body">
        <div class='row' style='text-align:center'>
            @component('components.common.letterpagination')
            @endcomponent
        </div>
        <div class='row'>
            
                
                @if($election && !$audit)
                    <!-- Show the vote controls -->
                    <div class='col-md-1'><h4 style='text-align:center'>Electronic ISSN</h4></div>
                    <div class='col-md-1'><h4 style='text-align:center'>Print ISSN</h4></div>
                    <div class='col-md-5'><h4 style='text-align:left'>Name</h4></div>
                    <div class='col-md-5'><h4 style='text-align:center'>Recommendation Options</h4></div>
                @else
                    <div class='col-md-3'><h4 style='text-align:center'>Electronic ISSN</h4></div>
                    <div class='col-md-3'><h4 style='text-align:center'>Print ISSN</h4></div>
                    <div class='col-md-6'><h4 style='text-align:center'>Name</h4></div>
                @endif
        </div>
        <HR WIDTH="100%">
            @foreach ($journals as $journal)
                @if($loop->iteration % 2 == 0)
                    <div class='row' style='text-align:center'>
                @else
                    <div class='row' style='background-color:#f9f9f9;text-align:center'>
                @endif


                @if($election && !$audit)
                    <div class='col-md-1'>{{$journal->e_issn}}</div>
                    <div class='col-md-1'>{{$journal->p_issn}}</div>
                    <div class='col-md-5'><a href="{{('journal/' . $journal->id)}}" style='text-align:left'>{{$journal->journal_title}}</a></div>
                    <?php
                        if($vote->contains('journal_id', $journal->id)) {
                            $vote_val=$vote->where('journal_id', $journal->id)->first()->vote;
                        } else {
                            $vote_val = -1;
                        }
                    ?>
                    <div class='col-md-5'>
                    <minivote add_url='{{route('vote.add')}}' delete_url='{{route('vote.remove')}}'
                                ballot_url='{{route('vote')}}' journal_id='{{$journal->id}}'
                                election_id='{{$election->id}}' voted='{{$vote_val}}'></minivote>
                    </div>
                @else
                    <div class='col-md-3'>{{$journal->e_issn}}</div>
                    <div class='col-md-3'>{{$journal->p_issn}}</div>
                    <div class='col-md-6'><a href="{{('journal/' . $journal->id)}}">{{$journal->journal_title}}</a></div>
                @endif
                </div>
                <HR WIDTH="100%" style='margin:2px'>
            @endforeach
            <h4>
                <a class="btn btn-primary btn-sm" style='float:right' href='{{$journals->url($journals->lastPage())}}'>>>|</a>
                <a class="btn btn-primary btn-sm" style='float:right' href='{{$journals->nextPageUrl()}}'>>></a>
                <a class="btn btn-primary btn-sm" style='float:right' href='{{$journals->previousPageUrl()}}'><<</a>
                <a class="btn btn-primary btn-sm" style='float:right' href='{{$journals->url(1)}}'>|<<</a>
            </h4>
    </div>
</div>
