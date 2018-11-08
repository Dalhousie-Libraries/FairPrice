<div class="panel panel-default" style='width:100%;float:left'>
  <div class="panel panel-heading"><h4><b>Filter</b><a style='float:right' data-toggle='collapse' data-target='#filter-panel-body'>Show/Hide</a></h4></div>
  <div id='filter-panel-body' class="panel-body collapse in">
        <form id='filter-form' method='POST' action=''>
            {{ csrf_field() }}
            <input type='hidden' id='page' value='1'/>
            <label for='type_filter'>Vote Category</label>
            <select id='type_filter' name='type_filter'>
                @if($type_filter == -1)
                    <option value='-1' selected="selected">All</option>
                @else
                    <option value='-1'>All</option>
                @endif
                @if($type_filter == 0)
                    <option value='0' selected="selected">Other</option>
                @else
                    <option value='0'>Other</option>
                @endif
                @if($type_filter == 1)
                    <option value='1' selected="selected">Student</option>
                @else
                    <option value='1'>Student</option>
                @endif
                @if($type_filter == 2)
                    <option value='2' selected="selected">Faculty</option>
                @else
                    <option value='2'>Faculty</option>
                @endif
                @if($type_filter == 3)
                    <option value=3 selected="selected">Staff<option>
                @else
                    <option value='3'>Staff</option>
                @endif
                
                
            </select>

            <label for='vote_filter'>Vote</label>
            <select id='vote_filter' name='vote_filter'>
                @if($vote_filter == -1)
                    <option value='-1' selected="selected">All</option>
                @else
                    <option value='-1'>All</option>
                @endif
                @if($vote_filter == 0)
                    <option value='0' selected="selected">Not Needed</option>
                @else
                    <option value='0'>Not Needed</option>
                @endif
                @if($vote_filter == 1)
                    <option value='1' selected="selected">Wanted</option>
                @else
                    <option value='1'>Wanted</option>
                @endif                                
                @if($vote_filter == 2)
                    <option value='2' selected="selected">Needed</option>
                @else
                    <option value='2' >Needed</option>
                @endif                
                
                
                

            </select>
            <button class='btn btn-primary btn-sm' type="submit" form='filter-form' value="Submit">Filter</button>
        </form>
  </div>
</div>
<div class="panel panel-default" style='width:100%;float:left'>
    <div class="panel-heading">
            <h4>
            <b>List of Recommendations for Consultation {{$election->election_name}} - Page {{$votes->currentPage()}} of {{$votes->lastPage()}}</b>
            <a class="btn btn-primary btn-sm" style='float:right' href='{{$votes->url($votes->lastPage())}}'>>>|</a>
            <a class="btn btn-primary btn-sm" style='float:right' href='{{$votes->nextPageUrl()}}'>>></a>
            <a class="btn btn-primary btn-sm" style='float:right' href='{{$votes->previousPageUrl()}}'><<</a>
            <a class="btn btn-primary btn-sm" style='float:right' href='{{$votes->url(1)}}'>|<<</a>
            
            </h4>
    </div>
    <div class="panel-body">
        <table class='table' style='width:100%'>
            @if($votes)
                <thead>
                    <th>Vote</th>
                    <th>Comments</th>
                </thead>
                @foreach ($votes as $vote)
                <tr>
                        <td>{{$vote->id}}</td>
                        <td>{{$vote->comments}}</td>
                </tr>
                @endforeach
            @else
            <p>There are no recommendations during this consultation for this journal.</p>
            @endif
        </table>        
    </div>
    <div class="panel-footer">

    </div>
</div>
