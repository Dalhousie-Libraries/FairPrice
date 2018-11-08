@extends('layouts.app')

@section("title", "Journals Retained By Library")

@section('content')
<div class="panel panel-default" style='width:100%;float:left'>
    <div class="panel-heading"><h4>{{$title}}<h4></div>
    <div class="panel-body">
    @component('components.common.listfiltercontrol', ['filter_subject' => $filter_subject, 'filter_term' => $filter_term, 'filter_platform' => $filter_platform,
    'src_page' => "$src_page", 'report' => "$report", 'alpha' => $alpha, 'filter_faculty' => $filter_faculty, 'filter_department' => $filter_department,
        'library' => $library]);
    @endcomponent
    @component('components.common.pagination', ['journals' => $journals])
    @endcomponent
    @component('components.common.exportcontrols', ['alpha' => $alpha])
    @endcomponent
    
    <?php  
        $libraries = App\Library::all();
    ?>
        <table class='table' style='width:100%'>
            <thead>
                <th>Electronic ISSN</th>
                <th>Print ISSN</th>
                <th>Name</th>
                @foreach($libraries as $library) 
                    <th>Retained By {{$library->library_name}}</th>
                @endforeach
            </thead>
            @foreach ($journals as $journal)
                <tr>
                        <td>{{$journal->e_issn}}</td>
                        <td>{{$journal->p_issn}}</td>
                        <td><a href="{{route('journal', ['id' => $journal->id])}}">{{$journal->journal_title}}</a></td>
                        @foreach($libraries as $library) 
                            <td>
                                @if(!$journal->retained_by || $journal->retained_by % $library->bit_value != 0)
                                    No
                                @else
                                    <b>Yes</b>
                                @endif
                            </td>
                        @endforeach
                </tr>
            @endforeach
        </table>        
    </div>
    <div class="panel-footer">
    
    </div>
</div>
@endsection
