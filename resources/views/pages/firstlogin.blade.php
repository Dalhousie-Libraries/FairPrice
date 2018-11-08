@extends('layouts.app')

@section("title", "First Login Wizard")

@section('content')
    <div class="panel panel-danger hidden" id='error' style='width:100%;float:left'>
        <div class="panel-heading"><h4>Submit Failed<h4></div>
        <div class="panel-body">
            <p id='errormsg'></p>
        </div>
        <div class="panel-footer">
        </div>
    </div>

    <div class="panel panel-success hidden" id='success' style='width:100%;float:left'>
        <div class="panel-heading"><h4>Submit Success<h4></div>
        <div class="panel-body">
            <p id='successmsg'></p>
        </div>
        <div class="panel-footer">
        </div>
    </div>

    <div class="panel panel-default" style='width:100%;float:left'>
    <div class="panel-heading"><h4>Account Setup<h4></div>
    <div class="panel-body">
        <p>Before you can review journals and make recommendations, we need to know more about you.</p>
        <p>Use the dropdown lists to choose your faculty and department.</p>
        <p>You can choose just a faculty if you are not associated with a specific department or if your department is not listed in the dropdown list.</p>
        
            {{csrf_field()}}
            <userwizard posturl="{{route('user.wizard.submit')}}" prop_faculties='{{App\Faculty::all()}}' prop_departments='{{App\Department::all()}}' type='{{Auth::user()->type}}'></userwizard>

        
    </div>
    <div class="panel-footer">

    </div>
    </div>
@endsection