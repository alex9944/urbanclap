<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use DB;
use App\Models\Category;
use App\Models\Listing;
use App\Models\CategoryType;
use App\Models\ListingViews;
use App\Models\Orders;
use App\Models\OrderBooking;
use App\Models\OrderBookingDetail;
use App\Models\BillingDetail;
use App\Models\FoodMenuItem;
use App\Models\Shopproducts;
use App\Models\PaymentGateway;
use App\Models\User;
use App\Models\Notifications;
use App\Models\OrderOnlineSettings;
use App\Models\States;
use App\Models\Cities;
use App\Models\MerchantOrder;
use App\Models\PaymentGatewaySite;
use App\Models\PaymentGatewaySiteSettings;

use App\Events\OrderCompleted;
use Razorpay\Api\Api;
use Jenssegers\Agent\Agent;
use Cookie;
use Validator;
use auth;
use Input;

class ApiOnlineOrderController extends ApiController
{
	
	public function __construct(Request $request)
	{
		parent:: __construct();
		
		$this->OrderBooking = new OrderBooking;
		
	}
	
	public function addToCart(Request $request)
	{

		$return = array('status' => 0, 'msg' => 'Invalid Request', 'data' => [], 'session_id' => $this->session_id);

		$request_data = $request->json()->all();

		if( $request_data )
		{				
			if( isset($request_data['user_id']) ) {
				$this->OrderBooking->restore_cart($request_data['user_id']);
			}
			
			$response = $this->OrderBooking->add_to_cart_multi($request_data['cart']);

			$items = $this->OrderBooking->get_cart();
			
			$return['msg'] = 'Item added to the cart.';
			$return['status'] = 1;
			$return['data'] = $items;
			$return['all_item_status'] = $response;
			
			if( isset($request_data['user_id']) ) {
				$this->OrderBooking->store_cart($request_data['user_id']);
			}
		}

		return response()->json($return);
	}

	public function updateCart(Request $request)
	{

		$return = array('status' => 0, 'msg' => 'Invalid Request', 'data' => [], 'session_id' => $this->session_id);

		if( $request )
		{			
			if( isset($request->user_id) ) {
				$this->OrderBooking->restore_cart($request->user_id);
			}
			
			$response = $this->OrderBooking->update_cart($request);
			
			if( isset($request->user_id) ) {
				$this->OrderBooking->store_cart($request->user_id);
			}

			$items = $this->OrderBooking->get_cart();

			$return['msg'] = $response['msg'];
			$return['status'] = 1;
			$return['data'] = $items;
			$return['status'] = $response['status'];
		}

		return response()->json($return);
	}

	public function getCart(Request $request)
	{
		$return = array('data' => [], 'session_id' => $this->session_id);
		
		if( isset($request->user_id) ) {
			$this->OrderBooking->restore_cart($request->user_id);
		}
		
		$items = $this->OrderBooking->get_cart();

		if($items)
			$return['data'] = $items;
		
		return response()->json($return);
	}
	
	public function delete_cart($id, Request $request)
    {
    	$return = array('status' => 0, 'msg' => 'Invalid Request', 'session_id' => $this->session_id);
		
		if( isset($request->user_id) ) 
		{
			$this->OrderBooking->restore_cart($request->user_id);
		
			$this->OrderBooking->delete_cart($id);
			
			$this->OrderBooking->store_cart($request->user_id);

			$return = array('status' => 1, 'msg' => 'Item removed from cart.', 'session_id' => $this->session_id);
		}

		return response()->json($return);
    }
	
	public function delete_cart_by_listing_id(Request $request)
    {
    	$return = array('status' => 0, 'msg' => 'Invalid Request', 'session_id' => $this->session_id);
		
		if( isset($request->user_id) and isset($request->listing_id) ) 
		{
			$this->OrderBooking->restore_cart($request->user_id);
		
			$this->OrderBooking->delete_cart_by_listing_id($request->listing_id);
			
			$this->OrderBooking->store_cart($request->user_id);
			
			$cart_items = $this->OrderBooking->get_cart();

			$return = array('status' => 1, 'data' => $cart_items, 'msg' => 'Item removed from cart.', 'session_id' => $this->session_id);
		}

		return response()->json($return);
    }

    public function empty_cart(Request $request)
    {
    	$return = array('status' => 0, 'msg' => 'Invalid Request', 'session_id' => $this->session_id);
		
		if( isset($request->user_id) ) 
		{
			$this->OrderBooking->restore_cart($request->user_id);
		
			$this->OrderBooking->empty_cart();
			
			$this->OrderBooking->empty_stored_cart($request->user_id);

			$return = array('status' => 1, 'msg' => 'All items are removed from cart.', 'session_id' => $this->session_id);
		}

		return response()->json($return);
    }
	
	public function get_default_payment_gateway()
	{
		$return = array('status' => 0, 'data' => [], 'msg' => 'Invalid Request', 'session_id' => $this->session_id);
		
		$payment_gateway = PaymentGatewaySite::where('is_default', 1)->first();
		
		$gateway_settings = $payment_gateway->gateway_settings;
		
		if($gateway_settings)
		{
			$payment_gateway->gateway_settings->currency;
			$return = array('status' => 1, 'data' => $gateway_settings, 'session_id' => $this->session_id);
		}
		
		return response()->json($return);
	}
	
	/*
	Type: POST
	{"user_id":"", "razorpay_payment_id":"", "billing_detail_id":"", "phone_number":"", "email":""}
	*/

	public function create_order(Request $request)
	{
		$return = array('status' => 0, 'data' => [], 'msg' => 'Invalid Request', 'session_id' => $this->session_id);
		
		if( isset($request->user_id) ) 
		{
			$user_id = $request->user_id;
			$razorpay_payment_id = $request->razorpay_payment_id;
			$user_detail = User::find($user_id);
			
			$this->OrderBooking->restore_cart($request->user_id);
			
			$is_empty = $this->OrderBooking->is_cart_empty();
			
			if(!$is_empty)
			{				
				$billing_detail_id = $request->billing_detail_id;
				$BillingDetail = BillingDetail::where(['id' => $billing_detail_id])->first();
				
				$payment_gateway_id = 3; // Razor
				
				// create order for user
				$OrderBooking = $this->OrderBooking->create_order($BillingDetail, $request, $user_id, $payment_gateway_id, 'pending');
				
				$Orders = new Orders;
				$orders_created = $Orders->create_main_order($OrderBooking->id, $user_id, 'online_order');

				$MerchantOrder = new MerchantOrder;
				$MerchantOrder->create_merchant_order($OrderBooking->id);
				
				// update status
				if($razorpay_payment_id)
				{
					$OrderBooking->razorpay_payment_id = $razorpay_payment_id;
					
					$gateway_settings = PaymentGatewaySiteSettings::where(['payment_gateway_site_id' => $payment_gateway_id])->first();
					
					$api_key = $gateway_settings->api_key;
					$secret_key = $gateway_settings->secret_key;
					
					$api = new Api($api_key, $secret_key);
				
					$payment = $api->payment->fetch($razorpay_payment_id);
					
					if(isset($payment['status']) and $payment['status'] == 'authorized')
					{
						try {
							$payment = $api->payment->fetch($razorpay_payment_id)->capture(array('amount'=>$payment['amount'])); 
							
							// update status in subscription table				
							$OrderBooking->payment_status = 'Success';	
							$OrderBooking->online_paid_amount = $payment['amount'];
							$OrderBooking->status = $this->OrderBooking->order_status['new_order']['id'];
							
							$OrderBookingDetail = OrderBookingDetail::where('order_id', $OrderBooking->id)
													->update(['status' => $this->OrderBooking->order_detail_status['new_order']['id']]);
							
							$MerchantOrder = MerchantOrder::where('order_booking_id', $OrderBooking->id)
													->update(['payment_status' => 'Success', 'order_status' => $this->OrderBooking->merchant_order_status['new_order']['id']]);

							// send mail
							event(new OrderCompleted($OrderBooking));

						} catch (\Exception $e) {
							//$response_error = $e->getMessage();
						}
					}
					
					$OrderBooking->save();
				}
				
				$this->OrderBooking->empty_cart();	
				$this->OrderBooking->empty_stored_cart($user_id);
				
				$return = array('status' => 1, 'data' => array('order_id' => $OrderBooking->id), 'msg' => 'Order created', 'session_id' => $this->session_id);
			}
		}
		
		return response()->json($return);
	}

	public function getOrders(){
		$response=array();
		$user_id=$_GET['id'];
		$online_orders = OrderBooking::where(['user_id' => $user_id, 'order_type' => 'online_order'])->get();
		if(sizeof($online_orders)>0){
			foreach ($online_orders as $key => $value) {
				$value['total']=OrderBookingDetail::where('order_id',$value->id)->sum('total_amount');
				$value['listing_name']=$value->listing->title;
			}
			$data['order']=$online_orders;
		}
		else{
			$data['order']='No bookings found ';
		}
		
		$responsecode = 200;        
		$header = array (
			'Content-Type' => 'application/json; charset=UTF-8',
			'charset' => 'utf-8'
		);       
		return response()->json($data , $responsecode, $header, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
		
	}
	
	public function get_states()
	{
		$country_id = $this->default_country->id;
		
		$states = States::where('country_id', $country_id)->get();
		
		$return = array('status' => 1, 'data' => $states, 'msg' => 'Success');
		
		return response()->json($return);
	}
	
	public function get_cities($state_id)
	{		
		$return = array('status' => 0, 'data' => '', 'msg' => 'Invalid Request');
				
		if($state_id) 
		{
			$cities = Cities::where('state_id', $state_id)->get();			
			$return = array('status' => 1, 'data' => $cities, 'msg' => 'Success');
		}
		
		return response()->json($return);
	}
	
	public function get_billing_address($user_id)
	{
		$return = array('status' => 0, 'data' => '', 'msg' => 'Invalid Request');
		
		if($user_id)
		{
			$BillingDetail = BillingDetail::where('user_id', $user_id)->get();
			
			$new_billing = [];
			foreach($BillingDetail as $address_detail)
			{
				$city = $address_detail->delivery_city->toArray();
				$state = $address_detail->delivery_state->toArray();
				$country = $address_detail->delivery_country->toArray();
				
				$address_detail = $address_detail->toArray();
				
				foreach($address_detail as $fld => $value) {
					if(strpos($fld, 'b_') === 0)
						unset($address_detail[$fld]);
				}
				
				$new_billing[] = $address_detail;
			}
			
			if($new_billing) {
				$return['data'] = $new_billing;
				$return['msg'] = 'Success';
				$return['status'] = 1;
			}
		}
		
		return response()->json($return);
	}
	
	public function add_billing_address(Request $request)
	{
		$return = array('status' => 0, 'data' => '', 'msg' => 'Invalid Request');
		
		$BillingDetail = new BillingDetail;
		$billing_rules = $BillingDetail->get_adding_rules();
		
		$validator = \Validator::make($request->all(), $billing_rules['rules'], $billing_rules['messages']);

        if ($validator->fails()) {
            $return['data'] = $validator->errors()->toArray();
			$return['msg'] = 'Validation Issue';
			return response()->json($return);
        }
		
		$BillingDetail->user_id = $request->user_id;
		$BillingDetail->s_address_1 = $request->s_address_1;
		$BillingDetail->s_address_2 = $request->s_address_2;
		$BillingDetail->s_city = $request->s_city;
		$BillingDetail->s_state = $request->s_state;
		$BillingDetail->s_country = $this->default_country->id;
		$BillingDetail->s_pincode = $request->s_pincode;
		$BillingDetail->s_name = $request->s_name;
		$BillingDetail->s_company = '';
		$BillingDetail->save();
		
		$return = array('status' => 1, 'data' => $BillingDetail, 'msg' => 'Success');
		
		return response()->json($return);
	}
	
	public function update_billing_address(Request $request)
	{
		$return = array('status' => 0, 'data' => '', 'msg' => 'Invalid Request');
		
		if($request->id)
		{
			$BillingDetail = BillingDetail::find($request->id);
			$billing_rules = $BillingDetail->get_adding_rules();
			
			$validator = \Validator::make($request->all(), $billing_rules['rules'], $billing_rules['messages']);

			if ($validator->fails()) {
				$return['data'] = $validator->errors()->toArray();
				$return['msg'] = 'Validation Issue';
				return response()->json($return);
			}
			
			//$BillingDetail->user_id = $request->user_id;
			$BillingDetail->s_address_1 = $request->s_address_1;
			$BillingDetail->s_address_2 = $request->s_address_2;
			$BillingDetail->s_city = $request->s_city;
			$BillingDetail->s_state = $request->s_state;
			$BillingDetail->s_country = $this->default_country->id;
			$BillingDetail->s_pincode = $request->s_pincode;
			$BillingDetail->s_name = $request->s_name;
			$BillingDetail->s_company = '';
			$BillingDetail->save();
			
			$return = array('status' => 1, 'data' => '', 'msg' => 'Success');
		}
		
		return response()->json($return);
	}
}