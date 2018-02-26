<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSettings extends Model
{
	protected $table = 'site_settings';
	
	public $timestamps = false;
	
    public function get_adding_rules()
    {
    	$rules = [
		'country_id'	=> 'required',
		'city_id'	=> 'required',
		'currency_id'	=> 'required',
		'city_lattitude'	=> 'required',
		'city_longitude'	=> 'required',
    	'merchant_commision_percent'	=> 'required|numeric',
    	'days_to_pay_merchant'	=>'required|numeric'
    	];

    	$messages = [
    	'country_id.required'	=> 'Default country is required',
    	'currency_id.required'	=> 'Default currency is required',
    	'city_id.required'	=> 'Default city is required',
    	'city_lattitude.required'	=> 'Default city lattitude is required',
    	'city_longitude.required'	=> 'Default city longitude is required',
    	'merchant_commision_percent.required'	=> 'Merchant commision is required',
    	'days_to_pay_merchant.required'	=> 'Days to pay merchant is required'
    	];

    	return array('rules' => $rules, 'messages' => $messages);
    }
	 
	public function country()
    {
        return $this->belongsTo('App\Models\Country', 'country_id');
    }
	
	public function city()
    {
        return $this->belongsTo('App\Models\Cities', 'city_id');
    }
	
	public function currency()
    {
        return $this->belongsTo('App\Models\Currency', 'currency_id');
    }
  
}
