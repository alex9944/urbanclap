<?php

namespace App\Listeners;

use App\Events\TableBooked;

use Mail;

use App\Models\TableBookingOrder;
use App\Models\SiteSettings;

class TableBooked_Email
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
     * @param  TableBooked  $event
     * @return void
     */
    public function handle(TableBooked $event)
    {
        $TableBookingOrder = $event->TableBookingOrder;
		
		if($TableBookingOrder)
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
		
		Mail::send('emails.table_booking', $mail_content, function($message)use ($mail_content) 
		{
			$email = $mail_content['email'];
			$message->to($email,'');
			
			if(isset($mail_content['admin_mail']))
				$message->bcc($mail_content['admin_mail'],'');
			
			$site_name = $mail_content['site_name'];
			$message->subject($site_name . ' | You have booked the table on ' . $mail_content['listing_name']);

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
				
		Mail::send('emails.table_booking_merchant', $mail_content, function($message)use ($mail_content) 
		{
			$email = $mail_content['email'];
			$message->to($email,'');
			
			$site_name = $mail_content['site_name'];
			$message->subject($site_name . ' | New table booking request received.');

		});
	}
}