<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function getWizard() {
        return view('pages.firstlogin');
    }

    public function submit(Request $request) {
        $faculty_id = $request->input('faculty', '');
        $department_id = $request->input('department', null);
        if($faculty_id != "") {
            $user = auth()->user();
            $user->faculty_id = $faculty_id;
            $user->department_id = $department_id;
            $user->save();
            

            return response()->json([
                'result' => true,
                'message' => 'Your choices were submitted successfully. Please wait while we redirect you to the homepage.'
            ]);

        }
        return response()->json([
            'result' => false,
            'message' => 'An error was encountered while recording your choices. Please check your input and try again.'
        ]);

    }

}
