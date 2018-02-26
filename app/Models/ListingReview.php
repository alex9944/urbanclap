<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ListingReview extends Model
{
	protected $table = 'listing_review';
	protected $primaryKey='r_id';

	public function listing()
	{
		return $this->belongsTo('App\Models\Listing', 'listing_id');
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