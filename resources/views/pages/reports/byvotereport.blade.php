@extends('layouts.app')

@section("title", "Journals By Vote Count")

@section('content')
<div class="panel panel-default" style='width:100%;float:left'>
    <div class="panel-heading"><h4>{{$title}}<h4></div>
    <div class="panel-body">

    @component('components.common.listfiltercontrol', ['filter_subject' => $filter_subject, 'filter_term' => $filter_term, 'filter_platform' => $filter_platform,
    'src_page' => "$src_page", 'report' => "$report", 'alpha' => $alpha, 'filter_faculty' => $filter_faculty, 'filter_department' => $filter_department])
    @endcomponent

    @component('components.common.pagination', ['journals' => $journals])
    @endcomponent
    @component('components.common.exportcontrols', ['alpha' => $alpha])
    @endcomponent
        <table class='table' style='width:100%'>
            <thead>
                <th>Electronic ISSN</th>
                <th>Print ISSN</th>
                <th>Name</th>
                <th># Votes (Needed)</th>
                <th># Votes (Nice to Have)</th>
                <th># Votes (Not Needed)</th>
            </thead>
            @foreach ($journals as $journal)
            <tr>
                
                    <td>{{$journal->e_issn}}</td>
                    <td>{{$journal->p_issn}}</td>
                    <td><a href="{{route('journal', ['id' => $journal->id])}}">{{$journal->journal_title}}</a></td>
                    <td>{{$journal->needed_votes}}</td>
                    <td>{{$journal->nice_votes}}</td>
                    <td>{{$journal->no_votes}}</td>

            </tr>
            @endforeach
        </table>        
    </div>
    <div class="panel-footer">
    
    </div>
</div>
@endsection
