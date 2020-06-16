<?php

namespace App\Http\Controllers;

use Validator;
use App\JobList;
use App\Submitter;
use App\Processor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JobListController extends Controller
{
    protected $results;
    protected $rules_fields;
    protected $message_rules_fields;
    protected $job_lists_ids;

    public function __construct()
    {
        $this->results=array('data'=>array(),'status'=>'204','message'=>'Data not Found');
        $this->rules_fields = array(
            'submitter_id' => 'required|integer',
            'processor_id' => 'required|integer',
            'quantity' => 'required|integer|min:1'
        );
        $this->job_lists_ids = array();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        unset($this->rules_fields['processors_id']);
        $validatedData = Validator::make($request->all(), $this->rules_fields);
        
        if (!$validatedData->fails()){
            $submitter = Submitter::where('id', $request->submitter_id)->first();

            if($submitter!=null){
                DB::transaction(function() use ($request){
                    $i=0;
                    for($i>=0;$i<$request->quantity;$i++){
                        $job_list = new JobList;
                        $job_list->submitter_id = $request->submitter_id;
                        $job_list->queue = 'high';
                        
                        if($job_list->save()){
                            array_push($this->job_lists_ids,$job_list->id);
                        }
                        
                    }  
                });
                
                $this->results = array('data'=>$this->job_lists_ids,'status'=>'201','message'=>'Success');
            }else{
                $this->results['message'] = 'Invalid Submitter.';
            }
        }else{
            
            foreach (array_slice($validatedData->messages()->getMessages(),0,1) as $field_name => $messages){
                
                $this->results['message'] = $messages[0];
            }
        }

        return response()->json($this->results);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        unset($this->rules_fields['submitter_id']);
        unset($this->rules_fields['quantity']);
        $start_time = strtotime(date('H:i:s'));
        
        $validatedData = Validator::make($request->all(), $this->rules_fields);
        
        if (!$validatedData->fails()){
            $processor = Processor::where('id', $request->processor_id)->doesntHave('job_list')->first();

            if($processor!=null){
            
                $job_list = JobList::where('id',$id)->whereNull('processor_id')->where('queue','high')->orderBy('created_at','desc')->first();

                if($job_list!=null){
                    $end_time = strtotime(date('H:i:s'));
                    $final_time = date('H:i:s',$end_time - $start_time);
                    
                    $job_list->processor_id = $request->processor_id;
                    $job_list->queue = 'high';
                    $job_list->processing_time = $final_time;
                    if($job_list->save()){
                        array_push($this->job_lists_ids,$job_list->id);
                        $this->results = array('data'=>$this->job_lists_ids,'status'=>'201','message'=>'Success');
                    }else{
                        $this->results['message'] = 'Updating Job Failed.';
                    }

                }else{
                    $this->results['message'] = 'Invalid Job Id.';
                }
                
                
            }else{
                $this->results['message'] = 'Invalid Processor or the processor is already running a job.';
            }
        }else{
            
            foreach (array_slice($validatedData->messages()->getMessages(),0,1) as $field_name => $messages){
                
                $this->results['message'] = $messages[0];
            }
        }

        return response()->json($this->results);
    }

}