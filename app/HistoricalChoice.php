<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HistoricalChoice extends Model
{
    protected $fillable = ["journal_id", "subscription_year", "created_at", "updated_at"];
}
