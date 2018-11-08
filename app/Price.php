<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
class Price extends Model
{
    public function journal()
    {
        return $this->belongsTo('App\Journal')->withTrashed();
    }
    protected $fillable = ['journal_id', 'report_year', 'price', 'currency', 'cost_per_use' , 'adjusted_cost_per_use']; 
}
