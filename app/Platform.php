<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Platform extends Model
{
    protected $appends = ['embargo', 'hrembargoduration'];

    public function journals()
    {
        return $this->belongsToMany('App\Journal', 'platform_journal')->withPivot('perpetual_access', 'years', 'start_volume', 'end_volume',
        'perpetual_access_coverage', 'priority_package', 'aggregator_platform', 'is_embargo', 'embargo_length', 'embargo_updated', 'date_embargo_checked');
    }

    public function getPrimaryAttribute()
    {
        if($this->is_primary == 1) {
            return "Yes";
        } else {
            return "No";
        }
    }

    public function getEmbargoAttribute()
    {
        if($this->is_embargo == 1) {
            return "Yes";
        } else {
            return "No";
        }
    }

    public function getHREmbargoDurationAttribute()
    {
        if(!$this->embargo_duration || $this->embargo_duration == null) {
            return "N/A";
        } 

        $out = $this->embargo_duration;
        $out = str_replace("y", " Year(s)", $out);
        $out = str_replace("m", " Month(s)", $out);
        return $out;
    }

    public function setPrimaryAttribute($value)
    {
        if(strtolower($value) == strtolower("Yes")) {
            $this->is_primary = 1;
        } else {
            $this->is_primary = 0;
        }
    }
}
