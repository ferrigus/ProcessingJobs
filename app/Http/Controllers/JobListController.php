<?php

namespace App\Http\Controllers;

use Validator;
use App\JobList;
use App\Submitter;
use App\Processor;
use App\Jobs\ProcessJobList;
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
     * Get a list of jobs
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $inputs = $request->input();

        $job_lists = JobList::orderBy('created_at','desc')->with('submitter')->with('processor');

        if(array_key_exists('processor_id',$inputs)){
            $job_lists = $job_lists->where('processor_id',$request->processor_id);
        }

        if(array_key_exists('submitter_id',$inputs)){
            $job_lists = $job_lists->where('processor_id',$request->processor_id);
        }

        if(array_key_exists('avaliable', $inputs) && $request->avaliable==1){

            $job_lists = $job_lists->whereNull('processor_id');
        }

        $job_lists = $job_lists->get();

        if($job_lists->count()>0){
            $this->results = array('data'=>$job_lists,'status'=>'200','message'=>'Success');
        }
        return response()->json($this->results);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        unset($this->rules_fields['processor_id']);
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
    public function update(Request $request)
    {
        unset($this->rules_fields['submitter_id']);
        unset($this->rules_fields['quantity']);
        $start_time = strtotime(date('H:i:s'));
        
        $validatedData = Validator::make($request->all(), $this->rules_fields);
        
        if (!$validatedData->fails()){
            $processor = Processor::where('id', $request->processor_id)->doesntHave('job_list')->first();

            if($processor!=null){
            
                $job_list = JobList::whereNull('processor_id')->where('queue','high')->orderBy('created_at','desc')->first();

                if($job_list!=null){

                    ProcessJobList::dispatch($job_list, $start_time, $request->processor_id);

                    array_push($this->job_lists_ids,$job_list->id);
                    $this->results = array('data'=>$this->job_lists_ids,'status'=>'201','message'=>'Success');
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
