<?php

namespace App\Http\Controllers;
use DB;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Models\SubscriptionPricing;
use App\Models\Country;
use App\Models\Currency;
use App\Models\SubscriptionPlan;
use App\Models\SubscriptionDuration;
use App\Models\SubscriptionFeatures;


use App\Http\Controllers\Controller;
use Image;


class SubscriptionPricingController extends Controller
{
    //
	public function index()
    {		
	
		//$pricing= SubscriptionPricing::all();
		
		$pricing = DB::table('subscription_pricing')
			->select('*', 'subscription_pricing.id as pid')
			//->leftJoin('countries', 'countries.id', '=', 'subscription_pricing.c_id')
			->leftJoin('subscription_plan', 'subscription_plan.id', '=', 'subscription_pricing.p_id')
			//->leftJoin('currency', 'currency.id', '=', 'subscription_pricing.cur_id')		
			->orderBy('subscription_pricing.order_by', 'ASC')
			->get();//print_r($pricing);exit;
		
		
		//$country= Country::all()->where('status','1');
		//$currency= Currency::all();
		$plan= SubscriptionPlan::all();
		$duration= SubscriptionDuration::all();
		$features= SubscriptionFeatures::all();
	
       return view('panels.admin.subscription.pricing',['pricing'=>$pricing,'plan'=>$plan,'duration'=>$duration,'features'=>$features,'edit_features'=>$features,'edit_plan'=>$plan,'edit_duration'=>$duration]);
    }
	
	public function getpricing(Request $request)
		{
			
		$id=$request->id;  
		$pricing= SubscriptionPricing::all()->where('id', $id)->first();   
		return '{"view_details": ' . json_encode($pricing) . '}';
	}
	
	public function getcurrency(Request $request)
		{
		     $id=$request->id;    
			 $currency= Currency::all()
			 ->where('c_id', $id)	 
			 ->first();    
			 return '{"view_details": ' . json_encode($currency) . '}';
	}
	
	
		
	public function added(Request $request)
	{
		 
			$this->validate($request,
			 [
                //'country'            	=> 'required',
                //'currency'             	=> 'required',
                'plan'                  => 'required',
                'duration'             	=> 'required',
                'features' 				=> 'required',
				'price' 				=> 'required',
               
            ],
            [
                //'country.required'   	=> 'Country is required',
                //'currency.required'    	=> 'Currency is required',
                'plan.required'        => 'Plan is required',             
                'duration.required'     => 'Duration is required',               
				'features.required'    => 'Features is required',
				'price.required'    => 'Amount is required',
               
            ]);								
				
				$pricing = new SubscriptionPricing;
				//$pricing->c_id=$request->country;
				//$pricing->cur_id=$request->currency;
				$pricing->f_id=implode(',',$request->features);				
				$pricing->p_id=$request->plan;
				$pricing->d_id=$request->duration;
				$pricing->order_by=$request->order_by;				
				$pricing->price=$request->price;				
				$pricing->description=$request->description;				
				$pricing->save();
	
			return redirect('admin/subscription/pricing')->with('message','Pricing added successfully');
	}
	public function updated(Request $request)
	{	 
	
	
		$this->validate($request,
			 [
                //'country'            	=> 'required',
                //'currency'             	=> 'required',
                'plan'                  => 'required',
                'duration'             	=> 'required',
                'features' 				=> 'required',
				'price' 				=> 'required',
               
            ],
            [
                //'country.required'   	=> 'Country is required',
                //'currency.required'    	=> 'Currency is required',
                'plan.required'        => 'Plan is required',             
                'duration.required'     => 'Duration is required',               
				'features.required'    => 'Features is required',
				'price.required'    => 'Amount is required',
               
            ]);	

			
				DB::table('subscription_pricing')
				->where('id', $request->id)
				->update(['f_id' => implode(',',$request->features),'p_id' => $request->plan,'d_id' => $request->duration,'price' => $request->price, 'order_by'=>$request->order_by, 'description' => $request->description]);

	return redirect('admin/subscription/pricing')->with('message','Pricing updated successfully');
	}


	public function deleted(Request $request)
	{	 
	$id=$request->id;  
		 $pricing = DB::table('subscription_pricing')
		 ->where('id', $id)
		 ->delete();
		 $status['deletedid']=$id;
		 $status['deletedtatus']='Pricing deleted successfully';
	 return '{"delete_details": ' . json_encode($status) . '}'; 
	
	}

	public function destroy(Request $request)
		{
			$cn=count($request->selected_id);
			if($cn>0)
			{
			$data = $request->selected_id;			
				foreach($data as $id) {
				DB::table('subscription_pricing')->where('id', $id)->delete();			
				}			
			} 
	return redirect('admin/subscription/pricing')->with('message','Seltected Pricing are deleted successfully');			

	}
}