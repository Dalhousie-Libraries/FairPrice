@extends('layouts.app')

@section("title", "Journal Details for" . $journal->journal_title)

@section('content')
        <div class='row'>
                <div class='col-xs-12'>
                        @include('components.journal.actionpanel')
                </div>
        </div>
        <div class='row' style='display:-webkit-box; display:-webkit-flex; display:-ms-flexbox;display:flex;'>
                <div class='col-xs-12'>
                        @include('components.journal.recommend')
                </div>
        </div>
        <div class='row' style='display:-webkit-box; display:-webkit-flex; display:-ms-flexbox;display:flex;'>                                        
                <div class='col-xs-12'>
                        @include('components.journal.summary')
                </div>
        </div>

        <div class='row'>
                <div class='col-xs-12'>
                        @include('components.journal.votes')   
                </div>
        </div>
        <div class='row'>
                <div class='col-xs-12 float-left'>
                        @include('components.journal.platforms')
                </div>
        </div>
        
        <div class='row'>
                <div class='col-xs-12'>
                        @include('components.journal.price')
                </div>
        </div>
        <div class='row'>
                <div class='col-xs-12'>
                        @include('components.journal.downloads')
                </div>
        </div>
        <div class='row'>
                <div class='col-xs-12'>
                        @include('components.journal.citations')
                </div>
        </div>
        <div class='row'>
                <div class='col-xs-12'>
                        @include('components.journal.comments')
                </div>
        </div>
@endsection