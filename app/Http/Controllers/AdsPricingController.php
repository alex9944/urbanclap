<?php

namespace App\Http\Controllers;
use DB;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Models\AdsPricing;
use App\Models\AdsPosition;
use App\Models\AdsDuration;
use App\Models\AdsModeAdvertisement;
use App\Models\AdsSlotPlacement;
use App\Models\Country;
use App\Models\States;
use App\Models\Cities;
use App\Models\Category;
use App\Http\Controllers\Controller;



class AdsPricingController extends Controller
{
    
	var $ads_mode_advertisement_id = 4;// listing page from table
	
	//
	public function index()
	{		
	   //$pricing= AdsPricing::all();
		$adsposition= AdsPosition::all();
		$duration= AdsDuration::all();
		$ads_mode_advertisement_id= $this->ads_mode_advertisement_id;
		$slotplacement= AdsSlotPlacement::all();

		$country=$this->default_country->id;
		$states=States::where('country_id',$country)->get();

		$Category=Category::where('parent_id','0')->get();

		$pricing = DB::table('ads_pricing')
		->select('*', 'ads_pricing.id as pid','ads_mode_advertisement.title as amd_title','ads_position.title as aptitle')
		->leftJoin('ads_mode_advertisement', 'ads_pricing.m_a_id', '=', 'ads_mode_advertisement.id')
		->leftJoin('ads_position', 'ads_pricing.a_p_id', '=', 'ads_position.id')		
		->get();

		return view('panels.admin.ads.pricing',['pricing'=>$pricing,'adsposition'=>$adsposition,'duration'=>$duration,'ads_mode_advertisement_id'=>$ads_mode_advertisement_id,'slotplacement'=>$slotplacement, 'editadsposition'=>$adsposition,'edit_adsposition'=>$adsposition,'edit_duration'=>$duration,'edit_slotplacement'=>$slotplacement,'states'=>$states,'Category'=>$Category]);
	}
	
	public function getpricing(Request $request)
	{
		$country=Country::where('default','1')->value('id');
		$states=States::where('country_id',$country)->get();

		$Category=Category::where('parent_id','0')->get();
		$id=$request->id;  
		$pricing = DB::table('ads_pricing')
		->where('id', $id)	 
		->first();   
		return '{"view_details": ' . json_encode($pricing) . ',"states":'.json_encode($states).',"Category":'.json_encode($Category).'}';
	}

	public function added(Request $request)
	{
		
		$this->validate($request,
			[
				'category'	=> 'required', 
				's_category'	=> 'required', 
				'state'	=> 'required', 
				'city'	=> 'required', 
				'mode'	=> 'required', 
				'zone'	=>	'required',
				'price'            	=> 'required',               

			],
			[
				'category.required'   	=> 'Category is required',
				's_category.required'   => 'Subcategory is required',
				'state.required'   	=> 'State is required',
				'city.required'   	=> 'City is required',
				'mode.required'   	=> 'Mode is required',
				'zone.required'		=> 'Zone is required',
				'price.required'   	=> 'Price is required',


			]);
		$pricing = new AdsPricing;				
		$pricing->m_a_id=$request->modeads;
		$pricing->a_p_id=$request->adsposition;
		$pricing->a_s_p_id=$request->slotplacement;
		$pricing->d_id=$request->duration;	
		$pricing->price=$request->price;
		$pricing->cat_id=$request->category;
		$pricing->s_cat_id=$request->s_category;
		$pricing->state_id=$request->state;
		$pricing->city_id=$request->city;
		$pricing->mode=$request->mode;
		$pricing->zone=$request->zone;

		$pricing->save();
		return redirect('admin/ads/pricing')->with('message','Pricing added successfully');
	}
	public function updated(Request $request)
	{	 

		$this->validate($request,
			[
				'category'	=> 'required', 
				's_category'	=> 'required', 
				'state'	=> 'required', 
				'city'	=> 'required', 
				'mode'	=> 'required', 
				'zone'	=>	'required',
				'price'            	=> 'required',               

			],
			[
				'category.required'   	=> 'Category is required',
				's_category.required'   => 'Subcategory is required',
				'state.required'   	=> 'State is required',
				'city.required'   	=> 'City is required',
				'mode.required'   	=> 'Mode is required',
				'zone.required'		=> 'Zone is required',
				'price.required'   	=> 'Price is required',


			]);


		DB::table('ads_pricing')
		->where('id', $request->id)
		->update([				
			'm_a_id'=>$request->modeads,
			'a_p_id'=>$request->adsposition,
			'a_s_p_id'=>$request->slotplacement,
			'd_id'=>$request->duration,	
			'price'=>$request->price,
			'cat_id'=>$request->category,
			's_cat_id'=>$request->s_category,
			'state_id'=>$request->state,
			'city_id'=>$request->city,
			'mode'	=>$request->mode,
			'zone'	=>$request->zone,
		]);

		return redirect('admin/ads/pricing')->with('message','Pricing updated successfully');
	}


	public function deleted(Request $request)
	{	 
		$id=$request->id;  
		$pricing = DB::table('ads_pricing')
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
				DB::table('ads_pricing')->where('id', $id)->delete();			
			}			
		} 
		return redirect('admin/ads/pricing')->with('message','Seltected mode pricing are deleted successfully');			

	}
}