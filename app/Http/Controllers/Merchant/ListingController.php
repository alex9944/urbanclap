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

use App\Http\Controllers\Controller;
use Image;
use Session;


class ListingController extends Controller
{

	public function index()
	{	

		$user_id = (auth()->check()) ? auth()->user()->id : null;
		$listing = Listing::where('user_id', '=', $user_id)->first();
		
		$merchant_services = MerchantServices::where(['merchant_id' => $user_id, 'is_enable' => 1])->get();
		
		// times
		$times = array();
		for($i = 1; $i <= 12; $i++)
		{
			$times[$i] = $i;
		}
		
		$country = Country::all();
		$categories = Category::where('parent_id', '0')->get();
		$subcategory = array();
		$states = array();
		$cities = array();
		$tags = array();
		
		if($this->subscribed_category)
		{
			$category_id = $this->subscribed_category->c_id;		
			$subcategory = Category::where('parent_id','=', $category_id)->get();
		}
		
		if( old('scategory') != '')
		{
			$scategory = Category::where('c_id', old('scategory'))->first();
			$tags = $scategory->category_tags;
		}
		
		if( old('country') != '')
		{
			$states = States::where('country_id','=', old('country'))->get();
		}else if($listing){
			$states = States::where('country_id','=', $listing->c_id)->get();
		}
		if( old('states') != '')
		{
			$cities = Cities::where('state_id','=', old('states'))->get();
		}else if($listing){
			$cities = Cities::where('state_id','=', $listing->state)->get();
		}

		//return view('panels.merchant.merchants.index', compact('listing', 'country', 'categories', 'subcategory', 'states', 'cities', 'tags', 'merchant_services', 'times'));
		
		if($listing) // update
		{
			$compact_array = compact('listing', 'country', 'categories', 'subcategory', 'states', 'cities', 'tags', 'merchant_services', 'times','images');
			return $this->_loadMerchantView('listing.listing', $compact_array); 
			//return view('panels.merchant.merchants.index', compact('listing', 'country', 'categories', 'subcategory', 'states', 'cities', 'tags', 'merchant_services', 'times'));
		}
		else // add
		{
			$compact_array = compact('country', 'categories', 'subcategory', 'states', 'cities', 'tags', 'merchant_services', 'times');
			return $this->_loadMerchantView('listing.add', $compact_array);
		}
	}

	public function getlisting(Request $request)
	{
		$id=$request->id;  
		$listing = Listing::find($id);

		$data['listing'] = $listing;
		$data['listing_images'] = $listing->listing_images;
		$data['tablebookingsettings'] = $listing->tablebookingsettings;

		$category = Category::where('c_id', $listing->m_c_id)->first();
		$data['subcategories'] = $category->subcategories;
		
		$subcategory = Category::where('c_id', $listing->s_c_id)->first();
		$data['tags'] = $subcategory->category_tags;
		$data['listing_tags'] = $listing->listing_tags;

		$country = Country::where('id', $listing->c_id)->first();
		$data['states'] = $country->states;

		$state = States::where('id', $listing->state)->first();
		$data['cities'] = $state->cities;

		return '{"view_details": ' . json_encode($data) . '}';
	}

	public function getsubcategory(Request $request)
	{
		$id=$request->id;  
		$return['category'] = DB::table('category')
		->where('parent_id', $id)	 
		->get(); 

		$category = Category::where('c_id', $request->id)->first();
		$return['tags'] = $category->category_tags;

		return '{"view_details": ' . json_encode($return) . '}';
	}

	public function gettags(Request $request)
	{
		$category = Category::where('c_id', $request->id)->first();
		$tags = $category->category_tags;

		return '{"view_details": ' . json_encode($tags) . '}';
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


		/*$listing_image_names = (array) $request->session()->get('listing_image_names');//print_r($listing_image_names);exit;
		$image_valid = '';
		if(! empty($listing_image_names))
			$image_valid = 'yes';
		$request->request->add(['image_field' => $image_valid]);*/
		
		
		$validate_rules = [
			//'user_id'		=> 'required',
			//'language_id'	=> 'required',
			//'category'		=> 'required',
			//'scategory'		=> 'required',
			'title'			=> 'required|unique:listing',
			//'website'		=> 'required',
			//'holidays'		=> 'required',
			//'country'		=> 'required',
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
			'photo'			=> 'required|image|mimes:jpeg,png,jpg,gif,svg|max:1024'
		];
		
		$validate_messages = [
			'title.required'		=> 'Title is required',
			//'website.required'		=> 'Website required',
			'states.required'		=> 'States is required',
			'cities.required'		=> 'Cities is required',
			'address1.required'		=> 'Address  is required',		
			'postcode.required'		=> 'Postcode is required',
			'phoneno.required'		=> 'Phoneno is required',
			//'image_field.required'	=> 'Image is required',
			'description.required'	=> 'Description is required',
			//'lat_long.required'		=> 'Lattitude and Longitude is required',
			'photo.required'			=> 'Image is required',
			'photo.image'           	=> 'Image should be a jpeg,png,gif,svg',
			'lattitude.required'		=> 'Lattitude is required',
			'longitude.required'		=> 'Longitude is required',
		];
				
		$this->validate($request, $validate_rules, $validate_messages);
		
		$user_id = (auth()->check()) ? auth()->user()->id : null;
		
		$tags = array();
		if($request->tags)
			$tags = $request->tags;//json_encode( CategoryTags::find(array_values($request->tags)) );
		
		$photo = $request->file('photo');
		$imagename = time() . '_' . rand(10,100) . '_' . strtolower($photo->getClientOriginalName());
		$destinationPath = public_path('/uploads/listing/thumbnail');
		$thumb_img = Image::make($photo->getRealPath())->crop(305, 119);
		$thumb_img->save($destinationPath.'/'.$imagename,80);                    
		$destinationPath = public_path('/uploads/listing/original');
		$photo->move($destinationPath, $imagename); 
			
		$listing = new Listing;				
		$listing->user_id = $user_id;
		$listing->l_id = 1;// eng - default $request->language_id;
		$listing->m_c_id=$this->subscribed_category->c_id;
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
		//$listing->address2=$request->address2;
		$listing->pincode=$request->postcode;
		$listing->phoneno=$request->phoneno;
		$listing->image=$imagename;
		$listing->status='Disable';//'Enable';//
		$listing->meta_tag=$request->meta_tag;
		$listing->meta_description=$request->meta_description;
		$listing->description=$request->description;	
		//$listing->category_tags	= $tags;
		$listing->lattitude=round($request->lattitude, 7);
		$listing->longitude=round($request->longitude,7);
		//$listing->lat_long=$request->lat_long;
		$listing->save();
		
		// add listing tags
		$tag_arr = [];
		foreach($tags as $category_tag_id)
		{
			$tag_arr[] = array('category_tag_id' => $category_tag_id, 'listing_id' => $listing->id);
		}
		if(!empty($tag_arr))
		ListingTags::insert($tag_arr);
		
		
				
		// add images
		/*$listing_image_lists = [];
		foreach($listing_image_names as $listing_image_name)
		{
			$listing_image_lists[] = ['image_name' => $listing_image_name];
		}//print_r($listing_images);exit;
		
		if(count($listing_image_lists) > 1)
			$listing->listing_images()->createMany($listing_image_lists);
		else
			$listing->listing_images()->create($listing_image_lists[0]);
		
		Session::forget('listing_image_names');
		*/
				
		return redirect('merchant/listing')->with('message','Listing added successfully. Once it will be moderated by admin then it will be published.');
	}
	public function updated(Request $request)
	{	 
		
		$listing = Listing::find($request->id);
		
		/*$listing_image_names = (array) $request->session()->get('listing_image_names');//print_r($listing_image_names);exit;
		$image_valid = '';
		if(! empty($listing_image_names))
		{
			$image_valid = 'yes';
		}
		else
		{
			$listing_images = $listing->listing_images;
			if($listing_images)
				$image_valid = 'yes';
		}
		$request->request->add(['image_field' => $image_valid]);*/
		
		
		

		$validate_rules = [
			'title'			=> 'required',
			//'website'		=> 'required',
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
            'photo'			=> 'image|mimes:jpeg,png,jpg,gif,svg|max:1024'			
               
        ];
		
		$validate_messages = [
			'title.required'		=> 'Title is required',
			//'website.required'		=> 'Website required',
			'states.required'		=> 'States is required',
			'cities.required'		=> 'Cities is required',
			'address1.required'		=> 'Address  is required',		
			'postcode.required'		=> 'Postcode is required',
			'phoneno.required'		=> 'Phoneno is required',
			//'image_field.required'	=> 'Image is required',						
			'description.required'	=> 'Description is required',
			//'photo.required'			=> 'Image is required',
			'photo.image'           	=> 'Image should be a jpeg,png,gif,svg',
			'lattitude.required'		=> 'Lattitude is required',
			'longitude.required'		=> 'Longitude is required',
		];
		
		$this->validate($request, $validate_rules, $validate_messages);
			
		$tags = [];
		if($request->tags)
			$tags = $request->tags;//json_encode( CategoryTags::find(array_values($request->tags)) );
		
		$photo = $request->file('photo');
		if($photo) {
			$imagename = time() . '_' . rand(10,100) . '_' . strtolower($photo->getClientOriginalName()); 
			$destinationPath = public_path('/uploads/listing/thumbnail');
			$thumb_img = Image::make($photo->getRealPath())->crop(305, 119);
			$thumb_img->save($destinationPath.'/'.$imagename,80);                    
			$destinationPath = public_path('/uploads/listing/original');
			$photo->move($destinationPath, $imagename);
				  DB::table('listing')
				->where('id', $request->id)
				->update(['image' => $imagename]);
		}

	
		$holidays = json_encode(array('no'));
		if($request->holidays)
			$holidays = json_encode($request->holidays);
		
		//print_r($holidays);exit;
		
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
	
		return redirect('merchant/listing')->with('message','Listing updated successfully');
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
		$blogs = DB::table('listing')
		->where('id', $id)
		->delete();
		$status['deletedid']=$id;
		$status['deletedtatus']='Listing deleted successfully';
		return '{"delete_details": ' . json_encode($status) . '}'; 
		
	}

	public function destroy(Request $request)
	{
		$cn=count($request->selected_id);
		if($cn>0)
		{
			$data = $request->selected_id;			
			foreach($data as $id) {
				DB::table('listing')->where('id', $id)->delete();			
			}			
		} 
		return redirect('merchant/listing')->with('message','Seltected listing are deleted successfully');			

	}
	public function merchantProfile(){
		return view('panels.merchant.front.profile');
	}
	public function getMerchantData(Request $request){
		$merchantid=$request->id;
		$mobile_no=DB::table('users')
		->where('id', $request->id)->value('mobile_no');
		DB::table('users')
		->where('mobile_no', $mobile_no)
		->update(['otp_valid' =>'1']);
		return $mobile_no;
	}
	public function updateMerchantOtp(Request $request){
		$mobile_no=$request->mobile;
		$otp=$request->otp;
		$affectedrows=DB::table('users')
		->where('mobile_no', $mobile_no)
		->update(['otp_code' =>$otp],['otp_valid','1']);
		return $affectedrows;
	}
	public function updateMerchantValid(Request $request){
		DB::table('users')
		->where('mobile_no', $request->mobile)
		->update(['otp_valid' =>'0']);
		return 1;
	}
	public function CheckValidOtp(Request $request){
		$merchant=DB::table('users')
		->where('id',$request->id)
		->where('otp_valid','1')
		->get();
		if(sizeof($merchant)>0){
			$upd_array=array('otp_valid'=>'0','verified'=>'1');
			DB::table('users')
			->where('id', $request->id)
			->update($upd_array);
			return 1;
		}
		else{
			return 0;
		}

	}
	public function imageUpload(Request $request)
	{		
		$this->validate($request, [

			'file' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',

			]);
		
		$photo = $request->file('file'); //print_r($photo);
		$imagename = time() . '_' . rand(10,100) . '_' . strtolower($photo->getClientOriginalName());   
		
		// thumb img
		$destinationPath = public_path('upload/images/small');
		$thumb_img = Image::make($photo->getRealPath())->resize(120, 100);
		$thumb_img->save($destinationPath.'/'.$imagename,80);   

		// original img
		$destinationPath = public_path('upload/images/medium');
		$medium_img = Image::make($photo->getRealPath())->resize(500, 416);
		$medium_img->save($destinationPath.'/'.$imagename,80);
		
		$destinationPath = public_path('upload/images');
		$photo->move($destinationPath, $imagename);
		
		// add name to session array
		//$listing_image_names = (array) session('listing_image_names');
		$listing_image_names = (array) $request->session()->get('listing_image_names');
		//print_r($listing_image_names);
		$image_id = count($listing_image_names)+1;
		$listing_image_names[$image_id] = $imagename;//print_r($listing_image_names);
		//session(['listing_image_names' => $listing_image_names]);
		$request->session()->put('listing_image_names', $listing_image_names);
		$request->session()->save();
		
		return response()->json(['status' => 1, 'msg' => 'success']);
	}
	
	public function getUploadedImages(Request $request)
	{
		$listing_image_names = session('listing_image_names');
		$data = array();
		$cnt = 0;
		
		if($listing_image_names)
			foreach($listing_image_names as $image_id => $image) {
				$data[$cnt] = new \stdClass();
				$data[$cnt]->id = $image_id;
				$data[$cnt]->image_name = $image;
				$cnt++;
			}

			return response()->json($data);
		}

		public function deleteUploadedImage($id)
		{	

		//Session::forget('listing_image_names.0');
			$listing_image_names = session('listing_image_names');

			if($listing_image_names)
				foreach($listing_image_names as $image_id => $image) {

					if($image_id == $id) {
				// Delete a single file
						@unlink(public_path('/upload/images/small').'/'.$image);
						@unlink(public_path('/upload/images/medium').'/'.$image);

				// Unset session
						Session::forget('listing_image_names.' . $image_id);
						break;
					}
				}

				return response()->json(['status' => 1, 'msg' => 'success']);

		//$listing_image_names = (array) session('listing_image_names');//print_r($listing_image_names);

			}

			public function deleteUploadedImageFromTable($id)
			{	

				$listing_image = ListingImages::find($id);

		// Delete a single file
				@unlink(public_path('/upload/images/small').'/'.($listing_image->image_name));
				@unlink(public_path('/upload/images/medium').'/'.($listing_image->image_name));

				$listing_image->delete();

				return response()->json(['status' => 1, 'msg' => 'success']);

			}
}