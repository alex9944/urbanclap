<?php

namespace App\Http\Controllers\Merchant;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\FoodMenu;
use App\Models\FoodMenuMerchant;
use App\Models\FoodMenuItem;
use App\Models\OrderOnlineSettings;
use App\Models\Category;
use App\Models\Listing;
use App\Models\OrderBookingDetail;
use App\Models\OrderBooking;
use App\Models\MerchantOrder;
use App\Events\OrderStatusChanged;
use App\Models\MerchantServices;
use Response;
use Validator;
use DB;
use Auth;
use Image;
use Mail;


class OnlineOrderController extends Controller
{
	
	public function __construct()
	{
		parent::__construct();
		
		$this->OrderOnlineSettings = new OrderOnlineSettings;
		$this->order_online_id = $this->OrderOnlineSettings->order_online_id;
	}
	
	public function menu_lists()
	{	
		$user_id = (auth()->check()) ? auth()->user()->id : null;
		
		$merchant_menus = FoodMenu::where(['status' => 1])->get();

		return view('panels.merchant.online-order.food_menu', compact('merchant_menus'));
	}
	
	public function add_menu()
	{
		$food_menus = FoodMenu::all()->where('status', 1);
		
		$user_id = (auth()->check()) ? auth()->user()->id : null;

		return view('panels.merchant.online-order.add_menu', compact('food_menus', 'user_id'));
	}
	
	public function post_menu(Request $request)
	{	
		
		$user_id = (auth()->check()) ? auth()->user()->id : null;
		
		// add
		$food_menu_ids = $request->food_menu_ids;
		$data = [];
		if(!empty($food_menu_ids))
		{
			foreach($food_menu_ids as $food_menu_id)
			{
				$data[] = array('merchant_id' => $user_id, 'food_menu_id' => $food_menu_id);
			}
			
			FoodMenuMerchant::insert($data);
		}
		
		if(!empty($data))		
			return redirect('merchant/online-order/menu-lists')->with('message', 'Selected menu added to my menu lists.');
		else
			return redirect('merchant/online-order/add-menu')->with('error_message', 'Please select atleast one checkbox.');
	}
	
	public function menu_items()
	{
		
		$user_id = (auth()->check()) ? auth()->user()->id : null;
		
		$menu_items = FoodMenuItem::all()->where('merchant_id', $user_id);
		
		$menus = FoodMenu::where(['status' => 1])->get();
				
		$listing = Listing::where('user_id', $user_id)->first();//print_r($listings);exit;
		$orderonlinesettings = OrderOnlineSettings::where('merchant_id', $user_id)->first();
	
		$merchant_services = MerchantServices::where('merchant_id',$user_id)->get();
		
		// check if shop enable or not
		$service_disable = true; // default
		$services = $this->services;
		$shop_id = $services['food']['id'];
		foreach($merchant_services as $merchant_service) {
			$category_type_id = $merchant_service->category_type_id;
			
			if($category_type_id == $shop_id and $merchant_service->is_enable) {
				$service_disable = false; // shop is now enable
				break;
			}
		}

		//return view('panels.merchant.online-order.menu_items', compact('menu_items', 'menus', 'listing', 'service_disable'));
		return $this->_loadMerchantView('online-order.menu_items', compact('menu_items', 'menus', 'listing', 'service_disable','orderonlinesettings'));
	}
	
	public function enable()
	{	 
		$merchant_service_id = null;
		
		$services = $this->services;
		
		$shop_category_type_id = $services['food']['id'];
			
		$merchant_id=Auth::user()->id;
		$merchant_services = MerchantServices::where('merchant_id',$merchant_id)->get();
		foreach($merchant_services as $merchant_service) {
			$category_type_id = $merchant_service->category_type_id;
			
			if($category_type_id == $shop_category_type_id) {
				$merchant_service_id = $merchant_service->id;
				break;
			}
		}
		
		$merchant_id = (auth()->check()) ? auth()->user()->id : null;
		
		if($merchant_service_id)
		{
			$merchant_service = MerchantServices::find($merchant_service_id);
			$merchant_service->category_type_id = $shop_category_type_id;
			$merchant_service->merchant_id = $merchant_id;
			$merchant_service->is_enable = 1;
			$merchant_service->save();
		}
		else
		{
			$merchant_service = new MerchantServices;
			$merchant_service->category_type_id = $shop_category_type_id;
			$merchant_service->merchant_id = $merchant_id;
			$merchant_service->is_enable = 1;
			$merchant_service->save();
		}
		
		return redirect('merchant/online-order/menu-items')->with('message', 'Service activated successfully');
	}
	
	public function disable()
	{	 
		$merchant_service_id = null;
		
		$services = $this->services;
		
		$shop_category_type_id = $services['food']['id'];
			
		$merchant_id=Auth::user()->id;
		$merchant_services = MerchantServices::where('merchant_id',$merchant_id)->get();
		foreach($merchant_services as $merchant_service) {
			$category_type_id = $merchant_service->category_type_id;
			
			if($category_type_id == $shop_category_type_id) {
				$merchant_service_id = $merchant_service->id;
				break;
			}
		}
		
		
		$merchant_id = (auth()->check()) ? auth()->user()->id : null;
		
		if($merchant_service_id)
		{
			$merchant_service = MerchantServices::find($merchant_service_id);
			$merchant_service->category_type_id = $shop_category_type_id;
			$merchant_service->merchant_id = $merchant_id;
			$merchant_service->is_enable = 0;
			$merchant_service->save();
		}
		else
		{
			$merchant_service = new MerchantServices;
			$merchant_service->category_type_id = $shop_category_type_id;
			$merchant_service->merchant_id = $merchant_id;
			$merchant_service->is_enable = 0;
			$merchant_service->save();
		}
		
		return redirect('merchant/online-order/menu-items')->with('message', 'Service de-activated successfully');
	}
	
	public function add_menu_item(Request $request)
	{		
		$original_price = (int)($request->original_price);
		
		$this->FoodMenuItem = new FoodMenuItem;
		$rules = $this->FoodMenuItem->get_adding_rules();
		$rules['rules']['discounted_price'] = $rules['rules']['discounted_price'] . '|max:'. ($original_price- 1);
		$this->validate($request, $rules['rules'], $rules['messages']);
		
		$user_id = (auth()->check()) ? auth()->user()->id : null;
		
		// add
		$this->FoodMenuItem->food_menu_id = $request->food_menu_id;
		$this->FoodMenuItem->merchant_id = $user_id;
		$this->FoodMenuItem->listing_id = $request->listing_id;
		$this->FoodMenuItem->item_type = $request->item_type;
		$this->FoodMenuItem->name = $request->name;
		$this->FoodMenuItem->description = '';//$request->description;
		$this->FoodMenuItem->original_price = $request->original_price;
		$this->FoodMenuItem->discounted_price = $request->discounted_price;
		$this->FoodMenuItem->status = $request->status;
		$this->FoodMenuItem->stock = $request->stock;
		$this->FoodMenuItem->save();
		
		return redirect('merchant/online-order/menu-items')->with('message', 'Menu item added successfully');
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
	public function add_delevery_fee(Request $request)
	{		
		$this->OrderOnlineSettings = new OrderOnlineSettings;
		$rules = $this->OrderOnlineSettings->get_adding_rules();
		$this->validate($request, $rules['rules'], $rules['messages']);
		
		$user_id = (auth()->check()) ? auth()->user()->id : null;
		
		$ordersettings = OrderOnlineSettings::where('merchant_id', '=', $user_id)->first();
		if($ordersettings)
		{
				// add	
				$ordersettings = OrderOnlineSettings::find($ordersettings->id);			
				$ordersettings->delivery_fee = $request->delivery_fee;		
				$ordersettings->save();
				$msg='Delevery fee updated successfully';
		}else{
				$this->OrderOnlineSettings->merchant_id = $user_id;
				$this->OrderOnlineSettings->delivery_fee = $request->delivery_fee;		
				$this->OrderOnlineSettings->save();
				$msg='Delevery fee added successfully';
		}
		
		return redirect('merchant/online-order/menu-items')->with('message', $msg);
	}
	
	public function status_menu_item($id)
	{		

		$menu_item = FoodMenuItem::find($id);
		if($menu_item->status==0)
		{
			$menu_item->status = 1;
			$menu_item->save();
			$return['status'] = 1;
		}else{
			$menu_item->status = 0;
			$menu_item->save();	
			$return['status'] = 0;
			}	
			
			$return['id'] = $id;		
			// ajax
			return Response::json(array(
				'view_details'   => $return
				));
	}
	public function update_menu_item(Request $request)
	{	
		$original_price = (int)($request->original_price);
		
		$this->FoodMenuItem = new FoodMenuItem;
		$rules = $this->FoodMenuItem->get_edit_rules();
		$rules['rules']['discounted_price'] = $rules['rules']['discounted_price'] . '|max:'. ($original_price- 1);
		
		$this->validate($request, $rules['rules'], $rules['messages']);
		
		$menu_item = FoodMenuItem::find($request->id);

		// update
		$menu_item->food_menu_id = $request->food_menu_id;
		$menu_item->item_type = $request->item_type;
		$menu_item->name = $request->name;
		$menu_item->original_price = $request->original_price;
		$menu_item->discounted_price = $request->discounted_price;
		$menu_item->status = $request->status;
		$menu_item->stock = $request->stock;
		$menu_item->save();
		
		return redirect('merchant/online-order/menu-items')->with('message', 'Menu updated successfully');
	}
	
	public function delete_menu_item($id)
	{	
		$menu_item = FoodMenuItem::find($id);
		
		if($menu_item)
		{

			$del=$menu_item->delete();
			\Session::flash('message', 'Menu item deleted successfully.');
			if($del==true){			
			return Response::json(array(
				'success' => true,
				'msg'   => 'Menu item deleted successfully.'
			));
		}else{
					return Response::json(array(
				'success' => false,
				'msg'   => 'Related order is existing'
		));
				}
		}
		
		return Response::json(array(
			'success' => false,
			'msg'   => 'Invalid Id'
			));
	}
	public function booking_customers()
	{
		$user_id = (auth()->check()) ? auth()->user()->id : null;
		$listing = Listing::where('user_id', '=', $user_id)->first();
		
		$order_booking = MerchantOrder::where(['listing_id' => $listing->id])->orderBy('created_at', 'Desc')->get();
		
		$OrderBooking = new OrderBooking;
		$merchant_order_status = $OrderBooking->merchant_order_status;
				
		return view('panels.merchant.online-order.booking_customers', compact('order_booking', 'merchant_order_status'));
	}
	
	public function booking_customers_detail($id)
	{
		$order_booking = MerchantOrder::find($id);
	
		$order_booking->total=OrderBookingDetail::where('merchant_order_id',$id)->sum('total_amount');
		//$value->total_qty=OrderBookingDetail::where('order_id',$value->id)->sum('quantity');

		$OrderBooking = new OrderBooking;
		$merchant_order_status = $OrderBooking->merchant_order_status;
		$order_detail_status = $OrderBooking->order_detail_status;
		
		if(!$order_booking)
			return redirect('merchant/online-order/booking-customers')->with('error_message', 'Invalid Id');
		
		return view('panels.merchant.online-order.booking_customers_detail', compact('order_booking', 'merchant_order_status', 'order_detail_status'));
	}
	
	public function update_booking_satus($id)
	{
		$input = \Input::all();
		$status_arr = $input['status'];
		$msg = '';
		
		foreach($status_arr as $order_detail_id => $new_status)
		{
			$OrderBookingDetail = OrderBookingDetail::find($order_detail_id);
			if($OrderBookingDetail->status != $new_status) {
				$OrderBookingDetail->status = $new_status;
				$OrderBookingDetail->save();
				
				// send mail to customer
				event(new OrderStatusChanged($OrderBookingDetail));
				
				$msg = 'Status updated successfully';
			}
		}
		
		$OrderBookingDetail = new OrderBookingDetail;
		$OrderBookingDetail->chkUpdateMerchantStatus($id);
		
		if($msg)
			return redirect('merchant/orders')->with('message', $msg);
		
		return redirect('merchant/orders')->with('error_message', 'Status not updated');		
	}
	
	public function settings()
	{
		$user_id = (auth()->check()) ? auth()->user()->id : null;
		
		$setting = OrderOnlineSettings::where('merchant_id', $user_id)->first();
		
		return view('panels.merchant.online-order.settings', compact('setting'));
	}
	
	public function update_settings(Request $request)
	{
		$OrderOnlineSettings = $this->OrderOnlineSettings;
		$rules = $OrderOnlineSettings->get_adding_rules();
		$this->validate($request, $rules['rules'], $rules['messages']);
		
		$merchant_id = (auth()->check()) ? auth()->user()->id : null;
		
		if($request->id)
		{
			$settings = OrderOnlineSettings::find($request->id);
			$settings->estimated_delivery_time = '';//$request->estimated_delivery_time;
			$settings->delivery_fee = $request->delivery_fee;
			$settings->minimum_delivery_amount = 0;//$request->minimum_delivery_amount;
			$settings->save();
		}
		else
		{
			$settings = $this->OrderOnlineSettings;
			$settings->merchant_id = $merchant_id;
			$settings->estimated_delivery_time = '';//$request->estimated_delivery_time;
			$settings->delivery_fee = $request->delivery_fee;
			$settings->minimum_delivery_amount = 0;//$request->minimum_delivery_amount;
			$settings->save();
		}
		
		return redirect('merchant/online-order/settings')->with('message', 'Settings updated successfully');
	}
	
	public function destroy_all(Request $request)
	{
		$cn = count($request->selected_id);
		if($cn > 0)
		{
			FoodMenu::destroy($request->selected_id);	
		}
		
		return redirect('merchant/online-order/menu-items')->with('message','Seltected rows are deleted successfully');
	}
	
	public function destroy($id)
	{
		$settings = FoodMenu::find($id);
		
		if($settings)
		{

			$settings->delete();
			
			\Session::flash('message', 'Selected row deleted successfully.');
			
			return Response::json(array(
				'success' => true,
				'msg'   => 'Seltected row deleted successfully.'
				));
		}
		
		return Response::json(array(
			'success' => false,
			'msg'   => 'Invalid Id'
			));
	}	
}