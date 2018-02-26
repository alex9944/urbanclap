<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table = 'menu';
	 
	public function page()
    {
        return $this->hasOne('App\Models\Page', 'menu_id');
    }
}
