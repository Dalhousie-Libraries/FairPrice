@extends('layouts.app')

@section("title", "Recommend")

@section('content')
    <div class='row'>
        <div class='col-md-10 col-md-offset-1'>
            @include('components.vote.journallist')   
        </div>
    </div>
@endsection
