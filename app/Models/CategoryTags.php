<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryTags extends Model
{
    protected $table = 'category_tags';
	
	protected $fillable = ['name', 'slug'];
	
	public $timestamps = false;
	 
	public function category()
    {
        return $this->belongsTo('App\Models\Category', 'category_id');
    }
	
	public function listing_tags()
    {
        return $this->hasMany('App\Models\ListingTags', 'category_tag_id');
    }
}
