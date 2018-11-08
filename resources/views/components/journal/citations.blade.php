<div class="panel panel-default">
  <div class="panel-heading"><h4><b>Citations</b><a style='float:right' data-toggle='collapse' data-target='#citations-panel-body'>Show/Hide</a></h4></div>
  <div id='citations-panel-body' class="panel-body collapse-in">
        <div>
            <h5>Annual Citations</h5>
            <table class='table'>
                @if (App\Citation::where('journal_id', $journal->id)->first())
                <thead>
                    @foreach(App\Citation::where('journal_id', $journal->id)->orderBy('report_year', 'DESC')->limit(5)->get() as $citation)
                        <th>{{$citation->report_year}}</th>
                    @endforeach
                    <th>Average Annual Citations</th>
                </thead>
                <tr>
                    @foreach(App\Citation::where('journal_id', $journal->id)->orderBy('report_year', 'DESC')->limit(5)->get() as $citation)
                        <td>{{$citation->citations_reported}}</td>
                    @endforeach
                    <td>{{ round(App\Citation::where('journal_id', $journal->id)->avg('citations_reported'), 2)}}</td>
                </tr>
                @else
                    <h6>No Citation Data Available for this Journal</h6>
                @endif
            </table>
        </div>
  </div>
</div>
