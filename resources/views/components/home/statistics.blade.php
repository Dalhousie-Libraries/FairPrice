
<div class="panel panel-default">
	<div class="panel-heading">Statistics</div>
	<div class="panel-body">
		<ul  class="custom-bullet" style="padding-left: 20px;">
		<li>There are <b>{{App\Journal::count()}}</b> journals in the database.<br/></li>
		<li><b>{{App\Vote::distinct('user_id')->where('is_submitted',1)->count('user_id')}}</b> people have made <b>{{App\Vote::where('is_submitted',1)->count()}}</b> recommendation(s).</li>
	</div>
</div>

