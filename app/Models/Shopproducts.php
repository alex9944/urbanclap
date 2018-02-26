<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shopproducts extends Model {

	protected $table = 'shop_products';
	
	protected static function boot()
	{
		parent::boot();

		static::deleting(function($telco) {
			$relationMethods = ['order_booking_details'];

			foreach ($relationMethods as $relationMethod) {
				if ($telco->$relationMethod()->count() > 0) {
					return false;
				}
			}
		});
	}

	public function get_adding_rules()
	{
		$rules = [
		
			'cat_id'				=> 'required',
			'pro_name'					=> 'required',
			'pro_price'			=> 'required',
			'pro_code'		=> 'required',
			'pro_img'		=> 'required',
			'pro_info'		=>'required',
			'stock'		=>'required',
		];
		
		$messages = [
		
			'cat_id.required'				=> 'Category is required',
			'pro_name.required'						=> 'Product name is required',
			'pro_price.required'				=> 'Product Price is required',
			'pro_code.required'			=> 'Product Code is required',
			'pro_img.required'			=>' Product Main Image required',
			'pro_info.required'			=>' Product Information required',
		];	
		return array('rules' => $rules, 'messages' => $messages);
	}
	
	public function get_adding_rules_mobile()
	{
		$rules = [
		    'user_id'				=> 'required',
			'cat_id'				=> 'required',
			'pro_name'					=> 'required',
			'pro_price'			=> 'required',
			'pro_code'		=> 'required',
			'pro_img'		=> 'required',
			'pro_info'		=>'required',
		];
		
		$messages = [
		 'user_id.required'				=> 'User id required',
			'cat_id.required'				=> 'Category is required',
			'pro_name.required'						=> 'Product name is required',
			'pro_price.required'				=> 'Product Price is required',
			'pro_code.required'			=> 'Product Code is required',
			'pro_img.required'			=>' Product Main Image required',
			'pro_info.required'			=>' Product Information required',
		];	
		return array('rules' => $rules, 'messages' => $messages);
	}
	
	public function categories()
	{
		return $this->belongsTo('App\Models\ShopProductCategory', 'cat_id', 'id');
	}
	
    public function listing()
    {
    	return $this->belongsTo('App\Models\Listing', 'listing_id');
    }
	
    public function merchant()
    {
    	return $this->belongsTo('App\Models\User', 'merchant_id');
    }
	
    public function images()
    {
    	return $this->hasMany('App\Models\ShopProductImages', 'proId');
    }
	
	public function order_booking_details()
	{
        return $this->hasMany('App\Models\OrderBookingDetail', 'product_id');
    }
}
