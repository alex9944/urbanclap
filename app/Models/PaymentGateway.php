<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentGateway extends Model
{
    protected $table = 'payment_gateway';
	
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
        return $this->hasMany('App\Models\PaymentGatewaySettings', 'payment_gateway_id');
    }
}