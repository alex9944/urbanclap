<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\User;
use App\Models\MerchantServices;
use App\Models\AppointmentBookingSettings;
use App\Models\TableBookingSettings;

class VendorSubscription extends Model
{
    protected $table = 'vendor_subscriptions';
	
	var $free_subscription_pricing_id = 5; // subscription_pricing table
	var $plan_change_free_subscription_pricing_id = 4;
	
	public function getExpiredDate($subscribed_date, $term)
	{
		
		if($term->term_type == 'month') {
			$time = strtotime($subscribed_date);
			$term_value = $term->term_value;
			$add_text = "+1 month";
			if($term_value > 1)
				$add_text = "+".$term_value." months";
			$expired_date = date('Y-m-d H:i:s', strtotime($add_text, $time));
			
		}
		else { //if($term->process_term == 'year') {
			$time = strtotime($subscribed_date);
			$expired_date = date('Y-m-d H:i:s', strtotime("+1 year", $time));
		}
		
		return $expired_date;
	}
	
	public function merchant()
	{
		return $this->belongsTo('App\Models\User', 'merchant_id');
	}
	
	public function vendor()
	{
		return $this->belongsTo('App\Models\Merchants', 'merchant_id');
	}
	
	public function currency()
	{
		return $this->belongsTo('App\Models\Currency', 'currency_id');
	}
	
	public function payment_gateway()
	{
		return $this->belongsTo('App\Models\PaymentGatewaySite', 'payment_gateway_id');
	}
	
	public function subscription_pricing()
	{
		return $this->belongsTo('App\Models\SubscriptionPricing', 'subscription_pricing_id');
	}
	
	public function _addSubscription($request, $merchant_id, $subscribed_date, $currency_id, $payment_status)
	{	
		$payment_gateway_id = $request->payment_gateway_id;
		$merchant_user = auth()->user();
		
		$plan_detail = array();
		$subsObj = $this->newInstance();
		
		$subscription_pricing = SubscriptionPricing::find($request->subscription_pricing_id);
		$subscription_category = Category::find($merchant_user->category_id);
		$subscription_term = SubscriptionTerm::find($request->subscription_term_id);
		$plan_detail['subscription_pricing'] = array('plan' => $subscription_pricing->plan->title, 'price' => $subscription_pricing->price, 'duration' => $subscription_pricing->duration->title, 'duration_type' => $subscription_pricing->duration->days_month, 'f_id' => $subscription_pricing->f_id);
		$plan_detail['subscription_category'] = array('title' => $subscription_category->c_title, 'category_type' => $subscription_category->category_type);
		$plan_detail['subscription_term'] = array('display_value' => $subscription_term->display_value);
		
		$unit_price = $subscription_pricing->price;
		$paid_amount = $unit_price;
		if($paid_amount != 0)
		{
			if($subscription_term->term_type == 'month') {
				$paid_amount = $unit_price*$subscription_term->term_value;
			} else {
				$paid_amount = $unit_price*$subscription_term->term_value*12;
			}
		}
		
		$expired_date = $this->getExpiredDate($subscribed_date, $subscription_term);
		
		// subscription order creation
		$subsObj->merchant_id = $merchant_user->id;
		$subsObj->subscription_pricing_id = $request->subscription_pricing_id;
		$subsObj->subscription_term_id = $request->subscription_term_id;
		$subsObj->currency_id = $currency_id;
		$subsObj->payment_gateway_id = $payment_gateway_id;
		$subsObj->unit_price = $unit_price;
		$subsObj->paid_amount = $paid_amount;
		$subsObj->payment_status = $payment_status;
		$subsObj->subscribed_date = $subscribed_date; 
		$subsObj->expired_date = $expired_date;
		//$subsObj->renewed_date = null;		
		$subsObj->plan_detail = json_encode($plan_detail);
		$subsObj->save();
		
		$return = array(
			'subscription_id'	=> $subsObj->id,
			'expired_date'		=> $subsObj->expired_date
		);
		
		return $return;
	}
	
	public function _updateUserSubscription($subscription_id, $expired_date)
	{
		$merchant_user = auth()->user();
		
		$merchant_user->last_subscription_id = $subscription_id;
		$merchant_user->next_renewal_date = $expired_date;
		$merchant_user->save();
		
	}
	
	public function _processFreePlan()
	{
		$merchant_user = auth()->user();
		
		MerchantServices::where('merchant_id', $merchant_user->id)
							->update(['is_enable' => 0]);	

		AppointmentBookingSettings::where('merchant_id', $merchant_user->id)
							->update(['status' => 0]);	

		TableBookingSettings::where('merchant_id', $merchant_user->id)
							->update(['status' => 0]);
	}
}
