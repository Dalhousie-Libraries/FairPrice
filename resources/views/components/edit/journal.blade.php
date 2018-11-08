@extends('layouts.app')

@section("title", "Edit Journal" . $journal->journal_title)

@section('content')
        <div class='col-md-12'>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 style='float-left'><b>Edit Journal {{$journal->journal_title}}</b><a style='float:right' data-toggle='collapse' data-target='#comments-panel-body'>Show/Hide</a></h4>       
                </div>
                <div id='comments-panel-body' class="panel-body collapse in">
                    @if(Auth::user()->isAdmin)
                        <editJournalForm id='{{$journal->id}}' 
                            admin='true'
                            prop_journal='{{$journal->load("faculties", "departments", "historical_choices")->toJson()}}'
                            prop_libraries='{{App\Library::all()->toJson()}}'
                            prop_subjects='{{App\Subject::all()->toJson()}}'
                            prop_departments='{{App\Department::all()->toJson()}}'
                            prop_faculties='{{App\Faculty::all()->toJson()}}'
                            >
                        </editJournalForm>      
                    @else
                        <editJournalForm id='{{$journal->id}}'
                                prop_journal='{{$journal->load("faculties", "departments")->toJson()}}'
                                prop_libraries='{{App\Library::all()->toJson()}}'
                                prop_subjects='{{App\Subject::all()->toJson()}}'
                                prop_departments='{{App\Department::all()->toJson()}}'
                                prop_faculties='{{App\Faculty::all()->toJson()}}'>
                        </editJournalForm>      
                    @endif
                </div>
                <div class="panel-footer">
                    <table style='width:100%'>
                        <tr>
                            <td style='width:50%'><b>Created:   </b>{{$journal->created_at}}</td><td><b>Last Updated:   </b>{{$journal->updated_at}}</td>
                        </tr>
                    </table>
                    
                </div>
                
            </div>
        </div>
    
@endsection
