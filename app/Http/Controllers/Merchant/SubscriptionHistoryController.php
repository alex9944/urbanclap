<?php

namespace App\Http\Controllers\Merchant;
use DB;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\VendorSubscription;
use App\Http\Controllers\Controller;


class SubscriptionHistoryController extends Controller
{
    //
	public function index()
    {		
	   
	   $merchant_id = (auth()->check()) ? auth()->user()->id : null;
	   
	   $subscribers = VendorSubscription::where('merchant_id', $merchant_id)->orderBy('updated_at', 'Desc')->get();
       return view('panels.merchant.subscription_history.index',['subscribers'=>$subscribers]);
    }
	
	public function view_order(Request $request)
	{
		$id = $request->id; 
		$merchant_id = (auth()->check()) ? auth()->user()->id : null;
		
		$subscriber = VendorSubscription::where(['id' => $id, 'merchant_id' => $merchant_id])->first();
		$plan_detail = json_decode($subscriber->plan_detail);
		//$plan = $plan_detail->subscription_pricing->plan.', '.$subscriber->currency->symbol.$plan_detail->subscription_pricing->price.' - '.$plan_detail->subscription_term->display_value;
		//$category = $plan_detail->subscription_category->title;
		
		$data['order_product'] = $subscriber;
		$data['plan_detail'] = $plan_detail;
		$data['subscription_category'] = $subscriber->subscription_category;
		$data['currency'] = $subscriber->currency;
		$data['merchant'] = $subscriber->merchant;
		$data['subscriptions_count'] = $subscriber->merchant->subscriptions->count();
			
		return '{"view_details": ' . json_encode($data) . '}';
	}

	public function deleted(Request $request)
	{	 
		$id = $request->id;  
		$merchant_id = (auth()->check()) ? auth()->user()->id : null;
		
		$subscriber = VendorSubscription::where(['id' => $id, 'merchant_id' => $merchant_id])->first();
		
		if($subscriber)
		{

			$subscriber->delete();
			
			\Session::flash('message', 'Subscribers detail deleted successfully.');
			
			return Response::json(array(
				'success' => true,
				'msg'   => 'Subscribers detail deleted successfully.'
				));
		}
		
		return Response::json(array(
			'success' => false,
			'msg'   => 'Invalid Id'
			));
	
	}

	public function destroy(Request $request)
	{
		$cn = count($request->selected_id);
		if($cn > 0)
		{
			VendorSubscription::destroy($request->selected_id);	
		}		
		return redirect('merchant/subscription-history')->with('message','Seltected rows are deleted successfully');
	}
}