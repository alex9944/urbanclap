<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ListingImages extends Model
{
    protected $table = 'listing_images';
	
	protected $fillable = ['image_name'];
	
	public $timestamps = false;
	 
	public function listing()
    {
        return $this->belongsTo('App\Models\Listing', 'listing_id');
    }
}
