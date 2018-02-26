<?php 

namespace App\Models;

use App\Models\PaymentGatewaySiteSettings;

class RazorLibrary {
	
	var $fields;
	var $submit_btn;
	
	var $ebs_mode;
	var $channel;
	var $secret_key;
	
	function __construct()
	{
		
		$this->fields = array();
		
	}
	
	function addAPIFilelds()
	{
		
		$payment_gateway_id = 3;		
		$gateway_settings = PaymentGatewaySiteSettings::where(['payment_gateway_site_id' => $payment_gateway_id])->first();
		
		$this->secret_key = $gateway_settings->secret_key;
		
		$this->addField('key', $gateway_settings->api_key);
		$this->addField('payment_url', 'https://checkout.razorpay.com/v1/checkout.js');
	}

	function button($value)
	{
		$this->submit_btn = \Form::button($value, array('id' => 'pay_button', 'onclick' => 'document.ebs_auto_form.submit();'));
	}


	function addField($field, $value) 
	{
		
		$this->fields[$field] = $value;
	}

	function generateForm() 
	{
		
		$this->button('Click here if you\'re not automatically redirected...');
		
		$html = '<p style="text-align:center;">Please wait, your order is being processed and you will be redirected to the third party website.</p>';
		$html .= $this->form_str();
		
		return $html;
	}

	function form_str() 
	{
		
		$str = '<form name="payment_auto_form" action="'.$this->fields['return_url'].'" method="POST" style="text-align: center;">
		<script
			src="'.$this->fields['payment_url'].'"
			data-key="'.$this->fields['key'].'"
			data-amount="'.$this->fields['amount'].'"
			data-currency="INR"
			data-name="Apoyou"
			data-description="'.$this->fields['description'].'"
			data-image="'.url('assets/images/logo.png').'"
			data-prefill.name="'.$this->fields['customer_name'].'"
			data-prefill.email="'.$this->fields['customer_email'].'"
			data-prefill.contact="'.$this->fields['phone'].'"
			data-notes.order_id="'.$this->fields['subscription_id'].'"
			data-theme.color="#F37254"
		></script>
		<input type="hidden" value="'.$this->fields['subscription_id'].'" name="subscription_id">
		</form>';

		return $str;

	}

	function generateFormOrder() 
	{
		
		$str = '<form name="payment_auto_form" action="'.$this->fields['return_url'].'" method="POST" style="text-align: center;">
		<script
			src="'.$this->fields['payment_url'].'"
			data-key="'.$this->fields['key'].'"
			data-amount="'.$this->fields['amount'].'"
			data-buttontext="Click here if you\'re not automatically redirected..."
			data-currency="INR"
			data-name="Apoyou"
			data-description="'.$this->fields['description'].'"
			data-image="'.url('assets/images/logo.png').'"
			data-prefill.name="'.$this->fields['customer_name'].'"
			data-prefill.email="'.$this->fields['customer_email'].'"
			data-prefill.contact="'.$this->fields['phone'].'"
			data-notes.order_id="'.$this->fields['order_id'].'"
			data-theme.color="#F37254"
		></script>
		<input type="hidden" value="'.$this->fields['order_id'].'" name="order_id">'.
		csrf_field().
		'</form>';

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