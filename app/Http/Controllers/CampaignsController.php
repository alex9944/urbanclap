<?php

namespace App\Http\Controllers;
use DB;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Campaigns;
use App\Models\User;
use App\Models\MultiLanguage;
use App\Models\Country;
use App\Models\States;
use App\Models\Cities;
use App\Models\Category;
use App\Models\AdsPricing;
use App\Models\AdsPosition;
use App\Models\AdsDuration;
use App\Models\AdsModeAdvertisement;
use App\Models\AdsSlotPlacement;
use App\Http\Controllers\Controller;
use Image;

use Mail;
use App\Mail\NeedifoMail;


class CampaignsController extends Controller
{
    //
	public function index()
    {		
	   $campaigns= Campaigns::all();
	   $language= MultiLanguage::all();
	   $country= Country::all();
	   //$category= Category::all();
	   $category = DB::table('category')
			 ->get();
	    $users = DB::table('users')
			->select('*', 'users.id as uid')
			->leftJoin('role_user', 'users.id', '=', 'role_user.user_id')
			->where('role_user.role_id', '=', 3)
			->get();
		
		$subcategory = DB::table('category')
			 ->where('parent_id','!=', '0')
			 ->get();
		$editstates = DB::table('states')			 
			 ->get();
		$editcities = DB::table('cities')			 
			 ->get();
	   $adsposition= AdsPosition::all();
	   $duration= AdsDuration::all();
	   $modeads= AdsModeAdvertisement::all();
	   $slotplacement= AdsSlotPlacement::all(); 
	   
       return view('panels.admin.merchants.campaigns',['campaigns'=>$campaigns,'users' => $users,'language' => $language,'country' => $country,'category' => $category,'editusers' => $users,'editlanguage' => $language,'editcountry' => $country,'editcategory' => $category,'editsubcategory' => $subcategory,'editstates' => $editstates,'editcities' => $editcities,'adsposition'=>$adsposition,'duration'=>$duration,'modeads'=>$modeads,'slotplacement'=>$slotplacement, 'editadsposition'=>$adsposition,'editadsposition'=>$adsposition,'editduration'=>$duration,'editmodeads'=>$modeads,'editslotplacement'=>$slotplacement,]);
    }
	
	public function getcampaigns(Request $request)
		{
			 $id=$request->id;  
			 $listing = DB::table('campaigns')
			 ->where('id', $id)	 
			 ->first();   
		 return '{"view_details": ' . json_encode($listing) . '}';
	}
	
	public function getsubcategory(Request $request)
		{
			 $id=$request->id;  
			 $category = DB::table('category')
			 ->where('parent_id', $id)	 
			 ->get();   
		 return '{"view_details": ' . json_encode($category) . '}';
	}
	
	public function getstates(Request $request)
		{
			 $id=$request->id;  
			 $states = DB::table('states')
			 ->where('country_id', $id)	 
			 ->get();   
		 return '{"view_details": ' . json_encode($states) . '}';
	}
	
	public function getcities(Request $request)
		{
			 $id=$request->id;  
			 $cities = DB::table('cities')
			 ->where('state_id', $id)	 
			 ->get();   
		 return '{"view_details": ' . json_encode($cities) . '}';
	}
		
	public function added(Request $request)
	{
		
		
		
		  $this->validate($request,
			 [
			 
		'user_id'		=> 'required',
		'language_id'	=> 'required',
		'category'		=> 'required',
		'position_id'	=> 'required',
		'modeads_id'	=> 'required',
		'slotplacement_id'	=> 'required',
		'duration_id'	=> 'required',
		'title'			=> 'required',
		'country'		=> 'required',
		'states'		=> 'required',
		'cities'		=> 'required',		
		'photo'			=> 'required|image|mimes:jpeg,png,jpg,gif,svg|max:1024',	
		
               
            ],
            [
			'user_id.required'		=> 'Username is required',
			'language_id.required'	=> 'Language is required',
			'category.required'		=> 'Category is required',
			'position_id.required'		=> 'Position is required',
			'modeads_id.required'		=> 'Modeads is required',
			'slotplacement_id.required'	=> 'Slot placement is required',
			'duration_id.required'		=> 'Duration is required',
			'title.required'			=> 'Title is required',
			'country.required'		=> 'Country is required',
			'states.required'		=> 'States is required',
			'cities.required'		=> 'Cities is required',
			'photo.required'			=> 'required|image|mimes:jpeg,png,jpg,gif,svg|max:1024',
			'photo.image'           	=> 'Image should be a jpeg,png,gif,svg',			
			
                     ]);
			
				$photo = $request->file('photo');
				$imagename = time().'.'.$photo->getClientOriginalExtension();   
				$destinationPath = public_path('/uploads/campaigns/thumbnail');
				$thumb_img = Image::make($photo->getRealPath())->crop(100, 100);
				$thumb_img->save($destinationPath.'/'.$imagename,80);                    
				$destinationPath = public_path('/uploads/campaigns/original');
				$photo->move($destinationPath, $imagename);
			
				$campaigns = new Campaigns;				
				$campaigns->user_id=$request->user_id;
				$campaigns->lang_id=$request->language_id;
				$campaigns->cat_id=$request->category;
				$campaigns->position_id=$request->position_id;
				$campaigns->modeads_id=$request->modeads_id;
				$campaigns->slotplacement_id=$request->slotplacement_id;
				$campaigns->duration_id=$request->duration_id;				
				$campaigns->title=$request->title;
				$campaigns->c_id=$request->country;
				$campaigns->state=$request->states;
				$campaigns->city=$request->cities;				
				$campaigns->image=$imagename;						
				$campaigns->save();
				
				
				$user = new User;	
				$users=$user->get_user($request->user_id);
				$email=$users->email;
				$first_name=$users->first_name;			
				Mail::send('emails.campaign_activate_reminder', ['first_name' => $first_name], function ($m) use ($user) {
				$m->from('info@needifo.com', 'Needifo');
				$m->to('alex@wifintech.com', 'Alex')->subject('Your Reminder Activate Campaign!');
					});


			return redirect('admin/merchants/campaigns')->with('message','Campaigns added successfully');
	}
	public function updated(Request $request)
	{	 
 $this->validate($request,
			 [
			 
		'user_id'		=> 'required',
		'language_id'	=> 'required',
		'category'		=> 'required',
		'position_id'	=> 'required',
		'modeads_id'	=> 'required',
		'slotplacement_id'	=> 'required',
		'duration_id'	=> 'required',
		'title'			=> 'required',
		'country'		=> 'required',
		'states'		=> 'required',
		'cities'		=> 'required',
            ],
            [
			'user_id.required'		=> 'Username is required',
			'language_id.required'	=> 'Language is required',
			'category.required'		=> 'Category is required',
			'position_id.required'		=> 'Position is required',
			'modeads_id.required'		=> 'Modeads is required',
			'slotplacement_id.required'	=> 'Slot placement is required',
			'duration_id.required'		=> 'Duration is required',
			'title.required'			=> 'Title is required',
			'country.required'		=> 'Country is required',
			'states.required'		=> 'States is required',
			'cities.required'		=> 'Cities is required',
			'photo.required'			=> 'required|image|mimes:jpeg,png,jpg,gif,svg|max:1024',
						
			
                     ]);
			$photo = $request->file('photo');
			 if($photo){
					$imagename = time().'.'.$photo->getClientOriginalExtension();   
					$destinationPath = public_path('/uploads/listing/thumbnail');
					$thumb_img = Image::make($photo->getRealPath())->crop(100, 100);
					$thumb_img->save($destinationPath.'/'.$imagename,80);                    
					$destinationPath = public_path('/uploads/listing/original');
					$photo->move($destinationPath, $imagename);
						  DB::table('campaigns')
						->where('id', $request->id)
						->update(['image' => $imagename,]);
			 }
			
				DB::table('campaigns')
				->where('id', $request->id)
				->update([
				
				'user_id' =>$request->user_id,
				'lang_id' =>$request->language_id,
				'cat_id' =>$request->category,
				'position_id' =>$request->position_id,
				'modeads_id' =>$request->modeads_id,
				'slotplacement_id' =>$request->slotplacement_id,
				'duration_id' =>$request->duration_id,				
				'title' =>$request->title,
				'c_id' =>$request->country,
				'state' =>$request->states,
				'city' =>$request->cities,
				
				]);

	return redirect('admin/merchants/campaigns')->with('message','Campaigns updated successfully');
	}
	public function enable(Request $request)
		{	 
		$id=$request->id;
		DB::table('campaigns')
		->where('id', $request->id)
		->update(['status' => 'Disable',]);
		$status['deletedtatus']='Campaigns status updated successfully';
		 return '{"delete_details": ' . json_encode($status) . '}'; 
		
		}
	public function disable(Request $request)
		{	 
		$id=$request->id;
		DB::table('campaigns')
		->where('id', $request->id)
		->update(['status' => 'Enable',]);
		$status['deletedtatus']='Campaigns status updated successfully';
		 return '{"delete_details": ' . json_encode($status) . '}'; 
		
		}

	public function deleted(Request $request)
		{	 
		$id=$request->id;  
			 $blogs = DB::table('campaigns')
			 ->where('id', $id)
			 ->delete();
			 $status['deletedid']=$id;
			 $status['deletedtatus']='Campaigns deleted successfully';
		 return '{"delete_details": ' . json_encode($status) . '}'; 
		
		}

	public function destroy(Request $request)
	{
			$cn=count($request->selected_id);
			if($cn>0)
			{
			$data = $request->selected_id;			
				foreach($data as $id) {
				DB::table('campaigns')->where('id', $id)->delete();			
				}			
			} 
		return redirect('admin/merchants/listing')->with('message','Seltected campaigns are deleted successfully');			

	}
}