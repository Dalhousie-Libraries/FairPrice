@extends('layouts.app')

@section("title", "Journal Details for" . $journal->journal_title)

@section('content')
        @if($agent = Agent::isDesktop())        
                @if(Auth::user()->role == 0)
                        @include('pages.desktop.generaljournaldetails') 
                @endif
                @if(Auth::user()->role > 0)
                        @include('pages.desktop.adminjournaldetails') 
                @endif
        @else
                @if(Auth::user()->role == 0)
                        @include('pages.mobile.generaljournaldetails') 
                @endif
                @if(Auth::user()->role > 0)
                        @include('pages.mobile.adminjournaldetails') 
                @endif
        @endif
@endsection