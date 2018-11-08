<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Request extends Model
{
    public function journal()
    {
        return $this->belongsTo('App\Journal')->withTrashed();
    }
}
