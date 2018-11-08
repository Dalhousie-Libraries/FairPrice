<div class='row'>
    <div class='{{$size}}'>
        <div class='panel panel-info'>
            <div class='panel-heading'>
                <h4>{{$election->election_name}} Underway - Unsubmitted Recommendation</h4>
            </div>
            <div class="panel-body">
                <p>You have saved recommendations that you have not submitted. You may review these recommendations <a href="{{route('vote', ['election' => $election->id])}}">here.</a></p>
            </div>
        </div>
    </div>