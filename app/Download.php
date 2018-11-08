<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Download extends Model
{
    protected $fillable = ['journal_id', 'report_year', 'downloads_reported'];
}
