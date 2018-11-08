<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Excel;
use DB;
use App\Journal as Journal;
use App\Faculty as Faculty;
use App\Department as Department;
use App\Platform as Platform;
use App\AlternateJournalTitle;
use Illuminate\Pagination\LengthAwarePaginator;

class JournalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $req)
    {
        $subject = !empty($req->input('subject')) ? $req->input('subject') : "";
        $term = !empty($req->input('term')) ? $req->input('term') : "";
        $alpha = $req->input('alpha', "");
        $platform = $req->input('platform', "");
        $faculty = $req->input('faculty', null);
        $department = $req->input('department', null);
        $page = $req->has('page') ? $req->input('page') : 1;
        $discipline = $req->input('discipline', null);
        $perPage = 20;
               
        $journals = new Journal;
        $query_string = "";
        if(!empty($subject)) {
            $journals = $journals->where('subject_1', 'like', $subject);
            if($query_string == "") {
                $query_string = "Subject: " . $subject;
            } else {
                $query_string .= ",Subject: " . $subject;
            }
        }
        
        if(!empty($discipline)) {
            $journals = $journals->where('domain', $discipline);
            if($query_string == "") {
                $query_string = "Discipline: " . $discipline;
            } else {
                $query_string .= ",Discipline: " . $discipline;
            }
        }

        if(!empty($faculty)) {
            $list = DB::table('faculty_journal')->where('faculty_id', $faculty)->pluck('journal_id')->all();
            $journals = $journals->whereIn('id', $list);
            if($query_string == "") {
                $query_string = "Faculty: " . Faculty::find($faculty)->faculty_name;
            } else {
                $query_string .= ",Faculty: " . Faculty::find($faculty)->faculty_name;
            }
        }

        if(!empty($department)) {
            $list = DB::table('department_journal')->where('department_id', $department)->pluck('journal_id')->all();
            $journals = $journals->whereIn('id', $list);
            if($query_string == "") {
                $query_string = "Department: " . Department::find($department)->department_name;
            } else {
                $query_string .= ",Department: " . Department::find($department)->department_name;
            }
        }

        if(!empty($term)) {
            $term = str_replace(" ", "%", $term);
            $journals = $journals->where('journal_title', 'like', "%" . $term . "%")
                                ->orWhere('p_issn', 'like', "%" . $term . "%")
                                ->orWhere('e_issn', 'like', "%" . $term . "%");
            if($query_string == "") {
                $query_string = "Search Term: " . $term;
            } else {
                $query_string .= ",Search Term: " . $term;
            }
        }
         
        if(!empty($alpha)) {
            if($alpha != "0-9") {
                $journals = $journals->where('journal_title', 'like', $alpha . "%");
            } else {
                $journals = $journals->where('journal_title', 'like', "0%")
                    ->orWhere('journal_title', 'like', "1%")
                    ->orWhere('journal_title', 'like', "2%")
                    ->orWhere('journal_title', 'like', "3%")
                    ->orWhere('journal_title', 'like', "4%")
                    ->orWhere('journal_title', 'like', "5%")
                    ->orWhere('journal_title', 'like', "6%")
                    ->orWhere('journal_title', 'like', "7%")
                    ->orWhere('journal_title', 'like', "8%")
                    ->orWhere('journal_title', 'like', "9%");
            }
            if($query_string == "") {
                $query_string = "Starting With: " . $alpha;
            } else {
                $query_string .= ",Starting With:  " . $alpha;
            }
        }

        if(!empty($platform)) {
            $journals = $journals->whereIn('id', function($query) use($platform) { 
                $query->select('journal_id')->from('platform_journal')->where('platform_id', $platform);
            });
            if($query_string == "") {
                $query_string = "Platform: " . Platform::find($platform)->name;
            } else {
                $query_string .= ",Platform: " . Platform::find($platform)->name;
            }
        }

        $req->session()->put('last_search_url', $req->fullUrl());
                
        $journals = $journals->orderBy('journal_title', 'asc');

        if($req->input('download')) {
            if($req->input('download') == 'xlsx') {
                Excel::create('journal_query_' . \Carbon\Carbon::now()->format('m-F-Y_his'), function($excel) use ($journals, $query_string, $req) {
                    
                        // Call writer methods here
                        $excel->sheet('Journals', function($sheet) use ($journals, $query_string, $req) {
                            
                                    $sheet->loadView('exports.journal.titlelist', ['journals' => $journals]);
        
                                });
                        $excel->sheet('Report Metadata', function($sheet) use ($journals, $query_string, $req) {
                            
                                    $sheet->loadView('exports.common.summary', 
                                        ['report_name' => 'Exported Journal Query List',
                                         'format_type' => 'Excel File (XLSX)',
                                         'record_count' => $journals->count(),
                                         'importable' => 'No',
                                         'query' => $query_string,
                                         'url' => $req->fullUrl()
                                            ]);
        
                                });
                        $excel->setActiveSheetIndex(0);
                    })->download('xlsx');
            } else {
                Excel::create('journal_query_' . \Carbon\Carbon::now()->format('m-F-Y_his'), function($excel) use ($journals, $query_string, $req) {
                        // Call writer methods here
                        $excel->sheet('Journals', function($sheet) use ($journals, $query_string, $req) {
                                    $sheet->loadView('exports.journal.titlelist', ['journals' => $journals]);
                                });
                    })->download('csv');
            }
        }

        $journals_paginated = $journals->paginate($perPage);
        $journals_paginated->withPath('journals/');
        $journals_paginated->appends(['subject' => $subject, 'term' => $term, 'platform' => $platform, 'alpha' => $alpha]);
        $data = array('journals' => $journals_paginated,
            'filter_subject' => $subject,
            'filter_platform' => $platform,
            'filter_term' => $req->input('term', ""),
            'alpha' => $alpha,
            'filter_department' => $department,
            'filter_faculty' => $faculty,
            'discipline' => $discipline,
        );
        
        return view('components.list.journals')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
        $j = Journal::find($id);
        
        $data = array('journal' => $j,
             'prices' => $j->prices()->orderBy('report_year', 'desc')->get()
        );
        
        return view('components.journal.showjournal')->with($data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(Auth::user()->role < 2) {
            return redirect()->route('home')->with('message', 'You are not authorized to edit this resource. If you feel this is an error, please contact an administrator.');
        }
        $j = Journal::find($id);
        $data = array('journal' => $j);
        
        return view('components.edit.journal')->with($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
        $j = Journal::find($id);
        $faculties = $request->input('faculties', null);
        $departments = $request->input('departments', null);
        $f_arr = [];
        $d_arr = [];
        foreach($faculties as $faculty) {
            $f_arr[] = $faculty['id'];
        }

        foreach($departments as $department) {
            $d_arr[] = $department['id'];
        }
        //Handle Alternate Title Creation here
        if ( $j->journal_title != $request->input('journal.journal_title')) {
                //Add current title as alternate title
                AlternateJournalTitle::firstOrCreate([
                    'journal_id' => $j->id,
                    'e_issn' => $j->p_issn,
                    'journal_title' => $j->journal_title,
                ]);
            }
        $j->journal_title = $request->input('journal.journal_title');
        $j->e_issn = $request->input('journal.p_issn');
        $j->p_issn = $request->input('journal.e_issn');
        
        $j->jup = $request->input('journal.jup');
        $j->doi = $request->input('journal.doi');
        $j->abbreviation = $request->input('journal.abbreviation');
        $j->proprietary_identifier = $request->input('journal.proprietary_identifier');
        $j->url = $request->input('journal.url');
        $j->subject_1 = $request->input('journal.subject_1');
        $j->subject_2 = $request->input('journal.subject_2');
        $j->subject_3 = $request->input('journal.subject_3');
        $j->subject_4 = $request->input('journal.subject_4');
        $j->user_subject = $request->input('journal.user_subject');
        $j->journal_status = $request->input('journal.journal_status');
        /*
        $j->faculty = $request->input('faculty');
        $j->departments = $request->input('departments');
        */


        $j->retained_by = $request->input('journal.retained_by');
        $j->libraries_holding_print = $request->input('journal.libraries_holding_print');
        $j->threshold_levels = $request->input('journal.threshold_levels');
        $j->comments = $request->input('journal.comments');
        $j->print_holdings = $request->input('journal.print_holdings');
        
        if(Auth::user()->isAdmin) {
            $j->fund = $request->input('journal.fund', $j->fund);
            $j->domain = $request->input('journal.domain', $j->domain);
            $j->is_priority = $request->input('journal.is_priority', $j->is_priority);
            $j->is_subscribed = $request->input('journal.is_subscribed', $j->is_subscribed);
            $j->is_recommendation = $request->input('journal.is_recommendation', $j->is_recommendation);
            $j->is_consultation = $request->input('journal.is_consultation', $j->is_consultation);
            $j->is_print_access = $request->input('journal.is_print_access', $j->is_print_access);
        }
        $j->save();
        $j->faculties()->sync($f_arr);
        $j->departments()->sync($d_arr);
        $j->save();

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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function comments(Request $request, $id)
    {
        try {
            if(Auth::user()->role < 2) {
                return redirect()->route('home')->with('message', 'You are not authorized to edit this resource. If you feel this is an error, please contact an administrator.');
            }
            $journal = Journal::find($id);
            $journal->comments = $request->input('comments', $journal->comments);
            $journal->save();
            return response()->json([
                'status' => 'success',
                'message' => 'Your comments have been saved successfully.'
            ]);
        } catch(Exception $e) {
            return response()->json([
                'status' => 'fail',
                'message' => 'Unable to update comments'
            ]);
        }
    }
}
