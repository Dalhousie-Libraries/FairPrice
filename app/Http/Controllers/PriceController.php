<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Price;
use App\Journal;
use Auth;

class PriceController extends Controller
{
    
    function indexforjournal(Request $req, $journal_id) {
        $j = Journal::find($journal_id);   
        $data = array('journal' => $j,
            'prices' => $j->prices
        );
   
        return view('components.list.prices')->with($data);
    }

    function edit(Request $req, $journal_id, $price_id) {
        $j = Journal::find($journal_id);   
        $p = Price::find($price_id);
        $data = array('journal' => $j,
            'price' => $p,
        );
   
        return view('components.edit.price')->with($data);
    }

    function create(Request $request, $journal_id) {
        try {
            if(Auth::user()->role < 2) {
                return redirect()->route('home')->with('message', 'You are not authorized to edit this resource. If you feel this is an error, please contact an administrator.');
            }
            $p = new Price;
            
            $p->report_year = $request->input('report_year');
            $p->journal_id = $request->input('journal_id');
            $p->price = $request->input('price_value');
            $p->currency = $request->input('currency');
            $p->cost_per_use = $request->input('cost_per_use');
            $p->adjusted_cost_per_use = $request->input('adjusted_cost_per_use');
            
            if (!is_numeric($p->report_year)) {
                return response()->json([
                    'status' => 'fail',
                    'message' => 'Error: The year field must be a valid year.  Please check your input and try again.'
                ]); 
            }

            if (!is_numeric($p->price)) {
                return response()->json([
                    'status' => 'fail',
                    'message' => 'Error: The price field must be a number. Please check your input and try again.'
                ]); 
            }

            $p->save();

            return response()->json([
                'redirect' => route('journal', ['id' => $id]), //App.library.dal.ca journal details
                'status' => 'success'
            ]);
        } catch(Exception $e) {
            return response()->json([
                'status' => 'fail',
                'message' => 'Your changes were not saved. Please check your input and try again.'
            ]);
        }
    }

    function update(Request $request, $journal_id, $price_id) {
        try {
            if(Auth::user()->role < 2) {
                return redirect()->route('home')->with('message', 'You are not authorized to edit this resource. If you feel this is an error, please contact an administrator.');
            }
            $p = Price::find($price_id);
            $p->price = $request->input('price_value');
            $p->journal_id = $request->input('journal_id');
            $p->currency = $request->input('currency');
            $p->cost_per_use = $request->input('cost_per_use');
            $p->adjusted_cost_per_use = $request->input('adjusted_cost_per_use');

                        
            if (!is_numeric($p->report_year)) {
                return response()->json([
                    'status' => 'fail',
                    'message' => 'Error: The year field must be a valid year.  Please check your input and try again.'
                ]); 
            }

            if (!is_numeric($p->price)) {
                return response()->json([
                    'status' => 'fail',
                    'message' => 'Error: The price field must be a number. Please check your input and try again.'
                ]); 
            }

            $p->save();
            return response()->json([
                'redirect' => route('journal', ['id' => $id]), //App.library.dal.ca journal details
                'status' => 'success'
            ]);
        } catch(Exception $e) {
            return response()->json([
                'status' => 'fail',
                'message' => 'Your changes were not saved. Please check your input and try again.'
            ]);
        }
    }

    function delete(Request $req, $journal_id, $price_id) {
        if(Auth::user()->role < 2) {
            return redirect()->route('home')->with('message', 'You are not authorized to edit this resource. If you feel this is an error, please contact an administrator.');
        }
        $p = Price::find($price_id);
        if($p) {
            $p->delete();
        }
        return redirect()->route('journal.pricelist',[$journal_id]);
    }
}
