@extends('layouts.app')

@section("title", "Export Votes")

@section('content')
<div class='panel panel-default'>
        <div class='panel-heading'>
            <h1>Export Votes</h1>
        </div>
        <div class='panel-body'>
            <form action="{{ route('export.vote.submit') }}" method="POST" enctype="multipart/form-data">
                {{ csrf_field() }}
                <b>File Output:</b><br/>
                    <input type="radio" name="format" value="XLSX">Excel 2007 File (.XLSX)<br/>
                    <input type="radio" name="format" value="XLS">Excel File (.XLS)<br/>
                    <input type="radio" name="format" value="CSV">Comma-Separated Values (.CSV)<br/>
                <b>Sort By Platform:</b><br/>
                    <input type="radio" name="by_platform" value="true">Yes<br/>
                    <input type="radio" name="by_platform" value="false">No<br/>
                <b>Select Election</b><br/>
                    <select name='election'> 
                        @foreach(\App\Election::all() as $election)
                            <option value='{{$election->id}}'>{{$election->election_name}}</option>
                        @endforeach
                    </select><br/>

                <b>Filter By Platform</b><br/>
                    <select name='platform'> 
                        <option value=''>All</option>
                        @foreach(\App\Platform::all() as $platform)
                            <option value='{{$platform->id}}'>{{$platform->name}}</option>
                        @endforeach
                    </select><br/>
                <b>Filter By Faculty</b><br/>
                    <select name='faculty'> 
                        <option value=''>All</option>
                        @foreach(\App\Faculty::all() as $faculty)
                            <option value='{{$faculty->id}}'>{{$faculty->faculty_name}}</option>
                        @endforeach
                    </select><br/>
                <b>Filter By Department</b><br/>
                    <select name='department'> 
                        <option value=''>All</option>
                        @foreach(\App\Department::all() as $department)
                            <option value='{{$department->id}}'>{{$department->department_name}}</option>
                        @endforeach
                    </select><br/>
                <b>Filter By Role</b><br/>
                    <select name='role'> 
                        <option value=''>All</option>
                        <option value=1>Student</option>
                        <option value=3>Faculty</option>
                        <option value=2>Staff</option> 
                    </select><br/>
                <b>Filter By Vote</b><br/>
                    <select name='vote'> 
                        <option value=''>All</option>
                        <option value=0>Not Needed</option>
                        <option value=1>Wanted</option>
                        <option value=2>Needed</option>
                    </select><br/>
                <b>Count Unsubmitted Votes?</b><br/>
                    <select name='submitted'> 
                        <option value='yes'>Yes</option>
                        <option value='no'>No</option>
                        <option value='unsubmitted_only'>Unsubmitted Only</option>
                    </select><br/>
                    
                <input type="submit" value="Save" />
            </form>
        </div>
        <div class='panel-footer'>
                
        </div>
    </div>
@endsection