<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GalleryImages extends Model
{
    protected $table = 'gallery_images';
	
	protected $fillable = ['image_name'];
	
	public $timestamps = false;
	 
	public function listing()
    {
        return $this->belongsTo('App\Models\Listing', 'listing_id');
    }
}
