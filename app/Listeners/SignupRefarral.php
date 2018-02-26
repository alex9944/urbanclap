<?php

namespace App\Listeners;

use App\Events\SignupVerified;

use Mail;

use App\Models\LocalvendorMarketing;
use App\Models\LocalVendorUsage;
use App\Models\SiteSettings;

class SignupRefarral
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
     * @param  SignupVerified  $event
     * @return void
     */
    public function handle(SignupVerified $event)
    {
        $user = $event->user;
		
		if($user->referral_vendor_code)
		{
			$local_vendor_marketing = LocalvendorMarketing::where('unique_code', $user->referral_vendor_code)->first();
			
			// update process
			if($local_vendor_marketing) {
				$settings = SiteSettings::first();
				$signup_user_points = $settings->signup_user_points;
				$signup_vendor_commission = $settings->signup_vendor_commission;
				
				// user update
				$user->available_points = $user->available_points+$signup_user_points;
				$user->total_points = $user->total_points+$signup_user_points;
				$user->save();
				//$this->_sendMailToUser($user, $settings);
				
				// local vendor update
				$local_vendor_marketing->account_balance = $local_vendor_marketing->account_balance + $signup_vendor_commission;
				$local_vendor_marketing->total_earned = $local_vendor_marketing->total_earned + $signup_vendor_commission;
				$local_vendor_marketing->save();
				$this->_sendSmsToLocalVendor($local_vendor_marketing, $settings);
				
				// add local vendor usage detail
				$LocalVendorUsage = new LocalVendorUsage;
				$LocalVendorUsage->local_vendor_marketing_id = $local_vendor_marketing->id;
				$LocalVendorUsage->user_id = $user->id;
				$LocalVendorUsage->user_points = $signup_user_points;
				$LocalVendorUsage->local_vendor_commission = $signup_vendor_commission;
				$LocalVendorUsage->usage_type = 'signup';
				$LocalVendorUsage->save();
			}
		}
    }
	
	protected function _sendMailToUser($user, $settings)
	{
		$mail_content = array();
		$mail_content['email'] = $user->email;
		$mail_content['name'] = $user->first_name;
		$mail_content['available_points'] = $user->available_points;
		$mail_content['earn_points'] = $settings->signup_user_points;
		$mail_content['site_name'] = ($settings->site_name != '') ? $settings->site_name : 'Needifo';
		$mail_content['referral_code'] = $user->referral_vendor_code;
		// admin email
		if( isset($settings->notification_email) )
			$mail_content['admin_mail'] = $settings->notification_email;
		Mail::send('emails.signup_referral', $mail_content, function($message)use ($mail_content) 
		{
			$email = $mail_content['email'];
			$message->to($email,'');
			
			if(isset($mail_content['admin_mail']))
				$message->bcc($mail_content['admin_mail'],'');
			
			$site_name = $mail_content['site_name'];
			$message->subject($site_name . ' | You have earned ' . $mail_content['earn_points'] . ' points by applying successfull refarral code');

		});
	}
	
	protected function _sendSmsToLocalVendor($local_vendor_marketing, $settings)
	{
		$mobile_no = $local_vendor_marketing->phone;
		$signup_vendor_commission = $settings->signup_vendor_commission;
		
		// sms integration
		if($mobile_no and $signup_vendor_commission) {
		
			try {
				
				$msg = 'You have earned Rs. '.$signup_vendor_commission.' by applying successfull refarral code.';
				
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