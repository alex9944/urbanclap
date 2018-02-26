<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class LocalvendorMarketing extends Model
{
	protected $table = 'local_vendor_marketing';
	
	public function get_adding_rules()
	{
		$rules = [
			'marketing_person_id'	=> 'required',
			'unique_code'	=> 'required',
			'shop_name'	=> 'required',
			'customer_name'	=> 'required',
			'address'	=> 'required',
			'phone'	=> 'required',
			'state_id'	=> 'required',
			'city_id'	=> 'required',
		];
		
		$messages = [
			'marketing_person_id.required'	=> 'Marketing person id is required',
			'unique_code.required'	=> 'Unique code is required',
			'shop_name.required'	=> 'Shop name is required',
			'customer_name.required'	=> 'Customer name is required',
			'address.required'	=> 'Address is required',
			'phone.required'	=> 'Phone number is required',
			'state_id.required'	=> 'State is required',
			'city_id.required'	=> 'City is required',
		];
		
		return array('rules' => $rules, 'messages' => $messages);
	}
	
	public function generateUniqueCode() 
	{
		$number = mt_rand(100000, 999999); // better than rand()
		
		$char = '';
		for($i = 0; $i < 3; $i++){
			$char .= chr(mt_rand(65, 90)); // see the ascii table why 65 to 90. 
		}
		
		$number = $char . '_' . $number;

		// call the same function if the barcode exists already
		if ($this->_uniqueCodeExists($number)) {
			return $this->generateUniqueCode();
		}

		// otherwise, it's valid and can be used
		return $number;
	}

	private function _uniqueCodeExists($number) 
	{
		// query the database and return a boolean
		// for instance, it might look like this in Laravel
		return self::whereUniqueCode($number)->exists();
	}
	
	/*
	protected static function boot() 
	{
		parent::boot();
		
		static::deleting(function($local_vendor) {
			//$local_vendor->usage_history()->delete();
			//$local_vendor->transactions()->delete();
		});
	}*/
	
	public function city()
    {
        return $this->belongsTo('App\Models\Cities', 'city_id');
    }
	
	public function state()
    {
        return $this->belongsTo('App\Models\States', 'state_id');
    }
	
	public function usage_history()
    {
        return $this->hasMany('App\Models\LocalVendorUsage', 'local_vendor_marketing_id');
    }
	
	public function transactions()
    {
        return $this->hasMany('App\Models\LocalVendorTransaction', 'local_vendor_marketing_id');
    }
	
}
	
?>