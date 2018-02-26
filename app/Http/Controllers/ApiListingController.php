<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Listing;
use App\Models\Category;
use App\Models\MerchantServices;
use App\Models\TableBookingSettings;
use App\Models\ListingReview;
use App\Models\AppointmentBookingSettings;
use App\Models\OrderOnlineSettings;
use App\Models\SubscriptionFeatures;

use DB;
class ApiListingController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
     $listing= Listing::all();
     $data['listing']=$listing;	 
     $responsecode = 200;        
     $header = array (
      'Content-Type' => 'application/json; charset=UTF-8',
      'charset' => 'utf-8'
    );       
     return response()->json($data , $responsecode, $header, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
	  //
   }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {


		$listing = Listing::with('listing_city', 'listing_state')->find($id);
		$response['result']=array();
    
		if(sizeof($listing)>0)
		{

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
		}
			/** get services based on category**/
			/*  $services=DB::table('category')->where('c_id',$listing->m_c_id)->value('category_type');
			$category_type_id=json_decode($services);*/
      
			$tablebookingsettings = new \stdClass;
			$appointmentbookingsettings = new \stdClass;
			$orderonline_menus = new \stdClass;
			$orderonline_menu_items = new \stdClass;

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
					//$data['days'] = $table_booking_settings->getUpcomingSixDays();

					$tablebookingsettings->times = $table_booking_settings->getListingTimes($tablebookingsettings);
				}
			}
			unset($listing->tablebookingsettings);
      
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
				  //$data['days'] = $appointment_booking_settings->getUpcomingSixDays();
				  
				  $appointmentbookingsettings->times = $appointment_booking_settings->getListingTimes($appointmentbookingsettings);
				}
			} 
			unset($listing->appointmentbookingsettings);

			//$reviews=ListingReview::get()->where('merchant_id',$listing->user_id)->where('listing_id',$listing->id)->where('approved','1');
			$reviews=ListingReview::with(['user' => function($query){
				$query->select('id','first_name');
			}])->where(['merchant_id' => $listing->user_id, 'listing_id' => $listing->id, 'approved' => '1'])->get();
			$avgrating=DB::table('listing_review')->where('listing_id',$listing->id)->where('approved','1')->avg('rating');
			
			$total_reviews=0;

			if(sizeof($reviews)>0)
			{
				$excellentcount=DB::table('listing_review')->where('listing_id',$listing->id)->where('rating', 5)->where('approved','1')->count();
				$goodcount=DB::table('listing_review')->where('listing_id',$listing->id)->where('rating','<', 5)->where('rating','>=', 4)->where('approved','1')->count();
				$averagecount=DB::table('listing_review')->where('listing_id',$listing->id)->where('rating','<', 4)->where('rating','>=', '2.5')->where('approved','1')->count();
				$badcount=DB::table('listing_review')->where('listing_id',$listing->id)->where('rating','<', '2.5')->where('approved', 1)->count();
				$total_reviews=sizeof($reviews);
				$excellent=$excellentcount/$total_reviews*100;
				$good=$goodcount/$total_reviews*100;
				$average=$averagecount/$total_reviews*100;
				$bad=$badcount/$total_reviews*100;

			}
			else
			{
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

			$orderonlinemenus = new \stdClass;
	
			if($orderonlinesettings_enable)
			{
				
				$orderonlinemenus = DB::table('food_menu')
									->join('food_menu_item', function ($join) use($listing) {
										$join->on('food_menu.id', '=', 'food_menu_item.food_menu_id')
										->where('food_menu_item.status', '=', 1)
										->where('food_menu_item.listing_id', '=', $listing->id)
										->where('food_menu_item.stock', '>', 0);
									})
									->select('food_menu.name AS menu_name', 'food_menu.id AS menu_id', 'food_menu_item.merchant_id', 'food_menu_item.item_type', 'food_menu_item.id As item_id', 'food_menu_item.name As item_name', 'food_menu_item.original_price',
									'food_menu_item.discounted_price', 'food_menu_item.stock')
									->where('food_menu.status', '=', 1)				
									->get();
        
			}     
			/*** online order end ***/

      /*online shopping start*/
      $Products=[];
      $product=array();
      $onlineshop_enable = MerchantServices::where(['is_enable' => 1, 'category_type_id' => $this->online_shopping_id, 'merchant_id' => $listing->user_id])->exists();
      $category_type=$listing->category->category_type;
      $type=(array)json_decode($category_type);
      if (in_array("2", $type) and $onlineshop_enable){
        $Products = DB::table('shop_products')->leftJoin('shop_pro_cat', 'shop_pro_cat.id', '=', 'shop_products.cat_id')->leftjoin('listing',function($join){
          $join->on('listing.id','=','shop_products.listing_id');
          $join->on('listing.user_id','=','shop_products.merchant_id');
        })->where('shop_pro_cat.status', '=', 0)->where('listing.id','=',$listing->id)->selectRaw('shop_products.id as prod_id,shop_products.*,shop_pro_cat.*,listing.*')->get();

        foreach($Products as $value){
          $shop_product=array();
          $shop_product['prod_id']=$value->prod_id;
          $shop_product['pro_name']=$value->pro_name;
          $shop_product['pro_price']=$value->pro_price;
          $shop_product['pro_info']=$value->pro_info;
          $shop_product['pro_img']=$value->pro_img;
          $shop_product['stock']=$value->stock;
          $shop_product['spl_price']=$value->spl_price;
          $shop_product['merchant_id']=$value->merchant_id;
          $shop_product['listing_id']=$value->listing_id;
          $shop_product['cat_id']=$value->cat_id;
          array_push($product,$shop_product);
        }
        
      }
	  
		$online_order_id = $this->online_order_id;
		$online_shopping_id = $this->online_shopping_id;
		$table_booking_id = $this->table_booking_id;
		$appointment_booking_id = $this->appointment_booking_id;
		$service_booking_id = $this->service_booking_id;
		$listing_type = '';
		if(in_array($online_order_id, $type))
			$listing_type = 'online_order';
		else if(in_array($online_shopping_id, $type))
			$listing_type = 'online_shopping';
		else if(in_array($table_booking_id, $type))
			$listing_type = 'table_booking';
		else if(in_array($appointment_booking_id, $type))
			$listing_type = 'appointment_booking';
		else if(in_array($service_booking_id, $type))
			$listing_type = 'service_booking';
		
      $result=array();
      $result['listing']=$listing;	  
      $result['order_type']=$listing_type;
      $result['TableBookingSettings']=$tablebookingsettings;
      $result['appointmentbookingsettings']=$appointmentbookingsettings;
      $result['ordermenu']=$orderonline_menus;
      $result['ordermenuitems']= $orderonlinemenus; //$orderonline_menu_items;
      $result['products']=$product;
      $result['reviews']=$reviews;
      $result['rating']=$avgrating;
      $result['total_reviews']=$total_reviews;
      $result['excellent']=$excellent;
      $result['good']=$good;
      $result['average']=$average;
      $result['bad']=$bad;
	  $result['enable_listing_model'] = $enable_listing_model;
      array_push($response['result'],$result);
      
	  
	 
	  
      //array_push($response['result'],$orderonline_menu_items);
      return response()->json($response);
       // return view('pages.listing_detail', compact('listing', 'data', 'tablebookingsettings', 'appointmentbookingsettings', 'orderonline_menus', 'orderonline_menu_items', 'page_name','reviews','avgrating','excellent','good','average','bad','Products'));

    }
    $response['result']='Invalid Listing';
    return response()->json($response);
  }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    public function listByLocation(Request $request)
	{
		$query=Listing::get();
		$data['listing']=array();
		$loc=array();
		
		if($request->has('id')&&$request->has('lat_lng')&&$request->has('s_category'))
		{
			$parent_id=$request->id;
			$location=$request->lat_lng; 
			$sub_cat=$request->s_category;
			$category=DB::table('category')->where('c_id',$parent_id)->value('c_title');
			try {
				$alllocations=file_get_contents('https://maps.googleapis.com/maps/api/place/nearbysearch/json?location='.$location.'&radius=1000&key=AIzaSyBU7YTBgP-ZsBlVcYGYAfuz2os6yfTWLIg');
				$locations=json_decode($alllocations);
				$loc=$locations->results;
			} catch(\Exception $e) {
			
			}
			$lat_long_qry = '1';
			$sep = ' AND ';
			$categ='m_c_id='.$parent_id.' AND s_c_id='.$sub_cat;
			foreach ($loc as $key => $value) 
			{
				$northeast_lat = round($value->geometry->viewport->northeast->lat, 7);
				$northeast_lng = round($value->geometry->viewport->northeast->lng, 7);

				$southwest_lat = round($value->geometry->viewport->southwest->lat, 7);
				$southwest_lng = round($value->geometry->viewport->southwest->lng, 7);

				$lat_long_qry .= $sep . '(lattitude BETWEEN ' . $southwest_lat.' AND '.$northeast_lat . ' AND '.
				' longitude BETWEEN ' . $southwest_lng.' AND '.$northeast_lng . ')';

				$sep = ' OR ';
			}
			$lat_long_qry=$categ.' AND '.$lat_long_qry;
			//return $lat_long_qry;
			$sql_only = "status = 'Enable'".
			' AND (' . $lat_long_qry . ')';
			$listing=Listing::whereRaw($lat_long_qry)->get(); 
		}      
		else if($request->has('id'))
		{
			$parent_id=$request->id;

			$location = '13.064261881771044,80.23641586303711';
			if($request->has('lat_lng'))
				$location=$request->lat_lng; 

			$category=DB::table('category')->where('c_id',$parent_id)->value('c_title');
			
			try {
				$alllocations=file_get_contents('https://maps.googleapis.com/maps/api/place/nearbysearch/json?location='.$location.'&radius=100&key=AIzaSyBU7YTBgP-ZsBlVcYGYAfuz2os6yfTWLIg');
				$locations=json_decode($alllocations);
				// print_r( $locations);die; 
				$loc=$locations->results;
			} catch(\Exception $e) {
			
			}
			/*  $location=[];
			foreach ($loc as $key => $value) {
			$lat=$value->geometry->location->lat;
			$long=$value->geometry->location->lng;
			$loct=$lat.','.$long;
			array_push($location, $loct);
			}
			$listing=$query->where('m_c_id',$parent_id)->whereIn('lat_long',$location);*/

			$lat_long_qry = '1';
			$categ='m_c_id='.$parent_id;

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
			$lat_long_qry=$categ.' AND '.$lat_long_qry;
			//return $lat_long_qry;
			$sql_only = "status = 'Enable'".
			' AND (' . $lat_long_qry . ')';
			$listing=Listing::whereRaw($lat_long_qry)->where('m_c_id',$parent_id)->get(); 
		}
		
		// re-arrange listing with services
		$category_types=DB::table('category_type')->get();		
		$services_by_id = $this->services_by_id;
		foreach ($listing as $key => $list) 
		{
			$list->services = array();
			$rating=DB::table('listing_review')->where('listing_id',$list->id)->where('approved',1)->avg('rating');
			if($rating==null){
			  $rating='';
			}
			
			if(isset($list->category->category_type))
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
			
			$list_array['id']=$list->id;
			$list_array['m_c_id']=$list->m_c_id;
			$list_array['title']=$list->title;
			$list_array['address1']=$list->address1;
			$list_array['address2']=$list->address2;
			$list_array['pincode']=$list->pincode;
			$list_array['phoneno']=$list->phoneno;
			$list_array['image']=$list->image;
			$list_array['website']=$list->website;
			$list_array['working_hours']=$list->working_hours;
			$list_array['holidays']=$list->holidays;
			$list_array['rating']=$rating;
			$list_array['services']=$list->services;
			array_push($data['listing'],$list_array);
		}
		
		/*foreach ($listing as $key => $list) 
		{
			$list_array=array();
			$rating=DB::table('listing_review')->where('listing_id',$list->id)->where('approved',1)->avg('rating');
			if($rating==null){
			  $rating='';
			}
			
			$list->services = array();
			
			$cat_id = $list->m_c_id;
			$category_type=DB::table('category')->where('c_id',$cat_id)->value('category_type');//print_r($category_type);exit;
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
						$service=array();
						$service['cat_slug']=$cat->slug;
						$service['service_name']=$cat->name;
						array_push($services, $service);
					}
				}
				$list->services=$services;
			}
			$list_array['id']=$list->id;
			$list_array['m_c_id']=$list->m_c_id;
			$list_array['title']=$list->title;
			$list_array['address1']=$list->address1;
			$list_array['address2']=$list->address2;
			$list_array['pincode']=$list->pincode;
			$list_array['phoneno']=$list->phoneno;
			$list_array['image']=$list->image;
			$list_array['website']=$list->website;
			$list_array['working_hours']=$list->working_hours;
			$list_array['holidays']=$list->holidays;
			$list_array['rating']=$rating;
			$list_array['services']=$list->services;
			array_push($data['listing'],$list_array);
		}*/

		$responsecode = 200;        
		$header = array (
		  'Content-Type' => 'application/json; charset=UTF-8',
		  'charset' => 'utf-8'
		);  
		return response()->json($data , $responsecode, $header, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

	}
}
