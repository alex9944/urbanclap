<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class States extends Model
{
    protected $table = 'states';
	
	public function cities()
    {
        return $this->hasMany('App\Models\Cities', 'state_id');
    }
	 
	public function country()
    {
        return $this->belongsTo('App\Models\Country', 'country_id');
    }
}
