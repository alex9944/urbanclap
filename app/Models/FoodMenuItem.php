<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FoodMenuItem extends Model
{
    protected $table = 'food_menu_item';
	
	//public $timestamps = false;
	
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
			'listing_id'	=> 'required',
			'food_menu_id'	=> 'required',
			'item_type'				=> 'required',
			'name'					=> 'required',
			//'description'			=> 'required',
			'original_price'		=> 'required|numeric',
			'discounted_price'		=> 'sometimes|numeric',
			'stock'					=> 'required|numeric',
		];
		
		$messages = [
			'listing_id.required'	=> 'Select listing',
			'food_menu_id.required'	=> 'Select Menu',
			'item_type.required'				=> 'Item type is required',
			'name.required'						=> 'Item name is required',
			//'description.required'				=> 'Description is required',
			'original_price.required'			=> 'Original price is required',
			'stock.required'					=> 'Stock is required',
		];
		
		return array('rules' => $rules, 'messages' => $messages);
	}
	public function get_edit_rules()
	{
		$rules = [
		
			'food_menu_id'	=> 'required',
			'item_type'				=> 'required',
			'name'					=> 'required',
			//'description'			=> 'required',
			'original_price'		=> 'required|numeric',
			'discounted_price'		=> 'sometimes|numeric',
			'stock'					=> 'required|numeric',
		];
		
		$messages = [		
			'food_menu_id.required'	=> 'Select Menu',
			'item_type.required'				=> 'Item type is required',
			'name.required'						=> 'Item name is required',
			//'description.required'				=> 'Description is required',
			'original_price.required'			=> 'Original price is required',
			'stock.required'					=> 'Stock is required',
		];
		
		return array('rules' => $rules, 'messages' => $messages);
	}
	
	public function menu_merchant()
    {
        return $this->belongsTo('App\Models\FoodMenuMerchant', 'food_menu_merchant_id');
    }
	
	public function menu()
    {
        return $this->belongsTo('App\Models\FoodMenu', 'food_menu_id');
    }
	
    public function listing()
    {
    	return $this->belongsTo('App\Models\Listing', 'listing_id');
    }
	
	public function order_booking_details()
	{
        return $this->hasMany('App\Models\OrderBookingDetail', 'item_id');
    }
}
