<div class="panel panel-default" style='width:100%;float:left'>
    <div class="panel-heading">
            <h4>
                <b>List of Prices for Journal: {{$journal->journal_title}}</b>
            </h4>
    </div>
    <div class="panel-body">
        @if(Auth::user()->role > 1)
            <a type='button' class='btn btn-info' href='{{route("price.edit", ["price_id" => 0, "journal_id" => $journal->id])}}'>Add Price</a>
        @endif
        <table class='table table-striped'>
            <thead>
                <th>Year</th>
                <th>Price</th>
                <th>Cost Per Use</th>
                <th>Adjusted Cost Per Use</th>
                <th>Actions</th>
            </thead>
        @foreach($prices->sortByDesc('report_year') as $price)
            <tr>
                <td>{{$price->report_year}}</td>
                <td>{{$price->price}}</td>
                <td>
                    @if($price->cost_per_use) 
                        {{$prices->first()->cost_per_use}} (Specified)
                    @else
                        <?php
                        $use = \App\Download::where('report_year', $price->report_year)
                        ->where('journal_id', $price->journal_id)->first()
                        ?>
                        @if($use)
                            {{$price->price / $use->downloads_reported}} (Per Download)
                        @endif
                    @endif
                </td>
                <td>{{$price->adjusted_cost_per_use}}</td>
                <td>
                    @if(Auth::user()->role > 1)
                        <a type='button' class='btn btn-info' href='{{route("price.edit", ["price_id" => $price->id, "journal_id" => $journal->id])}}'>Edit</a>
                        <a type='button' class='btn btn-danger' href='{{route("price.delete", ["price_id" => $price->id, "journal_id" => $journal->id])}}'>Delete</a>
                    @endif
                </td>
            </tr>
        @endforeach
        </table>
    </div>
    <div class="panel-footer">

    </div>
</div>
