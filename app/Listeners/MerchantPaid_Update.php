<?php

namespace App\Listeners;

use App\Events\MerchantPaid;

use Mail;

use App\Models\SiteSettings;
use App\Models\MerchantOrder;
use App\Models\OrderBooking;

class MerchantPaid_Update
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
     * @return void
     */
    public function handle(MerchantPaid $event)
    {
        $merchant_transaction = $event->merchant_transaction;
		$listing = [];
		if( isset($merchant_transaction->listing) )
			$listing = $merchant_transaction->listing;
		
		if($listing)
		{
			$OrderBooking = new OrderBooking;
			$merchant_order_status = $OrderBooking->merchant_order_status;
			
			MerchantOrder::where('listing_id', $listing->id)
							->where(['merchant_payment_status' => 'unpaid', 'order_status' => $merchant_order_status['completed']['id']])
							->whereIn('order_type', ['online_order', 'online_service', 'online_shopping'])
							->update(['merchant_payment_status' => 'paid', 'merchant_transaction_id' => $merchant_transaction->id]);
		}
    }
}