<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobList extends Model
{
    public function submitter()
    {
        return $this->belongsTo('App\Submitter');
    }

    public function processor()
    {
        return $this->belongsTo('App\Processor');
    }
}
