<div class="panel panel-default">
    <div class="panel-heading">
        <h4 style='float-left'><b>{{$journal->journal_title}}</b><a style='float:right' data-toggle='collapse' data-target='#summary-panel-body'>Show/Hide</a></h4>       
    </div>
    <div id='summary-panel-body' class="panel-body collapse in">
        @if(auth::user()->role > 1)
            <a appearance='button' class='btn btn-info btn-sm' style='float:right;padding-left:20px;padding-right:20px;' href='{{route("journal.edit", ["journal_id" => $journal->id])}}'>Edit</a>
        @endif
	<a class="btn btn-info btn-sm" appearance="Button" style="float: left; padding-left: 20px; padding-right: 20px;" target="_blank" href='{{$journal->fullurl}}'>View This Journal</a>
<br/><br/>
        <div class='row'>
            @if(auth::user()->role > 0)
            <div class='col col-md-6'>
            @else
            <div class='col col-md-12'>
            @endif
              <h4><b><u>Information</u></b></h4>

                <b>Electronic ISSN:  </b> {{$journal->e_issn}}<br/> <b>Print ISSN:</b> {{$journal->p_issn}}</br>
                <b>Title:  </b> {{$journal->journal_title}}<br/>
                @if(auth::user()->role > 0)
                    <b>Abbreviation:  </b> {{$journal->abbreviation}}<br/>
                    <b>Proprietary Identifier:  </b> {{$journal->proprietary_identifier}}<br/><br/>
                    <b>Priority:  </b>{{$journal->priority}}<br/>
                    <b>Subscribed:  </b>{{$journal->subscribed}}<br/>
                @endif
                @if($journal->jup && auth::user()->role > 0)
                    <b>JUP:       </b>{{$journal->jup}}
                @endif
                @if($journal->doi)
                    <b>     DOI:      </b>{{$journal->doi}}
                @endif
            </div>
            @if(auth::user()->role > 0)
            <div class='col col-md-6'>
                    <h4><b><u>5 Year Historical Subscription History</u></b></h4>
                    Subscribed In: <br/>
                    @foreach ($journal->historical_choices()
                        ->orderBy('subscription_year', 'desc')
                        ->take(5)
                        ->get() as $hc)
                        {{$hc->subscription_year}}<br/>
                    @endforeach
            </div>
            @endif
        </div>
        <div class='row'>
            <div class='col col-md-12'>
            <h4><b><u>Categories</u></b></h4>
                <b>Subjects: </b> {{ucwords(strtolower($journal->subject_1))}} 
                @if(Auth::user()->role > 0)
                    {{ucwords(strtolower($journal->subject_2))}} {{ucwords(strtolower($journal->subject_3))}} {{ucwords(strtolower($journal->subject_4))}} {{ucwords(strtolower($journal->user_subject))}}
                @endif
                <br/>
                @if(auth::user()->role > 0)
                    <b>Faculty:  </b> 
                    @foreach ($journal->faculties as $faculty)
                        {{$faculty->faculty_name}}
                        @if(!$loop->last)
                            {{"|"}}
                        @endif
                    @endforeach
                    </br>
                    <b>Departments:  </b>
                    @foreach ($journal->departments as $department)
                        {{$department->department_name}}
                        @if(!$loop->last)
                            {{"|"}}
                        @endif
                    @endforeach
                    <br/>
                    <b>Domain:  </b> {{$journal->textdomain}}</br>
                    <b>Fund:  </b> {{$journal->fund}}</br>
                    <br/>
                @endif
            </div>
        </div>
        @if(auth::user()->role > 0)
        <div class='row'>
            <div class='col col-md-6'>
                <h4><b><u>Lead Library</u></b></h4>
                @foreach (App\Library::all() as $library)
                    <b>{{$library->library_name}}    </b>:                     @if($journal->retained_by & $library->bit_value)
                        Yes
                    @else
                        No
                    @endif
                    <br/>
                @endforeach
            </div>
            <div class='col col-md-6'>
                <h4><b><u>Libraries Holding Print</u></b></h4>
                @foreach (App\Library::all() as $library)
                    <b>{{$library->library_name}}    </b>:                     @if($journal->libraries_holding_print & $library->bit_value)
                        Yes
                    @else
                        No
                    @endif
                    <br/>
                @endforeach
            </div>
        </div>
        <div class='row'>
            <div class='col col-md-12'>
                <h4><b><u>Alternative Titles</u></b></h4>
                @foreach (App\AlternateJournalTitle::where('journal_id', $journal->id)->get() as $title)
                    {{$title->journal_title}}
                        @if ($title != App\AlternateJournalTitle::where('journal_id', $journal->id)->get())
                        {{","}}
                        @endif
                @endforeach
            </div>
        </div>
        @endif

    </div>

    <div class="panel-footer">
        <table style='width:100%'>
            <tr>
                @if(Auth::user()->role > 0)
                <td style='width:50%'><b>Current Recommendation:   </b>{{$journal->recommendation}}</td><td style='width:50%'><b>Consultation:   </b>{{$journal->consultation}}</td>
                @endif
            </tr>
        </table>
        
    </div>
    
</div>

