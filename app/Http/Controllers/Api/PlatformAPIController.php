<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Platform;
use App\Journal;

class PlatformAPIController extends Controller
{
    function index() {
        return Platform::all();
    }

    function showwithjournal(Request $req, $platform_id, $journal_id) {
        if($platform_id != 0) {
        $id = $req->route('id');
        $p = Platform::find($platform_id)->journals()->where('journals.id', $journal_id)->first();
            if($p) {
                return $p;
            }
        }
    }

}

?>