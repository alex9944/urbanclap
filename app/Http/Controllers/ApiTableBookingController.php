<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use DB;
use App\Models\Category;
use App\Models\Listing;
use App\Models\CategoryType;
use App\Models\ListingViews;
use App\Models\TableBookingSettings;
use App\Models\TableBookingOrder;
use App\Models\Orders;

use App\Events\TableBooked;
use Jenssegers\Agent\Agent;
use Cookie;
use Validator;

class ApiTableBookingController extends Controller
{
	public function index(Request $request)
	{		
		$table_booking_order = new TableBookingOrder;		
		
		$adding_rules_and_messages = $table_booking_order->get_adding_rules();
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
			$tablebookingsettings = $listing->tablebookingsettings;
			
			$booking_validation = $table_booking_order->validateBookingDateTime($request, $tablebookingsettings);
			
			if( $booking_validation['status'] == 0 )
			{
				$return['msg'] = array($booking_validation['msg']);
			}
			else
			{
				// validation ok
				
				$table_booking_order->listing_id 	= $request->listing_id;
				$table_booking_order->user_id 		= $request->user_id;
				$table_booking_order->merchant_id 	= $listing->user_id;
				$table_booking_order->order_date 	= date('Y-m-d H:i:s');
				$table_booking_order->booked_date 	= date('Y-m-d', strtotime($request->booked_date));
				$table_booking_order->booked_time 	= $request->booked_time;
				$table_booking_order->total_peoples = $request->total_peoples;
				$table_booking_order->name 			= $request->name;
				$table_booking_order->phone_number 	= $request->phone_number;
				$table_booking_order->email 		= $request->email;
				$table_booking_order->additional_request = $request->additional_request;
				$table_booking_order->status 		= 'Pending';
				$table_booking_order->save();
				
				$Orders = new Orders;
				$orders_created = $Orders->create_main_order($table_booking_order->id, $request->user_id, 'table_booking');
				
				event(new TableBooked($table_booking_order));
				
				$return['msg'] = 'Thank you. Your booking will be confirmed shortly.';
				$return['status'] = 1;
			}

		}
		
		return response()->json($return);		

	}
	public function getbookings(){
		$response=array();
		$user_id=$_GET['id'];
		$allBookings=TableBookingOrder::get()->where('user_id',$user_id);
		if(sizeof($allBookings)>0){
			foreach ($allBookings as $key => $value) {
				$value['listing_name']=$value->listing->title;
			}
			$data['bookings']=$allBookings;
		}
		else{
			$data['bookings']='No bookings found for this Merchant';
		}
		
		$responsecode = 200;        
		$header = array (
			'Content-Type' => 'application/json; charset=UTF-8',
			'charset' => 'utf-8'
			);       
		return response()->json($data , $responsecode, $header, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
		
	}
}