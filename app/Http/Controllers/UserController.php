<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\AppointmentBookingOrder;
use App\Models\TableBookingOrder;
use App\Models\OrderBooking;
use App\Models\OrderBookingDetail;
use App\Models\ShopOrderBookingDetail;
use App\Models\Contacts;
use App\Models\Usercampaigns;
use App\Models\MerchantOrder;
use App\Models\ListingReview;

use auth;
use Image;
use DB;
class UserController extends Controller
{

	public function getHome()
	{

		$user_id = (auth()->check()) ? auth()->user()->id : null;

		
	// All Contacts	
		$user = User::find($user_id);
		$contacts=Contacts::get()->where('user_id',$user_id);
	
		//return view('panels.user.home', compact('user'));
		
	// Review	
		$review = ListingReview::with('listing.listing_city')->where(['user_id' => $user_id, 'approved' => 1])->get();
		
	// Appointment Booking
		$appointment_bookings = AppointmentBookingOrder::with('main_order', 'listing')->where('user_id', $user_id)->orderBy('order_date', 'DESC')->get();
		
	// Producs	
		$orderbooking = OrderBooking::whereHas('merchant_orders', function ($query) {
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
								
	// Food
	$foodbooking = OrderBooking::whereHas('merchant_orders', function ($query) {
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
										
	// Table Booking
	$tablebooking = TableBookingOrder::with('main_order', 'listing')->where('user_id', $user_id)->orderBy('order_date', 'DESC')->get();
										//DB::enableQueryLog();
										//dd(DB::getQueryLog());
					//print_r($tablebooking);
					//exit;
								
		return $this->_loadUserView('home', compact('user','contacts','appointment_bookings','review','orderbooking','foodbooking','tablebooking'));

	}
	public function getProfile()
	{

		$user_id = (auth()->check()) ? auth()->user()->id : null;

		$user = User::find($user_id);

		//return view('panels.user.home', compact('user'));
		
		return $this->_loadUserView('profile', compact('user'));

	}
	
	public function update(Request $request)
	{
		$user_id = (auth()->check()) ? auth()->user()->id : null;
		
		$this->validate($request,
            [
           
            'first_name'            => 'required',		
			'mobile_no'             =>'required|numeric|min:3|unique:users,mobile_no,'.$user_id,	
            'website'               =>'regex:/^((?:http(?:s)?\:\/\/)?[a-zA-Z0-9_-]+(?:.[a-zA-Z0-9_-]+)*.[a-zA-Z]{2,4}(?:\/[a-zA-Z0-9_]+)*(?:\/[a-zA-Z0-9_]+.[a-zA-Z]{2,4}(?:\?[a-zA-Z0-9_]+\=[a-zA-Z0-9_]+)?)?(?:\&[a-zA-Z0-9_]+\=[a-zA-Z0-9_]+)*)$/',
			'bio'                   =>'regex:/^[\pL\s\-]+$/u',
           

            ],
            [
          
            'first_name.required'   => 'First Name is required',		
			'mobile.required'       => 'First Name is required',
			'mobile.numeric'        => 'Enter Valid Mobile number',			
            'website.regex'         => 'Enter the Valid Url',
			'bio.regex'             => 'Enter Valid Bio Details'
            ]
            );
			
	/*		
		$this->validate($request,
			[
				'first_name'		=> 'required',
				'last_name'		=> 'required',
			//'email'			=> 'required|email',
				'mobile_no'		=> 'required'
				'mobile_no'		=> 'required'
				'mobile_no'		=> 'required'
				'mobile_no'		=> 'required'
			]
		);*/
		
		// validation ok
		
		$photo = $request->file('photo');
			 if($photo){
					$imagename = time().'.'.$photo->getClientOriginalExtension();   
					$destinationPath = public_path('/uploads/thumbnail');
					$thumb_img = Image::make($photo->getRealPath())->resize(100, 100);
					$thumb_img->save($destinationPath.'/'.$imagename,80);                    
					$destinationPath = public_path('/uploads/original');
					$photo->move($destinationPath, $imagename);
						 DB::table('users')
						->where('id', $user_id)
						->update(['image' => $imagename,]);
			 }

		$user = User::find($user_id);
		$user->first_name = $request->first_name;
		$user->website = $request->website;
		$user->mobile_no = $request->mobile_no;
		$user->gender = $request->gender;
		$user->bio = $request->bio;	
		$user->save();
		
		return redirect('user/profile')->with('message','Profile updated successfully');
	}
	
	public function changePassword()
	{
			$user_id = (auth()->check()) ? auth()->user()->id : null;

		$user = User::find($user_id);

		//return view('panels.user.home', compact('user'));
		
		return $this->_loadUserView('change_password', compact('user'));
	
	}
	
	public function storeChangePassword(Request $request)
	{
		
		\Validator::extend('pwdvalidation', function($field, $value, $parameters)
		{
			return \Hash::check($value, \Auth::user()->password);
		});
		
		$this->validate($request,
			[
				'cur_password'		=> 'required|pwdvalidation',
				'password'		=> 'required|confirmed|min:4|max:50|different:cur_password',
				'password_confirmation' => 'required'
			],
			[
				'cur_password.required'	=> 'Current password is required',
				'pwdvalidation' => 'The Old Password is Incorrect',
				'password.required'	=> 'New password is required',
				'password_confirmation.required'	=> 'Confirm new password is required',
			]
		);
		
		// validation ok
		$user_id = (auth()->check()) ? auth()->user()->id : null;

		$user = User::find($user_id);
		$user->password = bcrypt($request->password);
		$user->save();
		
		return redirect('user/change-password')->with('message','Password updated successfully');
	}
	
	public function appointmentBooking()
	{
		$user_id = (auth()->check()) ? auth()->user()->id : null;
		$appointment_bookings = AppointmentBookingOrder::where('user_id', $user_id)->get();		

		return view('panels.user.appointment_booking', compact('appointment_bookings'));
	}
	
	public function tableBooking()
	{
		$user_id = (auth()->check()) ? auth()->user()->id : null;
		$table_bookings = TableBookingOrder::where('user_id', $user_id)->get();
		
		return view('panels.user.table_booking', compact('table_bookings'));
	}
	
	public function onlineOrder()
	{
		$user_id = (auth()->check()) ? auth()->user()->id : null;

		$online_orders = OrderBooking::where(['user_id' => $user_id])->orderBy('created_at', 'Desc')->get();
		
		$OrderBooking = new OrderBooking;
		$order_status = $OrderBooking->order_status;
		
		return view('panels.user.online_order', compact('online_orders', 'order_status'));

	}
	
	public function onlineOrderDetail($id)
	{
		$user_id = (auth()->check()) ? auth()->user()->id : null;
		$order_booking = OrderBooking::where(['user_id' => $user_id, 'id' => $id])->first();
		$merchant_orders = MerchantOrder::where('order_booking_id', $id)->get();
		
		if(!$order_booking)
			return redirect('user/online-orders')->with('error_message', 'Invalid Id');

		$order_booking->total=OrderBookingDetail::where('order_id',$id)->sum('total_amount');

		$OrderBooking = new OrderBooking;
		$order_status = $OrderBooking->order_status;
		$order_detail_status = $OrderBooking->order_detail_status;
		
		return view('panels.user.online_order_detail', compact('order_booking', 'merchant_orders', 'order_status', 'order_detail_status'));
	}
	
	public function onlineShopping()
	{
		$user_id = (auth()->check()) ? auth()->user()->id : null;
		$online_orders = OrderBooking::where(['user_id' => $user_id, 'order_type' => 'online_shopping'])->get();

		return view('panels.user.online_shopping', compact('online_orders'));
	}
	
	public function onlineShoppingDetail($id)
	{
		$user_id = (auth()->check()) ? auth()->user()->id : null;
		$order_booking = OrderBooking::where(['user_id' => $user_id, 'id' => $id])->first();
		
		if(!$order_booking)
			return redirect('user/online-shoppings')->with('error_message', 'Invalid Id');

		$order_booking->total = ShopOrderBookingDetail::where('order_id',$id)->sum('total_amount');

		$OrderBooking = new OrderBooking;
		$order_status = $OrderBooking->order_status;
		
		return view('panels.user.online_shopping_detail', compact('order_booking', 'order_status'));
	}
	public function myContact(){
		$user_id = (auth()->check()) ? auth()->user()->id : null;

		$contacts=Contacts::get()->where('user_id',$user_id);
		return view('panels.user.mycontacts',compact('contacts'));
	}
	public function removecontact($id){
		$user_id = (auth()->check()) ? auth()->user()->id : null;
		$contact=Contacts::where('user_id',$user_id)->where('listing_id',$id)->delete();
		//return $this->myContact();
		
		return redirect('user')->with('message', 'Contact has been removed');
	}

	public function getProtected()
	{

		return view('panels.user.protected');

	}
	public function myCampaigns(){
		$user_id = (auth()->check()) ? auth()->user()->id : null;
		$campaign=Usercampaigns::where('user_id',$user_id)->get();
		return view('panels.user.mycampaigns',compact('campaign'));
	}

}