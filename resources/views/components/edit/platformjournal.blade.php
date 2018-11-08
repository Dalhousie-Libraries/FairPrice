@extends('layouts.app')

@section("title", "Edit Platform on Journal " . $journal->journal_title)

@section('content')
        <div class='col-md-12'>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 style='float-left'><b>Edit Platform for Journal {{$journal->journal_title}}</b><a style='float:right' data-toggle='collapse' data-target='#comments-panel-body'>Show/Hide</a></h4>       
                </div>
                <div id='comments-panel-body' class="panel-body collapse in">
                    <editPlatformForm platform_id='{{$platform_id}}' journal_id='{{$journal_id}}'></editJournalForm>      
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
