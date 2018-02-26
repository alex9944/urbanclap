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


class AppointmentBookingController extends Controller
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
	
	public function settings()
	{		
		$user_id = (auth()->check()) ? auth()->user()->id : null;
		
		$listings = Listing::
		where('user_id', $user_id)
		->whereIn('m_c_id', $this->appointment_booking_category_id_lists)
		->get();
		
	
		$appointment_booking_settings = AppointmentBookingSettings::where('merchant_id', $user_id)->first();
		
		if($appointment_booking_settings == null)
			$appointment_booking_settings = new AppointmentBookingSettings;
		
		// times
		$times = array();
		for($i = 1; $i <= 12; $i++)
		{
			$times[$i] = $i;
		}
		
		$merchant_services = MerchantServices::where('merchant_id',$user_id)->get();
		
		// check if service enable or not
		$service_disable = true; // default
		$services = $this->services;
		$shop_id = $services['appointment']['id'];
		foreach($merchant_services as $merchant_service) {
			$category_type_id = $merchant_service->category_type_id;
			
			if($category_type_id == $shop_id and $merchant_service->is_enable) {
				$service_disable = false; // shop is now enable
				break;
			}
		}
		$listing = Listing::where('user_id', '=', $user_id)->first();
		$listing_id=$listing->id;

		//return view('panels.merchant.appointment-booking.index', compact('appointment_booking_settings', 'listings', 'times', 'user_id'));
		return $this->_loadMerchantView('appointment-booking.settings', compact('appointment_booking_settings', 'listings', 'times', 'user_id', 'service_disable','listing_id'));
	}
	
	public function add(Request $request)
	{		

		$rules = $this->AppointmentBookingSettings->get_adding_rules();
		$this->validate($request, $rules['rules'], $rules['messages']);
		
		$user_id = (auth()->check()) ? auth()->user()->id : null;
		
		// add
		
		
		$listing = Listing::find($request->listing_id);
		
		$this->AppointmentBookingSettings->listing_id = $request->listing_id;
		$this->AppointmentBookingSettings->merchant_id = $listing->user_id;
		$this->AppointmentBookingSettings->start_time = $request->start_time.$request->start_time_ar;
		$this->AppointmentBookingSettings->end_time = $request->end_time.$request->end_time_ar;
		$this->AppointmentBookingSettings->time_interval = $request->time_interval;
		$this->AppointmentBookingSettings->status = $request->status;
		
		$holidays = json_encode(array());
		if($request->holidays)
			$holidays = json_encode($request->holidays);
		
		$this->AppointmentBookingSettings->holidays=$holidays;
		
		$this->AppointmentBookingSettings->save();
		
		return redirect('merchant/appointment-booking/settings')->with('message', 'Appointment booking settings added successfully');
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
		
		$rules = $this->AppointmentBookingSettings->get_adding_rules();
		$this->validate($request, $rules['rules'], $rules['messages']);
		
		$holidays = json_encode(array());
		if($request->holidays)
			$holidays = json_encode($request->holidays);
		// update
		$listing = Listing::find($request->listing_id);
		
		$settings = AppointmentBookingSettings::find($request->id);
		$settings->listing_id = $request->listing_id;
		$settings->merchant_id = $listing->user_id;
		$settings->start_time = $request->start_time.$request->start_time_ar;
		$settings->end_time = $request->end_time.$request->end_time_ar;
		$settings->time_interval = $request->time_interval;
		$settings->status = $request->status;
		$settings->holidays = $holidays;
		$settings->save();
		
		return redirect('merchant/appointment-booking/settings')->with('message', 'Appointment booking settings updated successfully');
	}
	
	public function destroy_all(Request $request)
	{
		$cn = count($request->selected_id);
		if($cn > 0)
		{
			AppointmentBookingSettings::destroy($request->selected_id);	
		}
		
		return redirect('admin/sitevariables')->with('message','Seltected rows are deleted successfully');
	}
	
	public function destroy($id)
	{
		$settings = AppointmentBookingSettings::find($id);
		
		if($settings)
		{

			$settings->delete();
			
			\Session::flash('message', 'Selected row deleted successfully.');
			
			return Response::json(array(
				'success' => true,
				'msg'   => 'Seltected row deleted successfully.'
				));
		}
		
		return Response::json(array(
			'success' => false,
			'msg'   => 'Invalid Id'
			));
	}
	
	public function enable()
	{	 
		$merchant_service_id = null;
		
		$services = $this->services;
		
		$shop_category_type_id = $services['appointment']['id'];
			
		$merchant_id=Auth::user()->id;
		$merchant_services = MerchantServices::where('merchant_id',$merchant_id)->get();
		foreach($merchant_services as $merchant_service) {
			$category_type_id = $merchant_service->category_type_id;
			
			if($category_type_id == $shop_category_type_id) {
				$merchant_service_id = $merchant_service->id;
				break;
			}
		}
		
		$merchant_id = (auth()->check()) ? auth()->user()->id : null;
		
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
		
		return redirect('merchant/appointment-booking/settings')->with('message', 'Service activated successfully');
	}
	
	public function disable()
	{	 
		$merchant_service_id = null;
		
		$services = $this->services;
		
		$shop_category_type_id = $services['appointment']['id'];
			
		$merchant_id=Auth::user()->id;
		$merchant_services = MerchantServices::where('merchant_id',$merchant_id)->get();
		foreach($merchant_services as $merchant_service) {
			$category_type_id = $merchant_service->category_type_id;
			
			if($category_type_id == $shop_category_type_id) {
				$merchant_service_id = $merchant_service->id;
				break;
			}
		}
		
		
		$merchant_id = (auth()->check()) ? auth()->user()->id : null;
		
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
		
		return redirect('merchant/appointment-booking/settings')->with('message', 'Service de-activated successfully');
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