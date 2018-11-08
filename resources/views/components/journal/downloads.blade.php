<div class="panel panel-default">
  <div class="panel-heading"><h4><b>Downloads</b><a style='float:right' data-toggle='collapse' data-target='#downloads-panel-body'>Show/Hide</a></h4></div>
  <div id='downloads-panel-body' class="panel-body collapse-in">
        <div>
            <h5>Annual Downloads</h5>
            <table class='table'>
                @if (App\Download::where('journal_id', $journal->id)->first())
                <thead>
                    @foreach(App\Download::where('journal_id', $journal->id)->orderBy('report_year', 'DESC')->limit(5)->get() as $download) 
                        <th>{{$download->report_year}}</th>
                    @endforeach
                    <th>Average Annual Downloads</th>
                </thead>
                <tr>
                    @foreach(App\Download::where('journal_id', $journal->id)->orderBy('report_year', 'DESC')->limit(5)->get() as $download)
                        <td>{{$download->downloads_reported}}</td>
                    @endforeach
                    <td>{{ round(App\Download::where('journal_id', $journal->id)->avg('downloads_reported'), 2)}}</td>
                </tr>
                @endif
            </table>
        </div>
  </div> 
</div> 
