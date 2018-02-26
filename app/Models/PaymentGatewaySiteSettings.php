<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentGatewaySiteSettings extends Model
{
    protected $table = 'payment_gateway_site_settings';
	
	public $timestamps = false;
	
	public function get_adding_rules()
	{
		$rules = [
			//'api_key'			=> 'required',
			'payment_gateway_site_id'=> 'required',
			'mode'=> 'required',
		];
		
		$messages = [
			//'api_key.required'				=> 'Api key is required',
			'payment_gateway_site_id.required'	=> 'Payment gateway is required',
			'mode.required'	=> 'Mode is required',
		];
		
		return array('rules' => $rules, 'messages' => $messages);
	}
	
	public function payment_gateway()
	{
		return $this->belongsTo('App\Models\PaymentGatewaySite', 'payment_gateway_site_id');
	}
	
	public function currency()
	{
		return $this->belongsTo('App\Models\Currency', 'currency_id');
	}
}