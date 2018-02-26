<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MerchantServices extends Model
{
    protected $table = 'merchant_services';
	
	protected $fillable = ['listing_id', 'ip', 'merchant_id', 'device', 'browser', 'user_type', 'platform', 'isDesktop', 'isPhone', 'isRobot', 'robot', 'cookie_id'];
	
	public $timestamps = false;
	 
	public function category_type()
    {
        return $this->belongsTo('App\Models\CategoryType');
    }
}
