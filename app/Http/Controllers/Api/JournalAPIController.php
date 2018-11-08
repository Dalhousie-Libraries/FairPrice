<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Journal;
use App\Subject;
use Adldap;
use Auth;

class JournalAPIController extends Controller
{
    function index(Request $req) {
        $subject = $req->input('subject');
        $term = $req->input('term');
        $j = Journal::where('journal_title', 'like', '%' . $term . '%');
        
        if(!empty($subject)) {
            $j = $j->where('subject_1', 'like', $subject . "%");
        }
        $j = $j->orderBy('journal_title', 'asc');
        
        return $j->paginate(20);
    }

    function subjects(Request $req) {
        return \App\Subject::all();
    }

    function show(Request $req) {
        $id = $req->route('id');
        return Journal::find($id);
    }

    function prices(Request $req, $journal_id) {
        return Journal::find($journal_id)->prices()->all();
    }

    function search(Request $req) {
        $subject = $req->route('subject');
        $term = $req->route('term');
        $j = Journal;
        if(!empty($subject)) {
            $j->where('subject_1', $subject);
        }

        if(!empty($term)) {
            $j->where('journal_title', 'like', '%' . $subject . '%');
        }
    }
}
