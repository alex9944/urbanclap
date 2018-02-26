<?php

namespace App\Listeners;

use App\Events\OrderCompleted;

use Mail;

use App\Models\CategoryType;

class OrderCompleted_Commission
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
				$this->_processOrder($merchant_order);
			}
		}
    }
	
	protected function _processOrder($merchant_order)
	{
		$total_amount = $merchant_order->total_amount;
		$total_refund_amount = $merchant_order->total_refund_amount;
		
		$net_total = $total_amount - $total_refund_amount;
		
		// calculate commission
		$site_commission_amount = $this->_getCommissionValue($net_total, $merchant_order->order_type);
		$merchant_net_amount = $net_total - $site_commission_amount;
		
		// update prices
		$merchant_order->site_commission_amount = $site_commission_amount;
		$merchant_order->merchant_net_amount = $merchant_net_amount;
		$merchant_order->save();
	}
	
	protected function _getCommissionValue($total_amount, $order_type)
	{
		if($order_type == 'online_order')
			$type = 'online-order';
		else if($order_type == 'online_shopping')
			$type = 'online-shopping';

		// get the settings data
		$commission_setting = CategoryType::where('slug', $type)->first();
		
		$site_commission_price_value = 0;
		switch($commission_setting->commission_type)
		{
			case 'flat':
				$site_commission_price_value = $commission_setting->commission_value;
				break;
			case 'percent':
				$site_commission_price_value = $total_amount * ( $commission_setting->commission_value / 100 );
				$site_commission_price_value = round($site_commission_price_value);
				break;
		}
		
		return $site_commission_price_value;
	}
}