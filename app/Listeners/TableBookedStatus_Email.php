<?php

namespace App\Listeners;

use App\Events\TableBookedStatus;

use Mail;

use App\Models\TableBookingOrder;
use App\Models\SiteSettings;

class TableBookedStatus_Email
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
     * @param  TableBookedStatus  $event
     * @return void
     */
    public function handle(TableBookedStatus $event)
    {
        $TableBookingOrder = $event->TableBookingOrder;
		
		if($TableBookingOrder and ($TableBookingOrder->status == 1 || $TableBookingOrder->status == 2) )
		{
			$listing = $TableBookingOrder->listing;
			$main_order = $TableBookingOrder->main_order;
			
			if($listing) {
				
				$settings = SiteSettings::first();
				
				$this->_sendMailToUser($TableBookingOrder, $main_order, $listing, $settings);
				
				$merchant = $TableBookingOrder->user;
				
				if($merchant)
					$this->_sendMailToMerchant($TableBookingOrder, $merchant, $main_order, $listing, $settings);
			}
		}
    }
	
	protected function _sendMailToUser($TableBookingOrder, $main_order, $listing, $settings)
	{		
		$mail_content = array();
		$mail_content['email'] = $TableBookingOrder->email;
		$mail_content['name'] = $TableBookingOrder->name;
		$mail_content['listing_name'] = $listing->title;
		$mail_content['TableBookingOrder'] = $TableBookingOrder;
		$mail_content['main_order'] = $main_order;
		$mail_content['site_name'] = ($settings->site_name != '') ? $settings->site_name : 'Needifo';
		
		if( isset($settings->notification_email) )
			$mail_content['admin_mail'] = $settings->notification_email;
		
		Mail::send('emails.table_booking_status', $mail_content, function($message)use ($mail_content) 
		{
			$email = $mail_content['email'];
			$message->to($email,'');
			
			if(isset($mail_content['admin_mail']))
				$message->bcc($mail_content['admin_mail'],'');
			
			$site_name = $mail_content['site_name'];
			
			$TableBookingOrder = $mail_content['TableBookingOrder'];
			$main_order = $mail_content['main_order'];
			
			if($TableBookingOrder->status == 1)
				$msg = 'Your table booking request for '.$main_order->invoice_id.' has been Confirmed';
			else
				$msg = 'Your table booking request for '.$main_order->invoice_id.' has been Declined';
			
			$message->subject($site_name . ' | ' . $msg);

		});
	}
	
	protected function _sendMailToMerchant($TableBookingOrder, $merchant, $main_order, $listing, $settings)
	{
		$mail_content = array();
		$mail_content['email'] = $merchant->email;
		$mail_content['name'] = $merchant->first_name;
		$mail_content['listing_name'] = $listing->title;
		$mail_content['TableBookingOrder'] = $TableBookingOrder;
		$mail_content['main_order'] = $main_order;
		$mail_content['site_name'] = ($settings->site_name != '') ? $settings->site_name : 'Needifo';
				
		Mail::send('emails.table_booking_status_merchant', $mail_content, function($message)use ($mail_content) 
		{
			$email = $mail_content['email'];
			$message->to($email,'');
			
			$site_name = $mail_content['site_name'];
			
			$TableBookingOrder = $mail_content['TableBookingOrder'];
			$main_order = $mail_content['main_order'];
			
			if($TableBookingOrder->status == 1)
				$msg = 'The table booking request for '.$main_order->invoice_id.' has been Confirmed';
			else
				$msg = 'The table booking request for '.$main_order->invoice_id.' has been Declined';
			
			$message->subject($site_name . ' | ' . $msg);

		});
	}
}