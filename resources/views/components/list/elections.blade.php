<div class="panel panel-default" style='width:100%;float:left'>
    <div class="panel-heading">
        <h4>
        <b>List of Consultations - Page {{$journals->currentPage()}} of {{$elections->lastPage()}}</b>
        <a class="btn btn-primary btn-sm" style='float:right' href='{{$elections->url($journals->lastPage())}}'>>>|</a>
        <a class="btn btn-primary btn-sm" style='float:right' href='{{$elections->nextPageUrl()}}'>>></a>
        <a class="btn btn-primary btn-sm" style='float:right' href='{{$elections->previousPageUrl()}}'><<</a>
        <a class="btn btn-primary btn-sm" style='float:right' href='{{$elections->url(1)}}'>|<<</a>
        
        </h4>
    </div>
    <div class="panel-body">
        <table class='table' style='width:100%'>
            <thead>
                <th>Consultation Name</th>
                <th>Consultation Description</th>
                <th>Start Date</th>
                <th>End Date</th>
                @if(Auth::user()->isAdmin)
                    <th>Options</th>
                @endif
            </thead>
            @foreach ($journals as $journal)
            <tr>
                <td>{{$election->election_name}}</td>
                <td>{{$election->election_description}}</td>
                <td>{{$election->start_date}}</td>
                <td>{{$election->end_date}}</td>
                @if(Auth::user()->isAdmin)
                    <th>Options</th>
                @endif
            </tr>
            @endforeach
        </table>        
    </div>
    <div class="panel-footer">

    </div>
</div>
