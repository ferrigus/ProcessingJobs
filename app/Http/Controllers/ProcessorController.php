<?php

namespace App\Http\Controllers;

use App\Processor;
use Illuminate\Http\Request;

class ProcessorController extends Controller
{
    protected $results;

    public function __construct()
    {
        $this->results=array('data'=>array(),'status'=>'204','message'=>'Data not Found');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $processors = Processor::with('job_list')->get();

        if($processors->count()>0){
            $this->results = array('data'=>$processors,'status'=>'200','message'=>'Success');
        }

        return response()->json($this->results);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $processor = Processor::where('id',$id)->with('job_list')->first();

        if($processor!=null){
            $this->results = array('data'=>$processor,'status'=>'200','message'=>'Success');
        }
        
        return response()->json($this->results);
    }
}
