<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Vote;
use App\Journal;
use App\Election;
use App\ElectionAudit;
use App\Faculty;
use Excel;
class VoteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->input('election')) {
            $election = Election::find($request->input('election'));
        } else {
            $election = Election::whereDate('end_date', '>=', Carbon\Carbon::now()->toDateString())->orWhere('end_date', null)->orderBy('start_date', 'desc')->first();
        }
        $finalize = $request->input('finalize', null);

        if($finalize) {
            $finalize = 'true';
        } else {
            $finalize = null;
        }
        $data = array('election' => $election, 'finalize' => $finalize);

        if($request->input('download')) {
            $format = $request->input('download');
            $votes = Vote::where('user_id', \Auth::user()->id)
                            ->where('election_id', $election->id)
                            ->with('journal', 'journal.platforms')
                            ->get();

            Excel::create('Your_Recommendations_' . \Carbon\Carbon::now()->format('m-F-Y_his'), function($excel) use ($request, $votes){
                // Call writer methods here
                $excel->sheet('Votes', function($sheet) use ($request, $votes) {
                    $sheet->loadView('exports.vote.personalvoteexport', ['votes' => $votes]);
                });
                $excel->setActiveSheetIndex(0);
            })->download($format);
        }
        return view('components.vote.votepage')->with($data);
    }

    public function indexforjournal(Request $req, $journal_id, $election_id) {
        
        $election = Election::find($election_id);
        $vote_filter = $req->input('vote_filter', "-1");
        $type_filter = $req->input('type_filter', "-1");
        
        $page = $req->has('page') ? $req->input('page') : 1;
        $perPage = 100;
        $votes = new Vote;
        $votes = $votes->where('election_id', $election_id)->where('journal_id', $journal_id);
        

        if($vote_filter != "-1") {
            $votes = $votes->where('vote', $vote_filter);
        }

        if($type_filter != "-1") {
            $votes = $votes->where('type', $type_filter);
        }


        $votes_paginated = $votes->paginate($perPage);
        
        $votes_paginated->withPath('/journal/' . $journal_id . '/votes/' . $election_id);
        $votes_paginated->appends(['vote_filter' => $vote_filter, 'type_filter' => $type_filter]);
        $data = array('votes' => $votes_paginated,
            'election' => $election,
            'type_filter' => $type_filter,
            'vote_filter' => $vote_filter,
        );
        
        return view('components.list.votes')->with($data);
        
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

            

        if(\Auth::check()) {
            //Anonymize the data
            //Is this an open election?
            if(!Election::whereDate('end_date', '>=', \Carbon\Carbon::now()->toDateString())->orWhere('end_date', null)->where('id', 1)->exists()) {
                abort(400, "This election is closed."); //This election is closed
            }

            if(ElectionAudit::where('election_id', $request->input('election_id'))->where('banner_id',\Auth::user()->email)->exists()) {
                abort(400, "this user has already voted"); //This election is closed
            }

            foreach(Vote::where('user_id', \Auth::user()->id)
                    ->where('election_id', $request->election_id)
                    ->get() as $vote) {
                $vote->is_submitted = true;
                $vote->save();
            }

            $e = new ElectionAudit;
            $e->election_id = $request->election_id;
            $e->banner_id = \Auth::user()->email;
            $e->save();
            return response()->json([
                'result' => true,
                'redirect_url' => route('vote.success'),
            ]);
        } else {
            abort(403);
        }
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
             'platforms' => $j->platforms,
             'prices' => $j->prices
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
        //
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
    
    public function success() {
        return redirect()->route('home')->with('message', 'Your recommendation has been recorded and will help the Dalhousie Libraries make informed decisions. Your participation will help shape our collection decisions for the next several years. Thank you.');
    }


    
    public function add(Request $req) {
        //Get the users ID, the election ID, the journal ID, the vote type, and the comments.
        $user = auth()->user()->id;
        $election = $req->input('election', -1);
        $journal = $req->input('journal', -1);
        $vote = $req->input('vote', -1);
        $comments = $req->input('comments', null);

        if(!$user) {
            abort(403);
        }

        if($election < 0 || $journal < 0) {
            abort(400);
        }

        //this is a valid vote;

        $v = Vote::firstOrNew([
            'election_id' => $election,
            'journal_id' => $journal, 
            'faculty' => auth()->user()->faculty_id, //something
            'department' =>auth()->user()->department_id, //something
            'type' => auth()->user()->type, //something
            'user_id' => auth()->user()->id,
            ]);
        $v->vote = $vote;
        $v->comments = $comments;
        $v->save();
        return response()->json([
            'result' => true,
        ]);
    }

    public function delete(Request $req) {
        //Get the users ID, the election ID, the journal ID, the vote type, and the comments.
        $user = auth()->user()->id;
        $election = $req->input('election', -1);
        $journal = $req->input('journal', -1);

        if(!$user) {
            abort(403);
        }

        if($election < 0 || $journal < 0) {
            abort(400);
        }

        //this is a valid vote delete request;

        $v = Vote::where('user_id', $user)->where('election_id', $election)->where('journal_id',$journal)->first()->delete();
        return response()->json([
            'result' => true,
        ]);
    }
}
