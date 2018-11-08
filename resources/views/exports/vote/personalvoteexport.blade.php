<html>
    <table>
        <tr><td><h1>Your Recommendations</h1></td></tr>
        <tr>
            <td><b>User:</b></td><td>{{auth()->user()->name}}</td>
        <tr>
        <tr>
            <td><b>Number of Recommendations in this Consultation:</b></td><td>{{$votes->count()}}</td>
        <tr>
        
            <h2>
                <td>Print ISSN</td>
                <td>Electronic ISSN</td>
                <td>Package</td>
                <td>Journal Title</td>
                <td>Journal URL</td>
                <td>Your Vote</td>
            </h2>
        </tr>

        @foreach($votes as $vote)
        <tr>
            <td>{{$vote->journal->p_issn}}</td>
            <td>{{$vote->journal->e_issn}}</td>
            <td>
                @foreach($vote->journal->platforms as $platform)
                    @if($platform->is_primary)
                        {{$platform->name}}
                    @endif
                @endforeach
            </td>
            <td>{{$vote->journal->journal_title}}</td>
            <td>{{$vote->journal->fullURL}}</td>
            <td>{{$vote->hrvote}}</td>
        </tr>
        @endforeach
    </table>
</html>
        