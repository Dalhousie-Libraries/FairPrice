<div class="panel panel-default">
  <div class="panel-heading"><h4><b>Pricing</b><a style='float:right' data-toggle='collapse' data-target='#pricing-panel-body'>Show/Hide</a></h4></div>
  <div id='pricing-panel-body' class="panel-body collapse-in">
        <div>
            @if(Auth::user()->role > 1)
                <a href="{{route('journal.pricelist', [$journal->id])}}">View All Prices</a>
            @endif
            <h5> Latest Price Information</h5>
            <table class='table'>
                <thead>
                    <th>Year</th>
                    <th>Price</th>
                    <th>Cost Per Use</th>
                    @if(Auth::user()->isAdmin)
                        <th>Adjusted Cost Per Use</th>
                    @endif
                </thead>

                <tr>
                    @if ($prices->isNotEmpty())
                        <td>
                            {{ $prices->first()->report_year }}
                        </td>
                        <td>
                            {{ $prices->first()->price . " " . $prices->first()->currency }}
                        </td>
                        <td>
                                @if($prices->first()->cost_per_use) 
                                    {{$prices->first()->cost_per_use}} (Specified)
                                @else
                                    <?php
                                    $use = \App\Download::where('report_year', $prices->first()->report_year)->where('journal_id', $prices->first()->journal_id)->first()
                                    ?>
                                    @if($use)
                                        {{$prices->first()->price / $use->downloads_reported}} (Per Download)
                                    @endif
                                @endif
                        </td>
                        @if(Auth::user()->isAdmin)
                        <td>
                            {{ $prices->first()->adjusted_cost_per_use }}
                        </td>
                        @endif
                    @else
                        <td>
                            No
                        </td>
                        <td>
                            Price
                        </td>
                        <td>
                            Data
                        </td>
                        @if(Auth::user()->isAdmin)
                        <td>
                            Available
                        </td>
                        @endif
                    @endif
                </tr>
            </table>
        </div>
        <div>
            <h3>Historical Price Information</h3>
                <pricechart id="{{$journal->id}}"></pricechart>
        </div>
  </div>
</div>