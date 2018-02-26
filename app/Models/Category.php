<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\MultiLanguage;

class Category extends Model
{
     protected $table = 'category';
     protected $primaryKey='c_id';
	
	protected static function boot()
	{
		parent::boot();

		static::deleting(function($telco) {
			$relationMethods = ['subcategories', 'category_listings'];//, 'subcategory_listings'

			foreach ($relationMethods as $relationMethod) {
				if ($telco->$relationMethod()->count() > 0) {
					return false;
				}
			}
		});
	}
	 
	public function category_slug()
    {
        return $this->belongsTo('App\Models\CategorySlug', 'c_slug_id');
    } 
	
	public function subcategories()
    {
        return $this->hasMany('App\Models\Category', 'parent_id', 'c_id');
    }
	 
	public function category_tags()
    {
        return $this->hasMany('App\Models\CategoryTags', 'category_id', 'c_id');
    }
	 
	public function category_listings()
    {
        return $this->hasMany('App\Models\Listing', 'm_c_id', 'c_id');
    }
	 
	public function subcategory_listings()
    {
        return $this->hasMany('App\Models\Listing', 's_c_id', 'c_id');
    }
	 
	public function getCategories()
	{

		$current_lang_id = MultiLanguage::getCurrentLangId();		
		$def_lang_id = MultiLanguage::getDefaultLangId();

		// get categories for selected lang
		$categories = self::where(
			[
				['parent_id', '=', '0'],
				['c_l_id', '=', $current_lang_id],
			]
		)->get();

		// get categories for default lang
		if($current_lang_id != $def_lang_id and empty($categories))
		{
			$categories = self::where(
				[
					['parent_id', '=', '0'],
					['c_l_id', '=', $def_lang_id],
				]
			)->get();
		}

		return $categories;
	}
	 
	public function getSubCategories($parent_id)
	{		

		$current_lang_id = MultiLanguage::getCurrentLangId();		
		$def_lang_id = MultiLanguage::getDefaultLangId();

		// get categories for selected lang
		$categories = self::where(
			[
				['parent_id', '=', $parent_id],
				['c_l_id', '=', $current_lang_id],
			]
		)->get();

		// get categories for default lang
		if($current_lang_id != $def_lang_id and empty($categories))
		{
			$categories = self::where(
				[
					['parent_id', '=', $parent_id],
					['c_l_id', '=', $def_lang_id],
				]
			)->get();
		}

		return $categories;
	}
}
