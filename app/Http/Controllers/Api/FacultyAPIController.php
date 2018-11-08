<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Faculty;


class FacultyAPIController extends Controller
{
    function faculty(Request $request) {
        $out = new \Stdclass();
        $faculties = \DB::table('faculties')
                        ->select('faculty_id')
                        ->groupBy('faculty_id')
                        ->get();
        
        
        foreach($faculties as $faculty)
        {
            $faculty->name = Faculty::where('faculty_id', $faculty->faculty_id)->first()->faculty_name;
            $faculty->departments = Faculty::where('faculty_id', $faculty->faculty_id)->get(['department_bit', 'department_name'])->toArray();
        }
        $out = $faculties;

        return JSON_ENCODE($out);
    }
}
