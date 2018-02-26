<?php

namespace App\Http\Controllers\Merchant;
use DB;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\VendorSubscription;
use App\Http\Controllers\Controller;


class ApiSubscriptionHistoryController extends Controller
{
    //
	public function index($merchant_id)
    {		
	   
		// $merchant_id = (auth()->check()) ? auth()->user()->id : null;
	   
		$subscribers = VendorSubscription::where('merchant_id', $merchant_id)->orderBy('updated_at', 'Desc')->get();
		foreach($subscribers as $subscriber)
			$subscriber->plan_detail = json_decode($subscriber->plan_detail);
		$return['subscribers'] = $subscribers;
		$return['status'] = 1;	
		$return['msg'] = "Success";
		return response()->json($return);
    }
	
	public function view_order($id)
	{
		
		$subscriber = VendorSubscription::where('id',$id)->first();
		$plan_detail = json_decode($subscriber->plan_detail);		
		$return['order_product'] = $subscriber;
		$return['plan_detail'] = $plan_detail;
		$return['subscription_category'] = $subscriber->subscription_category;
		$return['currency'] = $subscriber->currency;
		$return['merchant'] = $subscriber->merchant;
		$return['subscriptions_count'] = $subscriber->merchant->subscriptions->count();
		
		    $return['status'] = 1;	
			$return['msg'] = "Success";
			return response()->json($return);
	}

	
}