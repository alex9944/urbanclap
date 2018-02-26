<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdsPricing extends Model
{
     protected $table = 'ads_pricing';
     public function position(){
     	return $this->belongsTo('App\Models\AdsPosition','position_id');
     }
}
