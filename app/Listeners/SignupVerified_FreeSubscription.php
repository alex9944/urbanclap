<?php

namespace App\Listeners;

use App\Events\SignupVerified;

use Mail;

use App\Models\Country;
use App\Models\VendorSubscription;
use App\Models\SubscriptionPricing;
use App\Models\Category;
use App\Models\SubscriptionTerm;
use App\Models\SiteSettings;

class SignupVerified_FreeSubscription
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  SignupVerified  $event
     * @return void
     */
    public function handle(SignupVerified $event)
    {
        $user = $event->user;
		
		if ($user->hasRole('merchant'))
			$this->_addFreeSubscription($user);
    }
	
	protected function _addFreeSubscription($user)
	{	
		$settings = SiteSettings::first();
		$currency = $settings->currency;
		
		$plan_detail = array();
		$subsObj = new VendorSubscription;
		
		// Free subscription_pricing_id and subscription_term_id -> get from table manually
		$subscription_pricing_id = $subsObj->free_subscription_pricing_id; // Free plan
		$subscription_term_id = 1; // 1 month
		
		$subscription_pricing = SubscriptionPricing::find($subscription_pricing_id);
		$subscription_category = Category::find($user->category_id);
		$subscription_term = SubscriptionTerm::find($subscription_term_id);
		$plan_detail['subscription_pricing'] = array('plan' => $subscription_pricing->plan->title, 'price' => $subscription_pricing->price, 'duration' => $subscription_pricing->duration->title, 'duration_type' => $subscription_pricing->duration->days_month, 'f_id' => $subscription_pricing->f_id);
		$plan_detail['subscription_category'] = array('title' => $subscription_category->c_title, 'category_type' => $subscription_category->category_type);
		$plan_detail['subscription_term'] = array('display_value' => $subscription_term->display_value);
		
		$unit_price = $subscription_pricing->price;
		$paid_amount = $unit_price;
		
		$today_date = date('Y-m-d H:i:s'); // today date
		$expired_date = $subsObj->getExpiredDate($today_date, $subscription_term);
		
		// subscription order creation
		$subsObj->merchant_id = $user->id;
		$subsObj->subscription_pricing_id = $subscription_pricing_id;
		$subsObj->subscription_term_id = $subscription_term_id;
		$subsObj->currency_id = $currency->id;
		$subsObj->payment_gateway_id = 0;
		$subsObj->unit_price = $unit_price;
		$subsObj->paid_amount = 0;
		$subsObj->payment_status = 'Success';
		$subsObj->subscribed_date = $today_date; 
		$subsObj->expired_date = $expired_date;
		//$subsObj->renewed_date = null;		
		$subsObj->plan_detail = json_encode($plan_detail);
		$subsObj->save();
		
		$user->last_subscription_id = $subsObj->id;
		$user->next_renewal_date = $expired_date;
		$user->save();
		
	}
	
	protected function _addListing($user)
	{	
		$country = Country::where('default', 1)->first();
		
		$listing = new Listing;				
		$listing->user_id = $user->id;
		$listing->l_id = 1;// eng - default $request->language_id;
		$listing->m_c_id=$user->category_id;
		$listing->c_id=$country->id;
		$listing->status='Disable';//'Enable';//
		$listing->save();
		
	}
}