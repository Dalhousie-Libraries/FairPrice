<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Library;

class LibraryAPIController extends Controller
{
    function index() {
        return Library::all();
    }
}

?>