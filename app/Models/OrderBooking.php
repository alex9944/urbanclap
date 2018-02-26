<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Listing;
use App\Models\OrderOnlineSettings;
use App\Models\Shopproducts;
use App\Models\FoodMenuItem;
use App\Models\User;
use App\Models\PaymentGatewaySite;

class OrderBooking extends Model // Main Order model
{
    protected $table = 'order_booking';
	
	public $order_online_id = 1; // get from category_type table
	
	// status list
	public $order_status = array(
		'pending'			=> array('id' => 'pending', 'name' => 'Pending'),
		'new_order'			=> array('id' => 'new_order', 'name' => 'New order'),
		'new_order_partial'	=> array('id' => 'new_order_partial', 'name' => 'New order partial'),
		'partial_progressed'=> array('id' => 'partial_progressed', 'name' => 'Partial progressed'),
		'progressed'		=> array('id' => 'progressed', 'name' => 'Progressed'),
		'partial_completed'	=> array('id' => 'partial_completed', 'name' => 'Partial completed'),
		'completed'			=> array('id' => 'completed', 'name' => 'Completed'),
		'confirmed'			=> array('id' => 'confirmed', 'name' => 'Confirmed'),
		'cancelled'			=> array('id' => 'cancelled', 'name' => 'Cancelled')
	);
	public $merchant_order_status = array(
		'pending'			=> array('id' => 'pending', 'name' => 'Pending'),
		'new_order'			=> array('id' => 'new_order', 'name' => 'New order'),
		'new_order_partial'	=> array('id' => 'new_order_partial', 'name' => 'New order partial'),
		'partial_progressed'=> array('id' => 'partial_progressed', 'name' => 'Partial progressed'),
		'progressed'		=> array('id' => 'progressed', 'name' => 'Progressed'),
		'partial_completed'	=> array('id' => 'partial_completed', 'name' => 'Partial completed'),
		'completed'			=> array('id' => 'completed', 'name' => 'Completed'),
		'confirmed'			=> array('id' => 'confirmed', 'name' => 'Confirmed'),
		'cancelled'			=> array('id' => 'cancelled', 'name' => 'Cancelled')
	);
	public $order_detail_status = array(
		'pending'			=> array('id' => 'pending', 'name' => 'Pending'),
		'new_order'			=> array('id' => 'new_order', 'name' => 'New order'),
		'progressed'		=> array('id' => 'progressed', 'name' => 'Progressed'),
		'shipping'			=> array('id' => 'shipping', 'name' => 'Shipped / Delivered'),
		'completed'			=> array('id' => 'completed', 'name' => 'Completed'),
		'cancelled'			=> array('id' => 'cancelled', 'name' => 'Cancelled')
	);
		
	//public $timestamps = false;
	
	public function get_adding_rules()
	{
		$rules = [
			//'name'	=> 'required',
			'phone_number'	=> 'required',
			'email'	=> 'required|email',
			'payment_gateway_id'	=> 'required'
		];
		
		$messages = [
			//'name.required'	=> 'Name is required',
			'phone_number.required'	=> 'Phone number is required',
			'email.required'	=> 'Email is required',
			'email.email'	=> 'Invalid email',
			'payment_gateway_id.required' => 'Payment method is required'
		];
		
		return array('rules' => $rules, 'messages' => $messages);
	}
	
	public function billing_detail()
    {
        return $this->belongsTo('App\Models\BillingDetail', 'billing_detail_id');
    }
	
	public function order_detail()
    {
        return $this->hasMany('App\Models\OrderBookingDetail', 'order_id');
    }
	
    public function customer()
	{
    	return $this->belongsTo('App\Models\User','user_id');
    }
	
	public function merchant_orders()
    {
        return $this->hasMany('App\Models\MerchantOrder', 'order_booking_id');
    }
	 
	public function main_order()
    {
        return $this->hasOne('App\Models\Orders', 'order_booking_id');
    }
	
	public function add_to_cart($request)
	{
		if(is_object($request))
		{
			$item_id = $request->item_id;
			$name = $request->name;
			$price = $request->price;
			$quantity = $request->quantity;
			$order_type = $request->order_type;
			$user_id = $request->user_id;
			$listing_id = $request->listing_id;			
			$img = $request->img;
			$stock = $request->stock;
			
			$row_id = $request->order_type . '_' . $request->item_id;
		}
		else
		{
			$item_id = @$request['item_id'];
			$name = @$request['name'];
			$price = @$request['price'];
			$quantity = @$request['quantity'];
			$order_type = @$request['order_type'];
			$user_id = @$request['user_id'];
			$listing_id = @$request['listing_id'];			
			$img = @$request['img'];
			$stock = @$request['stock'];
			
			$row_id = @$request['order_type'] . '_' . @$request['item_id'];
		}
		
		$return = array('status' => 0, 'msg' => 'Invalid Request');
		
		if($item_id and $name and $quantity and $stock)
		{
			$product_categories = new \stdClass;
			$food_item_type = '';
			$shipping_price = 0;
			if($order_type == 'shop') {
				$product = Shopproducts::find($item_id);
				$product_categories = $product->categories;
				$shipping_price = $product->shipping_price;
				$listing_id = $product->listing_id;
				$price = $product->pro_price;
				$stock = $product->stock;
				if($product->spl_price)
					$price = $product->spl_price;
			} else {
				$menu_item = FoodMenuItem::find($item_id);
				if($menu_item) {
					$food_item_type = $menu_item->item_type;
					$listing_id = $menu_item->listing_id;
					$price = $menu_item->original_price;
					$stock = $menu_item->stock;
					if($menu_item->discounted_price)
						$price = $menu_item->discounted_price;
				}
			}
			
			// check stock
			$cart_item = \Cart::get($row_id);
			$return['status'] = 1;
			if($cart_item) {
				$cart_quantity = $cart_item->quantity;
				if($stock < $cart_quantity+$quantity) {
					$return['msg'] = 'Product not added. The product stock limit is '.$stock;
					$return['status'] = 0;
				}
			}
			
			if($return['status'])
			{
				\Cart::add($row_id,$name,$price,$quantity,['img' => $img, 'stock' => $stock, 'item_id' => $item_id, 'order_type' => $order_type, 'user_id' => $user_id, 'listing_id' => $listing_id, 'product_categories' => $product_categories, 'food_item_type' => $food_item_type, 'shipping_price' => $shipping_price]);
				
				$return['msg'] = 'Item added to the cart.';
			}
			
		}
		return $return;
	}
	
	public function add_to_cart_multi($request_array)
	{		
		foreach($request_array as $key => $request)
		{
			$return[$key] = array('status' => 0, 'msg' => 'Invalid Request', 'data' => $request);
			
			$response = $this->add_to_cart($request);
			
			$return[$key]['status'] = $response['status'];
			$return[$key]['msg'] = $response['msg'];
		}
		
		return $return;
	}
	
	public function update_cart($request)
	{
		if(is_object($request))
		{
			$row_id = $request->row_id;
			$item_id = $request->item_id;
			$quantity = $request->quantity;
			$stock = $request->stock;	

			if(!$row_id)
				$row_id = $request->order_type . '_' . $request->item_id;
		}
		else
		{
			$row_id = @$request['row_id'];
			$item_id = @$request['item_id'];
			$quantity = @$request['quantity'];
			$stock = @$request['stock'];
			
			if(!$row_id)
				$row_id = @$request['order_type'] . '_' . @$request['item_id'];
		}
		
		$return = array('status' => 0, 'msg' => 'Invalid Request');
		
		if($row_id and $stock and $quantity)
		{
			// check stock
			$return['status'] = 1;
			if($stock < $quantity) { 
				$return['msg'] = 'Product not added. The product stock limit is '.$stock;
				$return['status'] = 0;
			}
			
			if($return['status'])
			{
				$data = array(
					'quantity' => array(
						'relative' => false,
						'value' => $quantity
					),
				);
				
				\Cart::update($row_id, $data);
				$return['msg'] = 'Item updated to the cart.';			
			}
		}
		
		return $return;
	}
	
	public function get_cart()
	{
		
		$cart_items = \Cart::getContent();//print_r($cart_items);exit;
		
		$cart_items_split_by_merchant = [];
		$unique_listing_ids = array();
		$online_order_id = 1;
		$online_shopping_id = 2;
		$listing_cnt = -1;
		
		foreach($cart_items as $row)
		{
			$attributes = $row->attributes;
			$listing_id = $attributes['listing_id'];
			$category_type = [];
			
			$listing = Listing::find($listing_id);
			
			if($listing)
			{
				$category = $listing->category;
				$category_type = json_decode($category->category_type);	
			}
			
			$cart_item = array();
			
			if( in_array($listing_id, $unique_listing_ids) )
			{
				// exists
				\Cart::clearItemConditions($row->id);
			}
			else
			{
				$listing_cnt++;
				
				// new				
				$unique_listing_ids[$listing_cnt] = $listing_id;
				
				if($listing)
				{
					
					//$city = $listing->listing_city;
					$cart_items_split_by_merchant[$listing_id]['listing'] = $listing;
								
					if(in_array($online_order_id, $category_type))
						$cart_items_split_by_merchant[$listing_id]['category_type'] = 'online_order';
					if(in_array($online_shopping_id, $category_type))
						$cart_items_split_by_merchant[$listing_id]['category_type'] = 'online_shopping';
					$cart_items_split_by_merchant[$listing_id]['delivery_fee'] = 0;
					
					$merchant_id = $listing->user_id;
					$setting = OrderOnlineSettings::where('merchant_id', $merchant_id)->first();
					if( isset($setting->delivery_fee) )
					{
						$cart_items_split_by_merchant[$listing_id]['delivery_fee'] = $setting->delivery_fee;
						/*
						$condition = new \Darryldecode\Cart\CartCondition(array(
							'name' => 'Delivery_Fee',
							'type' => 'shipping',
							'target' => 'item',
							'value' => '+'.$setting->delivery_fee
						));
						\Cart::clearItemConditions($row->id);
						//\Cart::addItemCondition($row->id, $condition);
						*/
					}
				}				
				
			}
			
			// add shipping price for all items if it is product
			/*if(in_array($online_shopping_id, $category_type)) {
				$product = Shopproducts::find($attributes['item_id']);
				if($product->shipping_price)
				{
					$condition = new \Darryldecode\Cart\CartCondition(array(
						'name' => 'Shipping_Price',
						'type' => 'shipping',
						'target' => 'item',
						'value' => '+'.$product->shipping_price
					));
					\Cart::clearItemConditions($row->id);
					//\Cart::addItemCondition($row->id, $condition);
				}
			}*/
			
			$cart_items_split_by_merchant[$listing_id]['cart_item'][] = $row;			
		}//print_r($cart_items_split_by_merchant);exit;		
		
		$return = [];
		foreach($cart_items_split_by_merchant as $cart) {
			$return[] = $cart;
		}
		
		return $return;
	}
	
	public function get_cart_only()
    {
		return \Cart::getContent();
	}
	
	public function is_cart_empty()
    {
		//$cart = $this->get_cart_only();
		return \Cart::isEmpty();
	}
	
	public function get_subtotal()
    {
		return \Cart::getSubTotal();
	}
	
	public function get_total()
    {
		return \Cart::getTotal();
	}
	
	public function delete_cart($id)
    {    	
		\Cart::remove($id);
		\Cart::clearItemConditions($id);
    }
	
	public function delete_cart_by_listing_id($delete_listing_id)
	{
		$cart_items = $this->get_cart_only();
		
		foreach($cart_items as $row)
		{
			$attributes = $row->attributes;
			$listing_id = $attributes['listing_id'];
			
			if($listing_id == $delete_listing_id)
				$this->delete_cart($row->id);
		}
	}

    public function empty_cart()
    {
    	\Cart::clear();
		\Cart::clearCartConditions();    	
    }
	
	public function store_cart($user_id)
	{
		if($user_id)
		{
			$user = User::find($user_id);
			$is_cart_empty = $this->is_cart_empty();
			
			if(!$is_cart_empty) {
				
				$cart = $this->get_cart_only();				
				$user->cart_data = serialize($cart);
				
			} else {
				
				$user->cart_data = null;
			}
			
			$user->save();
		}
	}
	
	public function restore_cart($user_id)
	{
		if($user_id)
		{		
			$user = User::find($user_id);
			if($user->cart_data) {
				$cart = unserialize($user->cart_data);
				\Cart::save($cart);
			}
		}
	}
	
	public function empty_stored_cart($user_id)
	{
		if($user_id)
		{
			$user = User::find($user_id);
			if($user->cart_data) {
				$user->cart_data = null;
				$user->save();
			}
		}
	}
	
	public function create_order($BillingDetail, $request, $user_id, $payment_gateway_id, $payment_status)
	{
		$payment_gateway = PaymentGatewaySite::find($payment_gateway_id);
		$status = $this->order_status['pending']['id'];
		
		$OrderBooking = $this->newInstance();
		
		$cart_items_split_by_merchant = $OrderBooking->get_cart();
		$subtotal = \Cart::getSubTotal();
		$total_shipping_amount = 0;
		foreach($cart_items_split_by_merchant as $listing_carts) 
		{
			$cart_items = $listing_carts['cart_item'];
			$category_type = $listing_carts['category_type'];
			$delivery_fee = $listing_carts['delivery_fee'];	
			if($category_type == 'online_order') {
				$total_shipping_amount += $delivery_fee;
			}			
			foreach($cart_items as $row) { 
				
				$shipping_price = $row->attributes['shipping_price'];
				$total_shipping_amount += $shipping_price;
			}
		}
		$subtotal = \Cart::getSubTotal();
		$total = \Cart::getTotal() + $total_shipping_amount;
		
		$OrderBooking->user_id 		= $user_id;
		//$OrderBooking->listing_id = 0;
		//$OrderBooking->merchant_id = 0;
		$OrderBooking->billing_detail_id = $BillingDetail->id;
		$OrderBooking->name = $BillingDetail->s_name;
		$OrderBooking->phone_number = $request->phone_number;
		$OrderBooking->email = $request->email;
		$OrderBooking->comments = isset($request->comments) ? $request->comments : '';			
		$OrderBooking->sub_total = $subtotal;
		$OrderBooking->delivery_fee = $total_shipping_amount;
		$OrderBooking->total_amount = $total;
		$OrderBooking->tax_name1 = '';
		$OrderBooking->tax_percent1 = '';
		$OrderBooking->tax_name2 = '';
		$OrderBooking->tax_percent2 = '';			
		$OrderBooking->tax_total = 0;
		$OrderBooking->total_items = \Cart::getTotalQuantity();
		$OrderBooking->payment_type = $payment_gateway->name;
		$OrderBooking->payment_gateway_id = $payment_gateway_id;
		$OrderBooking->status = $status;
		$OrderBooking->payment_status = 'pending';
		$OrderBooking->order_type = '';
		$OrderBooking->save();
		
		return $OrderBooking;
	}
  
}
