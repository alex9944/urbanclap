<?php

namespace App\Listeners;

use App\Events\OrderCompleted;

use Mail;

use App\Models\OrderBooking;
use App\Models\SiteSettings;

class OrderCompleted_Email
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
			$main_order = $OrderBooking->main_order;
			
			$settings = SiteSettings::first();
			
			//$orderBookingObj = new OrderBooking;
				
			$this->_sendMailToUser($OrderBooking, $main_order, $settings);
			
			$this->_sendMailToMerchant($OrderBooking, $settings);
		}
    }
	
	protected function _sendMailToUser($OrderBooking, $main_order, $settings)
	{		
		$mail_content = array();
		$mail_content['email'] = $OrderBooking->email;
		$mail_content['name'] = $OrderBooking->name;
		
		$mail_content['OrderBooking'] = $OrderBooking;
		$mail_content['merchant_orders'] = $OrderBooking->merchant_orders;
		$mail_content['main_order'] = $main_order;
		$mail_content['site_name'] = ($settings->site_name != '') ? $settings->site_name : 'Needifo';
		
		if( isset($settings->notification_email) )
			$mail_content['admin_mail'] = $settings->notification_email;
		
		Mail::send('emails.order_completed', $mail_content, function($message)use ($mail_content) 
		{
			$email = $mail_content['email'];
			$message->to($email,'');
			
			if(isset($mail_content['admin_mail']))
				$message->bcc($mail_content['admin_mail'],'');
			
			$site_name = $mail_content['site_name'];
			$message->subject($site_name . ' | Order Detail');

		});
	}
	
	protected function _sendMailToMerchant($OrderBooking, $settings)
	{		
		$merchant_orders = $OrderBooking->merchant_orders;
		
		// send mail to all merchant of orders
		foreach($merchant_orders as $merchant_order)
		{
			$merchant = $merchant_order->listing->listing_merchant;
			
			$mail_content = array();
			$mail_content['email'] = $merchant->email;
			$mail_content['name'] = $merchant->first_name;
			
			$mail_content['OrderBooking'] = $OrderBooking;
			$mail_content['merchant_order'] = $merchant_order;
			$mail_content['site_name'] = ($settings->site_name != '') ? $settings->site_name : 'Needifo';
					
			Mail::send('emails.order_completed_merchant', $mail_content, function($message)use ($mail_content) 
			{
				$email = $mail_content['email'];
				$message->to($email,'');
				
				$site_name = $mail_content['site_name'];
				$message->subject($site_name . ' | New Order Received.');

			});
		}
	}
}