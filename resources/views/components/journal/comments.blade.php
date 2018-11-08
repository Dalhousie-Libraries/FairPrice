<div class="panel panel-default">
    <div class="panel-heading">
        <h4 style='float-left'><b>Comments</b><a style='float:right' data-toggle='collapse' data-target='#comments-panel-body'>Show/Hide</a></h4>       
    </div>
    <div id='comments-panel-body' class="panel-body collapse in">
        <commentpanel
            current_comments='{{$journal->comments}}'
            journal_id='{{$journal->id}}'
            is_admin='{{auth()->user()->role > 1}}'
            comment_url='{{route("journal.update.comments", ["id" => $journal->id])}}'
        ></commentpanel>
    </div>
    <div class="panel-footer">
        <table style='width:100%'>
            <tr>
                <td style='width:50%'><b>Created:   </b>{{$journal->created_at}}</td><td><b>Last Updated:   </b>{{$journal->updated_at}}</td>
            </tr>
        </table>
        
    </div>
    
</div>

