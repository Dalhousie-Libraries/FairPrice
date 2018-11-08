<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Excel;
Use App\Journal;

class vote_export extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:export';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Exports an excel file of a given journal id\'s votes';

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
        $journal_id = $this->ask("Which journal id would you like to generate a report on?");
        $journal = Journal::find($journal_id);
        Excel::create('votes_for_' . $journal_id . '.xlsx', function($excel) use ($journal) {
            
                // Call writer methods here
                $excel->sheet('Votes', function($sheet) use ($journal) {
                    
                            $sheet->loadView('exports.journal.votes', ['journal' => $journal]);

                        });
                $excel->sheet('Report Metadata', function($sheet) use ($journal) {
                    
                            $sheet->loadView('exports.common.summary', 
                                ['report_name' => 'Votes for Journal ' . $journal->journal_title,
                                 'format_type' => 'Excel File (XLSX)',
                                 'record_count' => $journal->votes->count(),
                                 'importable' => 'No',
                                 'query' => 'php artisan command:export PARAMETER Journal_id = 22',
                                 'url' => 'Executed from console, no URL available'
                                    ]);

                        });
            })->store('xlsx');;
    }
}
