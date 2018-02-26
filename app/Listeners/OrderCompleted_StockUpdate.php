<?php

namespace App\Listeners;

use App\Events\OrderCompleted;

use Mail;

use App\Models\Shopproducts;
use App\Models\FoodMenuItem;

class OrderCompleted_StockUpdate
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  OrderCompleted  $event
     * @return void
     */
    public function handle(OrderCompleted $event)
    {
        $OrderBooking = $event->OrderBooking;
		
		if($OrderBooking)
		{
			$merchant_orders = $OrderBooking->merchant_orders;
			
			foreach($merchant_orders as $merchant_order)
			{				
				if($merchant_order->order_type == 'online_order') 
				{
					// food
					$this->_processFood($merchant_order);					
				} 
				else if($merchant_order->order_type == 'online_shopping') 
				{
					// product
					$this->_processProduct($merchant_order);
				}
			}
		}
    }
	
	private function _processFood($merchant_order)
    {
		$order_details = $merchant_order->order_details;
		
		foreach($order_details as $order_detail)
		{
			$quantity = $order_detail->quantity;
			
			$menu_item = $order_detail->menu_item;
			$stock = $menu_item->stock;
			
			$new_stock = $stock - $quantity;
			
			FoodMenuItem::where('id', $menu_item->id)
								->update(['stock' => $new_stock]);
		}
	}
	
	private function _processProduct($merchant_order)
    {
		$order_details = $merchant_order->order_details;
		
		foreach($order_details as $order_detail)
		{
			$quantity = $order_detail->quantity;
			
			$shop_item = $order_detail->shop_item;
			$stock = $shop_item->stock;
			
			$new_stock = $stock - $quantity;
			
			Shopproducts::where('id', $shop_item->id)
								->update(['stock' => $new_stock]);
		}
	}
}