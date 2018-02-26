<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Listing;
use App\Models\FoodMenuItem;
use App\Models\States;
use App\Models\Cities;
use App\Models\Orders;
use App\Models\OrderBooking;
use App\Models\OrderBookingDetail;
use App\Models\BillingDetail;
use App\Models\OrderOnlineSettings;
use App\Models\PaymentGatewaySiteSettings;
use App\Models\PaymentGatewaySite;
use App\Models\PaypalLibray;
use App\Models\Shopproducts;
use App\Models\ShopOrderBookingDetail;
use App\Models\User;
use App\Models\FoodMenu;
use App\Models\Category;
use App\Models\Notifications;
use App\Models\MerchantOrder;
use App\Models\RazorLibrary;

use App\Events\OrderCompleted;
use Razorpay\Api\Api;
use Response;
use Mail;
use DB;

class OnlineOrderController extends Controller
{
	public function __construct(Request $request)
	{
		parent:: __construct();
		
		$this->OrderBooking = new OrderBooking;
		
	}
	
	public function ajaxAddToCart(Request $request)
	{

		$return = array('status' => 0, 'msg' => 'Invalid Request', 'data' => '');
		
		

		if( $request->item_id )
		{	
			$request->user_id = (auth()->check()) ? auth()->user()->id : null;
			
			$return = $this->OrderBooking->add_to_cart($request);
			
			if($return['status'] == 1)
			{
				$items = $this->OrderBooking->get_cart_only();

				$view = \View::make('pages.listing_detail.cart', ['online_order_items' => $items]);
				$contents = $view->render();

				$return['data'] = $contents;
			}
		}

		return response()->json($return);
	}

	public function ajaxUpdateCart(Request $request)
	{

		$return = array('status' => 0, 'msg' => 'Invalid Request', 'data' => '');

		if( $request )
		{
			
			$response = $this->OrderBooking->update_cart($request);
			
			if($response['status'])
			{
				if($request->add_item == 'cart_page')
				{
					\Session::flash('message', 'Item updated in the cart.');
				}
				else
				{
					$items = $this->OrderBooking->get_cart_only();

					$view = \View::make('pages.listing_detail.cart', ['online_order_items' => $items]);
					$contents = $view->render();

					$return['data'] = $contents;
				}				
			}
			$return['status'] = $response['status'];
			$return['msg'] = $response['msg'];
		}

		return response()->json($return);
	}

	public function cart()
	{
		$cart_items_split_by_merchant = $this->OrderBooking->get_cart();
		
		//$setting = OrderOnlineSettings::where('merchant_id', $merchant_id)->first();
		
		//return view('pages.listing_detail.online_order_cart', compact('cart_items_split_by_merchant'));
		//print_r($cart_items_split_by_merchant);
	//	exit;
       return view('pages.listing_detail.online_order_cart', compact('cart_items_split_by_merchant'));
	}

    public function delete_cart($id)
    {    	
		$this->OrderBooking->delete_cart($id);

    	return redirect('online-order/cart')->with('message', 'Item removed from cart.');
    }

    public function empty_cart($listing_id = null)
    {
		
		$this->OrderBooking->empty_cart();

    	//$listing = Listing::find($listing_id);

    	//return redirect('/'.$listing->slug);
		
		return redirect('online-order/cart')->with('message', 'All items are removed from cart.');
		
    }
	
	public function checkout()
	{
		$is_empty = $this->OrderBooking->is_cart_empty();
		if(!$is_empty)
		{
			$cart_items_split_by_merchant = $this->OrderBooking->get_cart();//\Cart::getContent();

			$payment_gateway = PaymentGatewaySite::where(['is_default' => 1])->first();
			
			$user_id = (auth()->check()) ? auth()->user()->id : 0;
			$billing_details = [];
			$user_data = [];
			if($user_id) {
				$user_data = auth()->user();
				$billing_details = BillingDetail::where('user_id', $user_id)->get();
			}

			$listing_country = $this->default_country;
			$b_states = $s_states = States::where('country_id', $listing_country->id)->get();

			$b_cities = $s_cities = [];
			if( old('b_state') != '')
			{
				$b_cities = Cities::where('state_id','=', old('b_state'))->get();
			}
			if( old('s_state') != '')
			{
				$s_cities = Cities::where('state_id','=', old('s_state'))->get();
			}

			return view('pages.listing_detail.online_order_checkout', compact('cart_items_split_by_merchant', 'payment_gateway', 'listing_country', 'b_states', 's_states', 'b_cities', 's_cities', 'billing_details', 'user_id', 'user_data'));
		}

		return redirect('online-order/cart');
	}

	public function place_order(Request $request)
	{
		$is_empty = $this->OrderBooking->is_cart_empty();

		if(!$is_empty)
		{
			$BillingDetail = new BillingDetail;			

			$order_rules = $this->OrderBooking->get_adding_rules();
			$billing_rules = $BillingDetail->get_adding_rules();

			$rules = $order_rules['rules'];
			$messages = $order_rules['messages'];
			
			$billing_detail_id = $request->billing_detail_id;
			$address = $request->address;
			$user_id = (auth()->check()) ? auth()->user()->id : 0;
			if($user_id) {
				$billing_details = BillingDetail::where('user_id', $user_id)->get();
				if($billing_details and $address == 'Existing Address' and !$billing_detail_id)
				{
					$billing_rules['rules'] = [
						'billing_detail_id'	=> 'required',
					];
					
					$billing_rules['messages'] = [
						'billing_detail_id.required'	=> 'Select existing address',
					];
				}
			}
			
			if(!$billing_detail_id) {
				$rules += $billing_rules['rules'];
				$messages += $billing_rules['messages'];
			}

			$this->validate($request, $rules, $messages);

			// validation ok
			
			// add billing detail			
			if(!$billing_detail_id) 
			{
				$BillingDetail->user_id = $user_id;
				/*$BillingDetail->b_address_1 = $request->b_address_1;
				$BillingDetail->b_address_2 = $request->b_address_2;
				$BillingDetail->b_city = $request->b_city;
				$BillingDetail->b_state = $request->b_state;
				$BillingDetail->b_country = $request->b_country;
				$BillingDetail->b_pincode = $request->b_pincode;
				$BillingDetail->b_name = $request->b_name;
				$BillingDetail->b_company = $request->b_company;*/
				$BillingDetail->s_address_1 = $request->s_address_1;
				$BillingDetail->s_address_2 = $request->s_address_2;
				$BillingDetail->s_city = $request->s_city;
				$BillingDetail->s_state = $request->s_state;
				$BillingDetail->s_country = $request->s_country;
				$BillingDetail->s_pincode = $request->s_pincode;
				$BillingDetail->s_name = $request->s_name;
				$BillingDetail->s_company = $request->s_company;
				$BillingDetail->save();
			}
			else
			{
				$BillingDetail = BillingDetail::where(['id' => $billing_detail_id, 'user_id' => $user_id])->first();
				
				if(!$BillingDetail) {
					return redirect('online-order/checkout')->with('error_message', 'Invalid Delivery Detail.');
				}
			}

			// payment method
			$payment_gateway_id = $request->payment_gateway_id;
			$payment_gateway = PaymentGatewaySite::find($payment_gateway_id);
			$status = $this->OrderBooking->order_status['pending']['id'];
			if($payment_gateway_id == 1) // COD
			{
				$status = $this->OrderBooking->order_status['confirmed'];
			}
						
			// create order		
			$OrderBooking = $this->OrderBooking->create_order($BillingDetail, $request, $user_id, $payment_gateway_id, 'pending');
				
			$Orders = new Orders;
			$orders_created = $Orders->create_main_order($OrderBooking->id, $user_id, 'online_order');

			$MerchantOrder = new MerchantOrder;
			$MerchantOrder->create_merchant_order($OrderBooking->id);
			
			$encrypt_id = base64_encode('JVinoNeedifo_'.$OrderBooking->id);
			
			$this->OrderBooking->empty_cart();
			
			if($payment_gateway_id == 1) // COD
			{
				//
			}
			else if($payment_gateway_id == 2) // Paypal
			{
				return redirect('online-order/payby-paypal/'.$encrypt_id);
			}
			else if($payment_gateway_id == 3) // Razor
			{
				return redirect('online-order/payby-razor/'.$encrypt_id);
			}
			return redirect('online-order/thankyou/'.$encrypt_id);
			
		}
		
		return redirect('online-order/cart');
	}
	
	public function payby_razor($encrypt_order_id)
	{
		$decrypt_id = base64_decode($encrypt_order_id);
		$order_id = (int) str_replace('JVinoNeedifo_', '', $decrypt_id);
		
		$order = [];
		if($order_id)
		{
			$OrderBooking = new OrderBooking;
			$order = OrderBooking::find($order_id);
			$billing_detail = $order->billing_detail;
			$order_status_pending = $OrderBooking->order_status['pending']['id'];
			if($order->status == $order_status_pending)
			{
				$payObj = new RazorLibrary;
				
				$payObj->addAPIFilelds();
				$payObj->addField('order_id', $order_id);
				$payObj->addField('return_url', url('/online-order/payment-response')); //return url
				$payObj->addField('customer_name', trim($billing_detail->s_name));
				$payObj->addField('description', 'Order');
				$payObj->addField('customer_email', $order->email);
				$payObj->addField('phone', $order->phone_number);
				$payObj->addField('amount', $order->total_amount*100);//.'.00'
				
				$razor_html = $payObj->generateFormOrder();
			
				return view('pages.listing_detail.online_order_payby_razor', compact('razor_html'));			
				
			}
		}
		
		return view('pages.listing_detail.online_order_thankyou', compact('order'));
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
			$OrderBooking = OrderBooking::find($input['order_id']);
			
			if($OrderBooking)
			{
				$OrderBooking->razorpay_payment_id = $input['razorpay_payment_id'];			
				
				$api = new Api($api_key, $secret_key);
				
				$payment = $api->payment->fetch($input['razorpay_payment_id']);
				
				if($payment)
				{
				
					if($payment['status'] == 'authorized')
					{
					
						try {
							$payment = $api->payment->fetch($input['razorpay_payment_id'])->capture(array('amount'=>$payment['amount'])); 
							
							// update status in subscription table				
							$OrderBooking->payment_status = 'Success';	
							$OrderBooking->online_paid_amount = $payment['amount'];
							$OrderBooking->status = $OrderBooking->order_status['new_order']['id'];
							
							$OrderBookingDetail = OrderBookingDetail::where('order_id', $OrderBooking->id)
													->update(['status' => $this->OrderBooking->order_detail_status['new_order']['id']]);
							
							$MerchantOrder = MerchantOrder::where('order_booking_id', $OrderBooking->id)
													->update(['payment_status' => 'Success', 'order_status' => $this->OrderBooking->merchant_order_status['new_order']['id']]);
							
							// send mail
							event(new OrderCompleted($OrderBooking));

						} catch (\Exception $e) {
							$response_error = $e->getMessage();
						}
					}
					
					$response_success = '<div class="col-sm-8 col-sm-offset-3"><div class="col-sm-6"><h6>Order ID :</h6></div><div class="col-sm-6"><h6>'.$OrderBooking->id.'</h6></div><div class="col-sm-6"><h6>Transaction ID :</h6></div><div class="col-sm-6"><h6>'.$payment['id'].'</h6></div><div class="col-sm-6"><h6>Payment Status :</h6></div><div class="col-sm-6"><h6>'.$payment['status'].'</h6></div><div class="col-sm-6"></div><div class="col-sm-6"></div></div>';
				}
				
				$OrderBooking->save();
			}

            // Do something here for store payment details in database...
        }
		
		return view('pages.listing_detail.online_order_payment_response', compact('response_success', 'response_error'));
		//return view('pages.listing_detail.online_order_paypal_success')->with($this->data);
	}
	
	public function _send_mail_merchants($OrderBooking)
	{
		$order_date = $OrderBooking->created_at;
		$merchant_orders = $OrderBooking->merchant_orders;
		
		foreach($merchant_orders as $merchant_order)
		{
			$mail_content=array();
			$mail_content['order_id']=$merchant_order->id;
			$mail_content['order_date']=$order_date->toDateString();
			$mail_content['email']=$OrderBooking->email;
			$mail_content['merchant_mail']=$merchant->email;
			$mail_content['mobile']=$OrderBooking->phone_number;
			
			$mail_content['s_name']=$BillingDetail->s_name;
			$mail_content['s_address']=$BillingDetail->s_address_1;
			$mail_content['s_city']=$BillingDetail->billing_city->name;
			$mail_content['s_state']=$BillingDetail->billing_state->name;
			$mail_content['s_country']=$BillingDetail->billing_country->name;
			$mail_content['s_pincode']=$BillingDetail->s_pincode;
			$mail_content['fooditem']=$merchant_order->order_details;
			$mail_content['products']=$merchant_order->order_details;
			$mail_content['total_amount']=$OrderBooking->total_amount;
			$mail_content['total_qty']=$OrderBooking->total_items;
			Mail::send('emails.order_details', $mail_content, function($message)use ($mail_content) 
			{
				$email=$mail_content['email'];
				$m_email=$mail_content['merchant_mail'];
				$message->to($email,'');
				$message->bcc($m_email,'');
				$message->subject('Order Details');

			});
			
			$notification=new Notifications;
			$notification->name=$BillingDetail->s_name;
			$notification->type='Order';
			$notification->message=$BillingDetail->s_name.' has placed an order at '.$listing->title;
			$notification->listing_id=$listing->id;
			$notification->merchant_id=$listing->user_id;
			$notification->save();
		}
	}
	
	public function _send_mail_user($OrderBooking)
	{
		$order_date = $OrderBooking->created_at;
		$merchant_orders = $OrderBooking->merchant_orders;
		
		$mail_content=array();
		$mail_content['order_id']=$OrderBooking->id;
		$mail_content['order_date']=$order_date->toDateString();
		$mail_content['email']=$OrderBooking->email;
		$mail_content['merchant_mail']=$merchant->email;
		$mail_content['mobile']=$OrderBooking->phone_number;
		
		$mail_content['s_name']=$BillingDetail->s_name;
		$mail_content['s_address']=$BillingDetail->s_address_1;
		$mail_content['s_city']=$BillingDetail->billing_city->name;
		$mail_content['s_state']=$BillingDetail->billing_state->name;
		$mail_content['s_country']=$BillingDetail->billing_country->name;
		$mail_content['s_pincode']=$BillingDetail->s_pincode;
		$mail_content['fooditem']=$item_order_booking_detail_data;
		$mail_content['products']=$item_order_booking_detail_data;
		$mail_content['total_amount']=$OrderBooking->total_amount;
		$mail_content['total_qty']=$OrderBooking->total_items;
		
		// admin email
		if( isset($this->site_settings->notification_email) )
			$mail_content['admin_mail'] = $this->site_settings->notification_email;
		
		Mail::send('emails.order_details', $mail_content, function($message)use ($mail_content) 
		{
			$email=$mail_content['email'];
			$message->to($email,'');
			if(isset($mail_content['admin_mail']))
				$message->bcc($mail_content['admin_mail'],'');
			$message->subject('Order Details');

		});
		
		$notification=new Notifications;
		$notification->name=$BillingDetail->s_name;
		$notification->type='Order';
		$notification->message=$BillingDetail->s_name.' has placed an order at '.$listing->title;
		$notification->listing_id=$listing->id;
		$notification->merchant_id=$listing->user_id;
		$notification->save();
		
	}
	
	public function payby_paypal($encrypt_order_id)
	{
		$decrypt_id = base64_decode($encrypt_order_id);
		$order_id = (int) str_replace('JVinoNeedifo_', '', $decrypt_id);
		
		$order = [];
		if($order_id)
		{
			$order = OrderBooking::find($order_id);
			$merchant_id = $order->merchant_id;
			$paypal_gateway_id  = 2;
			
			$user_role = auth()->user()->hasRole('user');		
			$user_id = 0;
			if($user_role)
				$user_id = (auth()->check()) ? auth()->user()->id : null;
			
			$paypal_setting = PaymentGatewaySiteSettings::where(['payment_gateway_id' => $paypal_gateway_id])->first();
			$mode = $paypal_setting->mode;
			$business_email = $paypal_setting->business_email;
			$currency_code = $paypal_setting->currency->code;
			
			$PaypalLibray = new PaypalLibray($business_email, $currency_code, $mode);
			$PaypalLibray->current_currency_code = $this->currency->code;
			$returnURL = url('online-order/paypal-success'); //payment success url
			$cancelURL = url('online-order/paypal-cancel'); //payment cancel url
			$notifyURL = url('online-order/paypal-ipn'); //ipn url
			$custom['order_id'] = $order_id;
			$custom['user_id'] = $user_id;
			$custom = json_encode($custom);
			$PaypalLibray->add_field('return', $returnURL);
			$PaypalLibray->add_field('cancel_return', $cancelURL);
			$PaypalLibray->add_field('notify_url', $notifyURL);
			$PaypalLibray->add_field('custom', $custom);
			//$PaypalLibray->add_field('quantity',  $quantity);
			$PaypalLibray->add_field('amount',  $order->total_amount);
			/*$PaypalLibray->add_field('on0',  'order_no');
			$PaypalLibray->add_field('os0',  $order_number);*/					
			$paypal_html = $PaypalLibray->paypal_auto_form();
			
			return view('pages.listing_detail.online_order_payby_paypal', compact('paypal_html'));
		}
		else
		{
			return view('pages.listing_detail.online_order_thankyou', compact('order'));
		}
	}
	
	function paypal_success(Request $request)
	{        
		
		//get the transaction data
        $paypalInfo = $request->all();//print_r($paypalInfo);exit;
        $custom = json_decode($paypalInfo['custom'], true);

        $this->data['order_number']    = $custom["order_id"]; 
        $this->data['txn_id'] = $paypalInfo["txn_id"];
        $this->data['payment_amt'] = $paypalInfo["mc_gross"];
        $this->data['currency_code'] = $paypalInfo["mc_currency"];
        $this->data['status'] = $paypalInfo["payment_status"];
        
        //pass the transaction data to view
        return view('pages.listing_detail.online_order_paypal_success')->with($this->data);
    }

    function paypal_cancel()
    {

    	return view('pages.listing_detail.online_order_paypal_cancel');
    }

    function paypal_ipn(Request $request)
    {
        //paypal return transaction details array
        $paypalInfo    = $request->all();//mail('jerosilinvinoth@gmail.com', 'ipn request', print_r($paypalInfo, TRUE));
        $custom = json_decode($paypalInfo['custom'], true);
        
        $data['user_id'] = $custom['user_id'];
        $data['order_id']    = $custom["order_id"];
        $data['txn_id']    = $paypalInfo["txn_id"];
        $data['payment_gross'] = $paypalInfo["payment_gross"];
        $data['currency_code'] = $paypalInfo["mc_currency"];
        $data['payer_email'] = $paypalInfo["payer_email"];
        $data['payment_status']    = $paypalInfo["payment_status"];

        $order = OrderBooking::find($data['order_id']);
        $merchant_id = $order->merchant_id;
        $paypal_gateway_id  = 2;

        $paypal_setting = PaymentGatewaySiteSettings::where(['payment_gateway_id' => $paypal_gateway_id])->first();
        $mode = $paypal_setting->mode;
        $business_email = $paypal_setting->business_email;
        $currency_code = $paypal_setting->currency->code;

        $PaypalLibray = new PaypalLibray($business_email, $currency_code, $mode);

        $paypalURL = $PaypalLibray->paypal_url;        
        $result    = $PaypalLibray->curlPost($paypalURL, $paypalInfo);

		//mail('jerosilinvinoth@gmail.com', 'ipn result', print_r($result, TRUE));exit;
        
        //check whether the payment is verified
        if(preg_match("/VERIFIED/i",$result))
        {
            //insert the transaction data into the database
        	\DB::table('paypal_log')->insert($data);

        	$OrderBooking = new OrderBooking;
        	$status = $OrderBooking->order_status['confirmed'];
        	DB::table('order_booking')
        	->where('id', $data['order_id'])
        	->update(['status' => $status]);
        }
    }

    public function thankyou($encrypt_order_id)
    {
    	$decrypt_id = base64_decode($encrypt_order_id);
    	$order_id = (int) str_replace('JVinoNeedifo_', '', $decrypt_id);

    	$order = [];
    	if($order_id)
    	{
    		$order = OrderBooking::find($order_id);
    	}

    	return view('pages.listing_detail.online_order_thankyou', compact('order'));
    }
    public function destroy($id){

    	\Cart::remove($id);
        	return back(); // will keep same page
        }
        public function update(Request $request, $id)
        {
        	$qty = $request->qty;
        	
        	$return = array('status' => 0, 'msg' => 'Invalid Request', 'data' => '');

        	$data = array(
        		'quantity' => array(
        			'relative' => false,
        			'value' => $qty 
        		),
        	);

        	if( $request->proId )
        	{
        		\Cart::update($request->proId, $data);

        		return view('pages.listing_detail.online_order_cart');
        	}



        }
		
	// admin

	public function menu_items()
	{

		$menu_items = FoodMenuItem::all();

		$listings = Listing::where(['status' => 'Enable'])->get();
		
		$services = $this->services;
		$order_online_id = $services['food']['id'];
		
		$listings = Listing::whereHas('category', function($query) use($order_online_id) {
			$query->where('category_type', 'like', '%'.$order_online_id.'%');
		})->where(['status' => 'Enable'])->get();
		
		$menus = FoodMenu::where(['status' => 1])->get();

		return view('panels.admin.online-order.menu_item', compact('menu_items', 'listings', 'menus'));
	}
	public function getlisting(Request $request){
		$merchant_id=$request->id;
		$data=array();
		$data['listing']=Listing::where('user_id',$merchant_id)->get();
		$data['menu']=FoodMenu::where('status','1')->get();
		return response()->json($data);
	}
	public function add_menu_item(Request $request)
	{		
		$this->FoodMenuItem = new FoodMenuItem;
		$rules = $this->FoodMenuItem->get_adding_rules();
		$this->validate($request, $rules['rules'], $rules['messages']);
		
		// add
		$this->FoodMenuItem->food_menu_id = $request->food_menu_id;
		$this->FoodMenuItem->listing_id = $request->listing_id;
		$this->FoodMenuItem->item_type = $request->item_type;
		$this->FoodMenuItem->name = $request->name;
		$this->FoodMenuItem->description = $request->description;
		$this->FoodMenuItem->original_price = $request->original_price;
		$this->FoodMenuItem->discounted_price = $request->discounted_price;
		$this->FoodMenuItem->status = $request->status;
		$this->FoodMenuItem->stock = $request->stock;
		$this->FoodMenuItem->save();
		
		return redirect('admin/menu-items')->with('message', 'Menu item added successfully');
	}
	public function edit_menu_item($id)
	{		

		$return['menu_item'] = FoodMenuItem::find($id);
		$return['listing'] = $return['menu_item']->listing;

		// ajax
		return Response::json(array(
			'view_details'   => $return
		));
	}
	
	public function update_menu_item(Request $request)
	{	
		$this->FoodMenuItem = new FoodMenuItem;
		$rules = $this->FoodMenuItem->get_adding_rules();
		$this->validate($request, $rules['rules'], $rules['messages']);
		
		$menu_item = FoodMenuItem::find($request->id);

		// update
		$menu_item->food_menu_id = $request->food_menu_id;
		$menu_item->listing_id = $request->listing_id;
		$menu_item->item_type = $request->item_type;
		$menu_item->name = $request->name;
		$menu_item->original_price = $request->original_price;
		$menu_item->discounted_price = $request->discounted_price;
		$menu_item->status = $request->status;
		$menu_item->stock = $request->stock;
		$menu_item->save();
		
		return redirect('admin/menu-items')->with('message', 'Menu updated successfully');
	}
	public function delete_menu_item($id)
	{			
		$menu_item = FoodMenuItem::find($id);
		$msg = 'Invalid Id';
		
		if($menu_item)
		{

			$is_deleted = $menu_item->delete();
			
			if($is_deleted)
			{
				\Session::flash('message', 'Menu item deleted successfully.');
				
				return Response::json(array(
					'success' => true,
					'msg'   => 'Menu item deleted successfully.'
					));
			}
			$msg = 'Not deleted. because related orders exists';
		}
		
		return Response::json(array(
			'success' => false,
			'msg'   => $msg
			));
	}
	
	public function menu_item_destroy_all(Request $request)
	{
		$msg = 'Seltected items are deleted successfully';
		$error_msg = '';
		
		$cn = count($request->selected_id);
		if($cn > 0)
		{
			$data = $request->selected_id;			
			foreach($data as $id) {
				
				$menu_item = FoodMenuItem::find($id);
				$is_deleted = $menu_item->delete();
				if(!$is_deleted)
					$error_msg = 'Some items are not deleted, because related orders exists.';
			}
			
			//FoodMenuItem::destroy($request->selected_id);	
		}
		
		if($error_msg)
			return redirect('admin/menu-items')->with('error_message', $error_msg);
		
		return redirect('admin/menu-items')->with('message', $msg);

	}
	
	public function booking_customers()
	{
		$order_booking = OrderBooking::orderBy('created_at', 'Desc')->get();
		foreach ($order_booking as $key => $value) {
			$value->total=OrderBookingDetail::where('order_id',$value->id)->sum('total_amount');
			$value->total_qty=OrderBookingDetail::where('order_id',$value->id)->sum('quantity');
		}
		
		return view('panels.admin.online-order.booking_customers', compact('order_booking'));
	}
	
	public function booking_customers_detail($id)
	{
		$order_booking = OrderBooking::find($id);
		$merchant_orders = MerchantOrder::where('order_booking_id', $id)->get();

		$order_booking->total=OrderBookingDetail::where('order_id',$id)->sum('total_amount');
			//$value->total_qty=OrderBookingDetail::where('order_id',$value->id)->sum('quantity');

		$OrderBooking = new OrderBooking;
		$order_status = $OrderBooking->order_status;
		$order_detail_status = $OrderBooking->order_detail_status;
		if(!$order_booking)
			return redirect('admin/online-order/booking-customers')->with('error_message', 'Invalid Id');
		
		return view('panels.admin.online-order.booking_customers_detail', compact('order_booking', 'order_status', 'merchant_orders', 'order_detail_status'));
	}
	
	public function update_booking_satus($id, Request $request)
	{
		
		$order_booking = OrderBooking::find($id);
		$order_booking->status = $request->status;
		$order_booking->save();
		
		return redirect('admin/online-order/booking-customers/'.$id)->with('message', 'Status updated successfully');
		
	}

}