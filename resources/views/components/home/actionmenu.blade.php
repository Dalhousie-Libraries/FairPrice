
<div class="panel panel-default">
	<div class="panel-heading">Menu</div>
	<div class="panel-body">
		<h4>I would like to...</h4>
		<ul  class="custom-bullet" style="padding-left: 20px;">
		<li><a href='{{route('journals')}}'>Start Reviewing Journals</a></li>
		@if($election)
			@if(!$audit)
				@if(!$audit)
					<li><a href='{{ route('vote', ['election' => $election->id]) }}'>Review my Saved Recommendations</a></li>
					<li><a href='{{ route('vote', ['election' => $election->id, 'finalize' => 'true'])}}'>Finalize and Submit my Recommendations</a></li>
				@endif
			@endif
		@endif
		<li><a href='{{ route("staticjournallist")}}' target="_blank">Download the full journal list in Excel</a></li>
		<li><a href='{{ route("staticsubjectlist")}}' target="_blank">Download the full subject list</a></li>
		<li><a href='{{ route("about")}}' target="_blank">Read more about this project</a></li>
		@if(Auth::user()->role > 0)
			<li><a href="{{route('report')}}">Run Report</a></li>
		@endif
		@if(Auth::user())
		<li>     
			<a href="{{ route('logout') }}"
				onclick="event.preventDefault();
						document.getElementById('logout-form').submit();">
				Logout
			</a>
		</li>
		<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
			{{ csrf_field() }}
		</form>
		@endif
		</ul>
	</div>
</div>
