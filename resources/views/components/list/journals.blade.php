@extends('layouts.app')

@section("title", "List of Journals")

@section('content')
    @if($agent = Agent::isDesktop())
        @include('pages.desktop.listjournals') 
    @else
        @include('pages.mobile.listjournals') 
    @endif
@endsection