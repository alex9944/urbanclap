<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryType extends Model
{
    protected $table = 'category_type';
	
	public $timestamps = false;
	 
	public function merchant_service()
    {
        return $this->hasMany('App\Models\MerchantServices', 'category_type_id');
    }
}
