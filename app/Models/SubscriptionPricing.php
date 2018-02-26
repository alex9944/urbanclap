<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionPricing extends Model
{
    protected $table = 'subscription_pricing';
	
	public function plan()
	{
		return $this->belongsTo('App\Models\SubscriptionPlan', 'p_id');
	}
	
	public function duration()
	{
		return $this->belongsTo('App\Models\SubscriptionDuration', 'd_id');
	}
}
