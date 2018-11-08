<?php 
    $letters = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J',
                'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T',
                'U', 'V', 'W', 'X', 'Y', 'Z', '0-9'];
    $parameters = request()->input();
    $form_letter = request()->input('alpha');
?>
<h4>
@if($form_letter == null)
    <b>All</b> {{"|"}}
@else
    <a href='{{Route(Route::currentRouteName(), array_merge($parameters, ["alpha" => ""]))}}'>All</a> {{"|"}}
@endif
@foreach($letters as $letter)
    @if($letter == $form_letter)
        <b>{{$letter}}</b>
    @else
        <a href='{{Route(Route::currentRouteName(), array_merge($parameters, ["alpha" => $letter]))}}'>{{$letter}}</a>
    @endif
    @if(!$loop->last)
        {{"|"}}
    @endif
@endforeach
</h4>