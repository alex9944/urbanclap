<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class LocalVendor extends Model
{
	protected $table = 'local_vendor';
	
	public function user()
	{
		return $this->belongsTo('App\Models\User', 'user_id');
	}
	
}
	
?>