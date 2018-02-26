<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShopOrderBookingDetail extends Model
{
    protected $table = 'shop_order_booking_detail';
	
	//public $timestamps = false;
	
	public function shop_item()
    {
        return $this->belongsTo('App\Models\Shopproducts', 'product_id');
    }
}
