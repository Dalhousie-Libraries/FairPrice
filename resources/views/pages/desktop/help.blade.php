@extends('layouts.app')

@section("title", "Dalhousie Journal Assessment Database")

@section('content')


<div class='col-md-10 col-md-offset-1'>




    <div class="row">
   
    </div>

    <div class="row">
		<div class="col-md-3">
	<div class="panel panel-default">
	<div class="panel-heading">Menu</div>
	<div class="panel-body">
		<h4>I would like to...</h4>
		<ul  class="custom-bullet" style="padding-left: 20px;">
		<li><a href="{{route('journals')}}">Start Reviewing Journals</a></li>
		<li><a href="{{route('helppdf')}}">Help PDF</a></li>
		

		</ul>
	</div>
</div>

		</div>
		<div class="col-md-9">
		
		<div class="panel panel-default">
	<div class="panel-heading">How to use Fairprice</div>
	<div class="panel-body">
			<style>
	html, body {
		margin: 0px;
		padding: 0px;
		font-family:Verdana, Geneva, sans-serif;
		background-color: #1a1a1a;
		text-align: center;
		width: 100%;
		height: 100%; 
	}

	</style>

	<link href="https://libcasts.library.dal.ca/Fairprice/fairprice-FINAL-2_embed.css" rel="stylesheet" type="text/css">
	<iframe class="tscplayer_inline" id="embeddedSmartPlayerInstance" src="https://libcasts.library.dal.ca/Fairprice/fairprice-FINAL-2_player.html?embedIFrameId=embeddedSmartPlayerInstance" scrolling="no" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>
		</div>
	</div>
	
	</div>
    </div>

    <div class="row">




    

    </div>    
</div>



@endsection