<?php

namespace App\Http\Controllers;
use DB;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Models\AdsDuration;
use App\Http\Controllers\Controller;



class AdsDurationController extends Controller
{
    //
	public function index()
    {		
	   $duration= AdsDuration::all();
	  
	   
       return view('panels.admin.ads.duration',['duration'=>$duration,]);
    }
	
	public function getduration(Request $request)
		{
			 $id=$request->id;  
			 $duration = DB::table('ads_duration')
			 ->where('id', $id)	 
			 ->first();   
		 return '{"view_details": ' . json_encode($duration) . '}';
	}
		
	public function added(Request $request)
	{
		
		$this->validate($request,
			 [
                'title'            	=> 'required',               
               
            ],
            [
                'title.required'   	=> 'Duration Time is required',
                          
               
            ]);
				$modeads = new AdsDuration;				
				$modeads->title=$request->title;
				$modeads->days_month=$request->days_month;				
				$modeads->save();
			return redirect('admin/ads/duration')->with('message','Duration added successfully');
	}
	public function updated(Request $request)
	{	 
			
			$this->validate($request,
			 [
                'title'            	=> 'required',               
               
            ],
            [
                'title.required'   	=> 'Duration Time is required',
                          
               
            ]);
			
				
				DB::table('ads_duration')
				->where('id', $request->id)
				->update(['title' => $request->title,'days_month' => $request->days_month,]);

	return redirect('admin/ads/duration')->with('message','Duration updated successfully');
	}


	public function deleted(Request $request)
	{	 
	     $id=$request->id;  
		 $duration = DB::table('ads_duration')
		 ->where('id', $id)
		 ->delete();
		 $status['deletedid']=$id;
		 $status['deletedtatus']='Duration deleted successfully';
	 return '{"delete_details": ' . json_encode($status) . '}'; 
	
	}

	public function destroy(Request $request)
		{
			$cn=count($request->selected_id);
			if($cn>0)
			{
			$data = $request->selected_id;			
				foreach($data as $id) {
				DB::table('ads_duration')->where('id', $id)->delete();			
				}			
			} 
	return redirect('admin/ads/duration')->with('message','Seltected duration are deleted successfully');			

	}
}