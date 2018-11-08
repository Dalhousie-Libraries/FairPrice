<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Vote as Vote;

class Journal extends Model
{
    protected $fillable = ["e_issn", "p_issn", "jup", "doi", "journal_title", "abbreviation", "proprietary_identifier", "url", "subject_1", "subject_2", "subject_3", "subject_4",
    "user_subject", "fund", "domain", "journal_status", "faculty", "departments", "retained_by", "libraries_holding_print", "threshold_levels", "comments", "is_priority",
    "is_subscribed", "is_recommendation", "is_consultation", "is_print_access", "print_holdings"];
    public function platforms(){
        return $this->belongsToMany('App\Platform', 'platform_journal')->withPivot('perpetual_access', 'years', 'start_volume', 'end_volume',
         'perpetual_access_coverage', 'priority_package', 'aggregator_platform', 'is_embargo', 'embargo_length', 'embargo_updated', 'date_embargo_checked');
    }

    public function faculties(){
        return $this->belongsToMany('App\Faculty', 'faculty_journal');
    }

    public function departments(){
        return $this->belongsToMany('App\Department', 'department_journal');
    }

    public function prices(){
        return $this->hasMany('App\Price');
    }

    public function requests(){
        return $this->hasMany('App\Request');
    }

    public function historical_choices(){
        return $this->hasMany('App\HistoricalChoice');
    }

    public function votes(){
        return $this->hasMany('App\Vote');
    }

    protected $appends = ['subscribed', 'priority', 'recommendation', 'consultation', 'vote', 'vote_value', 'needed_count', 'wanted_count', 'textdomain', 'fullurl'];

    public function getFullURLAttribute() {
        return "http://ezproxy.library.dal.ca/login?url=" . $this->url;
    }

    public function getNeededCountAttribute() {
        return $this->votes()->where('vote', 2)->count();
    }

    public function getWantedCountAttribute() {
        return $this->votes()->where('vote', 1)->count();
    }

    public function getVoteAttribute() {
        return "";
    }

    public function getVoteValueAttribute() {
        return "";
    }

    public function getSubscribedAttribute()
    {
        if($this->is_subscribed == 1) {
            return "Yes";
        } else {
            return "No";
        }
    }
    public function setSubscribedAttribute($value)
    {
        if(strtolower($value) == strtolower("Yes")) {
            $this->is_subscribed = 1;
        } else {
            $this->is_subscribed = 0;
        }
    }

    public function getPriorityAttribute()
    {
        if($this->is_priority == 1) {
            return "Yes";
        } else {
            return "No";
        }
    }
    public function setPriorityAttribute($value)
    {
        if(strtolower($value) == strtolower("Yes")) {
            $this->is_priority = 1;
        } else {
            $this->is_priority = 0;
        }
    }

    public function getConsultationAttribute()
    {
        if($this->is_consultation == 1) {
            return "In Progress";
        } else {
            return "Not In Progress";
        }
    }

    public function getTextDomainAttribute()
    {
        if($this->domain == "AH") {
            return "Arts and Humanities";
        }

        if($this->domain == "SS") {
            return "Social Sciences";
        }

        if($this->domain == "BM") {
            return "Biomedical";
        }

        if($this->domain == "NSE") {
            return "Natural Science and Engineering";
        }
    }

    public function setConsultationAttribute($value)
    {
        if(strtolower($value) == strtolower("In Progress")) {
            $this->is_consultation = 1;
        } else {
            $this->is_consultation = 0;
        }
    }

    public function getRecommendationAttribute()
    {
        if($this->is_recommendation == 1) {
            return "Yes";
        } else {
            return "No";
        }
    }
    public function setRecommendationAttribute($value)
    {
        if(strtolower($value) == strtolower("Yes")) {
            $this->is_recommendation = 1;
        } else {
            $this->is_recommendation = 0;
        }
    }
    
}
