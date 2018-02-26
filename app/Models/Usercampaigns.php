<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Usercampaigns extends Model
{
     protected $table = 'user_campaigns';

     public function state(){
     	return $this->belongsTo('App\Models\States','state_id');
     }
     public function city(){
     	return $this->belongsTo('App\Models\Cities','city_id');
     }
      public function position(){
     	return $this->belongsTo('App\Models\AdsPosition','position_id');
     }
     public function category(){
          return $this->belongsTo('App\Models\Category','cat_id');
     }
      public function subcategory(){
          return $this->belongsTo('App\Models\Category','s_cat_id');
     }
     public function campaignprice(){
          return $this->hasOne('App\Models\AdsOrder','u_c_id');
     }
}
