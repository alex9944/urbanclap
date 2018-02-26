<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;

use App\Models\Country;
use App\Models\States;
use App\Models\Cities;
use App\Models\Currency;
use App\Models\SiteSettings;
use App\Models\CategoryType;

use DB;


class SiteSettingsController extends Controller
{
	public function index($tab = null)
    {		
		$country = Country::all();	
		$currencies = Currency::all();
		$setting = SiteSettings::first();
	   
		if(isset($setting->country_id) and $setting->country_id) {
			$setting_country = $setting->country;
			$states = $setting_country->states;//States::where('country_id', $country_id)->get();
			$state_ids = [];
			foreach($states as $state) {
				$state_ids[] = $state->id;
			}//print_r($state_ids);exit;
			$cities = DB::table('cities')->whereIn('state_id', $state_ids)->orderBy('name')->get();//print_r($cities);exit;
		} else {
			$cities = Cities::all();
		}
		
		// service
		$services = CategoryType::all()->where('status', 1);
	   
       return view('panels.admin.settings.index', compact('country', 'currencies', 'cities', 'setting', 'services', 'tab'));
    }
	
	public function update(Request $request)
	{
		$SiteSettings = new SiteSettings;
		$rules = $SiteSettings->get_adding_rules();
		$this->validate($request, $rules['rules'], $rules['messages']);
		
		$merchant_id = (auth()->check()) ? auth()->user()->id : null;
		
		if($request->id)
		{
			$settings = SiteSettings::find($request->id);
			$settings->country_id = $request->country_id;
			$settings->city_id = $request->city_id;
			$settings->currency_id = $request->currency_id;
			$settings->city_lattitude = $request->city_lattitude;
			$settings->city_longitude = $request->city_longitude;
			$settings->merchant_commision_percent = $request->merchant_commision_percent;
			$settings->days_to_pay_merchant = $request->days_to_pay_merchant;			
			$settings->site_email = $request->site_email;
			$settings->site_contact_no = $request->site_contact_no;
			$settings->site_address = $request->site_address;
			$settings->facebook_url = $request->facebook_url;
			$settings->twitter_url = $request->twitter_url;
			$settings->gplus_url = $request->gplus_url;
			$settings->pinterest_url = $request->pinterest_url;
			$settings->linkedin_url = $request->linkedin_url;
			$settings->save();
			
			$add = 'updated';
		}
		else
		{
			$settings = new SiteSettings;
			$settings->country_id = $request->country_id;
			$settings->city_id = $request->city_id;
			$settings->currency_id = $request->currency_id;
			$settings->city_lattitude = $request->city_lattitude;
			$settings->city_longitude = $request->city_longitude;
			$settings->merchant_commision_percent = $request->merchant_commision_percent;
			$settings->days_to_pay_merchant = $request->days_to_pay_merchant;
			$settings->site_email = $request->site_email;
			$settings->site_contact_no = $request->site_contact_no;
			$settings->site_address = $request->site_address;
			$settings->facebook_url = $request->facebook_url;
			$settings->twitter_url = $request->twitter_url;
			$settings->gplus_url = $request->gplus_url;
			$settings->pinterest_url = $request->pinterest_url;
			$settings->linkedin_url = $request->linkedin_url;
			$settings->save();
			
			$add = 'added';
		}
		
		return redirect('admin/settings')->with('message', 'Settings '.$add.' successfully');
	}
	
	public function service(Request $request)
	{
		$services = CategoryType::all()->where('status', 1);
		
		foreach($services as $service)
		{
			$commission_value = $request->commission_value[$service->id];
			$commission_type = $request->commission_type[$service->id];
			
			$service->commission_value = $commission_value;
			$service->commission_type = $commission_type;
			$service->save();
		}
		
		return redirect('admin/settings/service')->with('message', 'Settings updated successfully');
	}
	
	public function localvendor_user(Request $request)
	{
		$settings = SiteSettings::find($request->id);
		$settings->user_local_vendor_active_time = $request->user_local_vendor_active_time;
		$settings->save();
		
		return redirect('admin/settings/localvendor_user')->with('message', 'Settings updated successfully');
	}
	
	public function localvendor_merchant(Request $request)
	{
		$settings = SiteSettings::find($request->id);
		$settings->signup_user_points = $request->signup_user_points;
		$settings->signup_vendor_commission = $request->signup_vendor_commission;
		$settings->scanning_user_points = $request->scanning_user_points;
		$settings->scanning_vendor_commission = $request->scanning_vendor_commission;
		$settings->scanning_user_period = $request->scanning_user_period;
		$settings->save();
		
		return redirect('admin/settings/localvendor_merchant')->with('message', 'Settings updated successfully');
	}
}