<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
     protected $table = 'services';
	 
	 //public $timestamps = false;

	public function questions()
	{
		return $this->hasMany('App\Models\Question', 'service_id');
	}
}
