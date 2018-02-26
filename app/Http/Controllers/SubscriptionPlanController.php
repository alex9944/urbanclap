<?php

namespace App\Http\Controllers;
use DB;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Models\SubscriptionPlan;
use App\Http\Controllers\Controller;



class SubscriptionPlanController extends Controller
{
    //
	public function index()
    {		
	   $plan= SubscriptionPlan::all();
       return view('panels.admin.subscription.plan',['plan'=>$plan]);
    }
	
	public function getplan(Request $request)
		{
			 $id=$request->id;  
			 $plan = DB::table('subscription_plan')
			 ->where('id', $id)	 
			 ->first();   
		 return '{"view_details": ' . json_encode($plan) . '}';
	}
		
	public function added(Request $request)
	{
		
		$this->validate($request,
			 [
                'title'            	=> 'required',               
               
            ],
            [
                'title.required'   	=> 'Plan Title is required',
                          
               
            ]);
				$adsposition = new SubscriptionPlan;				
				$adsposition->title=$request->title;				
				$adsposition->save();
			return redirect('admin/subscription/plan')->with('message','Plan added successfully');
	}
	public function updated(Request $request)
	{	 
			
			$this->validate($request,
			 [
                'title'            	=> 'required',               
               
            ],
            [
                'title.required'   	=> 'Plan Title is required',
                          
               
            ]);
			
				
				DB::table('subscription_plan')
				->where('id', $request->id)
				->update(['title' => $request->title,]);

	return redirect('admin/subscription/plan')->with('message','Plan updated successfully');
	}


	public function deleted(Request $request)
	{	 
	     $id=$request->id;  
		 $plan = DB::table('subscription_plan')
		 ->where('id', $id)
		 ->delete();
		 $status['deletedid']=$id;
		 $status['deletedtatus']='Plan deleted successfully';
	 return '{"delete_details": ' . json_encode($status) . '}'; 
	
	}

	public function destroy(Request $request)
		{
			$cn=count($request->selected_id);
			if($cn>0)
			{
			$data = $request->selected_id;			
				foreach($data as $id) {
				DB::table('subscription_plan')->where('id', $id)->delete();			
				}			
			} 
	return redirect('admin/subscription/plan')->with('message','Seltected Plan are deleted successfully');			

	}
}