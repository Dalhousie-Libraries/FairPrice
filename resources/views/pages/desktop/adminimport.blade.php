@extends('layouts.app')

@section("title", "Import From File")

@section('content')
    <div class='panel panel-default'>
        <div class='panel-heading'>
            <h1>Database Import</h1>
        </div>
        <div class='panel-body'>
            <p class='text-danger'>Warning: Direct import is designed for small batches of changes and is not a full backup facility. This
                script may require a great deal of time to execute with large data sets. Any changes made will be permenant
            </p>
            <form action="{{ route('import.old') }}" method="POST" enctype="multipart/form-data">
                {{ csrf_field() }}
                Excel File:
                <br />
                <input type="file" name="uploadedExcel" />
                <br /><br />
                <input type="submit" value="Save" />
            </form>
        </div>
        <div class='panel-footer'>
                
        </div>
    </div>
@endsection
