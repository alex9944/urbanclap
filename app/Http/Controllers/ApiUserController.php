<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\AppointmentBookingOrder;
use App\Models\ListingReview;
use App\Models\TableBookingOrder;
use App\Models\OrderBooking;
use App\Models\OrderBookingDetail;
use App\Models\ShopOrderBookingDetail;
use App\Models\Contacts;
use App\Models\Usercampaigns;
use App\Models\MerchantOrder;
use App\Models\Orders;
use App\Models\LocalvendorMarketing;
use App\Models\LocalVendorUsage;

use App\Events\QrScan;
use auth;
use Image;

class ApiUserController extends ApiController
{
	var $user = null;
	
	public function __construct(Request $request)
	{
		parent:: __construct();
		
	}
	
	public function filterRequests($route, $request)
    {
        // check if user exists
		$return = array('status' => 0, 'msg' => 'Invalid User.');
			
		if( isset($request->user_id) )
		{
			$user = User::find($request->user_id);
			
			if($user)
				$this->user = $user;	
		
		}
		
		if($this->user == null)
			return response()->json($return);
    }

	public function update_profile(Request $request) 
	{
		$data['status']='';
		if(!empty($request->uid)){		
			$user=DB::table('users')
			->where('id', $request->uid)->first();
				if($user)
				{					
					$photo = $request->file('photo');
					 if($photo){
							$imagename = time().'.'.$photo->getClientOriginalExtension();   
							$destinationPath = public_path('/uploads/thumbnail');
							$thumb_img = Image::make($photo->getRealPath())->resize(100, 100);
							$thumb_img->save($destinationPath.'/'.$imagename,80);                    
							$destinationPath = public_path('/uploads/original');
							$photo->move($destinationPath, $imagename);
								  DB::table('users')
								->where('id', $request->uid)
								->update(['image' => $imagename,]);						
							}
					
					
								$user=DB::table('users')
								->where('id', $request->uid)
								->update(['first_name' => $request->name,'phone_no' => $request->phone]);	
								//dd(DB::getQueryLog());
								 $usub = DB::table('users')
								->where('id', $request->uid)			
								->first();
								$response['users_profile']=$usub;
							
									$response['status'].="Success";
								return json_encode($response);
				}else{					
							$response['status'].="Invalid User";
						return json_encode($response);
				}
		}else{
						$response['status'].="User id empty";
						return json_encode($response);
			
		}
		
	}
	
	public function update_profile_data(Request $request)
	{
		$return = array('status' => 0, 'msg' => 'User Id is required.');
		
		$user_id = $request->user_id;
		
		if($user_id)
		{
			$user = User::find($user_id);
			
			if($user)
			{
				$rules = [
					'name'			=> 'required',
					'email'			=> 'required|email|unique:users,email,'.$user->id,
					'mobile_no'		=>'required|numeric|unique:users,mobile_no,'.$user->id,
				];				
				$messages = [
					'name.required'   		=> 'Name is required',
					'email.required'        => 'Email is required',
					'email.email' 			=> 'Email is invalid',
					'mobile_no.required'    => 'Mobile No is required',
					'mobile_no.numeric'     => 'Enter Valid Mobile number',
				];
				
				$validator = \Validator::make($request->all(), $rules, $messages);
				
				 if ($validator->fails()) {
					$return['data'] = $validator->errors()->toArray();
					$return['msg'] = 'Validation Issue';
					return response()->json($return);
				}
				
				// validation ok
				$user->first_name = $request->name;
				$user->website = $request->website;
				$user->bio = $request->bio;
				$user->email = $request->email;
				$user->mobile_no = $request->mobile_no;
				$user->gender = $request->gender;	
				$user->save();
				
				$return = array('status' => 1, 'data' => $user, 'msg' => 'Success');
			}
		}
		
		return response()->json($return);
	}
	
	public function update_photo(Request $request)
	{
		$return = array('status' => 0, 'msg' => 'User Id is required.');
		
		$user_id = $request->user_id;
		
		if($user_id)
		{
			$user = User::find($user_id);
			
			if($user)
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
				$destinationPath = public_path('/uploads/thumbnail');
				$thumb_img = Image::make($photo->getRealPath())->resize(100, 100);
				$thumb_img->save($destinationPath.'/'.$imagename,80);                    
				$destinationPath = public_path('/uploads/original');
				$photo->move($destinationPath, $imagename);
				
				$user->image = $imagename;	
				$user->save();
			
				$return = array('status' => 1, 'data' => $user, 'msg' => 'Success');
			}
		}
		
		return response()->json($return);
	}
	
	public function update_photo_ios(Request $request)
	{
		$return = array('status' => 0, 'msg' => 'User Id is required.');
		
		$user_id = $request->user_id;
		
		if($user_id)
		{
			$user = User::find($user_id);
			
			if($user)
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
				
							
					$destinationPath = public_path('/uploads/original/');
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
					$destinationThumPath = public_path('/uploads/thumbnail');
				    $thumb_img->save($destinationThumPath.'/'.$file,80); 
				
				$user->image = $file;	
				$user->save();
				
			
				$return = array('status' => 1, 'data' => $user, 'msg' => 'Success');
			}
		}
		
		return response()->json($return);
	}
	
	public function update_password($uid,$password) {
		if(!empty($uid)){
						$user=DB::table('users')
						->where('id', $uid)
						->update(['password' => \Hash::make($password)]);	
						return json_encode($user);
		}
		
	}
	
	// qr scan
	public function qr_scan(Request $request)
	{
		$return = array('status' => 0, 'msg' => 'User Id and Qr Code are required.');
		
		$user_id = $request->user_id;
		$qr_code = $request->qr_code;
		
		if($user_id and $qr_code)
		{
			$user = User::find($user_id);
			$local_vendor_marketing = LocalvendorMarketing::where('unique_code', $qr_code)->first();
			
			if($user and $local_vendor_marketing)
			{
				// check if code already used with the scheduled period
				$make_to_use = true;
				$LocalVendorUsage = LocalVendorUsage::where([
															'local_vendor_marketing_id' => $local_vendor_marketing->id, 
															'usage_type' 	=> 'scan',
															'user_id'		=> $user_id
															])->orderBy('created_at', 'Desc')->first();
				if($LocalVendorUsage)
				{
					$last_used_time = strtotime($LocalVendorUsage->created_at);
					$current_time = time();
					$hourdiff = round(($current_time - $last_used_time)/3600, 1);
					$scanning_user_period = $this->site_settings->scanning_user_period;// hrs
					
					if($hourdiff < $scanning_user_period) {
						$make_to_use = false;// prevent to use
						$next_to_use_hr = $scanning_user_period - $hourdiff;
					}
				}
				
				if($make_to_use)
				{
					$obj = new \stdClass;
					$obj->user = $user;
					$obj->local_vendor_marketing = $local_vendor_marketing;
					
					event(new QrScan($obj));
					
					$return = array('status' => 1, 'data' => $user, 'msg' => 'Success');
				}
				else
				{
					$return['msg'] = 'Qr Code already used for the scanning vendor. Please use after '. $next_to_use_hr . ' hours for the same vendor';
					$return['next_to_use_hour'] = $next_to_use_hr;
				}
			}
			else
			{
				$return['msg'] = 'Invalid User Id or Qr Code';
			}
		}
		
		return response()->json($return);
	}
	
	public function get_table_bookings(Request $request)
	{
		$return = array('status' => 0, 'msg' => 'User Id is required.');
		
		$user_id = $request->user_id;
		
		if($user_id)
		{
			$data = TableBookingOrder::with('main_order', 'listing')->where('user_id', $user_id)->orderBy('order_date', 'DESC')->get();
			
			$return = array('status' => 1, 'data' => $data, 'msg' => 'Success');
		}
		
		return response()->json($return);
	}
	
	public function get_appointment_bookings(Request $request)
	{
		$return = array('status' => 0, 'msg' => 'User Id is required.');
		
		$user_id = $request->user_id;
		
		if($user_id)
		{
			$data = AppointmentBookingOrder::with('main_order', 'listing')->where('user_id', $user_id)->orderBy('order_date', 'DESC')->get();
			
			$return = array('status' => 1, 'data' => $data, 'msg' => 'Success');
		}
		
		return response()->json($return);
	}
	
	public function get_food_orders(Request $request)
	{
		$return = array('status' => 0, 'msg' => 'User Id is required.');
		
		$user_id = $request->user_id;
		
		if($user_id)
		{
			$data = OrderBooking::whereHas('merchant_orders', function ($query) {
							$query->where('order_type', 'online_order');
						})->with([
							'main_order', 
							'merchant_orders' => function ($query1) {
									$query1->where('order_type', 'online_order')->with([
											'order_details.menu_item',
											'listing'
									]);
							}                                                                                        
						])
						->where('user_id', $user_id)->orderBy('created_at', 'DESC')->get();
			
			$return = array('status' => 1, 'data' => $data, 'msg' => 'Success');
		}
		
		return response()->json($return);
	}
	
	public function get_product_orders(Request $request)
	{
		$return = array('status' => 0, 'msg' => 'User Id is required.');
		
		$user_id = $request->user_id;
		
		if($user_id)
		{
			$data = OrderBooking::whereHas('merchant_orders', function ($query) {
							$query->where('order_type', 'online_shopping');
						})->with([
							'main_order', 
							'merchant_orders' => function ($query1) {
									$query1->where('order_type', 'online_shopping')->with([
											'order_details.shop_item',
											'listing'
									]);
							}                                                                                        
						])
						->where('user_id', $user_id)->orderBy('created_at', 'DESC')->get();
			
			$return = array('status' => 1, 'data' => $data, 'msg' => 'Success');
		}
		
		return response()->json($return);
	}
	
	public function get_orders_history(Request $request)
	{
		$return = array('status' => 0, 'msg' => 'User Id is required.');
		
		$user_id = $request->user_id;
		
		if($user_id)
		{
			$data = Orders::with([
									'order_booking.merchant_orders' => function ($query) {
										$query->with([
											'listing', 
											'order_details' => function ($subquery) {
												$subquery->with('menu_item', 'shop_item');
											}
										]);
									},
									'appointment_booking.listing',
									'table_booking.listing'
								])->where('user_id', $user_id)->orderBy('created_at', 'DESC')->get();
			
			$return = array('status' => 1, 'data' => $data, 'msg' => 'Success');
		}
		
		return response()->json($return);
	}
	
	public function get_reviews(Request $request)
	{
		$return = array('status' => 0, 'msg' => 'User Id is required.');
		
		$user_id = $request->user_id;
		
		if($user_id)
		{
			$data = ListingReview::with('listing.listing_city')->where(['user_id' => $user_id, 'approved' => 1])->get();
			
			$return = array('status' => 1, 'data' => $data, 'msg' => 'Success');
		}
		
		return response()->json($return);
	}
	public function tableBooking($user_id)
	{
		
		$data['table_bookings'] = TableBookingOrder::where('user_id', $user_id)->get();
		
	return json_encode($data);
	}
	
	// save contacts
	public function save_contact(Request $request)
	{			
		$return = array('status' => 0, 'msg' => 'User Id is required.');
		
		$user_id = $request->user_id;
		
		if($user_id)
		{
			$userObj = new User;
			$return = $userObj->_addContact($user_id, $request, $return);
		}
		
		return response()->json($return);
	}
	
	// get contacts
	public function get_contacts(Request $request)
	{			
		$return = array('status' => 0, 'msg' => 'User Id is required.');
		
		$user_id = $request->user_id;
		
		if($user_id)
		{
			$all_contacts = Contacts::with([
				'listing' => function ($query) {
					$query->with([
						'reviews' => function ($subquery) {
							$subquery
							->selectRaw('listing_id, AVG(rating) AS average_rating')
							->where('approved','1')
							->groupBy('listing_id');
						},
					]);
				},
			])->where(['user_id' => $user_id])->get();
			
			$return = array('status' => 1, 'data' => $all_contacts, 'msg' => 'Success');
		}
		
		return response()->json($return);
	}
	

}