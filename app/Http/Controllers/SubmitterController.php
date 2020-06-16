<?php

namespace App\Http\Controllers;

use App\Submitter;
use Illuminate\Http\Request;

class SubmitterController extends Controller
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
        $submitters = Submitter::with('job_lists')->get();

        if($submitters->count()>0){
            $this->results = array('data'=>$submitters,'status'=>'200','message'=>'Success');
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
        $submitter = Submitter::where('id',$id)->with('job_lists')->first();

        if($submitter!=null){
            $this->results = array('data'=>$submitter,'status'=>'200','message'=>'Success');
        }
        
        return response()->json($this->results);
    }

}
