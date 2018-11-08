<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    protected $fillable=['election_id', 'user_id', 'faculty', 'journal_id', 'type', 'department', 'vote', 'created_at', 'updated_at'];
    public function election()
    {
        return $this->belongsTo('App\Election');
    }

    public function journal()
    {
        return $this->belongsTo('App\Journal');
    }
    public function getHRVoteAttribute() {
        if ($this->vote == 0) return "Not Needed";
        if ($this->vote == 1) return "Wanted";
        if ($this->vote == 2) return "Needed";
    }
    public function getFinalizedAttribute() {
        if ($this->user_id != null) return false;
        return true;
    }

    protected $appends=['hrvote', 'finalized'];
}
