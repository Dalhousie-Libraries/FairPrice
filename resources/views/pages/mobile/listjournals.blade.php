@component('components.common.listfiltercontrol', ['filter_subject' => $filter_subject, 'filter_term' => $filter_term, 'filter_platform' => $filter_platform,
'src_page' => "", 'report' => "", 'alpha' => $alpha, 'filter_faculty' => $filter_faculty, 'filter_department' => $filter_department])
@endcomponent

<?php   
    $election = App\Election::whereDate('end_date', '>=', Carbon\Carbon::now()->toDateString())->orWhere('end_date', null)->orderBy('start_date', 'desc')->first();
    $audit = App\ElectionAudit::where('banner_id', Auth::user()->email)->first();
    if($election) {
        $vote = App\Vote::where("election_id", $election->id)->where("user_id", auth()->user()->id)->get();
    }
?>

<div class="col-xs-12">
	<div class="panel panel-default" style='width:100%;float:left'>
		<div class="panel-heading">
				<h4>
				<b>List of Journals - Page {{$journals->currentPage()}} of {{$journals->lastPage()}}</b>
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
			<div class='row' style='text-align:center'>
				<h4>
					<a class="btn btn-primary btn-sm"  href='{{$journals->url(1)}}'>|<<</a>
					<a class="btn btn-primary btn-sm" href='{{$journals->previousPageUrl()}}'><<</a>
					<a class="btn btn-primary btn-sm" href='{{$journals->nextPageUrl()}}'>>></a>
					<a class="btn btn-primary btn-sm" href='{{$journals->url($journals->lastPage())}}'>>>|</a>
				</h4>
			</div>
		</div>
	</div>
</div>

@foreach ($journals as $journal)
        @if($election && !$audit)
            <?php
                if($vote->contains('journal_id', $journal->id)) {
                    $vote_val=$vote->where('journal_id', $journal->id)->first()->vote;
                } else {
                    $vote_val = -1;
                }
            ?>
            <reportrow add_url='{{route('vote.add')}}' delete_url='{{route('vote.remove')}}'
                ballot_url='{{route('vote')}}' journal_id='{{$journal->id}}'
                election_id='{{$election->id}}' voted='{{$vote_val}}' election=1 prop_journal='{{$journal->toJson()}}'
                mobile='true'></reportrow>
        @else
            <reportrow add_url='{{route('vote.add')}}' delete_url='{{route('vote.remove')}}'
                ballot_url='{{route('vote')}}' journal_id='{{$journal->id}}'
                election_id='' voted='-1' election='' prop_journal='{{$journal->toJson()}}'
                mobile='true'></reportrow>
        @endif

@endforeach

<div class="col-xs-12">
	<div class="panel panel-default" style='width:100%;float:left'>
		<div class="panel-heading">
				<h4>
				<b>List of Journals - Page {{$journals->currentPage()}} of {{$journals->lastPage()}}</b>
				</h4>
		</div>
		<div class="panel-body">
			<div class='row'>
				@component('components.common.exportcontrols', ['alpha' => $alpha])
				@endcomponent
			</div>
			<div class='row' style='text-align:center'>
				@component('components.common.letterpagination')
				@endcomponent
			</div>
			<div class='row' style='text-align:center'>
				<h4>
					<a class="btn btn-primary btn-sm"  href='{{$journals->url(1)}}'>|<<</a>
					<a class="btn btn-primary btn-sm" href='{{$journals->previousPageUrl()}}'><<</a>
					<a class="btn btn-primary btn-sm" href='{{$journals->nextPageUrl()}}'>>></a>
					<a class="btn btn-primary btn-sm" href='{{$journals->url($journals->lastPage())}}'>>>|</a>
				</h4>
			</div>
		</div>
	</div>
</div>
