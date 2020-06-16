<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Submitter extends Model
{
    public function job_lists()
    {
        return $this->hasMany('App\JobList');
    }
}
