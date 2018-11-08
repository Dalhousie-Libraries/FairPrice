@extends('layouts.app')

@section("title", "Journal Details for" . $journal->journal_title)

@section('content')
        <div class='col-md-12'>
                <div class='row'>
                        @if(Auth::user()->role > 0)
                                <div class='col-md-6'>
                                        <div class='row'>
                                                <div class='col-md-12'>
                                                        @include('components.journal.summary')
                                                </div>
                                        </div>
                                </div>
                                <div class='col-md-6'>
                                        <div class='col-md-12'>
                                                @include('components.journal.votes')   
                                        </div>
                                </div>
                        @else
                                <div class='col-md-12'>
                                        <div class='row' style='display:-webkit-box; display:-webkit-flex; display:-ms-flexbox;display:flex;'>
                                                <div class='col-md-2'>
                                                        @include('components.journal.actionpanel')
                                                </div>
                                                <div class='col-md-4'>
                                                        @include('components.journal.recommend')
                                                </div>
                                                <div class='col-md-6'>
                                                        @include('components.journal.summary')
                                                </div>

                                        </div>
                                </div>
                        @endif
                </div>
                @if(Auth::user()->role > 0)
                <div class='row'>
                        <div class='col-md-12 float-left'>
                                @include('components.journal.platforms')
                        </div>
                </div>
                
                <div class='row'>
                        <div class='col-md-12'>
                                @include('components.journal.price')
                        </div>
                </div>
                
                <div class='row'>
                        <div class='col-md-12'>
                                @include('components.journal.comments')
                        </div>
                </div>
                <div class='row'>
                        <div class='col-md-6'>
                                @include('components.journal.downloads')
                        </div>
                        <div class='col-md-6'>
                                @include('components.journal.citations')
                        </div>
                </div>
                @endif
        </div>
    
@endsection