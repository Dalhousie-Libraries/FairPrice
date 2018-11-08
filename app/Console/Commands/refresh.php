<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Excel;
Use App\Journal;
Use App\Platform;
Use App\Faculty;
Use App\Download;
Use App\HistoricalChoice;
Use App\Price;
Use App\Citation;
Use App\AlternateJournalTitle;

class refresh extends Command
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
    
    function getEmbargoValue($row) {
        if (strpos(strtolower($row->{"type_of_alternate_access"}), "embargo") !== false) {
            return 1;
        }
        if (strpos(strtolower($row->{"type_of_alternate_access"}), "none") !== false) {
            return 0;
        }
        return null;
    }

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:refresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh data from excel file';

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
        Excel::load('beta_data.xlsx', function($reader) use ($platforms){
        // Loop through all sheets
            echo("Begin refresh\n");
            $reader->each(function($sheet) use ($platforms) {
                echo("Reading sheet\n");
                // Loop through all rows
                $sheet->each(function($row) use ($platforms) {
                    
                    if(!empty($row->title)) {
                        
                        echo("Now Importing " . $row->title + "\n");
                        echo("Reading core Journal Data\n");
                        $j = Journal::where('e_issn', $row->online_issn)
                                ->where('p_issn', $row->print_issn)
                                ->first();
                        if($j) {
                            //$j->journal_title = $row->title;
                            /*
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
                            */
                            //$j->url = $row->url;
                            //$j->fund = $row->fund;
                            //$j->domain = $row->discipline;
                            $j->subject_1 = $row->subject_1;
                            //$j->subject_2 = $row->subject_2;
                            //$j->subject_3 = $row->subject_3;
                            //$j->subject_4 = $row->subject_4;
                            //$j->comments = $j->general_notes;
                            //echo("Reading Consulation data\n");
                            //if($row->meets_preliminary_priority_criteria_for_consultation == "no") {
                            //    $j->is_priority = 0;
                            //} else {
                            //    $j->is_priority = 1;
                            //}
                            //echo("Reading faculty data\n");
                            //$j->faculty = Faculty::where(function($q) use ($row) {
                            //    $q->where('faculty_name', $row->faculty)
                            //        ->orwhere('faculty_code', $row->faculty);
                            //})->first()->faculty_id;
                            //echo("Reading department data\n");
                            //$j->departments = 0;
                            
                            //$dep_one=null;
                            //$dep_two=null;

                            //$dep_one = Faculty::where('faculty_id', $j->faculty)->where('department_name', $row->departmentunit_1)->first();
                            //if(is_null($dep_one) && $row->departmentunit_1 != "") {
                                // Try a code instead
                            //    $dep_one = Faculty::where('department_code', $row->departmentunit_1)
                            //        ->whereNotNull('department_code')
                            //        ->first();
                            //}

                            //if(is_null($dep_one)) {
                            //    $dep_one = new Faculty;
                            //    $dep_one->department_bit = 0;
                            //}                        
                            
                            //$j->departments += $dep_one->department_bit;
                                            
                            //$dep_two = Faculty::where('faculty_id', $j->faculty)->where('department_name', $row->departmentunit_2)->first();
                            //if(is_null($dep_two) && $row->departmentunit_2 != "") {
                                // Try a code instead
                            //    $dep_two = Faculty::where('department_code', $row->departmentunit_2)
                            //        ->whereNotNull('department_code')
                            //        ->first();
                            //}   
                            
                            //if(is_null($dep_two)) {
                            //    $dep_two = new Faculty;
                            //    $dep_two->department_bit = 0;
                            //} 
                            //$j->departments += $dep_two->department_bit;
                            //echo("Reading plaform data\n");
                            
                            
                            //$primary_platform = Platform::where('name', $row->package)->first();
                            $j->save();
                            //
                            //$j->platforms()->attach($primary_platform, [
                            //    'perpetual_access' => $this->getPerpetualAccessValue($row), 
                            //    'perpetual_access_coverage' => $row->{"post_cancellation_rights_start_with"},
                            //    'years' => $row->{"package_coverage"},
                            //    'start_volume' => null,
                            //    'end_volume' => null,
                            //    'is_embargo' => $this->getEmbargoValue($row),
                            //    'embargo_length' => null,
                            //    'embargo_updated' => null,
                            //    'date_embargo_checked' => null,
                            //]);
                            /*
                            $import_platform_names = [
                                "cambridge" => "cambridge",
                                "oxford" => "oxford",
                                "sage" => "sage",
                                "springer" => "springer",
                                "taylor & francis" => "taylor & francis",
                                "wiley" => "wiley",
                                "abi/inform trade & industry" => "abiinform_trade_industry",
                                "academic search premier" => "academic_search_premier",
                                "acm digital library" => "acm_digital_library",
                                "agricultural & environmental science" => "agricultural_environmental_science",
                                "biological science" => "biological_science",
                                "business source complete" => "business_source_complete",
                                "canadian business & current affairs" => "canadian_business_current_affairs",
                                "cinahl" => "cinahl",
                                "dentistry and oral sciences source" => "dentistry_and_oral_sciences_source",
                                "environment complete" => "environment_complete",
                                "factiva" => "factiva",
                                "film & television literature index" => "film_television_literature_index",
                                "free/oa e- journals" => "freeoa_e_journals", 
                                "geoscienceworld" => "geoscienceworld",
                                "heinonline" => "heinonline",
                                "international bibliography of theatre & dance" => "international_bibliography_of_theatre_dance",
                                "jstor" => "jstor",
                                "lexis/nexis academic" => "lexisnexis_academic",
                                "periodical archives online" => "periodical_archives_online",
                                "project muse" => "project_muse",
                                "pubmed central" => "pubmed_central",
                                "research library" => "research_library",
                            ];
                            
                            foreach($platforms as $pla) {
                                $journal_safe_name = $import_platform_names[strtolower($pla->name)];
                                echo("looking at row->" . $journal_safe_name . "\n");
                                if(!empty($row->{$journal_safe_name})) {
                                    echo("Found years " .  $row->{$journal_safe_name} . "\n");
                                    
                                    $j->platforms()->syncWithoutDetaching([$pla->id =>
                                        [
                                        'perpetual_access' => false,
                                        'perpetual_access_coverage' => null,
                                        'years' => $row->{$journal_safe_name},
                                        'start_volume' => null,
                                        'end_volume' => null,
                                        'is_embargo' => false,
                                        'embargo_length' => null,
                                        'embargo_updated' => null,
                                        'date_embargo_checked' => null,
                                        ]]);
                                }
                            }
                            //echo("Saving record\n");
                            //$maxyear = 2020;
                            //Lets check for year specific fields
                            //for($year = 2009;$year <= $maxyear;$year++) {
                            //    if(!is_null($row->{$year . "_downloads"}) && is_numeric($row->{$year . "_downloads"})) {
                            //        $d = Download::firstOrNew([
                            //            'journal_id' => $j->id,
                            //            'report_year' => $year
                            //        ]);

                            //        $d->downloads_reported = $row->{$year . "_downloads"};
                                    
                            //        if($this->getPerpetualAccessValue($row) != 1) {
                            //            $h = HistoricalChoice::firstOrNew([
                            //                'journal_id' => $j->id,
                            //                'subscription_year' => $year
                            //            ]);
                            //            $h->save();
                            //        }

                            //        $d->save();
                            //    }
                                
                            //    if(!is_null(trim($row->{$year . "_list_price_usd"})) && is_numeric($row->{$year . "_list_price_usd"})) {
                            //        $p = Price::firstOrNew([
                            //            'journal_id' => $j->id,
                            //            'report_year' => $year
                            //        ]);
                            //        $p->price = str_replace("$", "", $row->{$year . "_list_price_usd"});
                            //        $p->currency = "USD";
                            //        $p->cost_per_use = null;
                            //        $p->adjusted_cost_per_use = null;
                            //        $p->save();
                            //    }

                            //    if(!is_null($row->{$year . "_citations"})  && is_numeric($row->{$year . "_citations"})) {
                            //        $d = Citation::firstOrNew([
                            //            'journal_id' => $j->id,
                            //            'report_year' => $year
                            //        ]);
                            //        $d->citations_reported = $row->{$year . "_citations"};
                            //        $d->save();
                            //    }

                            //}
                            $j->save();
                            
                            //Now lets add alternate titles
/*
                            foreach(explode("|",$row->{"all_titles"}) as $title) {
                                $a = AlternateJournalTitle::firstOrNew([
                                    'journal_id' => $j->id,
                                    'journal_title' => trim($title)
                                ]);
                                $a->e_issn = $j->e_issn;
                                $a->p_issn = $j->p_issn;
                                $a->save();
                            }
*/
                        }
                    }
                });
        
            });
        });
    }
}
