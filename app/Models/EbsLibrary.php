<?php 

namespace App\Models;

use App\Models\PaymentGatewaySiteSettings;

class EbsLibrary {
	
	var $fields;
	var $submit_btn;
	
	var $ebs_url;
	var $ebs_mode;
	var $channel;
	var $hash_data;
	
	function __construct()
	{
		
		$this->ebs_url ='https://secure.ebs.in/pg/ma/payment/request';
		$this->channel = '0'; // standared payment
		$this->fields = array();
		
	}
	
	function addAPIFilelds()
	{
		
		$payment_gateway_id = 1;		
		$gateway_settings = PaymentGatewaySiteSettings::where(['payment_gateway_site_id' => $payment_gateway_id])->first();
		
		$mode = strtoupper($gateway_settings->mode);
		$channel = $this->channel;
		$this->hash_data = $gateway_settings->secret_key;
		
		$this->addField('account_id', $gateway_settings->api_key);
		$this->addField('channel', $channel);
		$this->addField('currency', $gateway_settings->currency->code);
		$this->addField('country', 'IND');
		$this->addField('mode', $mode);
	}

	function button($value)
	{
		$this->submit_btn = \Form::button($value, array('onclick' => 'document.ebs_auto_form.submit();'));
	}


	function addField($field, $value) 
	{
		
		$this->fields[$field] = $value;
	}

	function generateForm() 
	{
		
		$this->button('Click here if you\'re not automatically redirected...');
		
		$html = '<p style="text-align:center;">Please wait, your order is being processed and you will be redirected to the third party website.</p>';
		$html .= $this->ebs_form('ebs_auto_form');
		
		return $html;
	}

	function ebs_form($form_name) 
	{
		$hashData = $this->hash_data;
		ksort($this->fields);
		
		foreach ($this->fields as $key => $value){
			if (strlen($value) > 0) {
				$hashData .= '|'.$value;
			}

		}
		//echo $hashData;
		//$hashData = '24f1ced6756316018e24de3c42157b0f|23617|kodambakkam|4330.00|0|chennai|IND|INR|3|user1@wifin.com|TEST|vinoth|9874102563|600021|00000006|http://localhost/gs_shankhala/products/response/';
		if (strlen($hashData) > 0) {
			 $this->fields['secure_hash'] = strtoupper(hash("sha512",$hashData));
		}
		
		$str = '<form method="POST" action="'.$this->ebs_url.'" name="'.$form_name.'"/>' . "\n";	
		foreach ($this->fields as $name => $value) {
			
			$str .= \Form::hidden($name, $value) . "\n";
			
		}
		$str .= '<p style="text-align:center">'. $this->submit_btn . '</p>';
		$str .= \Form::close() . "\n";

		return $str;

	}
	
	
	function curlPost($paypalurl,$paypalreturnarr)
	{
		
		$req = 'cmd=_notify-validate';
		foreach($paypalreturnarr as $key => $value) 
		{
			$value = urlencode(stripslashes($value));
			$req .= "&$key=$value";
		}
			
		$ipnsiteurl=$paypalurl;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $ipnsiteurl);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
		$result = curl_exec($ch);
		curl_close($ch);
	
		return $result;
	}

}

?>