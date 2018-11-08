<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Journal;
use App\Platform;

class PlatformController extends Controller
{
    
    function edit(Request $req, $journal_id, $platform_id) {
        $j = Journal::find($journal_id);

        $data = ['journal' => $j,
        'journal_id' => $j->id,
        'platform_id' => $platform_id];
        return view('components.edit.platformjournal')->with($data);
    }

    function update(Request $req, $journal_id, $platform_id) {
        try {
            $j = Journal::find($journal_id);
            $p = Platform::find($platform_id);
            $j->platforms()->syncWithoutDetaching([$p->id => [
                'perpetual_access' => $req->input('perpetual_access'),
                'perpetual_access_coverage' => $req->input('perpetual_access_coverage'),
                'priority_package' => $req->input('priority_package'),
                'aggregator_platform' => $req->input('aggregator_platform'),
                'years' => $req->input('years'),
                'start_volume' => $req->input('start_volume'),
                'end_volume' => $req->input('end_volume'),
                'is_embargo' => $req->input('is_embargo'),
                'embargo_length' => $req->input('embargo_length'),
                'date_embargo_checked' => $req->input('date_embargo_checked'),
                'embargo_updated' => $req->input('embargo_updated'),
            ]]);
            return response()->json([
                'redirect' => route('journal', ['id' => $journal_id]), //App.library.dal.ca journal details
                'status' => 'success'
            ]);
        } catch(Exception $e) {
            return response()->json([
                'status' => 'fail',
                'message' => 'Your changes were not saved. Please check your input and try again.'
            ]);
        }
    }

    function delete(Request $req, $journal_id, $platform_id) {
        $j = Journal::find($journal_id);
        $p = Platform::find($platform_id);
        $j->platforms()->detach($p);
    }
}
