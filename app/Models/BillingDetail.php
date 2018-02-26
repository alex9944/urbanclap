<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BillingDetail extends Model
{
    protected $table = 'billing_detail';
	
	public $order_online_id = 1; // get from category_type table
	
	//public $timestamps = false;
	
	public function get_adding_rules()
	{
		$rules = [
			/*'b_address_1'	=> 'required',
			'b_city'	=> 'required',
			'b_state'	=> 'required',
			'b_pincode'	=> 'required',*/
			's_address_1'	=> 'required',
			's_city'	=> 'required',
			's_state'	=> 'required',
			's_pincode'	=> 'required',
		];
		
		$messages = [
			/*'b_address_1.required'	=> 'Billing address is required',
			'b_city.required'	=> 'Billing city is required',
			'b_state.required'	=> 'Billing state is required',
			'b_pincode.required'	=> 'Billing pincode is required',*/
			's_address_1.required'	=> 'Delivery address is required',
			's_city.required'	=> 'Delivery city is required',
			's_state.required'	=> 'Delivery state is required',
			's_pincode.required'	=> 'Delivery pincode is required',
		];
		
		return array('rules' => $rules, 'messages' => $messages);
	}
	
	public function billing_country()
    {
        return $this->belongsTo('App\Models\Country', 'b_country');
    }
	public function billing_state()
    {
        return $this->belongsTo('App\Models\States', 'b_state');
    }	
	public function billing_city()
    {
        return $this->belongsTo('App\Models\Cities', 'b_city');
    }
	
	public function delivery_country()
    {
        return $this->belongsTo('App\Models\Country', 's_country');
    }
	public function delivery_state()
    {
        return $this->belongsTo('App\Models\States', 's_state');
    }
	public function delivery_city()
    {
        return $this->belongsTo('App\Models\Cities', 's_city');
    }
}
