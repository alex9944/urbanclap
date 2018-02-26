<?php

namespace App\Http\Controllers\Merchant;
use DB;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\VendorSubscription;
use App\Models\User;
use App\Models\SubscriptionPricing;
use App\Models\SubscriptionFeatures;
use App\Models\Category;
use App\Models\CategoryType;
use App\Models\PaymentGatewaySite;
use App\Models\PaymentGatewaySiteSettings;
use App\Models\SubscriptionTerm;
use App\Models\EbsLibrary;
use App\Models\EbsTransactionSite;
use App\Models\RazorLibrary;

use Razorpay\Api\Api;
use App\Http\Controllers\Controller;


class SubscriptionController extends Controller
{	
	public function __construct()
	{
		parent::__construct();
	}
	
    // form view
	public function change_plan()
    {		
	   
		$merchant_id = (auth()->check()) ? auth()->user()->id : null;
		
		$subscribers = VendorSubscription::where('merchant_id', $merchant_id)->orderBy('updated_at', 'Desc')->get();
	   
		$subscriber = User::where('id', $merchant_id)->first();
		$last_subscription = $subscriber->last_subscription;
		
		$subscription_category = Category::where(['parent_id' => 0, 'c_id' => $subscriber->category_id])->first();
		$subscription_pricings = SubscriptionPricing::where('is_hidden', 0)->orderBy('subscription_pricing.order_by', 'ASC')->get();
		$subscription_features = SubscriptionFeatures::all();
		$payment_gateways = PaymentGatewaySite::where(['status' => 1])->get();
		$subscription_terms = SubscriptionTerm::all();
		
		$category_type_arr = array();
		$category_types = [];
		if($subscription_category->category_type)
			$category_type_arr = json_decode($subscription_category->category_type);
		if(!empty($category_type_arr)) 
		{
			$category_types = CategoryType::whereIn('id', $category_type_arr)->get();
		}
		
		$payment_gateway_id = $this->payment_gateway_id;
		$cash_on_delivery = $this->cash_on_delivery;
		$plan_change_free_subscription_pricing_id = $this->plan_change_free_subscription_pricing_id;
		$free_subscription_pricing_id = $this->free_subscription_pricing_id;
		
		$compact_array = compact('last_subscription', 'subscription_pricings', 'subscription_features', 'subscription_category', 'category_types', 'payment_gateways', 'subscription_terms', 'subscribers', 'payment_gateway_id', 'cash_on_delivery', 'free_subscription_pricing_id', 'plan_change_free_subscription_pricing_id');
		
		return $this->_loadMerchantView('subscription.change_plan', $compact_array);
	   
		//return view('panels.merchant.subscription.change_plan', compact('last_subscription', 'subscription_pricings', 'subscription_features', 'subscription_category', 'category_types', 'payment_gateways', 'subscription_terms'));
    }
	
	// form submit
	public function change_subscription_plan_confirm(Request $request)
	{
		$rules = [
			'subscription_pricing_id'	=> 'required',//|if_selected_is_same_plan|if_selected_is_free
			'subscription_term_id'	=> 'required',
			'payment_gateway_id'	=> 'required',
		];		
		$messages = [
			'payment_gateway_id.required'	=> 'Select payment method',
			'subscription_pricing_id.required'	=> 'Select subscription plan',
			'subscription_pricing_id.if_selected_is_free'	=> 'You can not change to free plan',
			'subscription_pricing_id.if_selected_is_same_plan'	=> 'You can not change to your current plan. Please click "renewal button" Or Change another plan.',
			'subscription_term_id.required'	=> 'Select subscription duration'
		];
		
		$merchant_user = auth()->user();
		$merchant_id = $merchant_user->id;
		$plan_change_free_subscription_pricing_id = $this->plan_change_free_subscription_pricing_id;
		$last_subscription = $merchant_user->last_subscription;
		$last_subscription_pricing = $merchant_user->last_subscription->subscription_pricing;
		
		if($request->subscription_pricing_id == $plan_change_free_subscription_pricing_id) // Free service
		{
			unset($rules['payment_gateway_id']);			
			$request->subscription_term_id = 3; // 1 year
			$request->payment_gateway_id = 0;
		}
		
		/*\Validator::extendImplicit('if_selected_is_free', function ($attribute, $value, $parameters, $validator) use($plan_change_free_subscription_pricing_id) {
			return $value != $plan_change_free_subscription_pricing_id;
		});
		\Validator::extendImplicit('if_selected_is_same_plan', function ($attribute, $value, $parameters, $validator) use($subscription_pricing) {
			return $value != $subscription_pricing->id;
		});*/
		
		$this->validate($request, $rules, $messages);
		
		// validation ok
		
		$subscriber = $merchant_user;
		
		$subscribed_date = date('Y-m-d H:i:s'); // today date
		$payment_gateway_id = $request->payment_gateway_id;
		$subscription_pricing_id = $request->subscription_pricing_id;
		$subscription_term_id = $request->subscription_term_id;
		
		$subsObj = new VendorSubscription;
		
		$subscription_category = Category::where(['parent_id' => 0, 'c_id' => $subscriber->category_id])->first();
		$category_type_arr = array();
		$category_types = [];
		if($subscription_category->category_type)
			$category_type_arr = json_decode($subscription_category->category_type);
		if(!empty($category_type_arr)) 
		{
			$category_types = CategoryType::whereIn('id', $category_type_arr)->get();
		}
		
		$subscription_pricing = SubscriptionPricing::find($request->subscription_pricing_id);
		$features = explode(',', $subscription_pricing->f_id);
		$subscription_features = SubscriptionFeatures::whereIn('id', array_values($features))->get();
		$subscription_term = SubscriptionTerm::find($request->subscription_term_id);
		
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
		
		// for same plan
		if($subscription_pricing->id == $last_subscription_pricing->id) {
			if(($last_subscription->expired_date) > $subscribed_date) // if expired date is higher than today date
				$subscribed_date = $last_subscription->expired_date;
		}
		
		$expired_date = $subsObj->getExpiredDate($subscribed_date, $subscription_term);
		
		$compact_array = compact('request', 'subscription_pricing', 'subscription_term', 'subscription_features', 'category_types', 'subscribed_date', 'expired_date', 'paid_amount');
		
		return $this->_loadMerchantView('subscription.change_plan_confirm', $compact_array);
	}
	
	// form submit
	public function change_subscription_plan(Request $request)
	{
		$rules = [
			'subscription_pricing_id'	=> 'required|numeric',//|if_selected_is_free
			'subscription_term_id'	=> 'required|numeric',
			'subscribed_date'	=> 'required',
			'payment_gateway_id'	=> 'required|numeric',
		];		
		$messages = [
			'payment_gateway_id.required'	=> 'Select payment method',
			'subscription_pricing_id.required'	=> 'Select subscription plan',
			'subscription_pricing_id.if_selected_is_free'	=> 'You can not change to free plan',
			'subscription_pricing_id.if_selected_is_same_plan'	=> 'You can not change to your current plan. Please click "renewal button" Or Change another plan.',
			'subscription_term_id.required'	=> 'Select subscription duration',
			'subscribed_date.required'	=> 'Invalid subscribed date',
		];
		
		/*if($request->subscription_pricing_id == $plan_change_free_subscription_pricing_id) // Free service
		{
			unset($rules['payment_gateway_id']);
			
			$expired_time = strtotime($last_subscription->expired_date);
			$before_expired_time = date('Y-m-d H:i:s', strtotime("-5 days", $expired_time));
			$current_time = date('Y-m-d H:i:s');
			
			if($before_expired_time < $current_time)
				$rules['subscription_pricing_id'] = 'required';
			
			$request->subscription_term_id = 3; // 1 year
		}
		\Validator::extendImplicit('if_selected_is_free', function ($attribute, $value, $parameters, $validator) use($plan_change_free_subscription_pricing_id) {
			return $value != $plan_change_free_subscription_pricing_id;
		});
		\Validator::extendImplicit('if_selected_is_same_plan', function ($attribute, $value, $parameters, $validator) use($subscription_pricing) {
			return $value != $subscription_pricing->id;
		});
		
		$this->validate($request, $rules, $messages);
		*/
		
		$validator = \Validator::make($request->all(), $rules, $messages);

		if ($validator->fails()) {
			return redirect('merchant/change-subscription-plan')
						->withErrors($validator)
						->withInput();
		}
		
		// validation ok
		
		$merchant_user = auth()->user();
		$merchant_id = $merchant_user->id;
		$plan_change_free_subscription_pricing_id = $this->plan_change_free_subscription_pricing_id;
		$last_subscription = $merchant_user->last_subscription;
		$subscription_pricing = $merchant_user->last_subscription->subscription_pricing;
		
		
		$subscribed_date = $request->subscribed_date;
		$payment_gateway_id = $request->payment_gateway_id;
		$subsObj = new VendorSubscription;
		
		if(	$request->subscription_pricing_id == $plan_change_free_subscription_pricing_id
			|| $payment_gateway_id == $this->cash_on_delivery
		) // Free service || COD
		{
			$currency_id = $this->currency->id;
			$payment_status = 'Success';			
		}
		else // payment
		{
			$gateway_settings = PaymentGatewaySiteSettings::where(['payment_gateway_site_id' => $payment_gateway_id])->first();
			$currency_id = $gateway_settings->currency->id;
			$payment_status = 'Pending';
		}
		
		// create subscription
		$response = $subsObj->_addSubscription($request, $merchant_id, $subscribed_date, $currency_id, $payment_status);
		
		if($request->subscription_pricing_id == $plan_change_free_subscription_pricing_id) // Free service
		{
			$subscription_id = $response['subscription_id'];
			$expired_date = $response['expired_date'];
			
			// update user table
			$subsObj->_updateUserSubscription($subscription_id, $expired_date);
			
			$subsObj->_processFreePlan();
			
			// redirect
			return redirect('merchant/change-subscription-plan')->with('message','You have limitted access on your profile, please upgrade with higher plan to access all our services. Thank you.');
		}	
		else if($payment_gateway_id == $this->cash_on_delivery) // COD
		{
			$subscription_id = $response['subscription_id'];
			$expired_date = $response['expired_date'];
			
			// update user table
			$subsObj->_updateUserSubscription($subscription_id, $expired_date);
			
			// redirect
			return redirect('merchant/change-subscription-plan')->with('message','Thank you. Our staff will come and collect your billing amount.');
		}
		else // payment
		{			
			$subscription_id = $response['subscription_id'];
			
			$encrypt_id = base64_encode('JVinoNeedifo_'.$subscription_id);
			
			return redirect('merchant/subscription/payby-razor/'.$encrypt_id);
		}
	}
	
	public function complete_subscription($encrypt_order_id)
	{
		$decrypt_id = base64_decode($encrypt_order_id);
		$subscription_id = (int) str_replace('JVinoNeedifo_', '', $decrypt_id);
		
		$order = [];
		if($subscription_id)
		{
			$merchant_user = auth()->user();
			
			$VendorSubscription = new VendorSubscription;
			$order = VendorSubscription::where(['id' => $subscription_id, 'merchant_id' => $merchant_user->id])->first();
			
			$subscription_pricing = SubscriptionPricing::find($order->subscription_pricing_id);
			$features = explode(',', $subscription_pricing->f_id);
			$subscription_features = SubscriptionFeatures::whereIn('id', array_values($features))->get();
			$subscription_term = SubscriptionTerm::find($order->subscription_term_id);
			
			$subscription_category = Category::where(['parent_id' => 0, 'c_id' => $merchant_user->category_id])->first();
			$category_type_arr = array();
			$category_types = [];
			if($subscription_category->category_type)
				$category_type_arr = json_decode($subscription_category->category_type);
			if(!empty($category_type_arr)) 
			{
				$category_types = CategoryType::whereIn('id', $category_type_arr)->get();
			}
			
			$compact_array = compact('encrypt_order_id', 'subscription_pricing', 'subscription_term', 'subscription_features', 'category_types', 'order');
		
		return $this->_loadMerchantView('subscription.complete_subscription', $compact_array);
		}
		
		return $this->_loadMerchantView('subscription.thankyou', compact('order'));
	}
	
	public function payby_razor($encrypt_order_id)
	{
		$decrypt_id = base64_decode($encrypt_order_id);
		$subscription_id = (int) str_replace('JVinoNeedifo_', '', $decrypt_id);
		$merchant_id = (auth()->check()) ? auth()->user()->id : null;
		
		$order = [];
		if($subscription_id)
		{
			$VendorSubscription = new VendorSubscription;
			$order = VendorSubscription::where(['id' => $subscription_id, 'merchant_id' => $merchant_id])->first();
			$billing_detail = $order->billing_detail;
			if($order and $order->payment_status == 'Pending')
			{
				$customer_name = auth()->user()->first_name;
				$customer_email = auth()->user()->email;
				$phone_number = auth()->user()->mobile_no;
				
				$payObj = new RazorLibrary;
				
				$payObj->addAPIFilelds();
				$payObj->addField('order_id', $subscription_id);
				$payObj->addField('return_url', url('merchant/subscription/payment-response')); //return url
				$payObj->addField('customer_name', trim($customer_name));
				$payObj->addField('description', 'Subscription');
				$payObj->addField('customer_email', $customer_email);
				$payObj->addField('phone', $phone_number);
				$payObj->addField('amount', $order->paid_amount*100);//.'.00'
				
				/*$razor_html = $payObj->generateFormOrder();
				
				return $this->_loadMerchantView('subscription.payby_razor', compact('razor_html'));*/
				
				$razor_fields = $payObj->fields;

				return $this->_loadMerchantView('subscription.payby_razor_manual', compact('razor_fields', 'encrypt_order_id'));	
			}
		}
		
		return $this->_loadMerchantView('subscription.thankyou', compact('order'));
	}
	
	public function payment_response()
	{
		$payment_gateway_id = 3;	// razor
		$gateway_settings = PaymentGatewaySiteSettings::where(['payment_gateway_site_id' => $payment_gateway_id])->first();
		
		$api_key = $gateway_settings->api_key;
		$secret_key = $gateway_settings->secret_key;
		
		$input = \Input::all();
		
		$response_error = '';
		$response_success = '';

        if(count($input)  && !empty($input['razorpay_payment_id']) && !empty($input['order_id'])) 
		{
			$VendorSubscription = VendorSubscription::find($input['order_id']);
			$old_razorpay_payment_id = $VendorSubscription->razorpay_payment_id;
			
			if($VendorSubscription)
			{
				$VendorSubscription->razorpay_payment_id = $input['razorpay_payment_id'];			
				
				$api = new Api($api_key, $secret_key);
				
				$payment = $api->payment->fetch($input['razorpay_payment_id']);
				
				if($payment)
				{
				
					if($payment['status'] == 'authorized')
					{
					
						try {
							$payment = $api->payment->fetch($input['razorpay_payment_id'])->capture(array('amount'=>$payment['amount'])); 
							
							// update status in subscription table				
							$VendorSubscription->payment_status = 'Success';	
							$VendorSubscription->online_paid_amount = $payment['amount'];
							
							// update user table
							$VendorSubscription->_updateUserSubscription($input['order_id'], $VendorSubscription->expired_date);
							
							$VendorSubscription->_processFreePlan();
							
							// send mail
							//event(new SubscriptionRenewal($VendorSubscription));

						} catch (\Exception $e) {
							$response_error = $e->getMessage();
						}
					}
					
					$response_success = '<div class="col-sm-8 col-sm-offset-3"><div class="col-sm-6"><h6>Order ID :</h6></div><div class="col-sm-6"><h6>'.$VendorSubscription->id.'</h6></div><div class="col-sm-6"><h6>Transaction ID :</h6></div><div class="col-sm-6"><h6>'.$payment['id'].'</h6></div><div class="col-sm-6"><h6>Payment Status :</h6></div><div class="col-sm-6"><h6>'.$payment['status'].'</h6></div><div class="col-sm-6"></div><div class="col-sm-6"></div></div>';
				}
				
				if( !$old_razorpay_payment_id )
					$VendorSubscription->save();
				
			}

            // Do something here for store payment details in database...
        }
		
		return $this->_loadMerchantView('subscription.payment_response', compact('response_success', 'response_error'));
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
				$ebsObj->addField('return_url', url('/merchant/subscription/ebs-thankyou')); //ebs return url
				$ebsObj->addField('name', trim($user->first_name . ' ' . $user->last_name));
				$ebsObj->addField('address', $user->address);
				$ebsObj->addField('postal_code', preg_replace('/\s+/', '', $user->postal_code));
				$ebsObj->addField('city', $user->city_name);
				$ebsObj->addField('email', $user->email);
				$ebsObj->addField('phone', $user->mobile_no);
				$ebsObj->addField('description', $merchant_id);
				$ebsObj->addField('amount', $subscription->paid_amount);//.'.00'
				
				$ebs_html = $ebsObj->generateForm();
				
				return view('panels.merchant.subscription.payby_ebs', compact('ebs_html'));
			}
		}
		
		return view('panels.merchant.subscription.ebs_thankyou', compact('response_html'));
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
						
						$user = User::find($subscription->merchant_id);
						$user->last_subscription_id = $subscription->id;
						$user->next_renewal_date = $subscription->expired_date;
						$user->save();
							
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
		return view('panels.merchant.subscription.ebs_thankyou', compact('response_html'));

    }
	
}