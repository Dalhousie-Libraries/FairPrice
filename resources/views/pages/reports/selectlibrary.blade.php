@extends('layouts.app')

@section("title", "Select Library")

@section('content')
<div class="panel panel-default" style='width:100%;float:left'>
    <div class="panel-heading"><h4>Select a Library<h4></div>
    <div class="panel-body">
        <form method='get' action=''>
            @if($report)
                <input type='hidden' name='report' value='{{$report}}'/>
            @endif
            <select name='library'>
                <option value='0'>All Libraries</option>
                @foreach(App\Library::all() as $library)
                    <option value='{{$library->bit_value}}'>{{$library->library_name}}</option>
                @endforeach
            </select>
        <input type="submit"></input>
        </form>
        
    </div>
    <div class="panel-footer">

    </div>
</div>
@endsection
