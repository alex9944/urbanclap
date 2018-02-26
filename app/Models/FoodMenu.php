<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FoodMenu extends Model
{
    protected $table = 'food_menu';
	
	//public $timestamps = false;
	
	public function get_adding_rules()
	{
		$rules = [
			'name'	=> 'required',
		];
		
		$messages = [
			'name.required'	=> 'Menu name is required',
		];
		
		return array('rules' => $rules, 'messages' => $messages);
	}
	
	public function menu_merchant()
    {
        return $this->hasMany('App\Models\FoodMenuMerchant', 'food_menu_id');
    }
	
	public function menu_items()
    {
        return $this->hasMany('App\Models\FoodMenuItem', 'food_menu_id');
    }
}
