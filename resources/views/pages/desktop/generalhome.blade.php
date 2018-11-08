<div class='col-md-10 col-md-offset-1'>
    @if(Auth::user())
        <?php $election = App\Election::whereDate('end_date', '>=', Carbon\Carbon::now()->toDateString())->orWhere('end_date', null)->orderBy('start_date', 'desc')->first();
                
                if($election) {
                    $audit = App\ElectionAudit::where('election_id', $election->id)->where('banner_id', Auth::user()->email)->first();
                    $vote = App\Vote::where("election_id", $election->id)->where("user_id", auth()->user()->id);
                } else {
                    $vote = null;
                    $audit = null;
                }?>
        @if($election)
            @if(!$audit)
                @if($vote->exists())
                    <div class='row'>    
                        @component('components.home.unsavedwarning', ['size' => 'col-md-8 col-md-offset-2', 'election' => $election])
                        @endcomponent
                    </div>
                @else
                    <div class='row'>    
                        @component('components.home.electionnotice', ['size' => 'col-md-8 col-md-offset-2', 'election' => $election])
                        @endcomponent    
                    </div>
                @endif
            @endif
        @endif
    @endif



    <div class="row">
    <div class="col-md-8 col-md-offset-2">
        @if($errors->any())
        <div class="panel panel-danger">
            <div class="panel-heading">Error</div>
            <div class='panel-body'>
                {{$errors->first()}}
            </div>
        </div>
        @endif

        @if (session('message'))
        <div class="panel panel-success">
            <div class="panel-heading">Thank You</div>
            <div class='panel-body'>
                {{ session('message') }}
            </div>
        </div>
        @endif
    </div>
    </div>

    <div class="row">
		<div class="col-md-3">
			@component('components.home.actionmenu', ['election' => $election, 'audit' => $audit, 'vote' => $vote])
			@endcomponent
			@component('components.home.statistics', [])
			@endcomponent
		</div>
		<div class="col-md-9">
			@component('components.home.welcomepanel', ['election' => $election])
			@endcomponent
		</div>
    </div>

    <div class="row">




    </div>    
</div>

