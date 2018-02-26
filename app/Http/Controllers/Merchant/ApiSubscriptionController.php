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


class ApiSubscriptionController extends Controller
{	
	public function __construct()
	{
		parent::__construct();
	}
	
    // form view
	public function change_plan($merchant_id)
    {		
	   
	
		
		$return['subscribers'] = VendorSubscription::where('merchant_id', $merchant_id)->orderBy('updated_at', 'Desc')->get();
	   
		$subscriber = User::where('id', $merchant_id)->first();
		if($subscriber)
		{
			$return['last_subscription'] = $subscriber->last_subscription;
			
			$return['subscription_category'] = $subscription_category = Category::where(['parent_id' => 0, 'c_id' => $subscriber->category_id])->first();
			$return['subscription_pricings'] = SubscriptionPricing::where('is_hidden', 0)->orderBy('subscription_pricing.order_by', 'ASC')->get();
			$return['subscription_features'] = SubscriptionFeatures::all();
			$return['payment_gateways'] = PaymentGatewaySite::where(['status' => 1])->get();
			$return['subscription_terms'] = SubscriptionTerm::all();
			
			$category_type_arr = array();
			 $return['category_types'] = [];
			if($subscription_category->category_type)
				$category_type_arr = json_decode($subscription_category->category_type);
			if(!empty($category_type_arr)) 
			{
				 $return['category_types'] = CategoryType::whereIn('id', $category_type_arr)->get();
			}
			
			$return['payment_gateway_id'] = $this->payment_gateway_id;
			$return['cash_on_delivery'] = $this->cash_on_delivery;
			$return['plan_change_free_subscription_pricing_id'] = $this->plan_change_free_subscription_pricing_id;
			$return['free_subscription_pricing_id'] = $this->free_subscription_pricing_id;
		
		
		
		    $return['status'] = 1;	
			$return['msg'] = "Success";
			return response()->json($return);	
		}	

		$return['status'] = 0;	
		$return['msg'] = "Invalid user";
		return response()->json($return);
	
    }
	
	
	// form submit
	public function subscription_plan_confirm(Request $request)
	{
		$rules = [
		    'merchant' => 'required',
			'subscription_pricing_id'	=> 'required|numeric',//|if_selected_is_free
			'subscription_term_id'	=> 'required|numeric',			
			'payment_gateway_id'	=> 'required|numeric',
		];		
		$messages = [
		    'merchant.required'	=> 'Merchant id is required',
			'payment_gateway_id.required'	=> 'Select payment method',
			'subscription_pricing_id.required'	=> 'Select subscription plan',
			'subscription_pricing_id.if_selected_is_free'	=> 'You can not change to free plan',
			'subscription_pricing_id.if_selected_is_same_plan'	=> 'You can not change to your current plan. Please click "renewal button" Or Change another plan.',
			'subscription_term_id.required'	=> 'Select subscription duration',
			
		];
		
		
		
		$validator = \Validator::make($request->all(), $rules, $messages);
				
				 if ($validator->fails()) {
					$return['data'] = $validator->errors()->toArray();
					$return['msg'] = 'Validation Issue';
					return response()->json($return);
				}
				
		
		// validation ok
		$merchant_user = User::find($request->merchant);		
		$merchant_id = $merchant_user->id;
		$plan_change_free_subscription_pricing_id = $this->plan_change_free_subscription_pricing_id;
		$last_subscription = $merchant_user->last_subscription;
		$subscription_pricing = $merchant_user->last_subscription->subscription_pricing;
		
		if($request->subscription_pricing_id == $plan_change_free_subscription_pricing_id) // Free service
		{
			unset($rules['payment_gateway_id']);			
			$request->subscription_term_id = 3; // 1 year
			$request->payment_gateway_id = 0;
		}
		$subscribed_date = date('Y-m-d H:i:s'); // today date
		// for same plan
		if($subscription_pricing->id == $last_subscription_pricing->id) {
			if(($last_subscription->expired_date) > $subscribed_date) // if expired date is higher than today date
				$subscribed_date = $last_subscription->expired_date;
		}
		
		//$expired_date = $subsObj->getExpiredDate($subscribed_date, $subscription_term);
		
		
	
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
			$return['status'] = 'Free service is activated';	
			$return['msg'] = "Success";
			return response()->json($return);
			//return redirect('merchant/change-subscription-plan')->with('message','You have limitted access on your profile, please upgrade with higher plan to access all our services. Thank you.');
		}	
		else if($payment_gateway_id == $this->cash_on_delivery) // COD
		{
			$subscription_id = $response['subscription_id'];
			$expired_date = $response['expired_date'];
			
			// update user table
			$subsObj->_updateUserSubscription($subscription_id, $expired_date);
			
			// redirect
			$return['status'] = 'COD  service activated';	
			$return['msg'] = "Success";
			return response()->json($return);
			//return redirect('merchant/change-subscription-plan')->with('message','Thank you. Our staff will come and collect your billing amount.');
		}
		else // payment
		{			
			$subscription_id = $response['subscription_id'];			
			$return['status'] = $subscription_id;	
			$return['msg'] = "Success";
			return response()->json($return);
			//return redirect('merchant/subscription/payby-razor/'.$encrypt_id);
		}
	}
	
	
	
	
	
	public function payment_response(Request $request)
	{
		$payment_gateway_id = 3;	// razor
		$gateway_settings = PaymentGatewaySiteSettings::where(['payment_gateway_site_id' => $payment_gateway_id])->first();
		
		$api_key = $gateway_settings->api_key;
		$secret_key = $gateway_settings->secret_key;
		
		//$input = \Input::all();
		
		$response_error = '';
		$response_success = '';
		$return['status'] = 0;	
		$return['msg'] = "Invalid Request";

        if(count($request)  && !empty($request->razorpay_payment_id) && !empty($request->order_id)) 
		{
			$VendorSubscription = VendorSubscription::find($request->order_id);
			$old_razorpay_payment_id = $VendorSubscription->razorpay_payment_id;
			
			if($VendorSubscription)
			{
				$VendorSubscription->razorpay_payment_id = $request->razorpay_payment_id;			
				
				$api = new Api($api_key, $secret_key);
				
				$payment = $api->payment->fetch($request->razorpay_payment_id);
				
				if($payment)
				{
				
					if($payment['status'] == 'authorized')
					{
					
						try {
							$payment = $api->payment->fetch($request->razorpay_payment_id)->capture(array('amount'=>$payment['amount'])); 
							
							// update status in subscription table				
							$VendorSubscription->payment_status = 'Success';	
							$VendorSubscription->online_paid_amount = $payment['amount'];
							
							// update user table
							$VendorSubscription->_updateUserSubscription($request->order_id, $VendorSubscription->expired_date);
							
							$VendorSubscription->_processFreePlan();
							
							// send mail
							//event(new SubscriptionRenewal($VendorSubscription));

						} catch (\Exception $e) {
							$response_error = $e->getMessage();
						}
					}
					
					$return['OrderID'] =$VendorSubscription->id;
					$return['TransactionID'] =$payment['id'];
					$return['PaymentStatus'] =$payment['status'];
					$return['status'] = 1;	
					$return['msg'] = "Success";
				}
				
				if( !$old_razorpay_payment_id )
					$VendorSubscription->save();
				
			}
			else
			{
				$return['msg'] = 'Invalid ID';	
			}
            // Do something here for store payment details in database...
        }
		
		   
			
		return response()->json($return);
		//return $this->_loadMerchantView('subscription.payment_response', compact('response_success', 'response_error'));
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