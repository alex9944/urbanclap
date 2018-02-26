<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use DB;
use App\Models\Category;
use App\Models\Listing;
use App\Models\CategoryType;
use App\Models\ListingViews;
use App\Models\OrderBooking;
use App\Models\ShopOrderBookingDetail;
use Jenssegers\Agent\Agent;
use Cookie;
use Validator;
use auth;

class ApiOnlineShopController extends Controller
{

	public function getShopOrders(){
		$response=array();
		$user_id=$_GET['id'];
		$online_orders = OrderBooking::where(['user_id' => $user_id, 'order_type' => 'online_shopping'])->get();
		if(sizeof($online_orders)>0){
			foreach ($online_orders as $key => $value) {
				$value['total']=ShopOrderBookingDetail::where('order_id',$value->id)->sum('total_amount');
				$value['listing_name']=$value->listing->title;
			}
			$data['products']=$online_orders;
		}
		else{
			$data['products']='No Order found ';
		}
		
		$responsecode = 200;        
		$header = array (
			'Content-Type' => 'application/json; charset=UTF-8',
			'charset' => 'utf-8'
		);       
		return response()->json($data , $responsecode, $header, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
		
	}
}