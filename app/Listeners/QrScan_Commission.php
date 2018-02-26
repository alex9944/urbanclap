<?php

namespace App\Listeners;

use App\Events\QrScan;

use Mail;

//use App\Models\LocalvendorMarketing;
use App\Models\LocalVendorUsage;
use App\Models\SiteSettings;

class QrScan_Commission
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
     * @param  QrScan  $event
     * @return void
     */
    public function handle(QrScan $event)
    {
        $user = $event->user;
		$local_vendor_marketing = $event->local_vendor_marketing;
		
		$settings = SiteSettings::first();
		$scanning_user_points = $settings->scanning_user_points;
		$scanning_vendor_commission = $settings->scanning_vendor_commission;
		
		// user update
		$user->available_points = $user->available_points+$scanning_user_points;
		$user->total_points = $user->total_points+$scanning_user_points;
		$user->save();
		//$this->_sendMailToUser($user, $settings);
		
		// local vendor update
		$local_vendor_marketing->account_balance = $local_vendor_marketing->account_balance + $scanning_vendor_commission;
		$local_vendor_marketing->total_earned = $local_vendor_marketing->total_earned + $scanning_vendor_commission;
		$local_vendor_marketing->save();
		$this->_sendSmsToLocalVendor($local_vendor_marketing, $settings);
		
		// add local vendor usage detail
		$LocalVendorUsage = new LocalVendorUsage;
		$LocalVendorUsage->local_vendor_marketing_id = $local_vendor_marketing->id;
		$LocalVendorUsage->user_id = $user->id;
		$LocalVendorUsage->user_points = $scanning_user_points;
		$LocalVendorUsage->local_vendor_commission = $scanning_vendor_commission;
		$LocalVendorUsage->usage_type = 'scan';
		$LocalVendorUsage->save();
    }
	
	protected function _sendMailToUser($user, $settings)
	{
		$mail_content = array();
		$mail_content['email'] = $user->email;
		$mail_content['name'] = $user->first_name;
		$mail_content['available_points'] = $user->available_points;
		$mail_content['earn_points'] = $settings->scanning_user_points;
		$mail_content['site_name'] = ($settings->site_name != '') ? $settings->site_name : 'Needifo';
		// admin email
		if( isset($settings->notification_email) )
			$mail_content['admin_mail'] = $settings->notification_email;
		Mail::send('emails.qrscan', $mail_content, function($message)use ($mail_content) 
		{
			$email = $mail_content['email'];
			$message->to($email,'');
			
			if(isset($mail_content['admin_mail']))
				$message->bcc($mail_content['admin_mail'],'');
			
			$site_name = $mail_content['site_name'];
			$message->subject($site_name . ' | You have earned ' . $mail_content['earn_points'] . ' points by scanning QR Code successfully');

		});
	}
	
	protected function _sendSmsToLocalVendor($local_vendor_marketing, $settings)
	{
		$mobile_no = $local_vendor_marketing->phone;
		$scanning_vendor_commission = $settings->scanning_vendor_commission;
		
		// sms integration
		if($mobile_no and $scanning_vendor_commission) {
		
			try {
				
				$msg = 'You have earned Rs. '.$scanning_vendor_commission.' by scanning QR Code successfully.';
				
				$sms_content = rawurlencode($msg);
				
				//$sms_content='Your%20verification%20code%20is%20'.$mail_content['verification_code'].'%20This%2code%20is%20working%20on%2012%20hours';
				
				//$url = "http://trans.smsfresh.co/api/sendmsg.php?user=malathy&pass=WiFiNIM@2017&sender=IMARTS&phone=".$mobile_no."&text=".$sms_content."&priority=ndnd&stype=normal";
				
				$url = "http://trans.smsfresh.co/api/sendmsg.php?user=needifo&pass=123456&sender=APOYOU&phone=".$mobile_no."&text=".$sms_content."&priority=ndnd&stype=normal";
			
				$ch = curl_init();
				// Disable SSL verification
				//curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				// Will return the response, if false it print the response
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				// Set the url
				curl_setopt($ch, CURLOPT_URL,$url);
				// Execute
				$result=curl_exec($ch);
				// Closing
				curl_close($ch);
				
			} catch(\Exception $e) {
				//print_r($e->getMessage());
			}
		}
	}
}