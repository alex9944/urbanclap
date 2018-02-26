<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\AppointmentBookingSettings;
use App\Models\AppointmentBookingOrder;
use App\Models\Category;
use App\Models\Listing;
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
	
	public function index()
	{		
		$user_id = (auth()->check()) ? auth()->user()->id : null;
		
		$listings = Listing::
		whereIn('m_c_id', $this->appointment_booking_category_id_lists)
		->get();
		$users = DB::table('users')
		->select('*', 'users.id as uid')
		->leftJoin('role_user', 'users.id', '=', 'role_user.user_id')
		->where('role_user.role_id', '=', 3)
		->get();
		$appointment_booking_settings = AppointmentBookingSettings::all();
		
		// times
		$times = array();
		for($i = 1; $i <= 12; $i++)
		{
			$times[$i] = $i;
		}

		return view('panels.admin.merchants.appointments', compact('appointment_booking_settings', 'listings', 'times', 'user_id','users'));
	}
	public function add(Request $request)
	{		

		$rules = $this->AppointmentBookingSettings->get_adding_rules();
		$this->validate($request, $rules['rules'], $rules['messages']);

		// add
		$listing = Listing::find($request->listing_id);
		
		$this->AppointmentBookingSettings->listing_id = $request->listing_id;
		$this->AppointmentBookingSettings->merchant_id = $listing->user_id;
		$this->AppointmentBookingSettings->start_time = $request->start_time.$request->start_time_ar;
		$this->AppointmentBookingSettings->end_time = $request->end_time.$request->end_time_ar;
		$this->AppointmentBookingSettings->time_interval = $request->time_interval;
		$this->AppointmentBookingSettings->status = $request->status;
		if($request->holidays)
			$this->AppointmentBookingSettings->holidays=json_encode($request->holidays);
		$this->AppointmentBookingSettings->save();
		
		return redirect('admin/merchants/appointments')->with('message', 'Appointment booking settings added successfully');
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

		$holidays = '';
				if($request->holidays)
					$holidays = json_encode($request->holidays);
		// update
		$listing = Listing::find($request->listing_id);
		
		$settings = AppointmentBookingSettings::find($request->id);
		$settings->listing_id=$request->listing_id;
		$settings->merchant_id=$listing->user_id;
		$settings->start_time = $request->start_time.$request->start_time_ar;
		$settings->end_time = $request->end_time.$request->end_time_ar;
		$settings->time_interval = $request->time_interval;
		$settings->status = $request->status;
		$settings->holidays = $holidays;
		$settings->save();
		
		return redirect('admin/merchants/appointments')->with('message', 'Appointment booking settings updated successfully');
		
	}
	public function destroy_all(Request $request)
	{
		$cn = count($request->selected_id);
		if($cn > 0)
		{
			AppointmentBookingSettings::destroy($request->selected_id);	
		}
		
		return redirect('admin/merchants/appointments')->with('message','Seltected rows are deleted successfully');
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
	public function getAppointments(){
		$user_id=Auth::user()->id;
		$allBookings=AppointmentBookingOrder::get();
		return view('panels.admin.merchants.allappointments',compact('allBookings'));
	}
	public function enableBooking(Request $request){
		$tablebooking = AppointmentBookingOrder::find($request->id);
		$tablebooking->status = 1;
		$tablebooking->save();
		/*$dateinit = \Carbon\Carbon::parse($tablebooking->booked_date);
		$mail_data=array('booked_date'=>$dateinit->format('M d, Y'),
			'booked_time'=>$tablebooking->booked_time,
			'total_peoples'=>$tablebooking->total_peoples,
			'name'=>$tablebooking->name,
			'listing'=>$tablebooking->listing->title,
			'email'=>$tablebooking->email,
			'type'=>'appointment',
			'booking_status'=>'Your Booking is Confirmed');
		Mail::send('emails.booking_request', $mail_data, function($message)use ($mail_data) 
		{
			$email=$mail_data['email'];
			$message->to($email,'');
			$message->subject('Appointment Booking Status');

		});*/
		return 1;
	}
	public function disableBooking(Request $request){
		$tablebooking = AppointmentBookingOrder::find($request->id);
		$tablebooking->status = 2;
		$tablebooking->save();
		/*$dateinit = \Carbon\Carbon::parse($tablebooking->booked_date);
		$mail_data=array('booked_date'=>$dateinit->format('M d, Y'),
			'booked_time'=>$tablebooking->booked_time,
			'total_peoples'=>$tablebooking->total_peoples,
			'name'=>$tablebooking->name,
			'listing'=>$tablebooking->listing->title,
			'email'=>$tablebooking->email,
			'type'=>'appointment',
			'booking_status'=>'Your Booking is Cancelled, Sorry for the inconvenience.');
		Mail::send('emails.booking_request', $mail_data, function($message)use ($mail_data) 
		{
			$email=$mail_data['email'];
			$message->to($email,'');
			$message->subject('Appointment Booking Status');

		});*/
		return 1;
	}
	
}