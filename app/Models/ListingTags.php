<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ListingTags extends Model
{
    protected $table = 'listing_tags';
	
	protected $fillable = ['listing_id', 'category_tag_id'];
	
	public $timestamps = false;
	 
	public function category_tag()
    {
        return $this->belongsTo('App\Models\CategoryTags', 'category_tag_id');
    } 
	public function listing()
    {
        return $this->belongsTo('App\Models\Listing', 'listing_id');
    }
}
