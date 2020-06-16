<?php

namespace App\Jobs;

use App\JobList;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessJobList implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels; 

    protected $job_list;
    protected $start_time_process;
    protected $end_time_process;
    protected $final_time_process;
    
    protected $processor_id;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(JobList $job_list, $start_time_process, $processor_id)
    {
        $this->job_list = $job_list;
        $this->start_time_process = $start_time_process;
        $this->processor_id = $processor_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->end_time_process = strtotime(date('H:i:s'));

        $this->final_time_process = date('H:i:s',$this->end_time_process - $this->start_time_process);

        //$job_list = JobList::where('id',$this->job_list_id);

        $this->job_list->processor_id = $this->processor_id;
        $this->job_list->processing_time = $this->final_time_process;

        $this->job_list->save();
    }
}
