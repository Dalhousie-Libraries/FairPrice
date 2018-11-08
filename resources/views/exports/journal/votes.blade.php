<?php
    $faculties = App\Faculty::all();
    $departments = App\Department::all();
?>

<html>
    <table>
    <tr><td><h1>Journal - Export Votes</h1></td></tr>
    <tr><h2>{{$journal->journal_title}}</h2></td></tr>
    <tr><td>Vote ID</td><td>Faculty</td><td>Department</td><td>Vote</td><td>Comments</td></tr>
    @foreach($journal->votes->all() as $vote)
        <tr><td>{{$vote->id}}</td><td>{{$faculties->where('id', $vote->faculty)->first()->faculty_name}}</td><td>{{$departments->where('id', $vote->department)->first()->department_name}}</td><td>{{$vote->hrvote}}</td><td>{{$vote->comments}}</td></tr>
    @endforeach
    </table>
</html>