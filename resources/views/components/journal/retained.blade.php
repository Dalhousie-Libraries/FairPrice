<div class="panel panel-default">
    <div class="panel-heading">
        <h4><b>Libraries Retaining</b><a style='float:right' data-toggle='collapse' data-target='#retained-panel-body'>Show/Hide</a></h4>
    </div>
    <div id='retained-panel-body' class="panel-body collapse in">
        <table class='table' style='width:100%'>
            <thead>
                @foreach (App\Library::all() as $library)
                    <th style='width:20%'>{{$library->library_name}}</th>
                @endforeach
            </thead>
            <tr>
                @foreach (App\Library::all() as $library)
                    @if($journal->retained_by & $library->bit_value)
                        <td style='width:20%'>Yes</td>
                    @else
                        <td style='width:20%'>No</td>
                    @endif
                @endforeach
            </tr>

        </table>        
    </div>
</div>
