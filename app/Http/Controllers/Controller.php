<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\CategoryType;
use App\Models\Currency;
use App\Models\SiteSettings;
use App\Models\Listing;
use App\Models\VendorSubscription;
use App\Models\SubscriptionFeatures;


abstract class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
	var $currency;
	var $merchant_user;
	var $subscribed_category;
	var $default_country;
	var $site_settings;
	
	// default settings
	var $merchant_layout = 'template_v1';
	var $user_layout = 'user-template_v1';
	var $payment_gateway_id = 3; // razor
	var $cash_on_delivery = 4; // payment_gateway_site table
	var $free_subscription_pricing_id = 5; // subscription_pricing table
	var $plan_change_free_subscription_pricing_id = 4;
	var $subscription_grace_period = 5; // days -> see same at logic/merchantrepository
	
	// services / category_types
	var $services = array(
		'food'			=> array('id' => 1, 'name' => 'Food'),
		'table'			=> array('id' => 3, 'name' => 'Table'),
		'appointment'	=> array('id' => 4, 'name' => 'Appointment'),
		'shop'			=> array('id' => 2, 'name' => 'Shop'),
		'services'			=> array('id' => 5, 'name' => 'Services')
	);
	var $services_by_id = array(
		1			=> array('id' => 1, 'name' => 'Food', 'slug' => 'food'),
		3			=> array('id' => 3, 'name' => 'Table', 'slug' => 'table'),
		4			=> array('id' => 4, 'name' => 'Appointment', 'slug' => 'appointment'),
		2			=> array('id' => 2, 'name' => 'Shop', 'slug' => 'shop'),
		5			=> array('id' => 5, 'name' => 'Services', 'slug' => 'services')
		
	);
	
	// initial setup
	var $merchant_services = array(
		'food'		=> false,
		'shop'		=> false,
		'table'		=> false,
		'appointment'	=> false,
		'services'	=> false
	);
	var $enable_listing_model = array(
						'information'			=> false,
						'category_dependent'	=> false,
						'review'				=> false,
						'call'					=> false,
						'map'					=> false,
						'gallery'				=> false,
						'share'					=> false,
						'save'					=> false
						);
	
	public function __construct()
	{
		$subsObj = new VendorSubscription;
		$this->free_subscription_pricing_id = $subsObj->free_subscription_pricing_id;
		$this->plan_change_free_subscription_pricing_id = $subsObj->plan_change_free_subscription_pricing_id;
		
		$this->middleware(function ($request, $next) {
			
			//echo \Session::getId();
            
			// For merchant only
			if(\Auth::check() and \Auth::user()->hasRole('merchant'))
			{
				$this->merchant_user = \Auth::user();
				$listing = Listing::where('user_id', '=', $this->merchant_user->id)->first();
				
				$all_services = [];
				$enable_services = false;
				$subscription_pricing = null;
				$current_page = '';
					
				$currentAction = \Route::currentRouteAction();
				list($controller_part, $method) = explode('@', $currentAction);
				$controller = preg_replace('/.*\\\/', '', $controller_part);
				
				$allowed_controller = array('App\Http\Controllers\PagesController', 'App\Http\Controllers\UserController', 'App\Http\Controllers\CampaignController', 'App\Http\Controllers\OnlineOrderController');
				
				$current_page = $controller.'/'.$method;
				
				$this->subscribed_category = $this->merchant_user->subscribed_category;//print_r($this->subscribed_category);exit;
				$category_types = [];
				if($this->subscribed_category)
				{
					$category_type = $this->subscribed_category->category_type;
					if($category_type) {
						$category_types = json_decode($category_type);
					}
				}
				
				$all_services = $this->get_all_services($category_types);
					
				$allowed_pages = array('SubscriptionController/change_plan', 
											'SubscriptionController/change_subscription_plan_confirm', 
											'SubscriptionController/change_subscription_plan', 
											'SubscriptionController/complete_subscription', 
											'SubscriptionController/payby_razor',
											'SubscriptionController/payment_response',
											'SubscriptionHistoryController/index',
											'SubscriptionHistoryController/view_order',
											'SubscriptionHistoryController/deleted',
											'LoginController/logout'
											);
				
				if(isset($this->merchant_user->last_subscription->subscription_pricing))
				{
					// get subscription
					$subscription_pricing = $this->merchant_user->last_subscription->subscription_pricing;
					
					$features = explode(',', $subscription_pricing->f_id);
					$category_dependent_id = 7;		
					if(in_array($category_dependent_id, $features)) { 
						// merchant can access category services
						$enable_services = true;
					}
					$subscription_features = SubscriptionFeatures::whereIn('id', array_values($features))->get();						
					foreach($subscription_features as $subscription_feature) {
						if( isset($this->enable_listing_model[$subscription_feature->functionality_name]) )
						$this->enable_listing_model[$subscription_feature->functionality_name] = true;
					}
					// check if subscription exists or not	
					$expired_time = strtotime($this->merchant_user->last_subscription->expired_date);
					$extend_expired_time = date('Y-m-d H:i:s', strtotime("+".$this->subscription_grace_period." days", $expired_time));
					$current_time = date('Y-m-d H:i:s');
					
					/*** plan expired ***/
					//if(!in_array($controller_part, $allowed_controller) and $this->merchant_user->last_subscription->payment_status == 'Pending') {
					if(!in_array($controller_part, $allowed_controller) and $extend_expired_time < $current_time) {
						
						if(!in_array($current_page, $allowed_pages)) {
							return redirect('merchant/change-subscription-plan');
						}
						
					}
				}
				
				// check if listing exists or not				
				$allowed_listing_pages = array('ListingController/index', 
											'ListingController/added'
											);
				//$allowed_pages = array_merge($allowed_pages, $allowed_listing_pages);//print_r($allowed_pages);exit;
				$allowed_listing_controller = array('App\Http\Controllers\Merchant\ListingController');
				$allowed_controller = array_merge($allowed_controller, $allowed_listing_controller);
				
				// check if listing exists, if not then redirect to listing controller
				if(!in_array($controller_part, $allowed_controller) and !$listing) {
					
					if(!in_array($current_page, $allowed_pages)) {
						return redirect('merchant/listing')->with('error_message', 'Please add listing to access other pages');
					}
				}
				
				// enable specific services
				if($enable_services and $this->subscribed_category) 
				{ 
					$category_type = $this->subscribed_category->category_type;
					$services_by_id = $this->services_by_id;
					
					if($category_type) {
						$category_types = json_decode($category_type);
						foreach($category_types as $category_type_id)
						{
							if( isset($services_by_id[$category_type_id]['slug']) and $services_by_id[$category_type_id]['slug'] == 'table' ) {
								$this->merchant_services['table'] = true;
							} else if( isset($services_by_id[$category_type_id]['slug']) and $services_by_id[$category_type_id]['slug'] == 'appointment' ) {
								$this->merchant_services['appointment'] = true;
							} else if( isset($services_by_id[$category_type_id]['slug']) and $services_by_id[$category_type_id]['slug'] == 'shop' ) {
								$this->merchant_services['shop'] = true;
							} else if( isset($services_by_id[$category_type_id]['slug']) and $services_by_id[$category_type_id]['slug'] == 'food' ) {
								$this->merchant_services['food'] = true;
							} else if( isset($services_by_id[$category_type_id]['slug']) and $services_by_id[$category_type_id]['slug'] == 'services' ) {
								$this->merchant_services['services'] = true;
							} 
						}
					}
				}
				
				$last_subscription = $this->merchant_user->last_subscription;
				$merchant_status = $this->merchant_user->merchant_status;
				$show_content_type = '';
				if(isset($extend_expired_time) and isset($current_time) and $extend_expired_time < $current_time) {
					$show_content_type = 'content';
				} else if($merchant_status == 1) {
					$show_content_type = 'content';
				} else if($merchant_status == 2) {
					$show_content_type = 'inactive';
				} else {
					$show_content_type = 'pending';
				}
				
				view()->share(['all_services' => $all_services, 'merchant_user' => $this->merchant_user, 'enable_services' => $enable_services, 'subscribed_category' => $this->subscribed_category, 'subscription_pricing' => $subscription_pricing, 'show_content_type' => $show_content_type, 'merchant_listing' => $listing]);
				
			}
			
			$online_order_items = \Cart::getContent();
			$is_empty_online_order = \Cart::isEmpty();
			
			view()->share(['online_order_items' => $online_order_items, 'is_empty_online_order' => $is_empty_online_order, 'merchant_services' => $this->merchant_services, 'enable_listing_model' => $this->enable_listing_model]);
			
			return $next($request);
        });
		
		// get site settings
		$this->site_settings = $site_settings = SiteSettings::first();
		
		// default city
		if(isset($site_settings->city->name))
			config(['settings.defaultcity' => $site_settings->city->name]);		
		// default country
		$this->default_country = $site_settings->country;
		// default currency
		$this->currency = $site_settings->currency;//$currency = Currency::getDefault();
		
		\View::share(['currency' => $this->currency, 'default_country' => $this->default_country, 'site_settings' => $site_settings, 'merchant_layout' => $this->merchant_layout, 
						'user_layout' => $this->user_layout, 'services' => $this->services, 'services_by_id' => $this->services_by_id]);
	}
	
	public function _loadMerchantView($view_name, $compact_array = array())
	{
		$merchant_layout = '';
		if($this->merchant_layout)
			$merchant_layout = $this->merchant_layout . '.';
		
		return view('panels.merchant.' . $merchant_layout . $view_name, $compact_array);
	}
	
	public function _loadUserView($view_name, $compact_array = array())
	{
		$user_layout = '';
		if($this->user_layout)
			$user_layout = $this->user_layout . '.';
		
		return view('panels.user.' . $user_layout . $view_name, $compact_array);
	}
	
	public function get_all_services($category_types)
	{
		$all_services = [];
		if($category_types)
			$all_services = CategoryType::where('status', 1)->whereIn('id', $category_types)->orderBy('name')->get();
		
		return $all_services;
	}
	
	public function random_code()
	{
		//$pool = '6789abcdefghijklmno012345pqrstuvwxy876644zABCDEFGHIJK89354LMNOPQRS109TUVWXYZ';

		//return substr(str_shuffle(str_repeat($pool, $length)), 0, $length);
		
		// Available alpha caracters
		$characters = '0123654789';//'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

		// generate a pin based on 2 * 7 digits + a random character
		$pin = mt_rand(100, 999)
			. mt_rand(10, 99)
			. $characters[rand(0, strlen($characters) - 1)];

		// shuffle the result
		return str_shuffle($pin);
	}
}
