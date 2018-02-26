<?php

namespace App\Http\Controllers\Merchant;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\TableBookingOrder;
use App\Models\ServiceBookingSettings;
use App\Models\Category;
use App\Models\Listing;
use App\Models\MerchantServices;

use App\Events\TableBookedStatus;
use Response;
use Validator;
use DB;
use Auth;
use Mail;
class ApiServiceBookingSettingsController extends Controller
{

	var $table_booking_id;
	var $ServiceBookingSettings;
	var $table_booking_category_id_lists = [];
	
	public function __construct()
	{
		parent::__construct();
		
		$this->ServiceBookingSettings = new ServiceBookingSettings;
		$this->table_booking_id = $this->ServiceBookingSettings->table_booking_id;
		
		$categories = Category::where('category_type', '!=', '')->get();
		foreach($categories as $category) {
			$category_type = json_decode($category->category_type);
			if( in_array($this->table_booking_id, $category_type)  )
				$this->table_booking_category_id_lists[] = $category->c_id;
		}//print_r($this->appointment_booking_category_id_lists);
	}
	
	public function enableBooking(Request $request)
	{
		$tablebooking = TableBookingOrder::find($request->id);
		
		if($tablebooking)
		{
			$tablebooking->status = 1;
			$tablebooking->save();		
			
			event(new TableBookedStatus($tablebooking));			
		
			return \Response::json(array(
					'status' => 1,
				'msg'   => 'Success'
				));
		}
		
		return \Response::json(array(
			'status' => 0,
			'msg'   => 'Invalid Id'
			));
	}
	public function disableBooking(Request $request)
	{
		$tablebooking = TableBookingOrder::find($request->id);
		
		if($tablebooking)
		{
			$tablebooking->status = 2;
			$tablebooking->save();
			
			event(new TableBookedStatus($tablebooking));
			
			

			return \Response::json(array(
					'status' => 1,
					'msg'   => 'Success'
					));
		}
		
		return \Response::json(array(
			'status' => 0,
			'msg'   => 'Invalid Id'
			));
	}
	public function add(Request $request)
	{		

		$rules = [
		'user_id'	=> 'required',
    	'listing_id'	=> 'required',
    	'start_time'	=>'required',
    	'end_time'		=>'required',
		'per_hour_charge'		=>'required|numeric',
		'extra_hour_charge'		=>'required|numeric',
    	'people_limit'	=>'required|numeric',
    	
    	];

    	$messages = [
		'user_id.required'	=> 'User id is required',
    	'listing_id.required'	=> 'Select listing',
    	'start_time.required'	=> 'Select start time',
    	'end_time.required'		=> 'Select end time',
		'per_hour_charge.required'		=> 'Per Hour charge is required',
		'extra_hour_charge.required'		=> 'Extra Hour charge is required',
    	'people_limit.required'	=> 'People limit required',
    	
    	];
		
			$validator = \Validator::make($request->all(), $rules, $messages);
				
				 if ($validator->fails()) {
					$return['data'] = $validator->errors()->toArray();
					$return['msg'] = 'Validation Issue';
					return response()->json($return);
				}
				
	
	
		// add
		$this->ServiceBookingSettings->listing_id = $request->listing_id;
		$this->ServiceBookingSettings->merchant_id = $request->user_id;
		$this->ServiceBookingSettings->start_time = $request->start_time.$request->start_time_ar;
		$this->ServiceBookingSettings->end_time = $request->end_time.$request->end_time_ar;
		$this->ServiceBookingSettings->per_hour_charge = $request->per_hour_charge;
		$this->ServiceBookingSettings->extra_hour_charge = $request->extra_hour_charge;
		$this->ServiceBookingSettings->people_limit = $request->people_limit;
		$this->ServiceBookingSettings->status=1;
		if($request->holidays)
			$this->ServiceBookingSettings->holidays=json_encode($request->holidays);
		$this->ServiceBookingSettings->save();
		$id=$this->ServiceBookingSettings->id;
		
			$return['table_booking'] = ServiceBookingSettings::find($id);
			$return['status'] = 1;	
			$return['msg'] = "Success";
			return response()->json($return);
	}
	public function edit($id)
	{		

		$return['table_booking'] = ServiceBookingSettings::find($id);	
		$return['status'] = 1;	
		$return['msg'] = "Success";
		return response()->json($return);
	}
	public function update(Request $request)
	{	
		$rules = [
		'id'	=> 'required',    	
    	'start_time'	=>'required',
    	'end_time'		=>'required',
		'per_hour_charge'		=>'required|numeric',
		'extra_hour_charge'		=>'required|numeric',
    	'people_limit'	=>'required|numeric',
    	
    	];

    	$messages = [
		'id.required'	=> 'Id is required',
    	'start_time.required'	=> 'Select start time',
    	'end_time.required'		=> 'Select end time',
		'per_hour_charge.required'		=> 'Per Hour charge is required',
		'extra_hour_charge.required'		=> 'Extra Hour charge is required',
    	'people_limit.required'	=> 'People limit required',
    	
    	];
		
			$validator = \Validator::make($request->all(), $rules, $messages);
				
				 if ($validator->fails()) {
					$return['data'] = $validator->errors()->toArray();
					$return['msg'] = 'Validation Issue';
					return response()->json($return);
				}
		$holidays = '';
		if($request->holidays)
					$holidays = json_encode($request->holidays);
				
		$booking_settings=array('start_time'=>$request->start_time.$request->start_time_ar, 
		    'end_time'=>$request->end_time.$request->end_time_ar,
			'per_hour_charge'=>$request->per_hour_charge,
			'extra_hour_charge'=>$request->extra_hour_charge,
			'people_limit'=>$request->people_limit,
			'holidays'=>$holidays,
			'status'=>$request->status);

		$update_settings=DB::table('table_booking_settings')->where('id',$request->id)->update($booking_settings);
		$return['table_booking'] = ServiceBookingSettings::find($request->id);
			$return['status'] = 1;	
			$return['msg'] = "Success";
			return response()->json($return);

	}
	
	public function enable($merchant_id)
	{
		$merchant_service_id = null;
		
		$services = $this->services;
		
		$shop_category_type_id = $services['table']['id'];
		
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
		
		$shop_category_type_id = $services['table']['id'];
			
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

	
	
}