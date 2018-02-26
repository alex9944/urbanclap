<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\OrderBooking;
use App\Models\OrderBookingDetail;
use App\Models\Shopproducts;
use App\Models\FoodMenuItem;

class MerchantOrder extends Model
{
    protected $table = 'merchant_orders';
	
	//public $timestamps = false;
	
	public static function boot()
	{
		parent::boot();
		
		//once created/inserted successfully this method fired, so I tested foo 
		static::created(function (MerchantOrder $MerchantOrder) {
			$MerchantOrder->invoice_id = $MerchantOrder->getInvoiceId($MerchantOrder->id);
			$MerchantOrder->save();
		});
	}
	
	public function main_order()
    {
        return $this->belongsTo('App\Models\OrderBooking', 'order_booking_id');
    }
	
	public function order_details()
    {
        return $this->hasMany('App\Models\OrderBookingDetail', 'merchant_order_id');
    }
	
	public function listing()
    {
        return $this->belongsTo('App\Models\Listing', 'listing_id');
    }
	
	public function create_merchant_order($main_order_id)
    {		
		$OrderBooking = new OrderBooking;
		$OrderBookingDetail = new OrderBookingDetail;
		
		$status = $OrderBooking->order_status['pending']['id'];
		$cart_items_split_by_merchant = $OrderBooking->get_cart();
		
		$item_order_booking_detail_data = [];
		$merchant_orders_data = [];
		$net_shipping_amount = 0;
		$net_total = 0;
		//print_r($cart_items_split_by_merchant);exit;
		foreach($cart_items_split_by_merchant as $listing_carts) 
		{			
			$cart_items = $listing_carts['cart_item'];
			$listing = $listing_carts['listing'];
			$category_type = $listing_carts['category_type'];
			$delivery_fee = $listing_carts['delivery_fee'];	

			if($category_type == 'online_order') {	
				$net_shipping_amount += $delivery_fee;
			}
			
			$total_items = 0;
			$sub_total = 0;
			$total_shipping_amount = 0;
			if($category_type == 'online_order')
				$total_shipping_amount = $delivery_fee;
			$total_tax_amount = 0;
			$total_amount = 0;
			$merchant_net_amount = 0;
			
			// create order for merchant
			foreach($cart_items as $item) 
			{					
				if($item->attributes['order_type']=='food')	{
					$shipping_price = 0;
				}
				else {
					$menu_item = Shopproducts::find($item->attributes['item_id']);		
					$shipping_price = $menu_item->shipping_price;
				}
				$item_total = $item->getPriceSum();
				$quantity_total = $item->quantity;
						
				$total_items += $quantity_total;
				$sub_total += $item_total;
				$total_shipping_amount += $shipping_price;
				
				$net_shipping_amount += $shipping_price;
				//$total_tax_amount += 0;										
			}
			$total_amount = $sub_total+$total_shipping_amount;
			$merchant_net_amount = $total_amount;
			
			$net_total += $sub_total + $net_shipping_amount;
			
			$MerchantOrder = $this->newInstance();
			$MerchantOrder->listing_id = $listing->id;
			$MerchantOrder->order_booking_id = $main_order_id;
			$MerchantOrder->total_items = $total_items;
			$MerchantOrder->sub_total = $sub_total;
			$MerchantOrder->total_shipping_amount = $total_shipping_amount;
			$MerchantOrder->total_tax_amount = 0;
			$MerchantOrder->total_amount = $total_amount;
			$MerchantOrder->total_refund_amount = 0;
			$MerchantOrder->site_commission_amount = 0;
			$MerchantOrder->merchant_net_amount = $merchant_net_amount;
			$MerchantOrder->order_type = $category_type;
			$MerchantOrder->payment_type = 'online_payment';
			$MerchantOrder->payment_status = 'Pending';
			$MerchantOrder->order_status = $status;
			$MerchantOrder->save();
			
			// create order detail
			foreach($cart_items as $item) 
			{
				$product_id = null;
				$item_id = null;
				
				if($item->attributes['order_type']=='food')	{
					$menu_item = FoodMenuItem::find($item->attributes['item_id']);
					$item_id = $menu_item->id;
				}
				else {
					$product = Shopproducts::find($item->attributes['item_id']);	
					$product_id = $product->id;
				}
				$item_order_booking_detail_data[] = array('order_id'=> $main_order_id, 'merchant_order_id' => $MerchantOrder->id, 'item_id'=> $item_id, 'product_id'=> $product_id, 'unit_price' => $item->price, 'quantity' => $item->quantity, 'total_amount' => $item->getPriceSum(), 'status' => $status);				
			}
		}
		
		if($item_order_booking_detail_data)	{
			OrderBookingDetail::insert($item_order_booking_detail_data);
		}
		
		// price on order booking table update
		/*$order_booking = OrderBooking::find($main_order_id);
		$order_booking->sub_total = $net_total - $net_shipping_amount;
		$order_booking->delivery_fee = $net_shipping_amount;
		$order_booking->total_amount = $net_total;
		$order_booking->save();*/
	}
	
	public function getInvoiceId($id)
	{
		$invoice_pre_txt = 'APOUV-';
		
		$zerofill_id = str_pad($id, 10, '0', STR_PAD_LEFT);

		return $invoice_pre_txt . $zerofill_id;
	}
}
