<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cities extends Model
{
    protected $table = 'cities';
	 
	public function state()
    {
        return $this->belongsTo('App\Models\States', 'state_id');
    }
}
