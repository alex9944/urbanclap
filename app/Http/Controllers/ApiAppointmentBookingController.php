<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use DB;
use App\Models\Category;
use App\Models\Listing;
use App\Models\CategoryType;
use App\Models\ListingViews;
use App\Models\AppointmentBookingSettings;
use App\Models\AppointmentBookingOrder;
use App\Models\Orders;

use App\Events\AppointmentBooked;
use Jenssegers\Agent\Agent;
use Cookie;
use Validator;

class ApiAppointmentBookingController extends Controller
{
	public function index(Request $request)
	{
		$appointment_booking_order = new AppointmentBookingOrder;		
		
		$adding_rules_and_messages = $appointment_booking_order->get_adding_rules();
		$adding_rules = $adding_rules_and_messages['rules'];
		$adding_messages = $adding_rules_and_messages['messages'];
		
		$validator = Validator::make($request->all(), $adding_rules, $adding_messages);
		
		$return = array('status' => 0, 'msg' => 'Invalid Request');
		if ($validator->fails()) 
		{
			$return['msg'] = $validator->errors()->toArray();
		}
		else
		{
			$listing = Listing::find($request->listing_id);
			$appointmentbookingsettings = $listing->appointmentbookingsettings;
			
			$booking_validation = $appointment_booking_order->validateBookingDateTime($request, $appointmentbookingsettings);
			
			if( $booking_validation['status'] == 0 )
			{
				$return['msg'] = array($booking_validation['msg']);
			}
			else
			{
				// validation ok
				
				$appointment_booking_order->listing_id 			= $request->listing_id;
				$appointment_booking_order->user_id 			= $request->user_id;
				$appointment_booking_order->merchant_id 		= $listing->user_id;
				$appointment_booking_order->order_date 			= date('Y-m-d H:i:s');
				$appointment_booking_order->booked_date 		= date('Y-m-d', strtotime($request->booked_date));
				$appointment_booking_order->booked_time 		= $request->booked_time;
				$appointment_booking_order->total_peoples 		= $request->total_peoples;
				$appointment_booking_order->name 				= $request->name;
				$appointment_booking_order->phone_number 		= $request->phone_number;
				$appointment_booking_order->email 				= $request->email;
				$appointment_booking_order->additional_request 	= $request->additional_request;
				$appointment_booking_order->status 				= 'Pending';
				$appointment_booking_order->save();
				
				$Orders = new Orders;
				$orders_created = $Orders->create_main_order($appointment_booking_order->id, $request->user_id, 'appointment_booking');
				
				event(new AppointmentBooked($appointment_booking_order));
				
				$return['msg'] = 'Thank you. Your booking will be confirmed shortly.';
				$return['status'] = 1;
			}
		}
		
		return response()->json($return);

	}
	public function getappointments(){
		$user_id=$_GET['id'];
		$allBookings=AppointmentBookingOrder::get()->where('merchant_id',$user_id);
		if(sizeof($allBookings)>0){
			foreach ($allBookings as $key => $value) {
				$value['listing_name']=$value->listing->title;
			}
			$data['appointments']=$allBookings;
		}
		else{
			$data['appointments']='No appointments found for this Merchant';
		}
		$responsecode = 200;        
		$header = array (
			'Content-Type' => 'application/json; charset=UTF-8',
			'charset' => 'utf-8'
			);       
		return response()->json($data , $responsecode, $header, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
		
	}
}