<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
     protected $table = 'currency';
	 
	public static function getDefault()
	{
		return self::where('is_default', 1)->first();
	}
	
	public function country()
    {
        return $this->belongsTo('App\Models\Country', 'c_id');
    }
}
