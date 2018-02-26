<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ListingViews extends Model
{
    protected $table = 'listing_views';
	
	protected $fillable = ['listing_id', 'ip', 'merchant_id', 'device', 'browser', 'user_type', 'platform', 'isDesktop', 'isPhone', 'isRobot', 'robot', 'cookie_id'];
	
	//public $timestamps = false;
	 
	public function listing()
    {
        return $this->belongsTo('App\Models\Listing', 'listing_id');
    }
}
