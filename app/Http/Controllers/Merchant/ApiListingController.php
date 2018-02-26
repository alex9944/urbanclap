<?php

namespace App\Http\Controllers\Merchant;

use DB;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Listing;
use App\Models\User;
use App\Models\MultiLanguage;
use App\Models\Country;
use App\Models\States;
use App\Models\Cities;
use App\Models\Category;
use App\Models\CategorySlug;
use App\Models\CategoryTags;
use App\Models\ListingImages;
use App\Models\MerchantServices;
use App\Models\TableBookingSettings;
use App\Models\ListingTags;
use App\Models\ShopProductImages;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Image;
use Session;


class ApiListingController extends Controller
{

	
/*
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
	} */
 protected function validator(array $data)
    {
		
		$validator = Validator::make($data,
            [
			'user_id'		=> 'required',			
			'scategory'		=> 'required',
			'title'			=> 'required|unique:listing',
			'website'		=> 'required',
			'holidays'		=> 'required',
			//'country'		=> 'required',
			'states'		=> 'required',
			'cities'		=> 'required',
			'address1'		=> 'required',		
			'postcode'		=> 'required',
			'phoneno'		=> 'required',			
			'description'	=> 'required'	
			
			
		],[
			'user_id.required'	=> 'User id is required',
			'scategory.required'	=> 'Subcategory is required',
			'title.required'		=> 'Title is required',
			'website.required'		=> 'Website required',
			'holidays.required'		=> 'Select Holidays',
			//'country.required'		=> 'Country is required',
			'states.required'		=> 'States is required',
			'cities.required'		=> 'Cities is required',
			'address1.required'		=> 'Address  is required',		
			'postcode.required'		=> 'Postcode is required',
			'phoneno.required'		=> 'Phoneno is required',		
			'description.required'	=> 'Description is required'
		
			
		]);
		

       return $validator;

   }
   
   protected function validator_update(array $data)
    {
		
		$validator = Validator::make($data,
            [		
			'id'		=> 'required',			
			'scategory'		=> 'required',
			'title'			=> 'required',
			'website'		=> 'required',
			'holidays'		=> 'required',
			//'country'		=> 'required',
			'states'		=> 'required',
			'cities'		=> 'required',
			'address1'		=> 'required',		
			'postcode'		=> 'required',
			'phoneno'		=> 'required',			
			'description'	=> 'required'	
			
			
		],[
			'id.required'	=> 'Listing id is required',
			'scategory.required'	=> 'Subcategory is required',
			'title.required'		=> 'Title is required',
			'website.required'		=> 'Website required',
			'holidays.required'		=> 'Select Holidays',
			//'country.required'		=> 'Country is required',
			'states.required'		=> 'States is required',
			'cities.required'		=> 'Cities is required',
			'address1.required'		=> 'Address  is required',		
			'postcode.required'		=> 'Postcode is required',
			'phoneno.required'		=> 'Phoneno is required',		
			'description.required'	=> 'Description is required'
		
			
		]);
		

       return $validator;

   }
   
	public function added(Request $request)
	{


$validator = $this->validator($request->all());
		
		if ($validator->fails()) {    
		
			$return['msg'] = $validator->messages();	
			
		} else {
				
	//	$user_id = (auth()->check()) ? auth()->user()->id : null;
		
		$tags = array();
		if($request->tags)
			$tags = $request->tags;//json_encode( CategoryTags::find(array_values($request->tags)) );
		$user=User::find($request->user_id);
		$subscribed_category=$user->category_id;
		
		
		$listing = new Listing;				
		$listing->user_id = $request->user_id ;
		$listing->l_id = 1;// eng - default $request->language_id;
		$listing->m_c_id=$subscribed_category;
		$listing->s_c_id=$request->scategory;
		$listing->website=$request->website;
		$listing->working_hours=$request->start_time.$request->start_time_ar.'-'.$request->end_time.$request->end_time_ar;
		if($request->holidays)
			$listing->holidays=json_encode($request->holidays);
		else
			$listing->holidays=json_encode(array('no'));
		$listing->title=$request->title;
		$listing->slug = str_slug($request->title, '-');
		$listing->c_id=$this->default_country->id;
		$listing->state=$request->states;
		$listing->city=$request->cities;
		$listing->address1=$request->address1;
		$listing->pincode=$request->postcode;
		$listing->phoneno=$request->phoneno;
		$listing->image='';
		$listing->status='Disable';//'Enable';//
		$listing->meta_tag=$request->meta_tag;
		$listing->meta_description=$request->meta_description;
		$listing->description=$request->description;
		$listing->lattitude=round($request->lattitude, 7);
		$listing->longitude=round($request->longitude,7);
		$listing->save();
		
		// add listing tags
		$tag_arr = [];
		foreach($tags as $category_tag_id)
		{
			$tag_arr[] = array('category_tag_id' => $category_tag_id, 'listing_id' => $listing->id);
		}
		if(!empty($tag_arr))
		ListingTags::insert($tag_arr);
		
		    $return['msg'] = 'Success';
			$return['status'] = 1;
			$return['listing'] = Listing::with('listing_city', 'listing_state', 'subcategory')->find($listing->id);
	
		}
			return response()->json($return);
	}
	
	
	
	public function updated(Request $request)
	{	 
		
		
		
		
		$validator = $this->validator_update($request->all());
		
		if ($validator->fails()) {    
		
			$return['msg'] = $validator->messages();	
			
		} else {
			$tags = [];
		if($request->tags)
			$tags = $request->tags;//json_encode( CategoryTags::find(array_values($request->tags)) );
		
		
		$holidays = json_encode(array('no'));
		if($request->holidays)
			$holidays = json_encode($request->holidays);
		
		DB::table('listing')
			->where('id', $request->id)
			->update([		
			's_c_id' =>$request->scategory,
			'website'=>$request->website,
			'working_hours'=>$request->start_time.$request->start_time_ar.'-'.$request->end_time.$request->end_time_ar,
			'holidays'=>$holidays,
			'title' =>$request->title,
			//'slug' => str_slug($request->title, '-'),
			'c_id' =>$this->default_country->id,
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
		
				// add listing tags
				$tag_arr = [];
				foreach($tags as $category_tag_id)
				{
					//$tag_arr[] = array('category_tag_id' => $category_tag_id, 'listing_id' => $listing->id);
					$listing_tag = ListingTags::firstOrNew(array('category_tag_id' => $category_tag_id, 'listing_id' => $request->id));
					$listing_tag->save();
				}
				
				$return['msg'] = 'Success';
				$return['status'] = 1;
				$return['listing'] = Listing::with('listing_city', 'listing_state', 'subcategory')->find($request->id);;
	
		}
			return response()->json($return);
}



public function listing_upload_image_android(Request $request)
	{
		$return = array('status' => 0, 'msg' => 'Listing Id is required.');
		
		$id = $request->id;
		
		if($id)
		{
			$listing = Listing::find($id);
			
			if($listing)
			{
				$rules = [
					'photo'			=> 'required|image|mimes:jpeg,png,jpg,gif|max:1024'
				];				
				$messages = [
					'photo.required'			=> 'required|image|mimes:jpeg,png,jpg,gif,svg|max:1024',
					'photo.image'           	=> 'Image should be a jpeg,png,gif',
				];
				
				$validator = \Validator::make($request->all(), $rules, $messages);
				
				 if ($validator->fails()) {
					$return['data'] = $validator->errors()->toArray();
					$return['msg'] = 'Validation Issue';
					return response()->json($return);
				}
				
				// validation ok				
				$photo = $request->file('photo');
				$imagename = time() . '_' . rand(10,100) . '_' . strtolower($photo->getClientOriginalName());
				$destinationPath = public_path('/uploads/listing/thumbnail');
				$thumb_img = Image::make($photo->getRealPath())->resize(100, 100);
				$thumb_img->save($destinationPath.'/'.$imagename,80);                    
				$destinationPath = public_path('/uploads/listing/original');
				$photo->move($destinationPath, $imagename);
				
				$listing->image = $imagename;	
				$listing->save();
			
				$return = array('status' => 1, 'listing' => $listing, 'msg' => 'Success');
			}
		}
		
		return response()->json($return);
	}
	
	public function listing_upload_image_ios(Request $request)
	{
		$return = array('status' => 0, 'msg' => 'Listing Id is required.');
		
		$id = $request->id;
		
		if($id)
		{
			$listing = Listing::find($id);
			
			if($listing)
			{
				$rules = [
					'photo'			=> 'required'
				];				
				$messages = [
					'photo.required'			=> 'required',
				];
				
				$validator = \Validator::make($request->all(), $rules, $messages);
				
				 if ($validator->fails()) {
					$return['data'] = $validator->errors()->toArray();
					$return['msg'] = 'Validation Issue';
					return response()->json($return);
				}
				
							
					$destinationPath = public_path('/uploads/listing/original/');
					/*$image_parts = explode(";base64,",$request->photo);
					$image_type_aux = explode("image/", $image_parts[0]);
					$image_type = $image_type_aux[1];
					$image_base64 = base64_decode($image_parts[1]);*/
					$image = $request->photo;
					$image_base64 = base64_decode($image);
					
					$file = uniqid() . '.png';
					$gfile=$destinationPath . $file;
					file_put_contents($gfile, $image_base64);
					$thumb_img = Image::make($destinationPath . $file)->crop(100, 100);
					$destinationThumPath = public_path('/uploads/listing/thumbnail');
				    $thumb_img->save($destinationThumPath.'/'.$file,80); 
				
				$listing->image = $file;	
				$listing->save();
				
			
				$return = array('status' => 1, 'listing' => $listing, 'msg' => 'Success');
			}
		}
		
		return response()->json($return);
	}

	

		
}