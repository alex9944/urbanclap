<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentSettings extends Model
{
    protected $table = 'payment_settings';
	
	//public $timestamps = false;
	
	public function get_adding_rules()
	{
		$rules = [
			'account_holder_name'	=> 'required',
			'account_id'	=> 'required',
			'bank_name'	=> 'required',
			'ifsc_code'	=> 'required',
		];
		
		$messages = [
			'account_holder_name.required'	=> 'Account holder name is required',
			'account_id.required'	=> 'Account id is required',
			'bank_name.required'	=> 'Bank name is required',
			'ifsc_code.required'	=> 'IFSC code is required',
		];
		
		return array('rules' => $rules, 'messages' => $messages);
	}
	
	/*public function gateway_settings()
    {
        return $this->hasMany('App\Models\PaymentGatewaySettings', 'payment_gateway_id');
    }*/
}