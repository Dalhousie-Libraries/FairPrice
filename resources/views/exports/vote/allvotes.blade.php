<html>
        <table>
        <tr><td><h1>Votes - Export Votes</h1></td></tr>
        <tr><td><h2>Export Summary</h2></td></tr>
        <tr><td>Platform</td><td>Not Needed</td><td>Wanted</td><td>Needed</td><td>Total</td></tr>
        @foreach($platforms as $platform)
        <tr>
            <?php
                $list = $platform->journals->pluck('id');
            ?>
            <td>{{$platform->name}}</td>
            <td>{{$votes->whereIn('journal_id', $list)->where('vote', 0)->count()}}</td>
            <td>{{$votes->whereIn('journal_id', $list)->where('vote', 1)->count()}}</td>
            <td>{{$votes->whereIn('journal_id', $list)->where('vote', 2)->count()}}</td>
            <td>{{$votes->whereIn('journal_id', $list)->count()}}</td>
        </tr>
        @endforeach
        <tr><td><h2>Vote Export</h2></td></tr>
        <tr>
            <td>Vote ID</td>
            <td>Journal ID</td>
            <td>Print ISSN</td>
            <td>Online ISSN</td>
            <td>Package</td>
            <td>Title</td>
            <td>Role</td>
            <td>Faculty</td>
            <td>Department</td>
            <td>Vote</td>
            <td>Not Needed</td>
            <td>Wanted</td>
            <td>Needed</td>
            <td>Comments</td>
            <td>Submitted</td>
        </tr>
        @foreach($votes as $vote)
            <tr>
                <td>{{$vote->id}}</td>
                <td>{{$vote->journal->id}}</td>
                <td>{{$vote->journal->p_issn}}</td>
                <td>{{$vote->journal->e_issn}}</td>
                <td>@foreach($vote->journal->platforms as $platform)
                        @if($platform->is_primary)
                            {{$platform->name}}
                        @endif
                    @endforeach
                </td>
                <td>{{$vote->journal->journal_title}}</td>
                <td>
                    @if($vote->type == 1)
                        Student
                    @endif
                    @if($vote->type == 3)
                        Faculty
                    @endif
                    @if($vote->type == 2) 
                        Staff
                    @endif
                </td>
                <td>{{\App\Faculty::find($vote->faculty)->faculty_name}}</td>
                <td>@if($vote->department)
                        {{\App\Department::find($vote->department)->department_name}}
                    @endif
                </td>
                <td>
                    @if($vote->vote == 0)
                        Not Needed
                    @endif
                    @if($vote->vote == 1)
                        Wanted
                    @endif
                    @if($vote->vote == 2)
                        Needed
                    @endif
                </td>
                <td>
                    @if($vote->vote == 0)
                        Yes
                    @endif
                </td>
                <td>
                    @if($vote->vote == 1)
                        Yes
                    @endif
                </td>
                <td>
                    @if($vote->vote == 2)
                        Yes
                    @endif
                </td>
                <td>{{$vote->comments}}</td>
                <td>
                    @if($vote->is_submitted)
                        Yes
                    @else
                        No
                    @endif
                </td>
            </tr>
        @endforeach
        </table>
    </html>