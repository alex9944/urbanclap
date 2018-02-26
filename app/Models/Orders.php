<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    protected $table = 'orders';	
	
	public static function boot()
	{
		parent::boot();
		
		//once created/inserted successfully this method fired, so I tested foo 
		static::created(function (Orders $Orders) {
			$Orders->invoice_id = $Orders->getInvoiceId($Orders->id);
			$Orders->save();
		});
	}
	
	public function order_booking()
    {
        return $this->belongsTo('App\Models\OrderBooking', 'order_booking_id');
    }
	
	public function appointment_booking()
    {
        return $this->belongsTo('App\Models\AppointmentBookingOrder', 'appointment_booking_id');
    }
	
	public function table_booking()
    {
        return $this->belongsTo('App\Models\TableBookingOrder', 'table_booking_id');
    }
	
	public function create_main_order($id, $user_id, $order_type)
	{
		$Order = $this->newInstance();
		
		$Order->user_id 		= $user_id;
		$Order->order_type 		= $order_type;
		if($order_type == 'online_order')
			$Order->order_booking_id 		= $id;
		else if($order_type == 'table_booking')
			$Order->table_booking_id 		= $id;
		else if($order_type == 'appointment_booking')
			$Order->appointment_booking_id 		= $id;
		
		$Order->save();
		
		return $Order;
	}
	
	public function getInvoiceId($id)
	{
		$invoice_pre_txt = 'APOU-';
		
		$zerofill_id = str_pad($id, 10, '0', STR_PAD_LEFT);

		return $invoice_pre_txt . $zerofill_id;
	}
}
