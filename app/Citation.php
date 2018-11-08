<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Citation extends Model
{
    protected $fillable=['journal_id','report_year', 'citations_reported'];
}
