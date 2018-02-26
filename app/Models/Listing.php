<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

use App\Models\CategoryTags;

class Listing extends Model
{
	protected $table = 'listing';
	
	protected static function boot()
	{
		parent::boot();

		static::deleting(function($telco) {
			$relationMethods = ['tablebookingsettings', 'appointmentbookingsettings', 'menu_items', 'reviews'];

			foreach ($relationMethods as $relationMethod) {
				if ($telco->$relationMethod()->count() > 0) {
					return false;
				}
			}
		});
	}
	
	public function subcategory()
	{
		return $this->belongsTo('App\Models\Category', 's_c_id');
	}	
	
	public function getListings(array $loc,$cat,$parent_id,$radius, $filter)
	{
		$listing=array();

		//$loc=($loc==0)?0:$loc;
		$cat=($cat==0)?0:$cat;
		$status='Enable';
		$criteria=[];
		$fcriteria=[];
		$criteria=[
		($cat!=0)?['s_c_id', '=', $cat]:[],
		['status', '=', 'Enable']
		];
		foreach ($criteria as $key => $value) {
			if(count($value)>0)
			{
				$fcriteria[]=$value;
			}
		}
		if(empty($loc)){
			$location=array();
			
			try {
				if(isset($_COOKIE['key'])){
					$lt= $_COOKIE['key'];
				}
				else{
					$ip=\Request::ip();
					$url = json_decode(file_get_contents("https://api.ipinfodb.com/v3/ip-city/?key=0d8b148d8024c8495984fa6ba17602ae95492ec5f95fd9b65eebd2551d4b4679&ip=".$ip."&format=json&radius=".$radius));
					$lt=$url->latitude.','.$url->longitude;
				}
				
				$locations=file_get_contents('https://maps.googleapis.com/maps/api/place/nearbysearch/json?location='.$lt.'&radius='.$radius.'&type=&key=AIzaSyBzBUddAxyaIr8QLgKjPo4qi_ZLwq_h06I');
				$location=json_decode($locations);
				$loc=$location->results;	
			} catch(\Exception $e) {
			}

		}
		
		/*
		$location=[];
		foreach ($loc as $key => $value) {
			$lat=$value->geometry->location->lat;
			$long=$value->geometry->location->lng;
			$loct=$lat.','.$long;
			array_push($location, $loct);
		}
		*/
		
		$lat_long_qry = '1';
		$sep = ' AND ';
		foreach ($loc as $key => $value) {
			$northeast_lat = round($value->geometry->viewport->northeast->lat, 7);
			$northeast_lng = round($value->geometry->viewport->northeast->lng, 7);
			
			$southwest_lat = round($value->geometry->viewport->southwest->lat, 7);
			$southwest_lng = round($value->geometry->viewport->southwest->lng, 7);
			
			$lat_long_qry .= $sep . '(lattitude BETWEEN ' . $southwest_lat.' AND '.$northeast_lat . ' AND '.
							' longitude BETWEEN ' . $southwest_lng.' AND '.$northeast_lng . ')';
			
			$sep = ' OR ';
		}
		//$lat_long_qry = 1;
		
		$sql_ext = '';
		if($cat != 0)
		{
			$sql_ext = ' AND s_c_id = '.$cat;
		}
		if($parent_id != 0)
		{
			$sql_ext .= ' AND m_c_id = '.$parent_id;
		}
		
		// qry
		/*$sql = 'SELECT * FROM listing '.
				' WHERE m_c_id = '. $parent_id.
				' AND status = \'Enable\''.
				' AND (' . $lat_long_qry . ')' . $sql_ext;*/
		
		$sql_only = 'status = \'Enable\''.
				' AND (' . $lat_long_qry . ')' . $sql_ext;
		
		/*$listing= self::where( 
			$fcriteria
			)->where('m_c_id',$parent_id)->whereIn('lat_long',$location)->get();*/
		$db = self::whereRaw($sql_only);
		if( $filter['tag'] )
		{
			$category_tag = CategoryTags::where('slug', $filter['tag'])->first();
			$db->join('listing_tags', function ($join) use($category_tag) {
				$join->on('listing.id', '=', 'listing_tags.listing_id')
					 ->where('listing_tags.category_tag_id', '=', $category_tag->id);
			});
		}		
		$listing = $db->get();		
		//DB::enableQueryLog();print_r($listing);exit;
		
		return $listing;
	}
	
	public function listing_merchant()
    {
        return $this->belongsTo('App\Models\Merchants', 'user_id');
    }

	public function listing_images()
	{
		return $this->hasMany('App\Models\ListingImages', 'listing_id');
	}
	
	public function listing_city()
    {
        return $this->belongsTo('App\Models\Cities', 'city');
    }
	
	public function listing_state()
    {
        return $this->belongsTo('App\Models\States', 'state');
    }
	
	public function listing_country()
    {
        return $this->belongsTo('App\Models\Country', 'c_id');
    }
	
	public function tablebookingsettings()
    {
        return $this->hasOne('App\Models\TableBookingSettings', 'listing_id');
    }
	
	public function appointmentbookingsettings()
    {
        return $this->hasOne('App\Models\AppointmentBookingSettings', 'listing_id');
    }
	
	public function menu_items()
    {
        return $this->hasMany('App\Models\FoodMenuItem', 'listing_id');
    }
	
    public function category()
	{
    	return $this->belongsTo('App\Models\Category','m_c_id');
    } 
	
	public function listing_tags()
    {
        return $this->hasMany('App\Models\ListingTags', 'listing_id');
    }
	
	public function reviews()
    {
        return $this->hasMany('App\Models\ListingReview', 'listing_id');
    }
}
