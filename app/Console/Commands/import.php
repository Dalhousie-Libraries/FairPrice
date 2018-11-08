<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Excel;
Use App\Journal;
Use App\Platform;
Use App\Faculty;
Use App\Department;
Use App\Download;
Use App\HistoricalChoice;
Use App\Price;
Use App\Citation;
Use App\AlternateJournalTitle;

class import extends Command
{
    function getPerpetualAccessValue($row) { 
        
            if ($row->{"post_cancellation_rights"} == "yes") {
                return 1;
            }
            if ($row->{"post_cancellation_rights"} == "no") {
                return 0;
            }
            return null;
    }
    
    function getEmbargoValue($value) {
        if (strtolower($value) == "yes") {
            return 1;
        }
        if (strtolower($value) == 'no') {
            return 0;
        }
        return null;
    }

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import data from excel file';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        
        $platforms = Platform::all();
        echo("Opening file\n");
        Excel::load('live_data.xlsx', function($reader) use ($platforms){
        // Loop through all sheets
            echo("Begin import\n");
            $reader->each(function($sheet) use ($platforms) {
                echo("Reading sheet\n");
                // Loop through all rows
                $i = 1;
                $sheet->each(function($row) use ($platforms, $i) {
                    echo "Now reading item $row->index\n";
                    $i = $i + 1;
                    if(!empty($row->title)) {
                        
                        $j = Journal::firstOrNew(['e_issn' => $row->online_issn,
                            'p_issn' => $row->print_issn,
                            'jup' => $row->jup_id]);
                        
                        $j->journal_title = $row->title;
                        if(is_numeric($row->jup_id)) {
                            $j->jup = $row->jup_id;
                        } else {
                            $j->jup = null;
                        }

                        if($row->journal_doi) {
                            $j->doi = $row->journal_doi;
                        } else {
                            $j->doi = null;
                        }
                        $j->url = $row->url;
                        $j->proprietary_identifier = $row->proprietary_identifier;
                        $j->fund = $row->fund;
                        $j->domain = $row->discipline;
                        $j->journal_status = $row->journal_status_oa_transferring_ceased;
                        $j->subject_1 = $row->subject_1;
                        $j->subject_2 = $row->subject_2;
                        $j->subject_3 = $row->subject_3;
                        $j->subject_4 = $row->subject_4;
                        $j->comments = $row->general_notes;

                        if($row->meets_preliminary_priority_criteria_for_consultation == "no") {
                            $j->is_priority = 0;
                        } else {
                            $j->is_priority = 1;
                        }

                        $j->save();

                        

                        $h = HistoricalChoice::firstOrNew([
                            'journal_id' => $j->id,
                            'subscription_year' => 2017
                        ]);
                        $h->save();


                        $primary_platform = Platform::where('name', $row->package)->first();                        
                        $j->platforms()->attach($primary_platform, [
                            'perpetual_access' => $this->getPerpetualAccessValue($row), 
                            'perpetual_access_coverage' => $row->{"post_cancellation_rights_start_with"},
                            'years' => $row->{"package_coverage"},
                            'start_volume' => null,
                            'end_volume' => null,
                            'is_embargo' => false,
                            'embargo_length' => null,
                            'embargo_updated' => null,
                            'date_embargo_checked' => null,
                        ]);
                        
                        $import_platform_names = [
                            "cambridge" => "cambridge",
                            "oxford" => "oxford",
                            "sage" => "sage",
                            "springer" => "springer",
                            "taylor & francis" => "taylor_francis",
                            "wiley" => "wiley",
                            "abi inform global" => "abi_inform_global",
                            "academic search premier" => "academic_search_premier",
                            "acm digital library" => "acm_digital_library",
                            "agricultural and environmental science" => "agricultural_and_environmental_science",
                            "biological science" => "biological_science",
                            "business source complete" => "business_source_complete",
                            "canadian business and current affairs" => "canadian_business_and_current_affairs",
                            "cinahl" => "cinahl",
                            "dentistry and oral sciences source" => "dentistry_and_oral_sciences_source",
                            "environment complete" => "environment_complete",
                            "factiva" => "factiva",
                            "film and television literature index" => "film_and_television_literature_index",
                            "free or oa" => "free_or_oa", 
                            "geoscienceworld" => "geoscienceworld",
                            "heinonline" => "heinonline",
                            "highwire press journals" => "highwire_press_journals",
                            "iop" => "iop",
                            "ios press journals" => "ios_press_journals",
                            "international bibliography of theatre and dance" => "international_bibliography_of_theatre_and_dance",
                            "jstor" => "jstor",
                            "lexis/nexis academic" => "lexisnexis_academic",
                            "library literature and information science" => "library_literature_and_information_science",
                            "ovid" => "ovid",
                            "periodical archives online" => "periodical_archives_online",
                            "project muse" => "project_muse",
                            "pubmed central" => "pubmed_central",
                            "research library" => "research_library",
                            "scielo" => "scielo",
                        ];

                        foreach($platforms as $pla) {
                            $journal_safe_name = $import_platform_names[strtolower($pla->name)];
                            if(!empty($row->{$journal_safe_name . "_coverage"})) {
                                $j->platforms()->attach($pla->id, 
                                    [
                                     'perpetual_access' => false,
                                     'perpetual_access_coverage' => null,
                                     'years' => $row->{$journal_safe_name . "_coverage"},
                                     'start_volume' => null,
                                     'end_volume' => null,
                                     'is_embargo' => $this->getEmbargoValue($row->{$journal_safe_name . "_embargo"}),
                                     'embargo_length' => $row->{$journal_safe_name . "_embargo_length"},
                                     'embargo_updated' => null,
                                     'date_embargo_checked' => null,
                                    ]);
                            }
                        }
                        $maxyear = 2020;
                        //Lets check for year specific fields
                        for($year = 2009;$year <= $maxyear;$year++) {
                            if(!is_null($row->{$year . "_downloads"}) && is_numeric($row->{$year . "_downloads"})) {
                                $d = Download::firstOrNew([
                                    'journal_id' => $j->id,
                                    'report_year' => $year
                                ]);

                                $d->downloads_reported = $row->{$year . "_downloads"};

                                $d->save();
                            }
                            
                            if(!is_null(trim($row->{$year . "_list_price_usd"})) && is_numeric($row->{$year . "_list_price_usd"})) {
                                $p = Price::firstOrNew([
                                    'journal_id' => $j->id,
                                    'report_year' => $year
                                ]);
                                $p->price = str_replace("$", "", $row->{$year . "_list_price_usd"});
                                $p->currency = "USD";
                                $p->cost_per_use = null;
                                $p->adjusted_cost_per_use = null;
                                $p->save();
                            }

                            if(!is_null($row->{$year . "_citations"})  && is_numeric($row->{$year . "_citations"})) {
                                $d = Citation::firstOrNew([
                                    'journal_id' => $j->id,
                                    'report_year' => $year
                                ]);
                                $d->citations_reported = $row->{$year . "_citations"};
                                $d->save();
                            }

                        }
                        $j->save();
                        
                        if($row->faculty_1 != "" && $row->faculty_1 != null) {
                            
                            //Find the faculty
                            $fac = new Faculty;
                            $fac = $fac->where('faculty_name', 'like', '%'.$row->faculty_1.'%')->orWhere('faculty_aliases', 'like', '%'.$row->faculty_1.'%')->first();

                            if($fac) { 
                                $j->faculties()->attach($fac->id);
                            } else {
                            }
                        }

                        if($row->faculty_2 != "" && $row->faculty_2 != null) {
                            //Find the faculty
                            $fac = new Faculty;
                            $fac = $fac->where('faculty_name', 'like', '%'.$row->faculty_2.'%')->orWhere('faculty_aliases', 'like', '%'.$row->faculty_2.'%')->first();

                            if($fac) { 
                                $j->faculties()->attach($fac->id);
                            } else {
                            }
                        }

                        if($row->department_1 != "" && $row->department_1 != null) {
                            //Find the faculty
                            $dep = new Department;
                            $dep = $dep->where('department_name', 'like', '%'.$row->department_1.'%')->orWhere('department_aliases', 'like', '%'.$row->department_1.'%')->first();
                            if($dep) { 
                                $j->departments()->attach($dep->id);
                            } else {
                            }
                        }

                        if($row->department_2 != "" && $row->department_2 != null) {
                            //Find the faculty
                            $dep = new Department;
                            $dep = $dep->where('department_name', 'like', '%'.$row->department_2.'%')->orWhere('department_aliases', 'like', '%'.$row->department_2.'%')->first();
                            if($dep) { 
                                $j->departments()->attach($dep->id);
                            } else {
                            }
                        }

                        if($row->department_3 != "" && $row->department_3 != null) {
                            //Find the faculty
                            $dep = new Department;
                            $dep = $dep->where('department_name', 'like', '%'.$row->department_3.'%')->orWhere('department_aliases', 'like', '%'.$row->department_3.'%')->first();
                            if($dep) { 
                                $j->departments()->attach($dep->id);
                            } else {
                            }
                        }
                        
                        if($row->department_4 != "" && $row->department_4 != null) {
                            //Find the faculty
                            $dep = new Department;
                            $dep = $dep->where('department_name', 'like', '%'.$row->department_4.'%')->orWhere('department_aliases', 'like', '%'.$row->department_4.'%')->first();
                            if($dep) { 
                                $j->departments()->attach($dep->id);
                            } else {
                            }
                        }

                        //Now lets add alternate titles
                        foreach(explode("|",$row->{"all_titles"}) as $title) {
                            $a = AlternateJournalTitle::firstOrNew([
                                'journal_id' => $j->id,
                                'journal_title' => trim($title)
                            ]);
                            $a->e_issn = $j->e_issn;
                            $a->p_issn = $j->p_issn;
                            $a->save();
                        }

                    }
                });
        
            });
        });
    }
}
