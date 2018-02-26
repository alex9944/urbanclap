<?php

namespace App\Listeners;

use App\Events\MerchantPaid;

use Mail;

use App\Models\SiteSettings;

class MerchantPaid_Notify
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
		$merchant_detail = [];
		if( isset($merchant_transaction->listing->listing_merchant) )
			$merchant_detail = $merchant_transaction->listing->listing_merchant;
		
		if($merchant_detail)
		{
			$this->_sendMailToMerchant($merchant_detail, $merchant_transaction);
		}
    }
	
	protected function _sendMailToMerchant($user, $merchant_transaction)
	{
		$settings = SiteSettings::first();
		
		$mail_content = array();
		$mail_content['email'] = $user->email;
		$mail_content['name'] = $user->first_name;
		$mail_content['paid_amount'] = $merchant_transaction->paid_amount;
		$mail_content['cheque_no'] = $merchant_transaction->signup_user_points;
		$mail_content['site_name'] = ($settings->site_name != '') ? $settings->site_name : 'Needifo';
		
		// admin email
		if( isset($settings->notification_email) )
			$mail_content['admin_mail'] = $settings->notification_email;
		
		Mail::send('emails.merchant_paid_notification', $mail_content, function($message)use ($mail_content) 
		{
			$email = $mail_content['email'];
			$message->to($email,'');
			
			if(isset($mail_content['admin_mail']))
				$message->bcc($mail_content['admin_mail'],'');
			
			$site_name = $mail_content['site_name'];
			$message->subject($site_name . ' | Payment of ' . $mail_content['paid_amount'] . ' has been done');

		});
	}
}