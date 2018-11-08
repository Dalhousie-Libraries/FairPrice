<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Journal as Journal;
use App\Vote as Vote;
use App\Library;
use Excel;

class ReportController extends Controller
{
    
    public function report(Request $request) {
        $request->session()->put('last_search_url', $request->fullUrl());
        $subject = $request->input('subject', '');
        $term = $request->input('term', '');
        $platform = $request->input('platform', '');
        $faculty = $request->input('faculty', '');
        $discipline = $request->input('discipline', null);
        $department = $request->input('department', '');
        
        $report = $request->input('report', '');
        $query_string = "";
        if(empty($report)) {
            return view('pages.reports.selectreport');
        }

        $j = new Journal;

        if(!empty($subject)) {
            $j = $j->where('subject_1', 'like', $subject);
        }        

        if(!empty($term)) {
            $term = str_replace(" ", "%", $term);
            $j = $j->where('journal_title', 'like', "%" . $term . "%")
                        ->orWhere('p_issn','like', "%" . $term . "%")
                        ->orWhere('e_issn', 'like', "%" . $term . "%");
        }
            
        if(!empty($platform)) {
            $j = $j->whereIn('id', function($query) use($platform) { 
                $query->select('journal_id')->from('platform_journal')->where('platform_id', $platform);
            });
        }

        if(!empty($faculty)) {
            $list = \DB::table('faculty_journal')->where('faculty_id', $faculty)->pluck('journal_id')->all();
            $j = $j->whereIn('id', $list);
        }

        if(!empty($department)) {
            $list = \DB::table('department_journal')->where('department_id', $department)->pluck('journal_id')->all();
            $j = $j->whereIn('id', $list);
        }

        if(!empty($discipline)) {
            $j = $j->where('domain', $discipline);
            if($query_string == "") {
                $query_string = "Discipline: " . $discipline;
            } else {
                $query_string .= ",Discipline: " . $discipline;
            }
        }
        if($report=='Perpetual') {
            return $this->getPerpetualAccessReport($request, $j);
        }

        if($report=='NoPerpetual') {
            return $this->getNoPerpetualAccessReport($request, $j);
        }
        
        if($report=='UndefinedPerpetual') {
            return $this->getUndefinedPerpetualAccessReport($request, $j);
        }

        if($report=='NoPrice') {
            return $this->getNoPriceReport($request, $j);
        }

        if($report=='NoVote') {
            return $this->getNoVoteReport($request, $j);
        }
        
        if($report=='ByVote') {
            return $this->getJournalByVoteReport($request, $j);
        }

        if($report=='ByNonVote') {
            return $this->getJournalByNonVoteReport($request, $j);
        }
        
        if($report=='Retained') {
            return $this->byRetainedLibraryReport($request, $j);
        }
    }

    
    public function getJournalByVoteReport(Request $req, $j)
    {
        
        $j = $j->withCount(['votes as needed_votes' => function($query) {
            $query->where('vote', '2');
        }, 'votes as nice_votes' => function($query) {
            $query->where('vote', 1);
        }, 'votes as no_votes' => function($query) {
            $query->where('vote', 0);
        }]);
        $list = Vote::groupBy('journal_id')->havingRaw('COUNT(*) > 0')->pluck('journal_id');
        $j = $j->whereIn('id', $list);

        $headers = collect(["Electronic ISSN", "Print ISSN", "Name", "# Votes (Needed)", "# Votes (Nice To Have)", "# Votes (Not Needed)"]);
        $report_name = "Journals By Vote Report";
        $filename = "Journals_By_Vote";

        $j = $j->orderByRaw("needed_votes DESC, nice_votes DESC, journal_title ASC");
        if($req->input('download', null)) {
            $data = [];
            foreach($j->get() as $journal) {
                $data[] = [$journal->e_issn, $journal->p_issn, $journal->journal_title, $journal->needed_votes, $journal->nice_votes, $journal->no_votes];
            }
            $this->exportReport($req, $report_name, $filename, $headers, $data, "");
        } else {
            return $this->generateReport($req, $j, 'pages.reports.byvotereport', 'Journals By Needed/Wanted Count');
        }
    }

    public function getJournalByNonVoteReport(Request $req, $j)
    {
        $j = $j->whereHas('votes', function ($query) {
                $query->where('vote', 0);
            })
            ->withCount(['votes as needed_votes' => function($query) {
            $query->where('vote', '2');
        }, 'votes as nice_votes' => function($query) {
            $query->where('vote', 1);
        }, 'votes as no_votes' => function($query) {
            $query->where('vote', 0);
        }]);
        $list = Vote::where('vote', 0)->groupBy('journal_id')->havingRaw('COUNT(*) > 0')->pluck('journal_id');
        $j = $j->whereIn('id', $list);
        $j = $j->orderByRaw("no_votes DESC, journal_title ASC");

        $headers = collect(["Electronic ISSN", "Print ISSN", "Name", "# Votes (Needed)", "# Votes (Nice To Have)", "# Votes (Not Needed)"]);
        $report_name = "Journals By Not Needed Recommendation Report";
        $filename = "Journals_By_Not_Needed_Recommendation";
        
        if($req->input('download', null)) {
            $data = [];
            foreach($j->get() as $journal) {
                $data[] = [$journal->e_issn, $journal->p_issn, $journal->journal_title, $journal->needed_votes, $journal->nice_votes, $journal->no_votes];
            }

            $this->exportReport($req, $report_name, $filename, $headers, $data, "");
        } else {        
            return $this->generateReport($req, $j, 'pages.reports.byvotereport', 'Journals by Highest Not Needed Recommendation Count');
        }
    }

    public function getNoVoteReport(Request $req, $j)
    {
        $j = $j->whereDoesntHave('votes');
        $j->orderBy('journal_title', 'asc');
        
        $headers = collect(["Electronic ISSN", "Print ISSN", "Name"]);
        $report_name = "Journals Without Recommendations";
        $filename = "Journals_Without_Recommendations";
        
        if($req->input('download', null)) {
            $data = [];
            foreach($j->get() as $journal) {
                $data[] = [$journal->e_issn, $journal->p_issn, $journal->journal_title];
            }
            $this->exportReport($req, $report_name, $filename, $headers, $data, "");
        } else {      
            return $this->generateReport($req, $j, 'pages.reports.novotereport', 'Journals Without Recommendations');
        }
    }


    public function getUndefinedPerpetualAccessReport(Request $req, $j)
    {
        $j = $j->orderBy('journal_title', 'asc');
        $j = $j->whereHas('platforms', function ($q) {
            $q->where('perpetual_access', null);
        })->with(['platforms' =>  function ($q) {
            $q->where('perpetual_access', null);
        }]);

        $headers = collect(["Electronic ISSN", "Print ISSN", "Name"]);
        $report_name = "Journals Missing Perpetual Access Data";
        $filename = "Journals_Missing_Perpetual_Access Data";
        
        if($req->input('download', null)) {
            $data = [];
            foreach($j->get() as $journal) {
                $data[] = [$journal->e_issn, $journal->p_issn, $journal->journal_title];
            }
            $this->exportReport($req, $report_name, $filename, $headers, $data, "");
        } else {      
            return $this->generateReport($req, $j, 'pages.reports.noperpetualaccessreport', 'Journals With Missing Perpetual Access Data');
        }
    }

    public function getNoPerpetualAccessReport(Request $req, $j)
    {
        $j = $j->orderBy('journal_title', 'asc');
        $j = $j->whereDoesntHave('platforms', function ($q) {
            $q->where('perpetual_access', 1);
        });
        
        $headers = collect(["Electronic ISSN", "Print ISSN", "Name"]);
        $report_name = "Journals Without Perpetual Access";
        $filename = "Journals_Without_Perpetual_Access";
        
        if($req->input('download', null)) {
            $data = [];
            foreach($j->get() as $journal) {
                $data[] = [$journal->e_issn, $journal->p_issn, $journal->journal_title];
            }

            $this->exportReport($req, $report_name, $filename, $headers, $data, "");
        } else { 
            return $this->generateReport($req, $j, 'pages.reports.noperpetualaccessreport', 'Journals Without Perpetual Access Coverage');
        }
    }

    public function getPerpetualAccessReport(Request $req, $j)
    {
        $j = $j->orderBy('journal_title', 'asc');
        $j = $j->whereHas('platforms', function ($q) {
            $q->where('perpetual_access', 1);
        })->with(['platforms' =>  function ($q) {
            $q->where('perpetual_access', 1);
        }]);

        $headers = collect(["Electronic ISSN", "Print ISSN", "Name", "Platform", "Perpetual Access Coverage"]);
        $report_name = "Journals With Perpetual Access";
        $filename = "Journals_With_Perpetual_Access";
        
        if($req->input('download', null)) {
            $data = [];
            foreach($j->get() as $journal) {
                $data[] = [$journal->e_issn, $journal->p_issn, $journal->journal_title, $journal->platforms()->first()->name, $journal->platforms()->first()->pivot->perpetual_access_coverage];
            }
            $this->exportReport($req, $report_name, $filename, $headers, $data, "");
        } else { 
            return $this->generateReport($req, $j, 'pages.reports.perpetualaccessreport', 'Journals With Perpetual Access Coverage');
        }
    }

    public function getNoPriceReport(Request $req, $j)
    {
        $j = $j->orderBy('journal_title', 'asc');
        $j = $j->whereDoesntHave('prices');

        $headers = collect(["Electronic ISSN", "Print ISSN", "Name"]);
        $report_name = "Journals Without Price Data";
        $filename = "Journals_Without_Price_Data";
        
        if($req->input('download', null)) {
            $data = [];
            foreach($j->get() as $journal) {
                $data[] = [$journal->e_issn, $journal->p_issn, $journal->journal_title];
            }
            $this->exportReport($req, $report_name, $filename, $headers, $data, "");
        } else { 
            return $this->generateReport($req, $j, 'pages.reports.nopricereport', "Journals Missing Price Data");
        }
    }

    public function byRetainedLibraryReport(Request $req, $j)
    {
        $j = $j->orderBy('journal_title', 'asc');
        $library_bit = $req->input('library', null);
        if($library_bit == null) {
            $data = [
                'title' => "Select a Library",
                'report' => $req->input('report'),
            ];
            return view('pages.reports.selectlibrary')->with($data);
        }
        if($library_bit != 0) {
            $library = Library::where('bit_value', $library_bit)->first();
            if(!$library_bit || !$library) {
                $data = array('journals' => $j,
                'title' => "Report: Journals With Perpetual Access",
                'report' => $req->input('report'),
                'filter_subject' => $req->input('subject', ''),
                'filter_term' => $req->input('term', ''),
                'filter_platform' => $req->input('platform', ''),
                'filter_faculty' => $req->input('faculty', ''),
                'filter_department' => $req->input('department', ''),
                'alpha' => $req->input('alpha', ''),
                'src_page' => $req->url(),
                );
                redirect('pages.reports.selectlibrary')->with($data);
            }
            $j = $j->where('retained_by', '>', 0)->whereRaw("(retained_by % $library_bit) = 0");

            $headers = collect(["Electronic ISSN", "Print ISSN", "Name"]);
            $report_name = "Journals Retained By " . $library->library_name;
            $filename = "Journals_Retained_By_". $library->library_name;
            
            if($req->input('download', null)) {
                $data = [];
                foreach($j->get() as $journal) {
                    $data[] = [$journal->e_issn, $journal->p_issn, $journal->journal_title];
                }
                $this->exportReport($req, $report_name, $filename, $headers, $data, "");
            } else { 
                return $this->generateReport($req, $j, 'pages.reports.retained', "Journals Retained By " . $library->library_name);
            }
        } else {
            $libraries = Library::all();
            $header_arr = ["Electronic ISSN", "Print ISSN", "Name"];
            foreach($libraries as $library) {
                $header_arr[] = $library->library_name;
            }
            
            $headers = collect($header_arr);
            $report_name = "Journals Retained By " . $library->library_name;
            $filename = "Journals_Retained_By_". $library->library_name;
            
            if($req->input('download', null)) {
                $data = [];

                foreach($j->get() as $journal) {
                    $data_row = [$journal->e_issn, $journal->p_issn, $journal->journal_title];
                    foreach($libraries as $library) {
                        if($journal->retained_by == null || $journal->retained_by == 0) {
                            $data_row[] = "No";
                        } else {
                            if($journal->retained_by % $library->bit_value == 0)  {
                                $data_row[] = "Yes";
                            } else {
                                $data_row[] = "No";
                            }
                        }
                    }
                    $data[] = $data_row;
                }
                $this->exportReport($req, $report_name, $filename, $headers, $data, "");
            } else { 
                return $this->generateReport($req, $j, 'pages.reports.retainedall', "Journals Retained By Library");
            }
        }
    }

    public function generateReport(Request $req, $j, $report_view, $title)
    {
        $j = $j->paginate(50)->appends([
            'report' => $req->input('report'),
            'subject' => $req->input('subject', null),
            'term' => $req->input('term', null),
            'platform' => $req->input('platform', null),
            'faculty' => $req->input('faculty', null),
            'alpha' => $req->input('alpha', null),
            'department' => $req->input('department', null),
            'discipline' => $req->input('discipline', null),
            'library' => $req->input('library', null)
        ]);
        $data = array('journals' => $j,
            'title' => $title,
            'report' => $req->input('report'),
            'filter_subject' => $req->input('subject', null),
            'filter_term' => $req->input('term', null),
            'filter_platform' => $req->input('platform', null),
            'filter_faculty' => $req->input('faculty', null),
            'filter_department' => $req->input('department', null),
            'alpha' => $req->input('alpha', null),
            'library' => $req->input('library', null),
            'discipline' => $req->input('discipline', null),
            'src_page' => $req->url()
        );
        return view($report_view)->with($data);
    }

    public function exportReport(Request $req, $report_name, $filename, $headers, $data, $query_string) {
        $format = $req->input('download', 'xlsx');
        Excel::create('report_' . $filename . "_" . \Carbon\Carbon::now()->format('m-F-Y_his'), function($excel) use ($req, $report_name, $headers, $data, $query_string, $format) {
            // Call writer methods here
            $excel->sheet('Report', function($sheet) use ($req, $report_name, $headers, $data, $query_string) {
                $sheet->loadView('exports.report.generalreport', ['report_title' => $report_name, 'headers' => $headers,'journals' => $data]);
            });
            $excel->sheet('Report Metadata', function($sheet) use ($req, $report_name, $headers, $data, $query_string, $format) {
                $sheet->loadView('exports.common.summary', 
                    ['report_name' => $report_name,
                    'format_type' => $format,
                    'record_count' => count($data),
                    'importable' => 'No',
                    'query' => $query_string,
                    'url' => $req->fullUrl()
                        ]);
            });
            $excel->setActiveSheetIndex(0);
        })->download($format);
    }
}
