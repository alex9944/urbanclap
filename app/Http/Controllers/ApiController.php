<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\CategoryType;
use App\Models\Currency;
use App\Models\SiteSettings;

abstract class ApiController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
	var $currency;
	var $merchant_user;
	var $subscribed_category;
	var $default_country;
	var $session_id;
	var $site_settings;
	
	// category types
	var $online_order_id = 1;
	var $online_shopping_id = 2;
	var $table_booking_id = 3;
	var $appointment_booking_id = 4;
	var $service_booking_id = 5;
	
	// services / category_types
	var $services = array(
		'food'			=> array('id' => 1, 'name' => 'Food'),
		'table'			=> array('id' => 3, 'name' => 'Table'),
		'appointment'	=> array('id' => 4, 'name' => 'Appointment'),
		'shop'			=> array('id' => 2, 'name' => 'Shop')
	);
	var $services_by_id = array(
		1			=> array('id' => 1, 'name' => 'Food', 'slug' => 'food'),
		3			=> array('id' => 3, 'name' => 'Table', 'slug' => 'table'),
		4			=> array('id' => 4, 'name' => 'Appointment', 'slug' => 'appointment'),
		2			=> array('id' => 2, 'name' => 'Shop', 'slug' => 'shop')
	);
	
	public function __construct()
	{
		
		$this->middleware(function ($request, $next) {
            
			$session_id = $request->session_id;
			if($session_id)
			{				
				$_COOKIE['laravel_session'] = $session_id;
				
				\Session::setId($session_id);
				\Session::save();
			}
			$this->session_id = \Session::getId();
			
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
		
		\View::share(['currency' => $this->currency, 'site_settings' => $site_settings]);
	}
}
