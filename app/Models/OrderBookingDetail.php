<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\MerchantOrder;
use App\Models\OrderBooking;

class OrderBookingDetail extends Model
{
    protected $table = 'order_booking_detail';
	
	//public $timestamps = false;
	
	public function menu_item()
    {
        return $this->belongsTo('App\Models\FoodMenuItem', 'item_id');
    }
	
	public function shop_item()
    {
        return $this->belongsTo('App\Models\Shopproducts', 'product_id');
    }
	
	public function merchant_order()
    {
        return $this->belongsTo('App\Models\MerchantOrder', 'merchant_order_id');
    }
	
	public function main_order()
    {
        return $this->belongsTo('App\Models\OrderBooking', 'order_id');
    }
	
	// process to update status in merchant_order table
	public function chkUpdateMerchantStatus($merchant_order_id)
	{
		$all_status = [];
		
		$order_booking_details = self::where('merchant_order_id',$merchant_order_id)->get();
		
		foreach($order_booking_details as $order_booking_detail)
		{			
			$all_status[$order_booking_detail->status] = $order_booking_detail->status;
		}
		
		$update_to_status = array('completed', 'cancelled');
		if(count($all_status) == 1 and in_array(key($all_status), $update_to_status))
		{
			$order_status = key($all_status);
			
			$OrderBooking = new OrderBooking;
			$merchant_order_status = $OrderBooking->merchant_order_status;
			
			$MerchantOrder = MerchantOrder::find($merchant_order_id);
			$MerchantOrder->order_status = $merchant_order_status[$order_status]['id'];
			$MerchantOrder->save();
		}
	}
}
