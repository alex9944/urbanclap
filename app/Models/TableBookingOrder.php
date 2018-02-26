<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TableBookingOrder extends Model
{
    protected $table = 'table_booking_order';

	public $timestamps = false;
	
	// status list
	public $order_status = array(
		0	=> array('id' => 0, 'name' => 'Pending'),
		1	=> array('id' => 1, 'name' => 'Confirmed'),
		2	=> array('id' => 2, 'name' => 'Declined'),
		3	=> array('id' => 3, 'name' => 'Completed')
	);
	
	public function get_adding_rules()
	{
		$rules = [
			'listing_id'	=> 'required',
			'user_id'	=> 'required',
			'booked_date'	=> 'required',
			'booked_time'	=> 'required',
			'total_peoples'	=> 'required',
			'name'			=> 'required',
			'phone_number'	=> 'required',
			'email'			=> 'required|email',
		];
		
		$messages = [
			'listing_id.required'	=> 'Invalid listing',
			'user_id.required'		=> 'Please login to book the table',
			'booked_date.required'	=> 'Booked date is required',
			'booked_time.required'	=> 'Booked time is required',
			'total_peoples.required'=> 'Number of people is required',
			'name.required'			=> 'Name is required',
			'phone_number.required'	=> 'Phone number is required',
			'email.required'		=> 'Email is required',
			'email.email'			=> 'Invalid Email',
		];
		
		return array('rules' => $rules, 'messages' => $messages);
	}
	
	public function validateBookingDateTime($request, $tablebookingsettings)
	{
		
		$return = array('status' => 0, 'msg' => 'Invalid request');
		
		$listing_id 	= $request->listing_id;
		$booked_date 	= $request->booked_date;
		$booked_time 	= $request->booked_time;
		$total_peoples = $request->total_peoples;
		
		$people_limit_per_slot = $tablebookingsettings->people_limit;
		
		$total_peoples_bookings = self::where([
			'listing_id' => $listing_id, 
			'booked_date' => $booked_date, 
			'booked_time' => $booked_time
		])->sum('total_peoples');
		
		$new_total_peoples = $total_peoples + $total_peoples_bookings;
	
		if( $new_total_peoples <= $people_limit_per_slot )
		{
			$return['msg'] = 'Success';
			$return['status'] = 1;
		}
		else if($total_peoples_bookings == $people_limit_per_slot)
		{
			// already booked full
			$return['msg'] = 'Selected table on the requested date and time is already full. Please change another one.';
		}
		else
		{
			// Request count is exceed from limit
			$allowed_people = $people_limit_per_slot - $total_peoples_bookings;
			$return['msg'] = $allowed_people . ' peoples only available on the requested date and time.';
		}
		
		return $return; // validation Fail
	}
	 
	public function listing()
    {
        return $this->belongsTo('App\Models\Listing', 'listing_id');
    }
	 
	public function main_order()
    {
        return $this->hasOne('App\Models\Orders', 'table_booking_id');
    }
	
    public function user()
	{
    	return $this->belongsTo('App\Models\User', 'merchant_id');
    }
	
	public function scopeOrdered($query)
	{
		return $query->orderBy('order_date', 'desc')->get();
	}
}
