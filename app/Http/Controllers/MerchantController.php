<?php

namespace App\Http\Controllers;
use DB;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Listing;
use App\Models\User;
use App\Models\MultiLanguage;
use App\Models\Country;
use App\Models\States;
use App\Models\Cities;
use App\Models\Category;
use App\Models\CategorySlug;
use App\Models\OrderBooking;
use App\Models\MerchantServices;
use App\Models\Notifications;
use App\Models\MerchantOrder;
use App\Models\ListingReview;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Auth;
use Carbon\Carbon;
use Image;

class MerchantController extends Controller
{

	public function getHome()
	{
		
		$merchant_id = (auth()->check()) ? auth()->user()->id : null;
		$listing = Listing::where('user_id', '=', $merchant_id)->first();
		
		// statistics for 7 days
		$date = new \Carbon\Carbon;
		$date->subWeek();

		 
		$listing_reviews = ListingReview::
		where('listing_id', $listing->id)
		->where('created_at', '>', $date->toDateTimeString() )
		->get();//print_r($listing_views);die;
		
		$reviews = ListingReview::
		where('listing_id', $listing->id)
		->where('created_at', '>', $date->toDateTimeString() )
		->count();
		//print_r($reviews);die;
		
		$visits = DB::table('listing_views')
		->where('merchant_id', $merchant_id)
		->where('created_at', '>', $date->toDateTimeString() )
		->count();
		
		$not_in_array = ['pending', 'cancelled'];
		
		$total_amount_week = MerchantOrder::
		where('listing_id', $listing->id)
		->whereNotIn('order_status', $not_in_array)
		->where('created_at', '>', $date->toDateTimeString() )
		->sum('total_amount');
		//print_r($total_amount_week);die;
		
		
		
		$orders = [];
		if($listing)
		{
			$orders = MerchantOrder::
			whereNotIn('order_status', $not_in_array)
			->where('listing_id', $listing->id)
			->where('created_at', '>', $date->toDateTimeString() )
			->count();
		}

		$appointments = DB::table('appointment_booking_order')
		->where('merchant_id', $merchant_id)
		->where('order_date', '>', $date->toDateTimeString() )
		->count();

		$table_bookings = DB::table('table_booking_order')
		->where('merchant_id', $merchant_id)
		->where('order_date', '>', $date->toDateTimeString() )
		->count();
		
		$merchant_services = MerchantServices::where(['merchant_id' => $merchant_id, 'is_enable' => 1])->get();
		
		$order_enable = $appointment_enable = $table_booking_enable = false;
		foreach($merchant_services as $merchant_service)
		{
			if($merchant_service->category_type->slug == 'online-order' || $merchant_service->category_type->slug == 'online-shopping')
				$order_enable = true;
			if($merchant_service->category_type->slug == 'appointment-booking')
				$appointment_enable = true;
			if($merchant_service->category_type->slug == 'table-booking')
				$table_booking_enable = true;
		}
		
		/*** bar graph start ***/
		$sales_daily_in_current_month = [];
		$start_date = $date->toDateString();
		$end_date = date('Y-m-d');
		if($listing)
		{
			$sql = 'SELECT DATE(merchant_orders.created_at) AS order_date, '.
			'COUNT(merchant_orders.id) AS total_order, '.
			'SUM(merchant_orders.total_items) AS total_products, '.
									//'SUM(merchant_orders.ord_tax_total) AS total_tax, '.
			'SUM(merchant_orders.total_amount) AS total_price '.
			'FROM merchant_orders '.
			'WHERE listing_id = ? AND DATE(merchant_orders.created_at) > ? AND DATE(merchant_orders.created_at) <= ? '.
			'GROUP BY DATE(merchant_orders.created_at)'.
			'ORDER BY order_date'
			;
		$sales_daily_in_current_month = DB::select($sql, [$listing->id, $start_date, $end_date]);
		//print_r($sales_daily_in_current_month);die;
			
		}
		$sales_daily_in_current_month_new = array();
		$total_revenue = 0;
		$total_no_of_orders = 0;
		$price_bar = '';
		$sep = '';
		foreach($sales_daily_in_current_month as $sales) {
			$sales_daily_in_current_month_new[$sales->order_date] = $sales;			
			$total_revenue += $sales->total_price;
			$total_no_of_orders += $sales->total_order;
			$price_bar .= $sep . $sales->total_price;
			$sep = ',';
		}
		$interval = new \DateInterval('P1D');
		$period = new \DatePeriod(
			new \DateTime($start_date),
			$interval,
			(new \DateTime($end_date))->add($interval),
			\DatePeriod::EXCLUDE_START_DATE
		);
		
		$sep = $labels_graph_bar = '';
		foreach ($period as $date) {
			$total_price = 0;
			$cur_date = $date->format('Y-m-d');
			if(isset($sales_daily_in_current_month_new[$cur_date])) {
				$sales = $sales_daily_in_current_month_new[$cur_date];
				$total_price = intval($sales->total_price);
			}
			$labels_graph_bar .= $sep . "{day: '".$cur_date."', order_amount: ".$total_price."}";	
			$sep = "," . "\n";
		}
		
		//print_r($labels_graph_bar);exit;
		/*** bar graph end ***/
		
		// traffic user
		$new_exists_traffic = DB::table('listing_views')
		->select(DB::raw('count(*) as user_type_count, user_type'))
		->where('merchant_id', $merchant_id)
		->groupBy('user_type')
		->get();//print_r($new_exists_traffic);exit;

		$traffic_data['labels']	= '';
		$traffic_data['data']	= '';
		$sep = '';
		$traffic_data['color']	= '"#26B99A","#3498DB"';
		$traffic_data['hover_color']	= '"#36CAAB","#49A9EA"';
		foreach($new_exists_traffic as $traffic)
		{
			$traffic_data['labels']	.= $sep . '"' . ucfirst($traffic->user_type) . '"';
			$traffic_data['data']	.= $sep . $traffic->user_type_count;
			$sep = ',';
		}

		// traffic device
		$device_traffic = DB::table('listing_views')
		->select(DB::raw('count(*) as device_count, device'))
		->where('merchant_id', $merchant_id)
		->groupBy('device')
		->get();
		$device_data['labels']	= '';
		$device_data['data']	= '';
		$device_data['color']	= '';
		$device_data['hover_color']	= '';
		$sep = '';
		$init_color	= 26;
		$init_hover_color	= 36;
		foreach($device_traffic as $device)
		{
			$device_data['labels']	.= $sep . '"' . ucfirst($device->device) . '"';
			$device_data['data']	.= $sep . $device->device_count;
			$device_data['color'] .= $sep . '"#'.$init_color.'B99A"';
			$device_data['hover_color'] .= $sep . '"#'.$init_hover_color.'CAAB"';
			$sep = ',';

			$init_color += 2;
			$init_hover_color += 2;
		}
		//print_r($device_data);exit;

		// recent orders
		$recent_orders = [];
		if($listing)
		{
			$recent_orders = MerchantOrder::where('listing_id', $listing->id)
			->orderBy('created_at', 'DESC')
			->limit(5)
			->get();
		}
		
		$compact_array = compact('visits', 'orders', 'appointments', 'table_bookings', 'table_booking_enable', 'appointment_enable', 'order_enable', 'total_no_of_orders', 'total_revenue', 'labels_graph_bar', 'price_bar', 'traffic_data', 'device_data', 'recent_orders','total_amount_week','reviews','listing_reviews');
		
		return $this->_loadMerchantView('home', $compact_array);

	}

			
			
	public function getOrderGraph(Request $request)
	{
		
		
		$merchant_id = (auth()->check()) ? auth()->user()->id : null;
		$listing = Listing::where('user_id', '=', $merchant_id)->first();

		$sales_daily_in_current_month = [];
		$start_date = $request->start_date;
		$end_date = $request->end_date;
		if($end_date > date('Y-m-d'))
			$end_date = date('Y-m-d');
        if($listing)
		{
		$sql = 'SELECT DATE(merchant_orders.created_at) AS order_date, '.
		'COUNT(merchant_orders.id) AS total_order, '.
		'SUM(merchant_orders.total_items) AS total_products, '.
						//'SUM(merchant_orders.ord_tax_total) AS total_tax, '.
		'SUM(merchant_orders.total_amount) AS total_price '.
		'FROM merchant_orders '.
		'WHERE listing_id = ? AND DATE(merchant_orders.created_at) >= ? AND DATE(merchant_orders.created_at) <= ? '.
		'GROUP BY DATE(merchant_orders.created_at)'.
		'ORDER BY order_date'
		;
		
		$sales_daily_in_current_month = DB::select($sql, [$listing->id, $start_date, $end_date]);
		}
		
		$sales_daily_in_current_month_new = array();
		$total_revenue = 0;
		$total_no_of_orders = 0;
		$price_bar = '';
		$sep = '';
		foreach($sales_daily_in_current_month as $sales) {
			$sales_daily_in_current_month_new[$sales->order_date] = $sales;			
			$total_revenue += $sales->total_price;
			$total_no_of_orders += $sales->total_order;
			$price_bar .= $sep . $sales->total_price;
			$sep = ',';
		}
		$interval = new \DateInterval('P1D');
		$period = new \DatePeriod(
			new \DateTime($start_date),
			$interval,
			(new \DateTime($end_date))->add($interval)
		);

		$sep = $labels_graph_bar = '';
		foreach ($period as $date) {
			$total_price = 0;
			$cur_date = $date->format('Y-m-d');
			if(isset($sales_daily_in_current_month_new[$cur_date])) {
				$sales = $sales_daily_in_current_month_new[$cur_date];
				$total_price = intval($sales->total_price);
			}
			$labels_graph_bar .= $sep . "{day: '".$cur_date."', order_amount: ".$total_price."}";	
			$sep = "," . "\n";
		}//print_r($labels_graph_bar);exit;
		
		return view('panels.merchant.order_graph', compact('total_no_of_orders', 'total_revenue', 'labels_graph_bar', 'price_bar'));
		
		//$compact_array = compact('total_no_of_orders', 'total_revenue', 'labels_graph_bar', 'price_bar');
		
		//return $this->_loadMerchantView('home', $compact_array);
	}
	
	public function getNotification(){
		$user_id=(auth()->check()) ? auth()->user()->id : null;
		//$today=Carbon::now();
		$notifications=Notifications::orderBy('created_at','desc')->where('merchant_id',$user_id)->where('read','0')->get();
		foreach ($notifications as $key => $value) {
			$value['time']=$value->created_at->diffForHumans();;
		}
		return response()->json($notifications);
	}
	public function changeStatus(Request $request){
		$id=$request->id;
		$notification=Notifications::where('id',$id)->update(['read'=>1]);
		return $notification;
	}
	public function allNotifications(Request $request){
		return view('panels.merchant.allnotifications');
	}
	public function all_Notification(){
		$user_id=(auth()->check()) ? auth()->user()->id : null;
		$notifications=Notifications::orderBy('created_at','desc')->where('merchant_id',$user_id)->get();
		foreach ($notifications as $key => $value) {
			$value['time']=$value->created_at->diffForHumans();;
		}
		return response()->json($notifications);
	}
	public function index()
	{	

		$user_id = (auth()->check()) ? auth()->user()->id : null;
		$listing= Listing::all()->where('user_id', '=', $user_id);


 //dd($listing);

	   //$listing= Listing::all()->where('user_id', '=', 3);
		$language= MultiLanguage::all();
		$country= Country::all();
	   //$category= Category::all();
		$category = DB::table('category')
		->where('parent_id', '0')	 
		->get();
		$users = DB::table('users')
		->select('*', 'users.id as uid')
		->leftJoin('role_user', 'users.id', '=', 'role_user.user_id')
		->where('role_user.role_id', '=', 3)
		->get();
		
		$subcategory = DB::table('category')
		->where('parent_id','!=', '0')
		->get();
		$editstates = DB::table('states')			 
		->get();
		$editcities = DB::table('cities')			 
		->get();

		return view('panels.merchant.merchants.index',['listing'=>$listing,'users' => $users,'language' => $language,'country' => $country,'category' => $category,'editusers' => $users,'editlanguage' => $language,'editcountry' => $country,'editcategory' => $category,'editsubcategory' => $subcategory,'editstates' => $editstates,'editcities' => $editcities]);
	}

	public function getProtected()
	{

		return view('panels.merchant.protected');

	}
	public function getLocation(Request $request){
		$latitude=$request->latitude;
		$longitude=$request->longitude;
		$geolocation=file_get_contents('https://maps.google.com/maps/api/geocode/json?latlng='.$latitude.','.$longitude.'&sensor=false&key=AIzaSyBU7YTBgP-ZsBlVcYGYAfuz2os6yfTWLIg');

		return $geolocation;

	}
	public function changeImage(Request $request){
		$listing_id=$request->listing_id;
		$photo = $request->file('listing_image');
		$imagename = time().'.'.$photo->getClientOriginalExtension();   
		$destinationPath = public_path('/uploads/listing/thumbnail');
		$thumb_img = Image::make($photo->getRealPath())->crop(100, 100);
		$thumb_img->save($destinationPath.'/'.$imagename,80);                    
		$destinationPath = public_path('/uploads/listing/original');
		$photo->move($destinationPath, $imagename);
		$img=DB::table('listing')->where('id',$listing_id)->update(['image'=>$imagename]);
		return $img;
	}
	public function changeListingInfo(Request $request){
		$listing_id=$request->listing_id;
		$description=$request->description;
		$website=$request->website;
		$address1=$request->address1;
		$address2=$request->address2;
		$pincode=$request->postcode;
		$lattitude=$request->lattitude;
		$longitude=$request->longitude;
		$lat_long=$request->lat_long;
		$upd_array=array();
		if($description!=''){
			$info=DB::table('listing')->where('id',$listing_id)->update(['description'=>$description]);
		}
		if($website!=''){
			$info=DB::table('listing')->where('id',$listing_id)->update(['website'=>$website]);
		}
		if($lat_long!=''){
			$info=DB::table('listing')->where('id',$listing_id)->update(['address1'=>$address1,'address2'=>$address2,'pincode'=>$pincode,'lattitude'=>$lattitude,'longitude'=>$longitude,'lat_long'=>$lat_long]);
		}
		print_r($info);
	}

}