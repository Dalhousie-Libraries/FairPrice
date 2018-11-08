@extends('layouts.app')

@section("title", "Journal Details for" . $journal->journal_title)

@section('content')
    <div class='col-xs-12'>
        <div class='row'>
            <div class='col-xs-12'>
                <div class='row'>
                    <div class='col-xs-12'>
                        @include('components.journal.actionpanel')
                    </div>
                </div>
                <div class='row'>
                    <div class='col-xs-12'>
                        @include('components.journal.recommend')
                    </div>
                </div>
                <div class='row'>
                    <div class='col-xs-12'>
                        @include('components.journal.summary')
                    </div>
                </div>
            </div>
        </div>
    </div>
    
@endsection