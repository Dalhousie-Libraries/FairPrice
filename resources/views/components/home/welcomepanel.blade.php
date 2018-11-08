
<div class="panel panel-default">
	<div class="panel-heading">Which Journals Should the Libraries Continue to Subscribe To?</div>
	<div class="panel-body">
		<p>The Dalhousie Libraries subscribe to thousands of journals. This year we are reviewing the titles we purchase in bundled subscriptions from Cambridge, Oxford, Sage, Springer, Taylor & Francis, and Wiley. </p>
		<p>Which of the journals under review are most important to your learning, teaching, and research?</p>
		<p>This database includes only the 7,165 titles from these publishers; it is not a comprehensive list of all Dalhousie subscriptions.</p>
		<p>You do not need to review all of the titles in this database, but we need your input about the journals associated with the subjects most important to you.</p>
		<p style='text-align: left'><a class='btn btn-success' href='{{route('journals')}}' role='button'>Start Your Review Here</a></p>
		<p>You can:</p>
		<ul>
			<li>Save your work in the database and return to it later</li>
			<li>Navigate the journal list by subject (download the full list <a target='_blank' href='{{route('staticsubjectlist')}}'>here</a>)</li>
			<li>Search by title or ISSN</li>
			<li>Include comments about specific journals</li>
			<li>Distinguish between journals that are 'crucial' and journals that are 'nice to have'</li>
			<li>Download <a target='_blank' href='{{route('staticjournallist')}}'>this Excel workbook</a> if you'd like to do some preparation offline</li>
		</ul>
		@if($election)
			<p><b>Don't forget to finalize and submit your recommendations by {{$election->end_date->format("F jS")}}. You can only submit recommendations ONCE.</b></p>
		@endif
		<p>Learn more about the journal assessment project <a target='_blank' href='{{route('about')}}'>here</a>.</p>
		<p style='text-align: left'><a class='btn btn-success' href='{{route('journals')}}' role='button'>Start Your Review Here</a></p>
		<p>Feedback about the project can be sent to <a href='mailto:Library.collections@dal.ca'>Library.Collections@dal.ca</a>.</p>
	</div>
</div>
    