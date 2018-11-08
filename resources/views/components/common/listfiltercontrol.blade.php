<div class='col-xs-12'>
    <div class="panel panel-default" style='width:100%;float:left'>
        <div class="panel panel-heading"><h4><b>Filter</b><a style='float:right' data-toggle='collapse' data-target='#filter-panel-body'>Show/Hide</a></h4></div>
        <div id='filter-panel-body' class="panel-body collapse in" style='width:100%'>
                <form id='filter-form' method='GET' action='{{Route(Route::currentRouteName())}}'>
                    <input type='hidden' id='page' value='1'/>
                    @if($report)
                        <input type='hidden' name='report' value='{{$report}}'/>
                    @endif
                    @if(isset($library))
                        <input type='hidden' name='library' value='{{$library}}'/>
                    @endif
                    <div class="form-group row" style="margin-bottom: 5px;">
                    <label class="col-sm-1 col-form-label" for='Subject'>Subject</label>
					<div class="form-group col-sm-4"  style="margin-bottom: 5px;">
						<select id='Subject' name='subject' style='text-overflow: ellipsis;' class="form-control">
							<option value=''>All</option>
							@foreach(App\Subject::all()->sortBy('subject') as $subject)
								@if($subject->subject == $filter_subject)
									<option value='{{$subject->subject}}' selected="selected">{{$subject->subject}}</option>
								@else
									<option value='{{$subject->subject}}'>{{$subject->subject}}</option>
								@endif
							@endforeach
						</select>
					</div>
                    </div>
                    <div class="form-group row" style="margin-bottom: 5px;">
                        <label class="col-sm-1 col-form-label" for='title'>Search</label>
						<div class="col-sm-4">
							<input type='text' name='term' value='{{$filter_term}}' class="form-control" ></input>
						</div>
                    </div>
                    <div class="form-group row" style="margin-bottom: 5px;">
                        <label class="col-sm-1 col-form-label" for='Platform' >Platform</label>
						<div class="form-group col-sm-4"  style="margin-bottom: 5px;">
							<select id='Platform' name='platform' class="form-control">
								<option value=''>All</option>
								@foreach(App\Platform::where('is_primary', 1)->get() as $platform)
									@if($platform->id == $filter_platform)
										<option value='{{$platform->id}}' selected="selected">{{$platform->name}}</option>
									@else
										<option value='{{$platform->id}}'>{{$platform->name}}</option>
									@endif
								@endforeach
							</select>
						</div>
                    </div>
                    @if(auth()->user()->role > 0)
                        <div class="form-group row" style="margin-bottom: 5px;">
                            <label class="col-sm-1 col-form-label" for='Faculty' >Faculty</label>
							<div class="form-group col-sm-4"  style="margin-bottom: 5px;">
								<select id='Faculty' name='faculty' class="form-control">
									<option value=''>All</option>
									@foreach(App\Faculty::all() as $faculty)
										@if($faculty->id == $filter_faculty)
											<option value='{{$faculty->id}}' selected="selected">{{$faculty->faculty_name}}</option>
										@else
											<option value='{{$faculty->id}}'>{{$faculty->faculty_name}}</option>
										@endif
									@endforeach
								</select>
							</div>
                        </div>
                        <div class="form-group row" style="margin-bottom: 5px;">
                            <div>
                                <label class="col-sm-1 col-form-label" for='Department'>Department</label>
								<div class="form-group col-sm-4"  style="margin-bottom: 5px;">
									<select id='Department' name='department' class="form-control">
										<option value=''>All</option>
										@foreach(App\Department::all() as $department)
											@if($department->id == $filter_department)
												<option value='{{$department->id}}' selected="selected">{{$department->department_name}}</option>
											@else
												<option value='{{$department->id}}'>{{$department->department_name}}</option>
											@endif
										@endforeach
									</select>
								</div>
                            </div>
                        </div>
                        <div class="form-group form-group row">
                            <div>
                                <label class="col-sm-1 col-form-label" for='Discipline'>Discipline</label>
								<div class="col-sm-4">
									<select id='Discipline' name='discipline' class="form-control">
									   
										<?php
											if(app('request')->input('discipline')) {
												$discipline = app('request')->input('discipline');
											} else {
												$discipline = "";
											}
										?>
										<option value=''>All</option>
										<option value='AH' {{ ('AH' == $discipline ? "selected":"") }}>Arts and Humanities</option>
										<option value='SS' {{ ('SS' == $discipline ? "selected":"") }}>Social Sciences</option>
										<option value='BM' {{ ('BM' == $discipline ? "selected":"") }}>Biomedical</option>
										<option value='NSE' {{ ('NSE' == $discipline ? "selected":"") }}>Natural Science and Engineering</option>
									</select>
								</div>
                            </div>
                        </div>
                    @endif
                    <div>
                        <button class='btn btn-primary btn-sm' type="submit" form='filter-form' value="Submit">Apply Filter</button>
                        @if($report)
                            <a class="btn btn-primary btn-sm" href='{{Route(Route::currentRouteName(), ["report" => $report])}}'>Clear Filter</a>
                        @else
                            <a class="btn btn-primary btn-sm" href='{{Route(Route::currentRouteName())}}'>Clear Filter</a>
                        @endif
                        
                    </div>
                </form>
        </div>
    </div>
</div>