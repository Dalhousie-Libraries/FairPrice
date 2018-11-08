@extends('layouts.app')

@section("title", "Edit Journal" . $journal->journal_title)

@section('content')
        <div class='col-md-12'>
            <div class="panel panel-default">
                <div class="panel-heading">
                    @if(!$price)
                        <h4 style='float-left'><b>Create Price for Journal {{$journal->journal_title}}</b><a style='float:right' data-toggle='collapse' data-target='#comments-panel-body'>Show/Hide</a></h4>       
                    @else
                        <h4 style='float-left'><b>Edit {{$price->report_year}} Price for Journal {{$journal->journal_title}}</b><a style='float:right' data-toggle='collapse' data-target='#comments-panel-body'>Show/Hide</a></h4>       
                        
                    @endif
                </div>
                <div id='comments-panel-body' class="panel-body collapse in">
                        <?php 
                            $journal_id = $journal->id;
                        ?>
                    @if(!$price)
                        <editpriceform journal="{{$journal->id}}" href="{{route('price.create', [$journal->id])}}" current_price="{}" create='true'></editpriceform>      
                    @else
                        <?php $price_id = $price->id;?>
                        <editpriceform journal="{{$journal->id}}" href="{{route('price.save', [$journal_id, $price_id])}}" current_price="{{$price->toJson()}}"></editpriceform>      
                    @endif
                </div>
                <div class="panel-footer">
                    <table style='width:100%'>
                        <tr>
                            <td style='width:50%'><b>Created:   </b>{{$journal->created_at}}</td><td><b>Last Updated:   </b>{{$journal->updated_at}}</td>
                        </tr>
                    </table>
                    
                </div>
                
            </div>
        </div>
    
@endsection
