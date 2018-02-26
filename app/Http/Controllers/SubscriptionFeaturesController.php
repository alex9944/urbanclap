<?php

namespace App\Http\Controllers;
use DB;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Models\SubscriptionFeatures;
use App\Http\Controllers\Controller;



class SubscriptionFeaturesController extends Controller
{
    //
	public function index()
    {		
	   $features= SubscriptionFeatures::all();
	   
	   $subFeatObj = new SubscriptionFeatures;
	   $listing_models = $subFeatObj->listing_models;
	   
       return view('panels.admin.subscription.features',['features'=>$features, 'listing_models' => $listing_models]);
    }
	
	public function getfeatures(Request $request)
		{
			 $id=$request->id;  
			 $features = DB::table('subscription_features')
			 ->where('id', $id)	 
			 ->first();   
		 return '{"view_details": ' . json_encode($features) . '}';
	}
		
	public function added(Request $request)
	{
		
		$this->validate($request,
			 [
                'title'            			=> 'required',   
				'functionality_name'	=> 'required',
               
            ],
            [
                'title.required'   				=> 'Features Title is required',
                'functionality_name.required'   => 'Functionality is required',        
               
            ]);
				$adsposition = new SubscriptionFeatures;				
				$adsposition->title=$request->title;				
				$adsposition->functionality_name=$request->functionality_name;							
				$adsposition->save();
			return redirect('admin/subscription/features')->with('message','Features added successfully');
	}
	public function updated(Request $request)
	{	 
			
			$this->validate($request,
			 [
                'title'            			=> 'required',   
				'functionality_name'	=> 'required',
               
            ],
            [
                'title.required'   				=> 'Features Title is required',
                'functionality_name.required'   => 'Functionality is required',        
               
            ]);
			
				
				DB::table('subscription_features')
				->where('id', $request->id)
				->update(['title' => $request->title, 'functionality_name' => $request->functionality_name]);

	return redirect('admin/subscription/features')->with('message','Features updated successfully');
	}


	public function deleted(Request $request)
	{	 
	     $id=$request->id;  
		 $features = DB::table('subscription_features')
		 ->where('id', $id)
		 ->delete();
		 $status['deletedid']=$id;
		 $status['deletedtatus']='Features deleted successfully';
	 return '{"delete_details": ' . json_encode($status) . '}'; 
	
	}

	public function destroy(Request $request)
		{
			$cn=count($request->selected_id);
			if($cn>0)
			{
			$data = $request->selected_id;			
				foreach($data as $id) {
				DB::table('subscription_features')->where('id', $id)->delete();			
				}			
			} 
	return redirect('admin/subscription/features')->with('message','Seltected features are deleted successfully');			

	}
}