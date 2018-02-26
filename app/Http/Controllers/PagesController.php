<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use DB;
use App\Models\Category;
use App\Models\Listing;
use App\Models\CategoryType;
use App\Models\ListingViews;
use App\Models\TableBookingSettings;
use App\Models\TableBookingOrder;
use App\Models\ListingReview;
use App\Models\MerchantServices;
use App\Models\AppointmentBookingSettings;
use App\Models\AppointmentBookingOrder;
use App\Models\OrderOnlineSettings;
use App\Models\Notifications;
use App\Models\Testimonials;
use App\Models\ListingTags;
use App\Models\CategoryTags;
use App\Models\Localvendor;
use App\Models\Contacts;
use App\Models\SubscriptionPricing;
use App\Models\SubscriptionFeatures;
use App\Models\PaymentGatewaySite;
use App\Models\PaymentGatewaySiteSettings;
use App\Models\SubscriptionTerm;
use App\Models\VendorSubscription;
use App\Models\EbsLibrary;
use App\Models\RazorLibrary;
use App\Models\EbsTransactionSite;
use App\Models\User;
use App\Models\Role;
use App\Models\Country;
use App\Models\States;
use App\Models\Cities;
use App\Models\Orders;
use App\Models\Shopproducts;
use App\Models\GalleryImages;
use App\Events\TableBooked;
use App\Events\AppointmentBooked;
use Razorpay\Api\Api;
use JeroenDesloovere\VCard\VCard;
use Jenssegers\Agent\Agent;
use Cookie;
use Validator;
use Auth;
use Image;

class PagesController extends Controller
{

	public function getHome()
	{
		$page_name = 'home';
		
		$catObj = new Category();
		$categories = $catObj->getCategories();
		
		$testimonials = Testimonials::orderBy('created_at', 'DESC')->limit(5)->get();

		return view('pages.home', compact('categories', 'testimonials'));

	}
	
	
	public function getLocation(Request $request){
		$latitude=$request->latitude;
		$longitude=$request->longitude;
		$geolocation = '';
		try {
			$url = 'https://maps.google.com/maps/api/geocode/json?latlng='.$latitude.','.$longitude.'&sensor=false&key=AIzaSyBU7YTBgP-ZsBlVcYGYAfuz2os6yfTWLIg';
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			//curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			
			if(curl_exec($ch) === false)
			{
				echo 'Curl error: ' . curl_error($ch);
			}
			else
			{
				$response = curl_exec($ch);
				if(is_string($response))
					$geolocation = $response;
			}
			
			curl_close($ch);
			
			//$geolocation=file_get_contents('https://maps.google.com/maps/api/geocode/json?latlng='.$latitude.','.$longitude.'&sensor=false&key=AIzaSyBU7YTBgP-ZsBlVcYGYAfuz2os6yfTWLIg');
		} catch(\Exception $e) {
			print_r($e->getMessage());
		}
		return $geolocation;

	}
	
	public function getCategory($slug = null)
	{
		if($slug)
		{
			// split slug to get parent id
			$split = explode('-', $slug);
			$parent_id = end($split);

			if($parent_id)
			{
				$catObj = new Category();
				$subcategories = $catObj->getSubCategories($parent_id);
				$category = $catObj->where('c_id', '=', $parent_id)->first();

				return view('pages.subcategory', compact('subcategories', 'category', 'page_name'));
			}
		}
		
		return redirect('/');
	}
	
	public function getListing($slug = null)
	{
		
		$page_name = 'listing';
		$tag = \Input::get('tag');
		$keyword = \Input::get('keyword');
		$radius = \Input::get('radius');
		
		$filter = ['tag' => $tag, 'keyword' => $keyword];
		
		if($radius == ''){
			$radius=100;
		}
		
		$parent_id = 0;
		$jq_slug = '';
		if($slug)
		{
			$jq_slug = '/'.$slug;
			//$c_slug_id =DB::table('category_slug')->where('slug',$slug)->value('id');
			//$parent_id =DB::table('category')->where('c_slug_id',$c_slug_id)->value('c_id');
			$parent_id =Category::where('slug',$slug)->value('c_id');
		}
		
		$subcategories = [];
		if($parent_id)
		{
			$catObj = new Category();
			$subcategories = $catObj->getSubCategories($parent_id);
		}

		$category_types=DB::table('category_type')->get();
		
		$loc=array();
		$cat=0;		
		$loct = \Config::get('settings.defaultcity');		
		if(!empty($_GET['locid'])>0)
		{
			$loct=$_GET['locid'];	
		}
		try {
			$locations=file_get_contents('https://maps.googleapis.com/maps/api/place/nearbysearch/json?location='.$loct.'&radius='.$radius.'&type='.$slug.'&key=AIzaSyBzBUddAxyaIr8QLgKjPo4qi_ZLwq_h06I');
			$location = json_decode($locations);
			$loc = $location->results;
		} catch(\Exception $e) {
			
		}
		
		if(!empty($_GET['catid'])>0)
		{
			$cat = $_GET['catid'];
		}				
		$listingObj = new Listing();
		$listings = $listingObj->getListings($loc,$cat,$parent_id,$radius, $filter);
		foreach($listings as $listing){
			$average=DB::table('listing_review')->where('listing_id',$listing->id)->where('approved',1)->avg('rating');
			$no_reviews=DB::table('listing_review')->where('listing_id',$listing->id)->where('approved',1)->count();
			$listing['avg']=$average;
			$listing['reviews']=$no_reviews;
		}
			
		// re-arrange listing with services
		$category_types=DB::table('category_type')->get();		
		$services_by_id = $this->services_by_id;
		foreach ($listings as $key => $list) 
		{
			$category_type=$list->category->category_type;
			if($category_type)
			{				
				$category_services=json_decode($category_type);
				$merchant_services = MerchantServices::where(['merchant_id' => $list->user_id, 'is_enable' => 1])->get();
				$enabled_services = [];
				foreach($merchant_services as $merchant_service) {
				  $enabled_services[$merchant_service->category_type_id] = $merchant_service->category_type_id;
				}

				$services=array();					
				
				foreach($category_types as $cat)
				{
					if(in_array($cat->id, $category_services) and isset($enabled_services[$cat->id])){
						$is_enable = false;
						if(isset($services_by_id[$cat->id]['slug']) and $services_by_id[$cat->id]['slug'] == 'table' and $list->tablebookingsettings) {

							$tablebookingsettings = $list->tablebookingsettings()->where('status', '=', 1)->first();
							if($tablebookingsettings)
								$is_enable = true;
						} else if(isset($services_by_id[$cat->id]['slug']) and $services_by_id[$cat->id]['slug'] == 'appointment' and $list->appointmentbookingsettings) {

							$appointmentbookingsettings = $list->appointmentbookingsettings()->where('status', '=', 1)->first();
							if($appointmentbookingsettings)
								$is_enable = true;
						} else if(isset($services_by_id[$cat->id]['slug']) and $services_by_id[$cat->id]['slug'] == 'food') {

							$food_item_exists = DB::table('food_menu')
												->join('food_menu_item', function ($join) use($list) {
													$join->on('food_menu.id', '=', 'food_menu_item.food_menu_id')
													->where('food_menu_item.status', '=', 1)
													->where('food_menu_item.listing_id', '=', $list->id)
													->where('food_menu_item.stock', '>', 0);
												})
												->where('food_menu.status', '=', 1)	
												->exists();
							if($food_item_exists)
								$is_enable = true;
						} else if(isset($services_by_id[$cat->id]['slug']) and $services_by_id[$cat->id]['slug'] == 'shop') {

							$product_exists = DB::table('shop_products')
										->join('shop_pro_cat', 'shop_pro_cat.id', '=', 'shop_products.cat_id')
										->join('listing',function($join){
											$join->on('listing.id','=','shop_products.listing_id');
										})
										->where('shop_pro_cat.status', '=', 0)
										->where('listing.id','=',$list->id)
										->exists();
							if($product_exists)
								$is_enable = true;
						}
						
						if($is_enable)
						{
							$service=array();
							$service['service_id']=$cat->id;
							$service['service_slug']=$cat->slug;
							$service['service_name']=$cat->name;
							array_push($services, $service);
						}
					}
				}
				$list->services=$services;
			}
		}
		
		return view('pages.listing',compact('listings', 'subcategories','slug', 'jq_slug','category_types', 'page_name', 'tag'));
		
	}
	public function getlocationlist()
	{
		$locations = DB::table('cities')
		->where('state_id', 35)	 
		->get();   
		return  json_encode($locations);
	}

	public function getsubcatlist()
	{
		$parent_id = 1;
		$catObj = new Category();
		$subcategories = $catObj->getSubCategories($parent_id);  
		return  json_encode($subcategories);
	}
	
	public function listingDetail($slug = null,Request $request)
	{
		
		$page_name = 'listing_detail';
		
		$listing = Listing::where('slug', $slug)->first();

		// subscriptiion detail
		$subscription_pricing = $listing->listing_merchant->last_subscription->subscription_pricing;
		$features = explode(',', $subscription_pricing->f_id);
		$subscription_features = SubscriptionFeatures::whereIn('id', array_values($features))->get();
		$enable_listing_model = array(
			'information'			=> true,
			'category_dependent'	=> false,
			'review'				=> false,
			'call'					=> false,
			'map'					=> false,
			'gallery'				=> false,
			'share'					=> true,
			'save'					=> false
		);
		foreach($subscription_features as $subscription_feature) {
			if( isset($enable_listing_model[$subscription_feature->functionality_name]) )
				$enable_listing_model[$subscription_feature->functionality_name] = true;
		}//print_r($subscription_features);exit;
		
		if($listing)
		{
			// view count
			$ip = \Request::ip();
			$cookie_id = Cookie::get('listing_view');		
			$minutes = 30 * 24 * 60;
			if($cookie_id == '')
			{
				$cookie_id = rand(1,100) . '_' . $ip;				
				$listing_view_cookie = cookie('listing_view', $cookie_id, $minutes);
				Cookie::queue($listing_view_cookie);
			}
			$listing_view = ListingViews::where(['listing_id' => $listing->id, 'ip' => $ip])
			->whereDate('created_at', '=', date('y-m-d'))
			->first();
			$listing_user_type = ListingViews::where(['listing_id' => $listing->id, 'cookie_id' => $cookie_id])->first();
			if($listing_view)
			{
				
			}
			else
			{
				$agent = new Agent();
				
				$device = $agent->device();
				$browser = $agent->browser();
				$platform = $agent->platform();
				$isDesktop = $agent->isDesktop();
				$isPhone = $agent->isPhone();
				$isRobot = $agent->isRobot();
				$robot = $agent->robot();
				
				$user_type = 'new';
				if($listing_user_type)
					$user_type = 'exists';
				
				ListingViews::create(['listing_id' => $listing->id, 'ip' => $ip, 'merchant_id' => $listing->user_id, 
					'device' => $device, 'browser' => $browser, 'user_type' => $user_type,
					'platform' => $platform, 'isDesktop' => $isDesktop, 'isPhone' => $isPhone,
					'isRobot' => $isRobot, 'robot' => $robot, 'cookie_id' => $cookie_id
				]);

				$listing->listing_view_count = ($listing->listing_view_count)+1;
				$listing->save();
			}
			
			$data = array('header_address' => '', 'address' => '');
			
			// get address
			$data['address'] = str_replace(',', ',<br />', $listing->address1) . ',<br />';			
			if($listing->address2)
			{
				$data['header_address'] = $listing->address2 . ', ';
				$data['address'] .= $listing->address2 . ',<br />';
			}
			else
			{
				$address1 = explode(',', $listing->address1);
				$data['header_address'] = end($address1) . ', ';
			}
			$data['header_address'] .= isset($listing->listing_city->name) ? $listing->listing_city->name : '';
			$data['address'] .= (isset($listing->listing_city->name) ? $listing->listing_city->name : '') . ' - ' . $listing->pincode;
			
			// get lat and long
			$lat_long = $listing->lat_long;
			$data['lat'] = 50.0875726;
			$data['long'] = 14.4189987;
			if($lat_long)
			{
				$lat_long_arr = explode(',', $lat_long);
				$data['lat'] = trim(current($lat_long_arr));
				$data['long'] = trim(end($lat_long_arr));
			}
			if($listing->lattitude)
				$data['lat'] = $listing->lattitude;
			if($listing->longitude)
				$data['long'] = $listing->longitude;
			
			/** get services based on category**/
			/*	$services=DB::table('category')->where('c_id',$listing->m_c_id)->value('category_type');
			$category_type_id=json_decode($services);*/
			
			$tablebookingsettings = [];
			$appointmentbookingsettings = [];
			$orderonline_menus = [];
			$orderonline_menu_items = [];
			
			/*** table booking start ***/
			
			$table_booking_settings = new TableBookingSettings;
			$table_booking_id = $table_booking_settings->table_booking_id;
			// check vendor enable table booking settings
			$tablebookingsettings_enable = MerchantServices::where(['is_enable' => 1, 'category_type_id' => $table_booking_id, 'merchant_id' => $listing->user_id])->exists();

			if($tablebookingsettings_enable and $listing->tablebookingsettings)
			{

				$tablebookingsettings = $listing->tablebookingsettings()->where('status', '=', 1)->first();

				if($tablebookingsettings)
				{
					$data['days'] = $table_booking_settings->getUpcomingSixDays();

					$data['times'] = $table_booking_settings->getListingTimes($tablebookingsettings);

					$data['no_of_people'] = $tablebookingsettings->people_limit;	

					$data['open_time'] = $tablebookingsettings->start_time;
					
					$data['close_time'] = $tablebookingsettings->end_time;
				}				

			}
			
			$reviews=ListingReview::get()->where('merchant_id',$listing->user_id)->where('listing_id',$listing->id)->where('approved','1');
			$avgrating=ListingReview::avg('rating');
			/*** table booking end ***/
			
			/*** appointment booking start ***/			
			$appointment_booking_settings = new AppointmentBookingSettings;
			$appointment_booking_id = $appointment_booking_settings->appointment_booking_id;
			// check vendor enable table booking settings
			$appointmentbookingsettings_enable = MerchantServices::where(['is_enable' => 1, 'category_type_id' => $appointment_booking_id, 'merchant_id' => $listing->user_id])->exists();
			
			if($appointmentbookingsettings_enable and $listing->appointmentbookingsettings)
			{
				$appointmentbookingsettings = $listing->appointmentbookingsettings()->where('status', 1)->first();
				
				if($appointmentbookingsettings)
				{
					$data['days'] = $appointment_booking_settings->getUpcomingSixDays();
					
					$data['times'] = $appointment_booking_settings->getListingTimes($appointmentbookingsettings);

					$data['open_time'] = $appointmentbookingsettings->start_time;
					
					$data['close_time'] = $appointmentbookingsettings->end_time;
				}				
				
			}

			$allreviews=ListingReview::where('merchant_id',$listing->user_id)->where('listing_id',$listing->id)->where('approved','1')->ordered();
			$reviews = $this->get_reviews($listing->id, $request);
			$avgrating=DB::table('listing_review')->where('listing_id',$listing->id)->where('approved','1')->avg('rating');
			
			$total_reviews=0;

			if(sizeof($allreviews)>0){

				$excellentcount=DB::table('listing_review')->where('listing_id',$listing->id)->where('rating', 5)->where('approved','1')->count();
				$goodcount=DB::table('listing_review')->where('listing_id',$listing->id)->where('rating','<', 5)->where('rating','>=', 4)->where('approved','1')->count();
				$averagecount=DB::table('listing_review')->where('listing_id',$listing->id)->where('rating','<', 4)->where('rating','>=', '2.5')->where('approved','1')->count();
				$badcount=DB::table('listing_review')->where('listing_id',$listing->id)->where('rating','<', '2.5')->where('approved', 1)->count();
				$total_reviews=sizeof($allreviews);
				$excellent=$excellentcount/$total_reviews*100;
				$good=$goodcount/$total_reviews*100;
				$average=$averagecount/$total_reviews*100;
				$bad=$badcount/$total_reviews*100;

			}
			else{
				$excellent='0';
				$good='0';
				$average='0';
				$bad='0';
			}
			/*** appointment booking end ***/
			
			
			/*** online order start ***/
			$order_online_settings = new OrderOnlineSettings;
			$order_online_id = $order_online_settings->order_online_id;
			// check vendor enable table booking settings
			$orderonlinesettings_enable = MerchantServices::where(['is_enable' => 1, 'category_type_id' => $order_online_id, 'merchant_id' => $listing->user_id])->exists();
			
			if($orderonlinesettings_enable)
			{
				$orderonlinemenus = DB::table('food_menu')
				->join('food_menu_item', function ($join) use($listing) {
											//global $listing;
					$join->on('food_menu.id', '=', 'food_menu_item.food_menu_id')
					->where('food_menu_item.status', '=', 1)
					->where('food_menu_item.listing_id', '=', $listing->id)
					->where('food_menu_item.stock', '>', 0);
				})
				->select('food_menu.name AS menu_name', 'food_menu.id AS menu_id', 'food_menu_item.merchant_id', 'food_menu_item.item_type', 'food_menu_item.id As item_id', 'food_menu_item.name As item_name', 'food_menu_item.original_price',
				'food_menu_item.discounted_price', 'food_menu_item.stock')
				->where('food_menu.status', '=', 1)				
				->get();

				if($orderonlinemenus)
				{					
					foreach($orderonlinemenus as $menu)
					{
						$orderonline_menus[$menu->menu_id] = $menu->menu_name;
						
						if($menu->item_type == 'veg')
							$orderonline_menu_items[$menu->menu_id]['veg'][] = $menu;
						else
							$orderonline_menu_items[$menu->menu_id]['non-veg'][] = $menu;
					}
				}

				//print_r($orderonline_menus);exit;
				
			}			
			/*** online order end ***/

			/*online shopping start*/
			$Products=[];
			$minmax = '';
			$onlineshop_enable = MerchantServices::where(['is_enable' => 1, 'category_type_id' => 2, 'merchant_id' => $listing->user_id])->exists();
			$category_type=$listing->category->category_type;
			$type=(array)json_decode($category_type);
			if (in_array("2", $type) and $onlineshop_enable){
				/*$Products = DB::table('shop_products')->leftJoin('shop_pro_cat', 'shop_pro_cat.id', '=', 'shop_products.cat_id')->leftjoin('listing',function($join){
					$join->on('listing.id','=','shop_products.listing_id');
					$join->on('listing.user_id','=','shop_products.merchant_id');
					})->where('shop_pro_cat.status', '=', 0)
					->where('listing.id','=',$listing->id)
					->where('shop_products.stock', '>', 0)
					->selectRaw('shop_products.id as prod_id,shop_products.*,shop_pro_cat.*,listing.*')->get();*/
					
				$Products = Shopproducts::where('listing_id','=',$listing->id)
					->where('stock', '>', 0)
					->get();
					
				
				$minmax = DB::select("SELECT  MIN(pro_price) AS MIN_PRICE , MAX(pro_price) AS MAX_PRICE FROM shop_products ".
									" where listing_id = ".$listing->id.
									" AND shop_products.stock > 0"
									); 
				if( isset($minmax[0]) )
					$minmax = $minmax[0];
				
			}
			
			$galleryimages=GalleryImages::where('listing_id',$listing->id)->get();
		
			/*online shopping end*/
			if(auth::check()){
				if(auth::user()->hasRole('merchant')&&$listing->user_id==auth::user()->id){
					return view('pages.merchant_listing', compact('listing', 'data', 'tablebookingsettings', 'appointmentbookingsettings', 'orderonline_menus', 'orderonline_menu_items', 'page_name','reviews','avgrating','excellent','good','average','bad','Products', 'minmax', 'total_reviews', 'enable_listing_model'));
				}
				else{
					return view('pages.listing_detail', compact('listing', 'data', 'tablebookingsettings', 'appointmentbookingsettings', 'orderonline_menus', 'orderonline_menu_items', 'page_name','reviews','avgrating','excellent','good','average','bad','Products', 'minmax', 'total_reviews', 'enable_listing_model','galleryimages'));
				}
			}
			else{
				return view('pages.listing_detail', compact('listing', 'data', 'tablebookingsettings', 'appointmentbookingsettings', 'orderonline_menus', 'orderonline_menu_items', 'page_name','reviews','avgrating','excellent','good','average','bad','Products', 'minmax', 'total_reviews', 'enable_listing_model','galleryimages'));
			}
		}
		
		return view('pages.listing_detail.invalid');
	}
	
	public function get_reviews($listing_id, Request $request)
	{
		$reviews = ListingReview::with('user')->where('listing_id',$listing_id)->where('approved','1')->orderBy('created_at','DESC')->paginate(5);
		
		if($request->ajax()){
			return response()->json($reviews);
		}
		
		return $reviews;
	}
	
	public function bookTable(Request $request)
	{
		$table_booking_order = new TableBookingOrder;		
		
		$adding_rules_and_messages = $table_booking_order->get_adding_rules();
		$adding_rules = $adding_rules_and_messages['rules'];
		$adding_messages = $adding_rules_and_messages['messages'];
		
		$user_id = (auth()->check()) ? auth()->user()->id : null;
		if($user_id) {
			unset($adding_rules['user_id']);
			unset($adding_messages['user_id.required']);
		}
		
		$validator = Validator::make($request->all(), $adding_rules, $adding_messages);
		
		$return = array('status' => 0, 'msg' => 'Invalid Request');
		if ($validator->fails()) 
		{
			$return['msg'] = $validator->errors();
		}
		else
		{
			$listing = Listing::find($request->listing_id);
			$tablebookingsettings = $listing->tablebookingsettings;
			
			$booking_validation = $table_booking_order->validateBookingDateTime($request, $tablebookingsettings);
			
			if( $booking_validation['status'] == 0 )
			{
				$return['msg'] = array($booking_validation['msg']);
			}
			else
			{
				// validation ok				
				
				$table_booking_order->listing_id 	= $request->listing_id;
				$table_booking_order->user_id 		= $user_id;
				$table_booking_order->merchant_id 	= $request->merchant_id;
				$table_booking_order->order_date 	= date('Y-m-d H:i:s');
				$table_booking_order->booked_date 	= $request->booked_date;
				$table_booking_order->booked_time 	= $request->booked_time;
				$table_booking_order->total_peoples = $request->total_peoples;
				$table_booking_order->name 			= $request->name;
				$table_booking_order->phone_number 	= $request->phone_number;
				$table_booking_order->email 		= $request->email;
				$table_booking_order->additional_request = $request->additional_request;
				$table_booking_order->status 		= 'Pending';
				$table_booking_order->save();
				
				$Orders = new Orders;
				$orders_created = $Orders->create_main_order($table_booking_order->id, $user_id, 'table_booking');
				
				event(new TableBooked($table_booking_order));
				
				$return['msg'] = 'Thank you. Your booking will be confirmed shortly.';
				$return['status'] = 1;

				/*$notification=new Notifications;
				$notification->name=$request->name;
				$notification->type='Table';
				$notification->message=$request->name.' has booked a table at '.$table_booking_order->listing->title;
				$notification->listing_id=$request->listing_id;
				$notification->merchant_id=$request->merchant_id;
				$notification->save();*/
			}

		}
		
		return response()->json($return);

	}
	
	public function bookAppointment(Request $request)
	{
		$appointment_booking_order = new AppointmentBookingOrder;		
		
		$adding_rules_and_messages = $appointment_booking_order->get_adding_rules();
		$adding_rules = $adding_rules_and_messages['rules'];
		$adding_messages = $adding_rules_and_messages['messages'];
		
		$user_id = (auth()->check()) ? auth()->user()->id : null;
		if($user_id) {
			unset($adding_rules['user_id']);
			unset($adding_messages['user_id.required']);
		}
		
		$validator = Validator::make($request->all(), $adding_rules, $adding_messages);
		
		$return = array('status' => 0, 'msg' => 'Invalid Request');
		if ($validator->fails()) 
		{
			$return['msg'] = $validator->errors();
		}
		else
		{
			$listing = Listing::find($request->listing_id);
			$appointmentbookingsettings = $listing->appointmentbookingsettings;
			
			$booking_validation = $appointment_booking_order->validateBookingDateTime($request, $appointmentbookingsettings);
			
			if( $booking_validation['status'] == 0 )
			{
				$return['msg'] = array($booking_validation['msg']);
			}
			else
			{
				// validation ok
				
				$appointment_booking_order->listing_id 			= $request->listing_id;
				$appointment_booking_order->user_id 			= $user_id;
				$appointment_booking_order->merchant_id 		= $request->merchant_id;
				$appointment_booking_order->order_date 			= date('Y-m-d H:i:s');
				$appointment_booking_order->booked_date 		= $request->booked_date;
				$appointment_booking_order->booked_time 		= $request->booked_time;
				$appointment_booking_order->total_peoples 		= $request->total_peoples;
				$appointment_booking_order->name 				= $request->name;
				$appointment_booking_order->phone_number 		= $request->phone_number;
				$appointment_booking_order->email 				= $request->email;
				$appointment_booking_order->additional_request 	= $request->additional_request;
				$appointment_booking_order->status 				= 'Pending';
				$appointment_booking_order->save();
				
				$Orders = new Orders;
				$orders_created = $Orders->create_main_order($appointment_booking_order->id, $user_id, 'appointment_booking');
				
				event(new AppointmentBooked($appointment_booking_order));
				
				$return['msg'] = 'Thank you. Your booking will be confirmed shortly.';
				$return['status'] = 1;

				/*$notification=new Notifications;
				$notification->name=$request->name;
				$notification->type='Appointment';
				$notification->message=$request->name.' has booked an appointment at '.$appointment_booking_order->listing->title;
				$notification->listing_id=$request->listing_id;
				$notification->merchant_id=$request->merchant_id;
				$notification->save();*/
			}
		}
		
		return response()->json($return);
	}
	
	public function keywordSuggestion(Request $request)
	{
		if($request->keyword) {
			//$listing_tags = ListingTags::groupby('category_tag_id')->get();
			$tags = CategoryTags::whereHas('listing_tags')->where('name', 'like', $request->keyword.'%')->get();//
			//print_r($tags);
			if($tags->count() != 0) {
				?>
				<ul class="dropdown-menu" role="listbox" id="keyword-list">
					<?php
					foreach($tags as $tag) {
						?>
						<li>
							<a class="dropdown-item" href="javascript:;" data-slug="<?php echo $tag->slug; ?>" role="option"><?php echo $tag->name; ?></a>						
						</li>
						<?php } ?>
					</ul>
					<?php 
				} 
			}
			exit;
		}

		public function saveVcard($listing_id)
		{
			$listing = Listing::find($listing_id);

			if($listing)
			{
				$merchant_firstname = $listing->listing_merchant->first_name;
				$merchant_lastname = $listing->listing_merchant->last_name;
				$merchant_phone = $listing->listing_merchant->mobile_no;
				$merchant_email = $listing->listing_merchant->email;

				$listing_title = $listing->title;
				$address1 = $listing->address1;			
				$address2 = $listing->address2;
				$city = $listing->listing_city->name;
				$state = $listing->listing_state->name;
				$country = $listing->listing_country->name;
				$pincode = $listing->pincode;

			// define vcard
				$vcard = new VCard();

				$vcard->addName($merchant_lastname, $merchant_firstname, '', '', '');
				$vcard->addCompany($listing_title);
				$vcard->addEmail($merchant_email);
				$vcard->addPhoneNumber($merchant_phone, 'PREF;WORK');
				$vcard->addAddress(null, $address2, $address1, $city, $state, $pincode, $country);
				$vcard->addURL($listing->website);

			// define variables
			//$lastname = 'Desloovere';
			//$firstname = 'Jeroen';
			//$additional = '';
			//$prefix = '';
			//$suffix = '';

			// add personal data
			//$vcard->addName($lastname, $firstname, $additional, $prefix, $suffix);

			// add work data
			//$vcard->addCompany('Siesqo');
			//$vcard->addJobtitle('Web Developer');
			//$vcard->addRole('Data Protection Officer');
			//$vcard->addEmail('info@jeroendesloovere.be');
			//$vcard->addPhoneNumber(1234121212, 'PREF;WORK');
			//$vcard->addPhoneNumber(123456789, 'WORK');			
			//$vcard->addAddress(null, null, 'street', 'worktown', null, 'workpostcode', 'Belgium');
			//$vcard->addURL('abc.com');

			//$vcard->addPhoto(__DIR__ . '/landscape.jpeg');

			// return vcard as a string
			//return $vcard->getOutput();

			// return vcard as a download
				return $vcard->download();

			// save vcard on disk
			//$vcard->setSavePath('/path/to/directory');
			//$vcard->save();
			}

			return view('pages.listing_detail.invalid');
		}

		public function getContact()
		{

			return view('pages.contact_us');
		}
		
		public function vendorRegister()
		{
			$catObj = new Category();
			$categories = $catObj->getCategories();
			
			return view('pages.vendor_register.signup_form', compact('categories'));
		}

		public function vendorRegister_OLD()
		{
			$catObj = new Category();
			$categories = $catObj->getCategories();

			$country = Country::where('default', 1)->first();
			$states = States::where('country_id','=', $country->id)->get();
			$subcategory = array();
			$cities = array();
			$tags = array();

			if(old('category_id') != '')
			{
				$category_id = old('category_id');		
				$subcategory = Category::where('parent_id','=', $category_id)->get();
			}

			if( old('scategory') != '')
			{
				$scategory = Category::where('c_id', old('scategory'))->first();
				$tags = $scategory->category_tags;
			}
			if( old('states') != '')
			{
				$cities = Cities::where('state_id','=', old('states'))->get();
			}

		// times
			$times = array();
			for($i = 1; $i <= 12; $i++)
			{
				$times[$i] = $i;
			}

			$subscription_pricings = SubscriptionPricing::orderBy('subscription_pricing.order_by', 'ASC')->get();
			$subscription_features = SubscriptionFeatures::all();
			$category_types = CategoryType::all();
			$payment_gateway = PaymentGatewaySite::where(['status' => 1, 'is_default' => 1])->first();
			$subscription_terms = SubscriptionTerm::all();

			$page_name = 'vendor_signup';

			return view('pages.vendor_register.select_category', compact('country', 'categories', 'subcategory', 'states', 'cities', 'tags', 'times', 'subscription_pricings', 'subscription_features', 'category_types', 'payment_gateway', 'subscription_terms', 'page_name'));
		}

		public function vendorSubscription(Request $request)
		{
			$rules = [			
				'category_id'			=> 'required',
			//'scategory'		=> 'required',
				'title'			=> 'required',
			//'website'		=> 'required',
			//'holidays'		=> 'required',
			//'country'		=> 'required',
				'states'		=> 'required',
				'cities'		=> 'required',
				'address1'		=> 'required',		
				'postcode'		=> 'required',
				'phoneno'		=> 'required',
			//'image_field'	=> 'required',	
				'description'	=> 'required',	
				'lat_long'		=> 'required',
				'photo'			=> 'required|image|mimes:jpeg,png,jpg,gif,svg|max:1024',

				'first_name'            => 'required',
				'last_name'             => 'required',
				'email'                 => 'required|email|unique:users',
				'mobile'                =>'required|numeric',			
				'address'            	=> 'required',
				'city_name'             => 'required',
				'postal_code'             => 'required',
				'password'              => 'required|min:6|max:20',
				'password_confirmation' => 'required|same:password',
				'subscription_pricing_id'	=> 'required',
				'payment_gateway_id'	=> 'required',
				'subscription_term_id'	=> 'required',
				'paid_amount'			=> 'required',
			];		
			$messages = [
				'category_id.required'		=> 'Category is required',
			//'scategory.required'	=> 'Subcategory is required',
				'title.required'		=> 'Title is required',
			//'website.required'		=> 'Website required',
			//'holidays.required'		=> 'Select Holidays',
			//'country.required'		=> 'Country is required',
				'states.required'		=> 'States is required',
				'cities.required'		=> 'Cities is required',
				'address1.required'		=> 'Address 1 is required',		
				'postcode.required'		=> 'Postcode is required',
				'phoneno.required'		=> 'Phoneno is required',
			//'image_field.required'	=> 'Image is required',
				'description.required'	=> 'Description is required',
				'lat_long.required'		=> 'Lattitude and Longitude is required',
				'photo.required'			=> 'required|image|mimes:jpeg,png,jpg,gif,svg|max:1024',
				'photo.image'           	=> 'Image should be a jpeg,png,gif,svg',

			//'category_id.required'	=> 'Category is required',
				'first_name.required'   => 'First Name is required',
				'last_name.required'    => 'Last Name is required',
				'email.required'        => 'Email is required',
				'email.email'           => 'Email is invalid',
				'mobile.required'       => 'Mobile No is required',
				'mobile.numeric'        => 'Enter Valid Mobile number',			
				'address.required'   	=> 'Address is required',
				'city_name.required'    => 'City Name is required',
				'postal_code.required'   => 'Postal code is required',
				'password.required'     => 'Password is required',
				'password.min'          => 'Password needs to have at least 6 characters',
				'password.max'          => 'Password maximum length is 20 characters',
				'subscription_pricing_id.required'	=> 'Select subscription plan',
				'payment_gateway_id.required'	=> 'Select payment gateway',
				'subscription_term_id.required'	=> 'Select subscription term',
				'paid_amount.required'     => 'Amount is required',
			];

			$this->validate($request, $rules, $messages);

		// validation ok

			$country = $this->default_country;//Country::where('default', 1)->first();

		// user registration
			$user =  User::create([
				'first_name' 	=> $request->first_name,
				'last_name' 	=> $request->last_name,
				'email' 		=> $request->email,
				'mobile_no' 	=> $request->mobile,
				'address' 		=> $request->address,
				'city_name' 	=> $request->city_name,
				'postal_code' 	=> $request->postal_code,
				'category_id'	=> $request->category_id,
				'password' 		=> bcrypt($request->password),
				'token' 		=> str_random(64),
				'activated' 	=> !config('settings.activation')
			]);
			$role = Role::whereName('merchant')->first();
			$user->assignRole($role);
		//$this->initiateEmailActivation($user);

		// listing creation
			$tags = array();
			if($request->tags)
				$tags = $request->tags;

			$photo = $request->file('photo');
			$imagename = time() . '_' . rand(10,100) . '_' . strtolower($photo->getClientOriginalName());
			$destinationPath = public_path('/uploads/listing/thumbnail');
			$thumb_img = Image::make($photo->getRealPath())->fit(300);
			$thumb_img->save($destinationPath.'/'.$imagename,80);                    
			$destinationPath = public_path('/uploads/listing/original');
			$photo->move($destinationPath, $imagename);
			
			$listing = new Listing;				
			$listing->user_id = $user->id;
		$listing->l_id = 1;// eng - default $request->language_id;
		$listing->m_c_id=$request->category_id;
		$listing->s_c_id=$request->scategory;
		$listing->website=$request->website;
		$listing->working_hours=$request->start_time.$request->start_time_ar.'-'.$request->end_time.$request->end_time_ar;
		$listing->holidays=json_encode($request->holidays);
		$listing->title=$request->title;
		$listing->slug = str_slug($request->title, '-');
		$listing->c_id=$country->id;
		$listing->state=$request->states;
		$listing->city=$request->cities;
		$listing->address1=$request->address1;
		$listing->address2=$request->address2;
		$listing->pincode=$request->postcode;
		$listing->phoneno=$request->phoneno;
		$listing->image=$imagename;
		$listing->status='Disable';//'Enable';//
		$listing->meta_tag=$request->meta_tag;
		$listing->meta_description=$request->meta_description;
		$listing->description=$request->description;	
		$listing->category_tags	= '';//$tags;
		$listing->lattitude=$request->lattitude;
		$listing->longitude=$request->longitude;
		$listing->lat_long=$request->lat_long;
		$listing->save();
		
		// add listing tags
		$tag_arr = [];
		foreach($tags as $category_tag_id)
		{
			$tag_arr[] = array('category_tag_id' => $category_tag_id, 'listing_id' => $listing->id);
		}
		if(!empty($tag_arr))
			ListingTags::insert($tag_arr);
		
		$payment_gateway_id = $request->payment_gateway_id;
		$gateway_settings = PaymentGatewaySiteSettings::where(['payment_gateway_site_id' => $payment_gateway_id])->first();
		$currency_id = $gateway_settings->currency->id;
		
		$plan_detail = array();
		$subsObj = new VendorSubscription;
		
		$subscription_pricing = SubscriptionPricing::find($request->subscription_pricing_id);
		$subscription_category = Category::find($request->category_id);
		$subscription_term = SubscriptionTerm::find($request->subscription_term_id);
		$plan_detail['subscription_pricing'] = array('plan' => $subscription_pricing->plan->title, 'price' => $subscription_pricing->price, 'duration' => $subscription_pricing->duration->title, 'duration_type' => $subscription_pricing->duration->days_month, 'f_id' => $subscription_pricing->f_id);
		$plan_detail['subscription_category'] = array('title' => $subscription_category->c_title, 'category_type' => $subscription_category->category_type);
		$plan_detail['subscription_term'] = array('display_value' => $subscription_term->display_value);
		
		$unit_price = $subscription_pricing->price;
		$paid_amount = $unit_price;
		if($subscription_term->term_type == 'month') {
			$paid_amount = $unit_price*$subscription_term->term_value;
		} else {
			$paid_amount = $unit_price*$subscription_term->term_value*12;
		}
		
		$today_date = date('Y-m-d H:i:s'); // today date
		$expired_date = $subsObj->getExpiredDate($today_date, $subscription_term);
		
		// subscription order creation
		$subsObj->merchant_id = $user->id;
		$subsObj->subscription_pricing_id = $request->subscription_pricing_id;
		$subsObj->subscription_term_id = $request->subscription_term_id;
		$subsObj->currency_id = $currency_id;
		$subsObj->payment_gateway_id = $payment_gateway_id;
		$subsObj->unit_price = $unit_price;
		$subsObj->paid_amount = $paid_amount;
		$subsObj->payment_status = 'Pending';
		$subsObj->subscribed_date = $today_date; 
		$subsObj->expired_date = $expired_date;
		//$subsObj->renewed_date = null;		
		$subsObj->plan_detail = json_encode($plan_detail);
		$subsObj->save();
		
		$user->last_subscription_id = $subsObj->id;
		$user->next_renewal_date = $expired_date;
		$user->save();
		
		// redirect to payment gateway
		$encrypt_id = base64_encode('JVinoNeedifo_'.$subsObj->id);
		
		if($payment_gateway_id == 1) // Ebs
			return redirect('payby-ebs/'.$encrypt_id);
		else if($payment_gateway_id == 3) // Razor
			return redirect('payby-razor/'.$encrypt_id);
	}
	
	public function payby_ebs($encrypt_subscription_id)
	{
		$decrypt_id = base64_decode($encrypt_subscription_id);
		$subscription_id = (int) str_replace('JVinoNeedifo_', '', $decrypt_id);
		
		$subscription = [];
		$response_html = '';
		if($subscription_id)
		{
			$subscription = VendorSubscription::find($subscription_id);
			if($subscription->payment_status == 'Pending')
			{
				$merchant_id = $subscription->merchant_id;
				
				$ebsObj = new EbsLibrary;
				$user = User::find($merchant_id);
				
				$ebsObj->addAPIFilelds();
				$ebsObj->addField('reference_no', $subscription_id);
				$ebsObj->addField('return_url', url('/ebs-thankyou')); //ebs return url
				$ebsObj->addField('name', trim($user->first_name . ' ' . $user->last_name));
				$ebsObj->addField('address', $user->address);
				$ebsObj->addField('postal_code', preg_replace('/\s+/', '', $user->postal_code));
				$ebsObj->addField('city', $user->city_name);
				$ebsObj->addField('email', $user->email);
				$ebsObj->addField('phone', $user->mobile_no);
				$ebsObj->addField('description', $merchant_id);
				$ebsObj->addField('amount', $subscription->paid_amount);//.'.00'
				
				$ebs_html = $ebsObj->generateForm();
				
				return view('pages.vendor_register.payby_ebs', compact('ebs_html'));
			}
		}
		
		return view('pages.vendor_register.ebs_thankyou', compact('response_html'));
	}
	
	function ebs_thankyou()
	{
		
		$payment_gateway_id = 1;	// Ebs
		$gateway_settings = PaymentGatewaySiteSettings::where(['payment_gateway_site_id' => $payment_gateway_id])->first();
		
		$response_html = '';
		$secret_key = $gateway_settings->secret_key;
		
		if($_REQUEST)
		{
			$response = $_REQUEST;
			$sh = $response['SecureHash'];	
			$params = $secret_key;
			ksort($response);
			$ebsObj = new EbsTransactionSite;

			$valid_table_fields = array('BillingAddress', 'BillingCity', 'BillingCountry', 'BillingEmail', 'BillingName', 'BillingPhone', 'BillingPostalCode', 'DateCreated', 'Description', 'IsFlagged', 'MerchantRefNo', 'Mode', 'PaymentID', 'PaymentMethod', 'RequestID', 'ResponseCode', 'ResponseMessage', 'SecureHash', 'Amount', 'TransactionID');

			foreach ($response as $key => $value)
			{
				if (strlen($value) > 0 && $key!='SecureHash') {
					$params .= '|'.$value;
				}

				if(strlen($value) > 0){
					if(in_array($key, $valid_table_fields)) {
						//$datacc[$key] = $value;						
						$ebsObj->{$key} = $value;
					}
					
					if($key == 'MerchantRefNo'){							
						$MerchantRefNo=$key;
						$MerchantRefval=$value;									
					}
					if($key == 'ResponseMessage'){							
						$ResponseMessage=$key;
						$ResponseMessageval=$value;
					}
					if($key == 'TransactionID'){							
						$TransactionID=$key;
						$TransactionIDval=$value;
					}
				}
			}
			
			if($MerchantRefNo)
			{
				$data = EbsTransactionSite::where(['MerchantRefNo' => $MerchantRefval])->first();
				
				if(empty($data->id))
				{
					if($ResponseMessageval == "Transaction Successful")
					{
						// update status in subscription table
						$subscription = VendorSubscription::find($MerchantRefval);
						$subscription->payment_status = 'Success';
						$subscription->save();	
					}
					// add ebs log
					$ebsObj->save();
					
					$response_html = '<div class="col-sm-8 col-sm-offset-3"><div class="col-sm-6"><h6>Order ID :</h6></div><div class="col-sm-6"><h6>'.$MerchantRefval.'</h6></div><div class="col-sm-6"><h6>Transaction ID :</h6></div><div class="col-sm-6"><h6>'.$TransactionIDval.'</h6></div><div class="col-sm-6"><h6>ResponseMessage :</h6></div><div class="col-sm-6"><h6>'.$ResponseMessageval.'</h6></div><div class="col-sm-6"></div><div class="col-sm-6"></div></div>';		

				}
				else
				{					
					$response_html = '<div class="col-sm-8 col-sm-offset-3"><div class="col-sm-6"><h6>Order ID :</h6></div><div class="col-sm-6"><h6>'.$MerchantRefval.'</h6></div><div class="col-sm-6"><h6>Transaction ID :</h6></div><div class="col-sm-6"><h6>'.$TransactionIDval.'</h6></div><div class="col-sm-6"><h6>ResponseMessage :</h6></div><div class="col-sm-6"><h6>'.$ResponseMessageval.'</h6></div><div class="col-sm-6"></div><div class="col-sm-6"></div></div>';							
				}
			}  

		}
		return view('pages.vendor_register.ebs_thankyou', compact('response_html'));

	}
	
	public function payby_razor($encrypt_subscription_id)
	{
		$decrypt_id = base64_decode($encrypt_subscription_id);
		$subscription_id = (int) str_replace('JVinoNeedifo_', '', $decrypt_id);
		
		$subscription = [];
		$response_html = '';
		if($subscription_id)
		{
			$subscription = VendorSubscription::find($subscription_id);
			if($subscription->payment_status == 'Pending')
			{
				$merchant_id = $subscription->merchant_id;
				
				$payObj = new RazorLibrary;
				$user = User::find($merchant_id);
				
				$payObj->addAPIFilelds();
				$payObj->addField('subscription_id', $subscription_id);
				$payObj->addField('return_url', url('/razor-thankyou')); //ebs return url
				$payObj->addField('customer_name', trim($user->first_name . ' ' . $user->last_name));
				$payObj->addField('description', 'Subscription');
				$payObj->addField('customer_email', $user->email);
				$payObj->addField('phone', $user->mobile_no);
				$payObj->addField('amount', $subscription->paid_amount*100);//.'.00'
				
				$razor_html = $payObj->generateForm();
				
				return view('pages.vendor_register.payby_razor', compact('razor_html'));
			}
		}
		
		return view('pages.vendor_register.razor_thankyou', compact('response_html'));
	}
	
	function razor_thankyou()
	{//print_r($_REQUEST);
		
		$payment_gateway_id = 3;	// razor
		$gateway_settings = PaymentGatewaySiteSettings::where(['payment_gateway_site_id' => $payment_gateway_id])->first();
		
		$api_key = $gateway_settings->api_key;
		$secret_key = $gateway_settings->secret_key;
		
		
		$input = \Input::all();
		
		$response_error = '';
		$response_success = '';

        if(count($input)  && !empty($input['razorpay_payment_id']) && !empty($input['subscription_id'])) 
		{
			$subscription = VendorSubscription::find($input['subscription_id']);
			$subscription->razorpay_payment_id = $input['razorpay_payment_id'];
			
			$api = new Api($api_key, $secret_key);
			
			$payment = $api->payment->fetch($input['razorpay_payment_id']);
			
			if($payment)
			{
			
				if($payment['status'] == 'authorized')
				{
				
					try {
						$payment = $api->payment->fetch($input['razorpay_payment_id'])->capture(array('amount'=>$payment['amount'])); 
						
						// update status in subscription table				
						$subscription->payment_status = 'Success';		

						// send mail
						$merchant = $subscription->merchant;
						$mail_content = array();
						$mail_content['email'] = $merchant->email;
						$mail_content['order_id'] = $subscription->id;
						$mail_content['payment_id'] = $payment['id'];
						$mail_content['status'] = $payment['status'];
						// admin email
						if( isset($this->site_settings->notification_email) )
							$mail_content['admin_mail'] = $this->site_settings->notification_email;
						Mail::send('emails.subscription', $mail_content, function($message)use ($mail_content) 
						{
							$email = $mail_content['email'];
							$message->to($email,'');
							if(isset($mail_content['admin_mail']))
								$message->bcc($mail_content['admin_mail'],'');
							$message->subject('Thankyou for subscribe');

						});

					} catch (\Exception $e) {
						$response_error = $e->getMessage();
					}
				}
				
				$response_success = '<div class="col-sm-8 col-sm-offset-3"><div class="col-sm-6"><h6>Order ID :</h6></div><div class="col-sm-6"><h6>'.$subscription->id.'</h6></div><div class="col-sm-6"><h6>Transaction ID :</h6></div><div class="col-sm-6"><h6>'.$payment['id'].'</h6></div><div class="col-sm-6"><h6>Payment Status :</h6></div><div class="col-sm-6"><h6>'.$payment['status'].'</h6></div><div class="col-sm-6"></div><div class="col-sm-6"></div></div>';
			}
			
			$subscription->save();

            // Do something here for store payment details in database...
        }
		
		return view('pages.vendor_register.razor_thankyou', compact('response_success', 'response_error'));		

	}

	public function ContactStore(Request $request)
	{

		Mail::send('emails.contact',
			array(
				'name' => $request->get('name'),
				'email' => $request->get('email'),
				'user_message' => $request->get('message')
			), function($message)
			{
				$message->from('alex@wjgilmore.com');
				$message->to('alexander.mca08@gmail.com', 'Admin')->subject('Needifo Feedback');
			});

		return Redirect::route('contact-us')->with('message', 'Thanks for contacting us!');

	}
	
	// local Vendor  Listing Details
	
	public function getLocalVendorListing(Request $request)
	{		
		$current_lat = isset($_COOKIE['current_lat']) ? $_COOKIE['current_lat'] : '';
		if($current_lat)
			$current_lat_arr = explode(',', $current_lat);
		
		$radius = 100;
		
		if( isset($current_lat_arr) and count($current_lat_arr) == 2 )
		{
			$lattitude = $current_lat_arr[0];
			$longitude = $current_lat_arr[1];
			
			$locations=file_get_contents('https://maps.googleapis.com/maps/api/place/nearbysearch/json?location='.$current_lat.'&radius='.$radius.'&type=&key=AIzaSyBzBUddAxyaIr8QLgKjPo4qi_ZLwq_h06I');
			$location=json_decode($locations);
			$loc=$location->results;			
		}
		else
		{
			$location = \Config::get('settings.defaultcity');
			
			$locations=file_get_contents('https://maps.googleapis.com/maps/api/place/nearbysearch/json?location='.$location.'&radius='.$radius.'&key=AIzaSyBzBUddAxyaIr8QLgKjPo4qi_ZLwq_h06I');
			$location = json_decode($locations);
			$loc = $location->results;
		}
		
		$lat_long_qry = '1';
		$sep = ' AND ';
		/*foreach ($loc as $key => $value) {
			$northeast_lat = round($value->geometry->viewport->northeast->lat, 7);
			$northeast_lng = round($value->geometry->viewport->northeast->lng, 7);
			
			$southwest_lat = round($value->geometry->viewport->southwest->lat, 7);
			$southwest_lng = round($value->geometry->viewport->southwest->lng, 7);
			
			$lat_long_qry .= $sep . '(latitude BETWEEN ' . $southwest_lat.' AND '.$northeast_lat . ' AND '.
							' longitude BETWEEN ' . $southwest_lng.' AND '.$northeast_lng . ')';
			
			$sep = ' OR ';
		}*/
		
		$site_settings = $this->site_settings;
		
		$active_hour = $site_settings->user_local_vendor_active_time;
		
		$sql_ext = ' AND DATE_ADD(created_at, INTERVAL '.$active_hour.' HOUR) > "'.date('Y-m-d H:i:s').'"';
		
		$sql_only = ' 1 AND (' . $lat_long_qry . ')' . $sql_ext;
			
		$localvendor = LocalVendor::select('*')->selectRaw("DATE_ADD(created_at, INTERVAL ".$active_hour." HOUR) AS exp_date, MOD(HOUR(TIMEDIFF('".date('Y-m-d H:i:s')."', DATE_ADD(created_at, INTERVAL ".$active_hour." HOUR))), 24) AS hours , MINUTE(TIMEDIFF('".date('Y-m-d H:i:s')."', DATE_ADD(created_at, INTERVAL ".$active_hour." HOUR))) AS minutes, SECOND(TIMEDIFF('".date('Y-m-d H:i:s')."', DATE_ADD(created_at, INTERVAL ".$active_hour." HOUR))) AS seconds")->whereRaw($sql_only)->get();
			
			
	    return view ('pages.local_vendor.local_vendor_listing',['localvendor'=>$localvendor]);
		
	}
	
	public function getLocalVendor(Request $request){
		$cr_location=$request->location;
			/*$cur_loc=explode(',', $location);
			$latitude=$cur_loc[0];
			$longitude=$cur_loc[1];*/
			$locations=file_get_contents('https://maps.googleapis.com/maps/api/place/nearbysearch/json?location='.$cr_location.'&radius=1000&key=AIzaSyBzBUddAxyaIr8QLgKjPo4qi_ZLwq_h06I');
			$location=json_decode($locations);
			$loc=$location->results;		
			$lat_long_qry = '1';
			$sep = ' AND ';
			foreach ($loc as $key => $value) {
				$northeast_lat = round($value->geometry->viewport->northeast->lat, 7);
				$northeast_lng = round($value->geometry->viewport->northeast->lng, 7);

				$southwest_lat = round($value->geometry->viewport->southwest->lat, 7);
				$southwest_lng = round($value->geometry->viewport->southwest->lng, 7);

				$lat_long_qry .= $sep . '(latitude BETWEEN ' . $southwest_lat.' AND '.$northeast_lat . ' AND '.
				' longitude BETWEEN ' . $southwest_lng.' AND '.$northeast_lng . ')';

				$sep = ' OR ';
			}
			$sql_only = 'status = 1'.
			' AND (' . $lat_long_qry . ')';
			return $data=DB::table('local_vendor')->whereRaw($lat_long_qry)->get();	
		}	
		
	public function saveContact(Request $request)
	{			
		$return = array('status' => 0, 'msg' => 'Please login to perform this request.');
		
		$user_id = (auth()->check()) ? auth()->user()->id : null;
		
		if($user_id)
		{
			$userObj = new User;
			$return = $userObj->_addContact($user_id, $request, $return);
		}
		
		return response()->json($return);
	}
}
