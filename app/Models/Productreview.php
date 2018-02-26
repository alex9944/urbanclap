<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Productreview extends Model
{
	protected $table = 'product_review';
	protected $primaryKey='id';

	public function products()
	{
		return $this->belongsTo('App\Models\Listing', 'product_id');
	}
	public function user()
	{
		return $this->belongsTo('App\Models\User', 'user_id');
	}
	
	public function scopeOrdered($query)
	{
		return $query->orderBy('created_at', 'Desc')->get();
	}
}
