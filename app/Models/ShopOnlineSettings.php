<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShopOnlineSettings extends Model
{
    protected $table = 'shop_online_settings';
	
	public $order_online_id = 2; // get from category_type table
	
	public $timestamps = false;
	
	public function get_adding_rules()
	{
		$rules = [
			'delivery_fee'				=> 'required|numeric',
			//'estimated_delivery_time' 	=> 'required'
		];
		
		$messages = [
			'delivery_fee.required'				=> 'Delivery fee is required',
			//'estimated_delivery_time.required'	=> 'Estimated delivery time is required',
		];
		
		return array('rules' => $rules, 'messages' => $messages);
	}
}
