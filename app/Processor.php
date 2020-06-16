<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Processor extends Model
{
    public function job_list()
    {
        return $this->hasOne('App\JobList');
    }
}
