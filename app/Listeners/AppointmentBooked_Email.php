<?php

namespace App\Listeners;

use App\Events\AppointmentBooked;

use Mail;

use App\Models\AppointmentBookingOrder;
use App\Models\SiteSettings;

class AppointmentBooked_Email
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
     * @param  AppointmentBooked  $event
     * @return void
     */
    public function handle(AppointmentBooked $event)
    {
        $AppointmentBookingOrder = $event->AppointmentBookingOrder;
		
		if($AppointmentBookingOrder)
		{
			$listing = $AppointmentBookingOrder->listing;
			$main_order = $AppointmentBookingOrder->main_order;
			
			if($listing) {
				
				$settings = SiteSettings::first();
				
				$this->_sendMailToUser($AppointmentBookingOrder, $main_order, $listing, $settings);
				
				$merchant = $AppointmentBookingOrder->user;
				
				if($merchant)
					$this->_sendMailToMerchant($AppointmentBookingOrder, $merchant, $main_order, $listing, $settings);
			}
		}
    }
	
	protected function _sendMailToUser($AppointmentBookingOrder, $main_order, $listing, $settings)
	{		
		$mail_content = array();
		$mail_content['email'] = $AppointmentBookingOrder->email;
		$mail_content['name'] = $AppointmentBookingOrder->name;
		$mail_content['listing_name'] = $listing->title;
		$mail_content['AppointmentBookingOrder'] = $AppointmentBookingOrder;
		$mail_content['main_order'] = $main_order;
		$mail_content['site_name'] = ($settings->site_name != '') ? $settings->site_name : 'Needifo';
		
		if( isset($settings->notification_email) )
			$mail_content['admin_mail'] = $settings->notification_email;
		
		Mail::send('emails.appointment_booking', $mail_content, function($message)use ($mail_content) 
		{
			$email = $mail_content['email'];
			$message->to($email,'');
			
			if(isset($mail_content['admin_mail']))
				$message->bcc($mail_content['admin_mail'],'');
			
			$site_name = $mail_content['site_name'];
			$message->subject($site_name . ' | You have booked appointment in ' . $mail_content['listing_name']);

		});
	}
	
	protected function _sendMailToMerchant($AppointmentBookingOrder, $merchant, $main_order, $listing, $settings)
	{
		$mail_content = array();
		$mail_content['email'] = $merchant->email;
		$mail_content['name'] = $merchant->first_name;
		$mail_content['listing_name'] = $listing->title;
		$mail_content['AppointmentBookingOrder'] = $AppointmentBookingOrder;
		$mail_content['main_order'] = $main_order;
		$mail_content['site_name'] = ($settings->site_name != '') ? $settings->site_name : 'Needifo';
				
		Mail::send('emails.appointment_booking_merchant', $mail_content, function($message)use ($mail_content) 
		{
			$email = $mail_content['email'];
			$message->to($email,'');
			
			$site_name = $mail_content['site_name'];
			$message->subject($site_name . ' | New appointment booking request received.');

		});
	}
}