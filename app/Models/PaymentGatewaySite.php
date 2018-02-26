<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentGatewaySite extends Model
{
    protected $table = 'payment_gateway_site';
	
	public $timestamps = false;
	
	public function get_adding_rules()
	{
		$rules = [
			'name'	=> 'required',
		];
		
		$messages = [
			'name.required'	=> 'Name is required',
		];
		
		return array('rules' => $rules, 'messages' => $messages);
	}
	
	public function gateway_settings()
    {
        return $this->hasOne('App\Models\PaymentGatewaySiteSettings', 'payment_gateway_site_id');
    }
}