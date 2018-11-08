<?php

namespace App\Http\Controllers; 

use Illuminate\Http\Request;
use App\Vote;
use App\Journal;
use App\Platform;
use App\Faculty;
use App\Department;
use App\Election;
use Excel;

class ExportController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function export(Request $request)
    {
        Excel::create('Export', function($excel) use ($request){
            if($request->input('type') == "journals") {
                $excel->sheet('Journals', function($sheet) {
                    $sheet->fromModel(App\Journal::all());
                });
            }
            if($request->input('type') == "journals_and_relationships") {
                $excel->sheet('Journals', function($sheet) {
                    $sheet->fromModel(App\Journal::all());
                });
                $excel->sheet('Platforms', function($sheet) {
                    $sheet->fromModel(App\Platform::all());
                });
                $excel->sheet('Faculties', function($sheet) {
                    $sheet->fromModel(App\Faculty::all());
                });
                $excel->sheet('Departments', function($sheet) {
                    $sheet->fromModel(App\Department::all());
                });
                $excel->sheet('Subjects', function($sheet) {
                    $sheet->fromModel(App\Subject::all());
                });
            }
        })->download('xlsx');
    }

    public function voteExportIndex(Request $request) {
        return view('pages.desktop.exportVotes');
    }

    public function exportVotes(Request $request)
    {
		ini_set('max_execution_time', -1);
		
        $format = $request->input('format', 'xlsx');
        $filter_platform = $request->input('platform', ''); //All platforms, or only one platform
        $filter_role = $request->input('role', ''); //All, Faculty, Student, or Staff
        $filter_faculty = $request->input('faculty', ''); //Filter by specific faculty
        $filter_department = $request->input('department', ''); //Filter by specific department
        $filter_vote = $request->input('vote', ''); //Filter by specific vote (not needed/wanted/needed)
        $filter_election = $request->input('election', ''); //Filter by specific department
        $by_platform = $request->input('by_platform', false);
		$filter_submitted = $request->input('submitted', false);

        $votes = Vote::where('election_id', $filter_election);
        $query_string = "";
        if($filter_platform != '') {
            $platform = Platform::find($filter_platform);
            $journals = $platform->journals->pluck('id');
            $votes = $votes->whereIn('journal_id', $journals);
            $query_string .= "Filter by Platform: " . $platform->name . "\n";
        }

        if($filter_role != '') {
            $votes = $votes->where('type', $filter_role);
            if($filter_role == 1) $role = "Student";
            if($filter_role == 3) $role = "Faculty";
            if($filter_role == 2) $role = "Staff";
            $query_string .= "Filter by Role: " . $role . "\n";
        }

        if($filter_faculty != '') {
            $votes = $votes->where('faculty', $filter_faculty);
            $query_string .= "Filter by Faculty:" . Faculty::find($filter_faculty)->faculty_name . "\n";
        }

        if($filter_department != '') {
            $votes = $votes->where('department', $filter_department);
            $query_string .= "Filter by Department:" . Department::find($filter_department)->department_name . "\n";
        }

        if($filter_vote != '') {
            $votes = $votes->where('vote', $filter_vote);
            if($filter_vote == 0) $v = "Not Needed";
            if($filter_vote == 1) $v = "Wanted";
            if($filter_vote == 2) $v = "Needed";
            $query_string .= "Filter by Vote:" . $v . "\n";
        }
        
        if($filter_submitted != '') {
            if($filter_submitted == "yes"){
				$submitted = "Include unsubmitted votes";
				// Do not need to filter on submitted
			}
            elseif($filter_submitted == "no"){
				$submitted = "Only include submitted votes";
				$votes = $votes->where('is_submitted', 1);
			}
            elseif($filter_submitted == "unsubmitted_only"){
				$submitted = "Only innclude unsubmitted votes";
				$votes = $votes->where('is_submitted', 0);
			}
			$query_string .= "Filter by Submitted:" . $submitted . "\n";
        }
		
        if($by_platform == 'false' || $by_platform == false) {
            Excel::create('Export_(AllVotes)_' . \Carbon\Carbon::now()->format('m-F-Y_his'), function($excel) use ($request, $votes, $query_string){
                // Call writer methods here
                $excel->sheet('Votes', function($sheet) use ($request, $votes, $query_string) {
                        $votes = $votes->with('journal', 'journal.platforms')->get();
                            $sheet->loadView('exports.vote.allvotes', ['votes' => $votes, 'platforms' => Platform::with('journals')->get()]);

                });
                $excel->sheet('Journal Summary', function($sheet) use ($request, $votes, $query_string) {
                    $votes = $votes->with('journal', 'journal.platforms')->get();
                        $sheet->loadView('exports.vote.byjournal', ['votes' => $votes]);

                });
                $excel->sheet('Report Metadata', function($sheet) use ($votes, $query_string, $request) {
                    
                            $sheet->loadView('exports.common.summary', 
                                ['report_name' => 'Vote Export (Ungrouped)',
                                 'format_type' => 'Excel File (XLSX)',
                                 'record_count' => $votes->count(),
                                 'importable' => 'No',
                                 'query' => $query_string,
                                 'url' => $request->fullUrl()
                                    ]);

                        });
                $excel->setActiveSheetIndex(0);
                
            })->download($format);
        } else {
            Excel::create('Export_(VoteByPlatform)_' . \Carbon\Carbon::now()->format('m-F-Y_his'), function($excel) use ($request, $votes, $query_string){
                // Call writer methods here
                foreach(Platform::where('is_primary', 1)->with('journals')->get() as $platform) {
                    $list = $platform->journals->pluck('id');
                    $currentvotes = clone $votes;
                    $currentvotes = $currentvotes->whereIn('journal_id', $list)->with('journal', 'journal.platforms')->get();
                $excel->sheet($platform->name, function($sheet) use ($request, $platform, $currentvotes, $query_string) {
                        $sheet->loadView('exports.vote.votes_for_platform', ['votes' => $currentvotes, 'platform' => $platform]);
                        });
                $excel->sheet('Summary ' . $platform->name, function($sheet) use ($request, $platform, $currentvotes, $query_string) {
                        $sheet->loadView('exports.vote.byjournal', ['votes' => $currentvotes]);
                        });
                }
                
                $excel->sheet('Report Metadata', function($sheet) use ($votes, $query_string, $request) {
                    
                            $sheet->loadView('exports.common.summary', 
                                ['report_name' => 'Vote Export (Ungrouped)',
                                 'format_type' => 'Excel File (XLSX)',
                                 'record_count' => $votes->count(),
                                 'importable' => 'No',
                                 'query' => $query_string,
                                 'url' => $request->fullUrl()
                                    ]);

                        });
                $excel->setActiveSheetIndex(0);
                
            })->download($format);
        }
    }
}
