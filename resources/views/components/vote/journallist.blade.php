<input type='hidden' value='{{$election->id}}'/>

@if($agent = Agent::isDesktop())
<votelist name='{{explode(" ", Auth::user()->name)[0]}}' 
    save_url='{{route("vote.add")}}'
    delete_url='{{route("vote.remove")}}'
    submit_url='{{route("vote.submit")}}'
    initial_votes='{{App\Vote::with("journal")
        ->where("user_id", auth()->user()->id)
        ->where("election_id", $election->id)
        ->orderBy("votes.id")
        ->get()->toJSON()}}'
    election_id='{{$election->id}}'
    browse_url='{{route("journals")}}'
    finalize='{{$finalize}}'
    election_end='{{$election->end_date->format("F jS")}}'></votelist>
@else
<votelist name='{{explode(" ", Auth::user()->name)[0]}}' 
        save_url='{{route("vote.add")}}'
        delete_url='{{route("vote.remove")}}'
        submit_url='{{route("vote.submit")}}'
        initial_votes='{{App\Vote::with("journal")
            ->where("user_id", auth()->user()->id)
            ->where("election_id", $election->id)
            ->orderBy("votes.id")
            ->get()->toJSON()}}'
        election_id='{{$election->id}}'
        browse_url='{{route("journals")}}'
        is_mobile='true'
        finalize='{{$finalize}}'
        election_end='{{$election->end_date->format("F jS")}}'></votelist>
@endif
