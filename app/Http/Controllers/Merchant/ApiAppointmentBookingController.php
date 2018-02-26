<?php

namespace App\Http\Controllers\Merchant;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\AppointmentBookingSettings;
use App\Models\AppointmentBookingOrder;
use App\Models\Category;
use App\Models\Listing;
use App\Models\MerchantServices;

use App\Events\AppointmentBookedStatus;
use Response;
use Validator;
use DB;
use Auth;
use Mail;


class ApiAppointmentBookingController extends Controller
{

	var $appointment_booking_id;
	var $AppointmentBookingSettings;
	var $appointment_booking_category_id_lists = [];
	
	public function __construct()
	{
		parent::__construct();
		
		$this->AppointmentBookingSettings = new AppointmentBookingSettings;
		$this->appointment_booking_id = $this->AppointmentBookingSettings->appointment_booking_id;
		
		$categories = Category::where('category_type', '!=', '')->get();
		foreach($categories as $category) {
			$category_type = json_decode($category->category_type);
			if( in_array($this->appointment_booking_id, $category_type)  )
				$this->appointment_booking_category_id_lists[] = $category->c_id;
		}//print_r($this->appointment_booking_category_id_lists);
	}
	
	
	
	public function add(Request $request)
	{		

				
		$rules = [
		 'user_id'	=> 'required',
			'listing_id'	=> 'required',
			'time_interval'	=> 'required|numeric',
		];
		
		$messages = [
			'user_id'	=> 'User id is required',
			'listing_id.required'	=> 'Listing id is required',
			'time_interval.required'=> 'Time interval is required',
		];
		
			$validator = \Validator::make($request->all(), $rules, $messages);
				
				 if ($validator->fails()) {
					$return['data'] = $validator->errors()->toArray();
					$return['msg'] = 'Validation Issue';
					return response()->json($return);
				}
				
			// add
		$listing = Listing::find($request->listing_id);
		
		$this->AppointmentBookingSettings->listing_id = $request->listing_id;
		$this->AppointmentBookingSettings->merchant_id = $request->user_id;
		$this->AppointmentBookingSettings->start_time = $request->start_time.$request->start_time_ar;
		$this->AppointmentBookingSettings->end_time = $request->end_time.$request->end_time_ar;
		$this->AppointmentBookingSettings->time_interval = $request->time_interval;
		$this->AppointmentBookingSettings->status = 1;
		
		$holidays = json_encode(array());
		if($request->holidays)
			$holidays = json_encode($request->holidays);
		
		$this->AppointmentBookingSettings->holidays=$holidays;
		
		$this->AppointmentBookingSettings->save();
		
		$id=$this->AppointmentBookingSettings->id;
		
		$return['appointment_booking'] = AppointmentBookingSettings::find($id);
				$return['status'] = 1;	
				$return['msg'] = "Success";
			return response()->json($return);
	}
	
	public function edit($id)
	{		

		$return['settings'] = AppointmentBookingSettings::find($id);
		$return['listing'] = $return['settings']->listing;

		// ajax
		return Response::json(array(
			'view_details'   => $return
			));
	}
	
	public function update(Request $request)
	{	

		
		
		// update
	//	$listing = Listing::find($request->listing_id);
	
	$rules = [
			'id'	=> 'required',
			'time_interval'	=> 'required|numeric',
		];
		
		$messages = [
			'id.required'	=> 'Id is required',
			'time_interval.required'=> 'Time interval is required',
		];
		
			$validator = \Validator::make($request->all(), $rules, $messages);
				
				 if ($validator->fails()) {
					$return['data'] = $validator->errors()->toArray();
					$return['msg'] = 'Validation Issue';
					return response()->json($return);
				}
						
		$settings = AppointmentBookingSettings::find($request->id);	
		$settings->start_time = $request->start_time.$request->start_time_ar;
		$settings->end_time = $request->end_time.$request->end_time_ar;
		$settings->time_interval = $request->time_interval;	
		
		$holidays = json_encode(array());
		if($request->holidays)
			$holidays = json_encode($request->holidays);
		
		$settings->holidays = $holidays;
		$settings->save();
		
		$return['appointment_booking'] = $settings;
				$return['status'] = 1;	
				$return['msg'] = "Success";
			return response()->json($return);
	}
	
	
	
	
	
	public function getAppointments(){
		$user_id=Auth::user()->id;
		$allBookings=AppointmentBookingOrder::orderBy('order_date','desc')->where('merchant_id',$user_id)->get();
		return view('panels.merchant.appointmentbookings',compact('allBookings'));
	}
	public function enableBooking(Request $request)
	{
		$app_booking = AppointmentBookingOrder::find($request->id);
		
		if($app_booking)
		{
			$app_booking->status = 1;
			$app_booking->save();		
			
			event(new AppointmentBookedStatus($app_booking));
			
			\Session::flash('message', 'Order accepted successfully.');

			return \Response::json(array(
					'success' => true,
				'msg'   => 'Success'
				));
		}
		
		return \Response::json(array(
			'success' => false,
			'msg'   => 'Invalid Id'
			));
	}	
	
	public function enable($merchant_id)
	{	 
		$merchant_service_id = null;
		
		$services = $this->services;
		
		$shop_category_type_id = $services['appointment']['id'];
			
		$merchant_services = MerchantServices::where('merchant_id',$merchant_id)->get();
		foreach($merchant_services as $merchant_service) {
			$category_type_id = $merchant_service->category_type_id;
			
			if($category_type_id == $shop_category_type_id) {
				$merchant_service_id = $merchant_service->id;
				break;
			}
		}
				
		if($merchant_service_id)
		{
			$merchant_service = MerchantServices::find($merchant_service_id);
			$merchant_service->category_type_id = $shop_category_type_id;
			$merchant_service->merchant_id = $merchant_id;
			$merchant_service->is_enable = 1;
			$merchant_service->save();
		}
		else
		{
			$merchant_service = new MerchantServices;
			$merchant_service->category_type_id = $shop_category_type_id;
			$merchant_service->merchant_id = $merchant_id;
			$merchant_service->is_enable = 1;
			$merchant_service->save();
		}
		
		$return['status'] = 1;
		$return['msg'] = 'Service activated successfully';
	
		return response()->json($return);
	}
	
	public function disable($merchant_id)
	{	 
		$merchant_service_id = null;
		
		$services = $this->services;
		
		$shop_category_type_id = $services['appointment']['id'];
		
		$merchant_services = MerchantServices::where('merchant_id',$merchant_id)->get();
		foreach($merchant_services as $merchant_service) {
			$category_type_id = $merchant_service->category_type_id;
			
			if($category_type_id == $shop_category_type_id) {
				$merchant_service_id = $merchant_service->id;
				break;
			}
		}
		
		if($merchant_service_id)
		{
			$merchant_service = MerchantServices::find($merchant_service_id);
			$merchant_service->category_type_id = $shop_category_type_id;
			$merchant_service->merchant_id = $merchant_id;
			$merchant_service->is_enable = 0;
			$merchant_service->save();
		}
		else
		{
			$merchant_service = new MerchantServices;
			$merchant_service->category_type_id = $shop_category_type_id;
			$merchant_service->merchant_id = $merchant_id;
			$merchant_service->is_enable = 0;
			$merchant_service->save();
		}
		
		$return['status'] = 1;
		$return['msg'] = 'Service Deactivate successfully';
		return response()->json($return);
	}
	
	public function disableBooking(Request $request)
	{
		$app_booking = AppointmentBookingOrder::find($request->id);
		
		if($app_booking)
		{
			$app_booking->status = 2;
			$app_booking->save();
			
			event(new AppointmentBookedStatus($app_booking));
			
			\Session::flash('message', 'Order declined.');

			return \Response::json(array(
					'success' => true,
					'msg'   => 'Success'
					));
		}
		
		return \Response::json(array(
			'success' => false,
			'msg'   => 'Invalid Id'
			));
	}
}