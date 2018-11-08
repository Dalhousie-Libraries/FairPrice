<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Vote;
use App\Election;

class VoteAPIController extends Controller
{
    function voteGraph(Request $request) {
        $number = $request->number;
        $journal_id = $request->input('journal_id');
        if(empty($number)) { $number = 3; }
        if($number > 5) {$number = 5; }

        
        $raw_elections = Election::orderBy('start_date', "desc")->take($number)->get();
        
        $elections = new \stdClass();
        $elections->election = array();
        foreach ($raw_elections as $election) {
            $e = new \stdClass();
            $e->id = $election->id;
            $e->start_date = $election->start_date->toDateString();;
            
            $e->student_votes_needed = Vote::where('election_id', $e->id)
                                        ->where('journal_id', $journal_id)
                                        ->where('type', 1)
                                        ->where('vote', 2)
                                        ->where('is_submitted', true)
                                        ->count();
            $e->student_votes_wanted = Vote::where('election_id', $e->id)
                                        ->where('journal_id', $journal_id)
                                        ->where('type', 1)
                                        ->where('vote', 1)
                                        ->where('is_submitted', true)
                                        ->count();                                        
            $e->student_votes_notneeded = Vote::where('election_id', $e->id)
                                        ->where('journal_id', $journal_id)
                                        ->where('type', 1)
                                        ->where('vote', 0)
                                        ->where('is_submitted', true)
                                        ->count();       
            $e->faculty_votes_needed = Vote::where('election_id', $e->id)
                                        ->where('journal_id', $journal_id)
                                        ->where('type', 2)
                                        ->where('vote', 2)
                                        ->where('is_submitted', true)
                                        ->count();
            $e->faculty_votes_wanted = Vote::where('election_id', $e->id)
                                        ->where('journal_id', $journal_id)
                                        ->where('type', 2)
                                        ->where('vote', 1)
                                        ->where('is_submitted', true)
                                        ->count();                                        
            $e->faculty_votes_notneeded = Vote::where('election_id', $e->id)
                                        ->where('journal_id', $journal_id)
                                        ->where('type', 2)
                                        ->where('vote', 0)
                                        ->where('is_submitted', true)
                                        ->count(); 
            $e->staff_votes_needed = Vote::where('election_id', $e->id)
                                        ->where('journal_id', $journal_id)
                                        ->where('type', 3)
                                        ->where('vote', 2)
                                        ->where('is_submitted', true)
                                        ->count();
            $e->staff_votes_wanted = Vote::where('election_id', $e->id)
                                        ->where('journal_id', $journal_id)
                                        ->where('type', 3)
                                        ->where('vote', 1)
                                        ->where('is_submitted', true)
                                        ->count();                                        
            $e->staff_votes_notneeded = Vote::where('election_id', $e->id)
                                        ->where('journal_id', $journal_id)
                                        ->where('type', 3)
                                        ->where('vote', 0)
                                        ->where('is_submitted', true)
                                        ->count();             
            $e->other_votes_needed = Vote::where('election_id', $e->id)
                                        ->where('journal_id', $journal_id)
                                        ->where('type', 0)
                                        ->where('vote', 2)
                                        ->where('is_submitted', true)
                                        ->count();
            $e->other_votes_wanted = Vote::where('election_id', $e->id)
                                        ->where('journal_id', $journal_id)
                                        ->where('type', 0)
                                        ->where('vote', 1)
                                        ->where('is_submitted', true)
                                        ->count();                                        
            $e->other_votes_notneeded = Vote::where('election_id', $e->id)
                                        ->where('journal_id', $journal_id)
                                        ->where('type', 0)
                                        ->where('vote', 0)
                                        ->where('is_submitted', true)
                                        ->count();
            $e->student_color = "rgba(155, 49, 49, 0.64)";
            $e->faculty_color = "rgba(70, 149, 206, 0.64)";
            $e->other_color = "rgba(19, 185, 52, 0.64)";
            $e->staff_color = "rgba(239, 134, 15, 0.64)";
            $e->total_color = "rgba(186, 136, 19, 0.64)";
            
            $elections->election[] = $e;
        }

        $chartDataCollection = array();
        
        
        foreach($elections->election as $election) {
            $chartData = new \stdClass();
            $chartData->labels = array();
            $chartData->labels[] = "Not Needed";
            $chartData->labels[] = "Wanted";
            $chartData->labels[] = "Needed";
    
            $datasets = array();
            
            $dataset = new \stdClass();
            $dataset->label = "Other";
            $dataset->data = array($election->other_votes_notneeded, $election->other_votes_wanted, $election->other_votes_needed);
            $dataset->backgroundColor = $e->other_color;
            $dataset->borderWidth = 1;
            $datasets[] = $dataset;

            $dataset = new \stdClass();
            $dataset->label = "Students";
            $dataset->data = array($election->student_votes_notneeded, $election->student_votes_wanted, $election->student_votes_needed);
            $dataset->backgroundColor = $e->student_color;
            $dataset->borderWidth = 1;
            $datasets[] = $dataset;

            $dataset = new \stdClass();
            $dataset->label = "Faculty";
            $dataset->data = array($election->faculty_votes_notneeded, $election->faculty_votes_wanted, $election->faculty_votes_needed);
            $dataset->backgroundColor = $e->faculty_color;
            $dataset->borderWidth = 1;
            $datasets[] = $dataset;

            $dataset = new \stdClass();
            $dataset->label = "Staff";
            $dataset->data = array($election->staff_votes_notneeded, $election->staff_votes_wanted, $election->staff_votes_needed);
            $dataset->backgroundColor = $e->staff_color;
            $dataset->borderWidth = 1;
            $datasets[] = $dataset;


            
            $chartData->datasets = $datasets;
            $chartData->name = $election->start_date;
            $chartData->id = $election->id;
            $chartDataCollection[] = $chartData;
        }
      
        return JSON_ENCODE($chartDataCollection);

    }
}
