<?php

namespace App\Listeners;

use App\Events\OrderStatusChanged;

use Mail;

use App\Models\OrderBookingDetail;
use App\Models\OrderBooking;
use App\Models\SiteSettings;

class OrderStatusChanged_Email
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
     * @param  OrderStatusChanged  $event
     * @return void
     */
    public function handle(OrderStatusChanged $event)
    {
        $OrderBookingDetail = $event->OrderBookingDetail;
		
		if($OrderBookingDetail)
		{			
			$settings = SiteSettings::first();
				
			$this->_sendMailToUser($OrderBookingDetail, $settings);
		}
    }
	
	protected function _sendMailToUser($OrderBookingDetail, $settings)
	{		
		$parent_order = $OrderBookingDetail->main_order;
		$main_order = $parent_order->main_order;
		
		$mail_content = array();
		$mail_content['email'] = $parent_order->email;
		$mail_content['name'] = $parent_order->name;
		$mail_content['invoice_id'] = $main_order->invoice_id;
		
		$status = $OrderBookingDetail->status;
		$mail_content['status'] = $status;
		$OrderBooking = new OrderBooking;
		$order_detail_status = $OrderBooking->order_detail_status;
		
		if( isset($order_detail_status[$status]['name']))
		{
			$mail_content['product_status'] = $order_detail_status[$status]['name'];
			
			$mail_content['site_name'] = ($settings->site_name != '') ? $settings->site_name : 'Needifo';
			
			if( isset($settings->notification_email) )
				$mail_content['admin_mail'] = $settings->notification_email;
			
			Mail::send('emails.order_status_changed', $mail_content, function($message)use ($mail_content) 
			{
				$email = $mail_content['email'];
				$message->to($email,'');
				
				if(isset($mail_content['admin_mail']))
					$message->bcc($mail_content['admin_mail'],'');
				
				$site_name = $mail_content['site_name'];
				$message->subject($site_name . ' | Your Order ' . $mail_content['invoice_id'] . ' status updated');

			});
		}
	}
}