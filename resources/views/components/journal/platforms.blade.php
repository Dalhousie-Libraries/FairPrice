<div class="panel panel-default">
  <div class="panel-heading"><h4><b>Platforms</b><a style='float:right' data-toggle='collapse' data-target='#platforms-panel-body'>Show/Hide</a></h4>
        @if(Auth::user()->role > 1)
            <a type="button" class="btn btn-info btn-sm" href='{{route('journal.platform.edit', ['journal_id' => $journal->id, 'platform_id' => 0])}}'>Add to New Platform</a>
        @endif
  </div>

  <div id='platforms-panel-body' class="panel-body collapse in">
    <div>
        <table class='table table-bordered table-striped'>
            <thead style='border:solid 1px'>
                <th>#</th>
                <th>Platform Name</th>
                <th>Primary Platform</th>
                <th>Dates</th>
                <th>Volumes</th>
                <th>Embargo?</th>
                <th>Embargo Duration</th>
                <th>Perpetual Access</th>
                @if(Auth::user()->role > 0)
                <th>Options</th>
                @endif
            </thead>
            @foreach ($journal->platforms as $platform)
            <tr style='border:solid 1px'>
                    
                    <td>
                        {{ $loop->iteration }}
                    </td>
                    <td>
                        {{ $platform->name }}
                    </td>
                    <td>
                        {{ $platform->primary }}
                    </td>
                    <td>
                        {{ DB::table('platform_journal')->where('journal_id', $journal->id)->where('platform_id', $platform->id)->first()->years}}
                    </td>
                    <td>   
                        @if(DB::table('platform_journal')->where('journal_id', $journal->id)->where('platform_id', $platform->id)->first()->start_volume != null)
                            {{ DB::table('platform_journal')->where('journal_id', $journal->id)->where('platform_id', $platform->id)->first()->start_volume}} to {{DB::table('platform_journal')->where('journal_id', $journal->id)->where('platform_id', $platform->id)->first()->end_volume}}
                        @else
                            N/A
                        @endif
                    </td>
                    <td>
                        <?php
                            $is_embargo = DB::table('platform_journal')->where('journal_id', $journal->id)->where('platform_id', $platform->id)->first()->is_embargo;
                            if($is_embargo == 1) { 
                                echo "Yes";
                            } else {
                                echo "No";
                            }
                        ?>
                    </td>
                    <td>
                        <?php
                            $embargo_duration = DB::table('platform_journal')->where('journal_id', $journal->id)->where('platform_id', $platform->id)->first()->embargo_length;
                            if(!$embargo_duration || $embargo_duration == null) {
                                echo "N/A";
                            } 
                            
                            $embargo_duration = str_replace("y", " Year(s)", strtolower($embargo_duration));
                            $embargo_duration = str_replace("m", " Month(s)", $embargo_duration);
                            echo $embargo_duration;
                        ?>
                    </td>
                    <td>
                        @if($platform->journals->find($journal->id)->pivot->perpetual_access == "1")
                            Yes
                        @else
                            No
                        @endif
                    </td>
                    @if(Auth::user()->role > 1)
                    <td>
                        <a type="button" class="btn btn-info btn-sm" href='{{route('journal.platform.edit', ['journal_id' => $journal->id, 'platform_id' => $platform->id])}}'>Edit</a>
                        @if(Auth::user()->isAdmin)
                            <deletePlatform delete_url='{{route('journal.platform.delete', ['journal_id' => $journal->id, 'platform_id' => $platform->id])}}'></deletePlatform>
                        @endif
                    </td>
                    @endif
                
            </tr>
            @endforeach
        </table>
    </div>
  </div>
  <div class="panel-footer">Number of Platforms: {{ $journal->platforms->count() }}</div>
</div>