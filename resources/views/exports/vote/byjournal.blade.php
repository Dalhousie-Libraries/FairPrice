<html>
        <table>
        <tr><td><h1>Summary By Journal</h1></td></tr>
        <tr><td>Journal</td><td>Not Needed</td><td>Wanted</td><td>Needed</td><td>Total</td></tr>
        <?php $journal_arr = [];?>
        @foreach($votes as $vote)
            <?php
                $journal_arr[] = $vote->journal;
            ?>
        @endforeach
        <?php 
            $journals = collect($journal_arr)->unique('id');
        ?>
        @foreach($journals as $journal)
        <tr>
            <td>{{$journal->journal_title}}</td>
            <td>{{$votes->where('journal_id', $journal->id)->where('vote', 0)->count()}}</td>
            <td>{{$votes->where('journal_id', $journal->id)->where('vote', 1)->count()}}</td>
            <td>{{$votes->where('journal_id', $journal->id)->where('vote', 2)->count()}}</td>
            <td>{{$votes->where('journal_id', $journal->id)->count()}}</td>
        </tr>
        @endforeach
        </table>
    </html>