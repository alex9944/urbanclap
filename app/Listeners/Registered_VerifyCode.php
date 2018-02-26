<?php

namespace App\Listeners;

use App\Events\Registered;

use Mail;

use App\Models\SiteSettings;

class Registered_VerifyCode
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
     * @param  Registered  $event
     * @return void
     */
    public function handle(Registered $event)
    {
        $user = $event->user;
		
		if($user)
		{
			//$this->_sendMailToUser($user);
			
			//Send Mobile
			$this->_sendSMSToUser($user);
		}
    }
	
	protected function _sendSMSToUser($user)
	{
		$msg = 'Please use the verification code to complete the signup progress: ' . $user->verification_code;
		
		$mobile_no = $user->mobile_no;
		$verification_code = $user->verification_code;
		
		if($mobile_no and $verification_code) {
		
			try {
				
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
	
	protected function _sendMailToUser($user)
	{
		$settings = SiteSettings::first();
		
		$mail_content = array();
		$mail_content['email'] = $user->email;
		$mail_content['name'] = $user->first_name;
		$mail_content['verification_code'] = $user->verification_code;
		$mail_content['site_name'] = ($settings->site_name != '') ? $settings->site_name : 'Needifo';
		// admin email
		if( isset($settings->notification_email) )
			$mail_content['admin_mail'] = $settings->notification_email;
		Mail::send('emails.signup_verification', $mail_content, function($message)use ($mail_content) 
		{
			$email = $mail_content['email'];
			$message->to($email,'');
			
			if(isset($mail_content['admin_mail']))
				$message->bcc($mail_content['admin_mail'],'');
			
			$site_name = $mail_content['site_name'];
			$message->subject($site_name . ' | Your Signup Verification Code');

		});
	}
}