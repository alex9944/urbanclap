<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\TableBookingOrder;
use App\Models\TableBookingSettings;
use App\Models\Category;
use App\Models\Listing;
use Response;
use Validator;
use DB;
use Auth;
use Mail;
class TableBookingController extends Controller
{

	var $table_booking_id;
	var $TableBookingSettings;
	var $table_booking_category_id_lists = [];
	
	public function __construct()
	{
		parent::__construct();
		
		$this->TableBookingSettings = new TableBookingSettings;
		$this->table_booking_id = $this->TableBookingSettings->table_booking_id;
		
		$categories = Category::where('category_type', '!=', '')->get();
		foreach($categories as $category) {
			$category_type = json_decode($category->category_type);
			if( in_array($this->table_booking_id, $category_type)  )
				$this->table_booking_category_id_lists[] = $category->c_id;
		}//print_r($this->appointment_booking_category_id_lists);
	}
	public function index()
	{		
		
		$listings = Listing::whereIn('m_c_id', $this->table_booking_category_id_lists)
		->get();
		
		$table_booking_settings = TableBookingSettings::all();
		
		// times
		$times = array();
		for($i = 1; $i <= 12; $i++)
		{
			$times[$i] = $i;
		}
		$users = DB::table('users')
		->select('*', 'users.id as uid')
		->leftJoin('role_user', 'users.id', '=', 'role_user.user_id')
		->where('role_user.role_id', '=', 3)
		->get();
		return view('panels.admin.merchants.tablebooking', compact('table_booking_settings', 'listings', 'times', 'user_id','users'));
	}
	public function bookTable(Request $request){
		$tableobj=new TableBookingOrder();
		$tableobj->user_id=$request->user_id;
		$tableobj->listing_id=$request->listing_id;
		$tableobj->merchant_id=$request->merchant_id;
		$tableobj->booked_date=$request->booked_date;
		$tableobj->booked_time=$request->booked_time;
		$tableobj->total_peoples=$request->total_peoples;
		$tableobj->name=$request->name;
		$tableobj->phone_number=$request->phone_number;
		$tableobj->email=$request->email;
		$tableobj->additional_request=$request->additional_request;
		$tableobj->save();
		if($tableobj->id!=''){
			$dateinit = \Carbon\Carbon::parse($tableobj->booked_date);
			$mail_data=array('booked_date'=>$dateinit->format('M d, Y'),
				'booked_time'=>$tableobj->booked_time,
				'total_peoples'=>$tableobj->total_peoples,
				'name'=>$tableobj->name,
				'listing'=>$tableobj->listing->title,
				'email'=>$tableobj->email,
				'booking_status'=>'Your Booking will be confirmed Shortly..');
			Mail::send('emails.booking_request', $mail_data, function($message)use ($mail_data) 
			{
				$email=$mail_data['email'];
				$message->to($email,'');
				$message->subject('Table Booking Status');

			});
		}
	}
	public function getBookings(){
		$allBookings=TableBookingOrder::ordered();
		return view('panels.admin.merchants.allbookings',compact('allBookings'));
	}
	public function enableBooking(Request $request){
		$tablebooking = TableBookingOrder::find($request->id);
		$tablebooking->status = 1;
		$tablebooking->save();
		/*$dateinit = \Carbon\Carbon::parse($tablebooking->booked_date);
		$mail_data=array('booked_date'=>$dateinit->format('M d, Y'),
			'vendor'=>Auth::user()->first_name,
			'vendor_email'=>Auth::user()->email,
			'booked_time'=>$tablebooking->booked_time,
			'total_peoples'=>$tablebooking->total_peoples,
			'name'=>$tablebooking->name,
			'listing'=>$tablebooking->listing->title,
			'email'=>$tablebooking->email,
			'type'=>'table',
			'booking_status'=>'Your Booking is Confirmed');
		Mail::send('emails.booking_request', $mail_data, function($message)use ($mail_data) 
		{
			$email=$mail_data['email'];
			$vendor_email=$mail_data['vendor_email'];
			$message->to($email,'');
			$message->bcc($vendor_email,'');
			$message->subject('Table Booking Status');

		});*/


		return 1;
	}
	public function disableBooking(Request $request){
		$tablebooking = TableBookingOrder::find($request->id);
		$tablebooking->status = 2;
		$tablebooking->save();
		/*$dateinit = \Carbon\Carbon::parse($tablebooking->booked_date);
		$mail_data=array('booked_date'=>$dateinit->format('M d, Y'),
			'vendor'=>Auth::user()->first_name,
			'vendor_email'=>Auth::user()->email,
			'booked_time'=>$tablebooking->booked_time,
			'total_peoples'=>$tablebooking->total_peoples,
			'name'=>$tablebooking->name,
			'listing'=>$tablebooking->listing->title,
			'email'=>$tablebooking->email,
			'type'=>'table',
			'booking_status'=>'Your Booking is Cancelled, Sorry for the inconvenience.');
		Mail::send('emails.booking_request', $mail_data, function($message)use ($mail_data) 
		{
			$email=$mail_data['email'];
			$vendor_email=$mail_data['vendor_email'];
			$message->to($email,'');
			$message->bcc($vendor_email,'');
			$message->subject('Table Booking Status');

		});
*/
		return 1;
	}
	public function add(Request $request)
	{		

		$rules = $this->TableBookingSettings->get_adding_rules();
		$this->validate($request, $rules['rules'], $rules['messages']);
		// add
		$listing = Listing::find($request->listing_id);
		
		$this->TableBookingSettings->listing_id = $request->listing_id;
		$this->TableBookingSettings->merchant_id = $listing->user_id;
		$this->TableBookingSettings->start_time = $request->start_time.$request->start_time_ar;
		$this->TableBookingSettings->end_time = $request->end_time.$request->end_time_ar;
		$this->TableBookingSettings->time_interval = $request->time_interval;
		$this->TableBookingSettings->people_limit = $request->people_limit;
		$this->TableBookingSettings->status=$request->status;
		if($request->holidays)
			$this->AppointmentBookingSettings->holidays=json_encode($request->holidays);
		
		$this->TableBookingSettings->save();
		
		return redirect('admin/merchants/bookings')->with('message', 'Table booking settings added successfully');
	}
	public function edit($id)
	{		

		$return['settings'] = TableBookingSettings::find($id);
		$return['listing'] = $return['settings']->listing;

		// ajax
		return Response::json(array(
			'view_details'   => $return
			));
	}
	public function update(Request $request)
	{		
		$rules = $this->TableBookingSettings->get_adding_rules();
		$this->validate($request, $rules['rules'], $rules['messages']);
		
		$holidays = '';
		if($request->holidays)
					$holidays = json_encode($request->holidays);
				
		$listing = Listing::find($request->listing_id);
		
		$booking_settings=array('start_time'=>$request->start_time.$request->start_time_ar,
			'listing_id' => $request->listing_id,
			'merchant_id' => $listing->user_id,
			'end_time'=>$request->end_time.$request->end_time_ar,
			'time_interval'=>$request->time_interval,
			'people_limit'=>$request->people_limit,
			'holidays'=>$holidays,
			'status'=>$request->status);

		$update_settings=DB::table('table_booking_settings')->where('id',$request->id)->update($booking_settings);
		return redirect('admin/merchants/bookings')->with('message','Booking settings updated successfully');

	}

	public function destroy($id)
	{
		$settings = TableBookingSettings::find($id);
		
		if($settings)
		{

			$settings->delete();
			
			\Session::flash('message', 'Seltected row deleted successfully.');
			
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
	public function destroy_all(Request $request)
	{
		$cn = count($request->selected_id);
		if($cn > 0)
		{
			TableBookingSettings::destroy($request->selected_id);	
		}
		
		return redirect('admin/merchants/bookings')->with('message','Selected settings deleted successfully');

	}
	
}