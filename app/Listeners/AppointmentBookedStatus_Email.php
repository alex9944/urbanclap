<?php

namespace App\Listeners;

use App\Events\AppointmentBookedStatus;

use Mail;

use App\Models\AppointmentBookingOrder;
use App\Models\SiteSettings;

class AppointmentBookedStatus_Email
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
     * @param  AppointmentBookedStatus  $event
     * @return void
     */
    public function handle(AppointmentBookedStatus $event)
    {
        $AppointmentBookingOrder = $event->AppointmentBookingOrder;
		
		if($AppointmentBookingOrder and ($AppointmentBookingOrder->status == 1 || $AppointmentBookingOrder->status == 2) )
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
		
		Mail::send('emails.appointment_booking_status', $mail_content, function($message)use ($mail_content) 
		{
			$email = $mail_content['email'];
			$message->to($email,'');
			
			if(isset($mail_content['admin_mail']))
				$message->bcc($mail_content['admin_mail'],'');
			
			$site_name = $mail_content['site_name'];
			
			$AppointmentBookingOrder = $mail_content['AppointmentBookingOrder'];
			$main_order = $mail_content['main_order'];
			
			if($AppointmentBookingOrder->status == 1)
				$msg = 'Your appointment booking request for '.$main_order->invoice_id.' has been Confirmed';
			else
				$msg = 'Your appointment booking request for '.$main_order->invoice_id.' has been Declined';
			
			$message->subject($site_name . ' | ' . $msg);

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
				
		Mail::send('emails.appointment_booking_status_merchant', $mail_content, function($message)use ($mail_content) 
		{
			$email = $mail_content['email'];
			$message->to($email,'');
			
			$site_name = $mail_content['site_name'];
			
			$AppointmentBookingOrder = $mail_content['AppointmentBookingOrder'];
			$main_order = $mail_content['main_order'];
			
			if($AppointmentBookingOrder->status == 1)
				$msg = 'The appointment booking request for '.$main_order->invoice_id.' has been Confirmed';
			else
				$msg = 'The appointment booking request for '.$main_order->invoice_id.' has been Declined';
			
			$message->subject($site_name . ' | ' . $msg);

		});
	}
}