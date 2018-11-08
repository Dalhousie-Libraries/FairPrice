<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Election extends Model
{
    public function votes(){
        return $this->hasMany('App\Vote');
    }

    protected $dates=['start_date', 'end_date'];
}
