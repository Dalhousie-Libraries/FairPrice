@extends('layouts.app')

@section("title", "Dalhousie Journal Assessment Database")

@section('content')
    @if($agent = Agent::isDesktop())
        @include('pages.desktop.generalhome') 
    @else
        @include('pages.mobile.generalhome') 
    @endif
@endsection