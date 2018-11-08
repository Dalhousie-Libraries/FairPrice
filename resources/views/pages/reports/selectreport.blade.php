@extends('layouts.app')

@section("title", "No Vote Report")

@section('content')
<div class="panel panel-default" style='width:100%;float:left'>
    <div class="panel-heading"><h4>Select Report<h4></div>
    <div class="panel-body">
        <form method='get' action=''>
            <select name='report'>
                <option value=''></option> 
                <option value='ByVote'>Journals By Vote Tally</option>
                <option value='ByNonVote'>Journals By Not Needed Vote Tally</option>
                <option value='NoVote'>Journals Without Votes</option>
                <option value='NoPrice'>Journals With No Price Data</option>
                <option value='UndefinedPerpetual'>Journals With Undefined Perpetual Access</option>
                <option value='NoPerpetual'>Journals With No Perpetual Access</option>
                <option value='Perpetual'>Journals With Perpetual Access</option>
                <option value='Retained'>Journals Retained by Library</option>
            </select>
        <input type="submit"></input>
        </form>
        
    </div>
    <div class="panel-footer">

    </div>
</div>
@endsection
