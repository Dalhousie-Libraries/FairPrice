<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Download;
use Carbon\Carbon;
class DownloadAPIController extends Controller
{
    function downloadGraph(Request $request) {
        $id = $request->id;

        $downloads = Download::where('journal_id', $id)
            ->orderBy('report_year', 'asc')
            ->get();

        $download_array = array();
        $date_array = array();
        $d = new \stdClass(); 
        $d->datasets = array();
        $d->labels = array();
        foreach ($downloads as $download) {
            $single_point = new \stdClass();
            $date_array[] = Carbon::parse($download->report_year)->timestamp;
            $download_array[] = $download->downloads_reported;
        }
        $ds = new \stdClass(); 
        $ds->label = "Downloads / Year";
        $ds->backgroundColor="rgba(41, 163, 90, 255)";
        $ds->data = array();
        $ds->data = $download_array;
        
        
        $p->labels = $date_array;
        $p->datasets[] = $ds;


        
        
        return JSON_ENCODE($p);

    }
}
