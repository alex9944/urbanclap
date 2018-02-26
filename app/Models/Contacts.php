<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contacts extends Model
{
     protected $table = 'contacts';

     public function listing(){
     	return $this->belongsTo('App\Models\Listing','listing_id');
     }
}
