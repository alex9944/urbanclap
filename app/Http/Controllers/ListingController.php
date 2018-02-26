<?php

namespace App\Http\Controllers;
use DB;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Listing;
use App\Models\User;
use App\Models\Merchants;
use App\Models\MultiLanguage;
use App\Models\Country;
use App\Models\States;
use App\Models\Cities;
use App\Models\Category;
use App\Models\CategorySlug;
use App\Models\OrderOnlineSettings;

use App\Events\ListingCreated;
use App\Http\Controllers\Controller;
use Image;


class ListingController extends Controller
{
    //
	public function index()
    {		
		$listing= Listing::all();
		$language= MultiLanguage::all();
		$country= Country::all();
	   
		$subcategory = array();
		$states = array();
		$cities = array();
		$tags = array();
		
		if( old('user_id') != '')
		{
			$user = User::find(old('user_id'));
			$subcategory = Category::where('parent_id', $user->category_id)->get();
		}
		
		if( old('country', $this->default_country->id) != '')
		{
			$states = States::where('country_id','=', old('country', $this->default_country->id))->get();
		}
		if( old('states') != '')
		{
			$cities = Cities::where('state_id','=', old('states'))->get();
		}
		
		$times = array();
		for($i = 1; $i <= 12; $i++)
		{
			$times[$i] = $i;
		}
		
		// get users don't have listing		
		$users = User::whereHas(
			'roles', function($q){
				$q->where('name', 'merchant');
			}
		)->where(['merchant_status' => 1, 'activated' => 1])->where('category_id', '<>', '')->doesntHave('listing')->get();
			 
       return view('panels.admin.merchants.index',['listing'=>$listing,'users' => $users,'country' => $country, 'states' => $states, 'cities' => $cities, 'subcategory' => $subcategory, 'times'=>$times]);
    }
	
	public function getlisting(Request $request)
	{
		$id=$request->id;  
		$listing = Listing::with('listing_merchant')->find($id);
		
		$subcategory = Category::where('parent_id', $listing->m_c_id)->get();
		$cities = Cities::where('state_id','=', $listing->state)->get();
		$states = States::where('country_id','=', $listing->c_id)->get();
		$ordersettings = OrderOnlineSettings::where('merchant_id', '=', $listing->user_id)->first();
		
		$data['listing'] = $listing;
		$data['subcategory'] = $subcategory;
		$data['states'] = $states;
		$data['cities'] = $cities;
		$data['ordersettings'] = $ordersettings;
		
		return '{"view_details": ' . json_encode($data) . '}';
	}
	
	public function getsubcategory(Request $request)
	{
		$user_id = $request->id;  
		$user = User::find($user_id);
		$subcategory = Category::where('parent_id', $user->category_id)->get();  
		
		return '{"view_details": ' . json_encode($subcategory) . '}';
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
		
		$validate_rules = [
			'user_id'		=> 'required',
			//'language_id'	=> 'required',
			//'category'		=> 'required',
			//'scategory'		=> 'required',
			'title'			=> 'required|unique:listing',
			//'website'		=> 'required',
			//'holidays'		=> 'required',
			'country'		=> 'required',
			'states'		=> 'required',
			'cities'		=> 'required',
			'address1'		=> 'required',		
			'postcode'		=> 'required',
			'phoneno'		=> 'required',
			//'image_field'	=> 'required',	
			'description'	=> 'required',	
			//'lat_long'		=> 'required',
			'lattitude'		=> 'required',
			'longitude'		=> 'required',
			'photo'			=> 'required|image|mimes:jpeg,png,jpg,gif,svg|max:1024',
			'delivery_charge'=> 'numeric'
		];
		
		$validate_messages = [
			'user_id.required'		=> 'Username is required',
			//'language_id.required'	=> 'Language is required',
			//'category.required'		=> 'Category is required',
			//'scategory.required'	=> 'Subcategory is required',
			'title.required'		=> 'Title is required',
			//'website.required'		=> 'Website required',
			//'holidays.required'		=> 'Select Holidays',
			'country.required'		=> 'Country is required',
			'states.required'		=> 'States is required',
			'cities.required'		=> 'Cities is required',
			'address1.required'		=> 'Address 1 is required',		
			'postcode.required'		=> 'Postcode is required',
			'phoneno.required'		=> 'Phoneno is required',
			//'image_field.required'	=> 'Image is required',
			'description.required'	=> 'Description is required',
			//'lat_long.required'		=> 'Lattitude and Longitude is required',
			'lattitude.required'		=> 'Lattitude is required',
			'longitude.required'		=> 'Longitude is required',
			'photo.required'			=> 'Image is required',
			'photo.image'           	=> 'Image should be a jpeg,png,gif,svg',
		];
		
		
		$this->validate($request, $validate_rules, $validate_messages);
		
		$photo = $request->file('photo');
		$imagename = time() . '_' . rand(10,100) . '_' . strtolower($photo->getClientOriginalName());
		$destinationPath = public_path('/uploads/listing/thumbnail');
		$thumb_img = Image::make($photo->getRealPath())->crop(305, 119);
		$thumb_img->save($destinationPath.'/'.$imagename,80);                    
		$destinationPath = public_path('/uploads/listing/original');
		$photo->move($destinationPath, $imagename); 
		
		$user = User::find($request->user_id);
		
		$listing = new Listing;				
		$listing->user_id = $request->user_id;
		$listing->l_id = 1;// eng - default $request->language_id;
		$listing->m_c_id = $user->subscribed_category->c_id;
		$listing->s_c_id=$request->scategory;
		$listing->website=$request->website;
		$listing->working_hours=$request->start_time.$request->start_time_ar.'-'.$request->end_time.$request->end_time_ar;
		if($request->holidays)
			$listing->holidays=json_encode($request->holidays);
		$listing->title=$request->title;
		$listing->slug = str_slug($request->title, '-');
		$listing->c_id=$request->country;
		$listing->state=$request->states;
		$listing->city=$request->cities;
		$listing->address1=$request->address1;
		//$listing->address2=$request->address2;
		$listing->pincode=$request->postcode;
		$listing->phoneno=$request->phoneno;
		$listing->image=$imagename;
		$listing->status='Enable';//'Disable';//
		$listing->meta_tag=$request->meta_tag;
		$listing->meta_description=$request->meta_description;
		$listing->description=$request->description;	
		//$listing->category_tags	= $tags;
		$listing->lattitude=round($request->lattitude, 7);
		$listing->longitude=round($request->longitude, 7);
		//$listing->lat_long=$request->lat_long;
		$listing->save();
		
		// delivery charge update
		if($request->delivery_charge) {
			$this->_add_delevery_fee($request->user_id, $request->delivery_charge);
		}
		
		event(new ListingCreated($listing));
		
		return redirect('admin/merchants/listing')->with('message','Listing added successfully');
	}
	
	private function _add_delevery_fee($merchant_id, $delivery_fee)
	{			
		$ordersettings = OrderOnlineSettings::where('merchant_id', '=', $merchant_id)->first();
		if($ordersettings)
		{
			// add	
			$ordersettings = OrderOnlineSettings::find($ordersettings->id);			
			$ordersettings->delivery_fee = $delivery_fee;		
			$ordersettings->save();
		}
		else
		{
			$OrderOnlineSettings = new OrderOnlineSettings;
			$OrderOnlineSettings->merchant_id = $merchant_id;
			$OrderOnlineSettings->delivery_fee = $delivery_fee;		
			$OrderOnlineSettings->save();
		}
	}
	
	
	public function updated(Request $request)
	{	 
		//$listing = Listing::find($request->id);
		
		$validate_rules = [
			'title'			=> 'required|unique:listing,title,'.$request->id,
			'country'		=> 'required',
			'states'		=> 'required',
			'cities'		=> 'required',
			'address1'		=> 'required',		
			'postcode'		=> 'required',
			'phoneno'		=> 'required',
			//'image_field'	=> 'required',		
			'description'	=> 'required',	
			//'lat_long'		=> 'required',
			'lattitude'		=> 'required',
			'longitude'		=> 'required',
            //'photo'			=> 'required|image|mimes:jpeg,png,jpg,gif,svg|max:1024'		,
			'delivery_charge'=> 'numeric'	
               
        ];
		
		$validate_messages = [
			'title.required'		=> 'Title is required',
			'country.required'		=> 'Country is required',
			'states.required'		=> 'States is required',
			'cities.required'		=> 'Cities is required',
			'address1.required'		=> 'Address 1 is required',		
			'postcode.required'		=> 'Postcode is required',
			'phoneno.required'		=> 'Phoneno is required',
			//'image_field.required'	=> 'Image is required',						
			'description.required'	=> 'Description is required',
			//'photo.required'			=> 'Image is required',
			//'photo.image'           	=> 'Image should be a jpeg,png,gif,svg',
			//'lat_long.required'		=> 'Lattitude and Longitude is required'
			'lattitude.required'		=> 'Lattitude is required',
			'longitude.required'		=> 'Longitude is required',
		];
		
		$this->validate($request, $validate_rules, $validate_messages);
		
		$photo = $request->file('photo');
		if($photo)
		{
			$imagename = time() . '_' . rand(10,100) . '_' . strtolower($photo->getClientOriginalName()); 
			$destinationPath = public_path('/uploads/listing/thumbnail');
			$thumb_img = Image::make($photo->getRealPath())->crop(305, 119);
			$thumb_img->save($destinationPath.'/'.$imagename,80);                    
			$destinationPath = public_path('/uploads/listing/original');
			$photo->move($destinationPath, $imagename);
				  DB::table('listing')
				->where('id', $request->id)
				->update(['image' => $imagename,]);
		}
		$holidays = '';
		if($request->holidays)
			$holidays = json_encode($request->holidays);
		
		DB::table('listing')
		->where('id', $request->id)
		->update([
		//'l_id' =>$request->language_id,
		//'m_c_id' =>$request->category,
		's_c_id' =>$request->scategory,
		'website'=>$request->website,
		'working_hours'=>$request->start_time.$request->start_time_ar.'-'.$request->end_time.$request->end_time_ar,
		'holidays'=>$holidays,
		'title' =>$request->title,
		'slug' => str_slug($request->title, '-'),
		'c_id' =>$request->country,
		'state' =>$request->states,
		'city' =>$request->cities,
		'address1' =>$request->address1,
		//'address2' =>$request->address2,
		'pincode' =>$request->postcode,
		'phoneno' =>$request->phoneno,
		'meta_tag' =>$request->meta_tag,
		'meta_description' =>$request->meta_description,
		'description' =>$request->description,	
		//'lat_long' =>$request->lat_long,	
		'lattitude' => round($request->lattitude, 7),
		'longitude' => round($request->longitude,7),
		//'category_tags'	=> $tags
		]);
		
		$listing = Listing::find($request->id);
		
		// delivery charge update
		if($request->delivery_charge) {
			$this->_add_delevery_fee($listing->user_id, $request->delivery_charge);
		}
		
		event(new ListingCreated($listing));

		return redirect('admin/merchants/listing')->with('message','Listing updated successfully');
	}
	public function enable(Request $request)
		{	 
		$id=$request->id;
		DB::table('listing')
		->where('id', $request->id)
		->update(['status' => 'Disable',]);
		$status['deletedtatus']='Listing status updated successfully';
		 return '{"delete_details": ' . json_encode($status) . '}'; 
		
		}
	public function disable(Request $request)
		{	 
		$id=$request->id;
		DB::table('listing')
		->where('id', $request->id)
		->update(['status' => 'Enable',]);
		$status['deletedtatus']='Listing status updated successfully';
		 return '{"delete_details": ' . json_encode($status) . '}'; 
		
		}

	public function deleted(Request $request)
	{	 
		$id=$request->id;  
		
		$success_msg = 'Seltected row deleted successfully.';
		
		return $this->_delete($id, $success_msg); 
		
		/*$listing = Listing::find($id);
		if($listing)
		{
			$listing->listing_images()->delete();
			$listing->tablebookingsettings()->delete();
			$listing->appointmentbookingsettings()->delete();
			$listing->menu_items()->delete();
			$listing->reviews()->delete();
			$listing->delete();
			
			\Session::flash('message', 'Selected row deleted successfully.');
			
			return \Response::json(array(
				'success' => true,
				'msg'   => 'Seltected row deleted successfully.'
				));
		}
		
		return \Response::json(array(
			'success' => false,
			'msg'   => 'Invalid Id'
			)); */
		
	}
	
	private function _delete($id, $success_msg, $is_continue = false)
	{
		$listing = Listing::find($id);		
		$tablebookingsettings = $listing->tablebookingsettings;//print_r($tablebookingsettings);
		$appointmentbookingsettings = $listing->appointmentbookingsettings;
		$menu_items = $listing->menu_items;
		
		$error_msg = '';
		
		if(isset($tablebookingsettings) and $tablebookingsettings) {			
			if(!$is_continue)
			{
				return \Response::json(array(
					'success' => false,
					'msg'   => 'Remove all tablebooking settings before delete listing.'
					));
			}
			else
			{
				$error_msg = 'Some listings not deleted, because related tablebooking settings exists.';
			}
		}
		if(isset($appointmentbookingsettings) and $appointmentbookingsettings) {				
			if(!$is_continue)
			{		
				return \Response::json(array(
					'success' => false,
					'msg'   => 'Remove all appointmentbooking settings before delete listing.'
					));
			}
			else
			{
				$error_msg = 'Some listings not deleted, because related appointmentbooking settings exists.';
			}
		}
		if(isset($menu_items) and $menu_items->count() > 0) {				
			if(!$is_continue)
			{		
				return \Response::json(array(
					'success' => false,
					'msg'   => 'Remove all menu items before delete listing.'
					));
			}
			else
			{
				$error_msg = 'Some listings not deleted, because related menu items exists.';
			}
		}
		
		if(!$is_continue)
		{
			$listing->listing_images()->delete();
			$listing->tablebookingsettings()->delete();
			$listing->appointmentbookingsettings()->delete();
			$listing->menu_items()->delete();
			$listing->reviews()->delete();
			$listing->delete();
			
			\Session::flash('message', $success_msg);
				
			return \Response::json(array(
				'success' => true,
				'msg'   => $success_msg
				));
		}
		else if(!$error_msg)
		{
			$listing->listing_images()->delete();
			$listing->tablebookingsettings()->delete();
			$listing->appointmentbookingsettings()->delete();
			$listing->menu_items()->delete();
			$listing->reviews()->delete();
			$listing->delete();
		}
		
		return $error_msg;
	}

	public function destroy(Request $request)
	{
		$msg = 'Seltected category are deleted successfully';
		$error_msg = '';
		
		$cn=count($request->selected_id);
		if($cn>0)
		{
			$data = $request->selected_id;			
			foreach($data as $id) {
				
				$return_msg = $this->_delete($id, $msg, true);
			
				if($return_msg)
					$error_msg = $return_msg;
				
				/*$listing = Listing::find($id);
				$listing->listing_images()->delete();
				$listing->tablebookingsettings()->delete();
				$listing->appointmentbookingsettings()->delete();
				$listing->menu_items()->delete();
				$listing->reviews()->delete();
				$listing->delete();*/
				//DB::table('listing')->where('id', $id)->delete();			
			}			
		} 
		
		if($error_msg)
			return redirect('admin/merchants/listing')->with('error_message', $error_msg);
		
		return redirect('admin/merchants/listing')->with('message', $msg);	
		//return redirect('admin/merchants/listing')->with('message','Seltected listing are deleted successfully');			

	}
}