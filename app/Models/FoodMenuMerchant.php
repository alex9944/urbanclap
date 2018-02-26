<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FoodMenuMerchant extends Model
{
    protected $table = 'food_menu_merchant';
	
	protected $fillable = ['merchant_id', 'food_menu_id'];
	public $timestamps = false;
	
	//public $timestamps = false;
	
	public function menu()
    {
        return $this->belongsTo('App\Models\FoodMenu', 'food_menu_id');
    }
}
