<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Price;
use Carbon\Carbon;

class PriceAPIController extends Controller
{
    
    function pricegraph(Request $request) {
        $id = $request->id;

        $prices = Price::where('journal_id', $id)
            ->orderBy('report_year', 'asc')
            ->get();
        $price_array = array();
        $date_array = array();
        $p = new \stdClass(); 
        $p->datasets = array();
        $p->labels = array();
        foreach ($prices as $price) {
            $single_point = new \stdClass();
            $date_array[] = Carbon::createFromDate($price->report_year, 1, 1)->timestamp;
            $price_array[] = $price->price;
        }
        $ds = new \stdClass(); 
        $ds->label = "Prices";
        $ds->backgroundColor="rgba(41, 163, 90, 255)";
        $ds->data = array();
        $ds->data = $price_array;

        $p->labels = $date_array;
        $p->datasets[] = $ds;
        return JSON_ENCODE($p);
    }
}
