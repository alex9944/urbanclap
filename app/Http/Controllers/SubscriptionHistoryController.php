<?php

namespace App\Http\Controllers;
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
	   $subscribers = VendorSubscription::with('payment_gateway')->orderBy('created_at', 'Desc')->get();
       return view('panels.admin.subscription_history.index',['subscribers'=>$subscribers]);
    }
	
	public function view_order(Request $request)
	{
		$id = $request->id;  			
		$subscriber = VendorSubscription::find($id);
		$plan_detail = json_decode($subscriber->plan_detail);
		//$plan = $plan_detail->subscription_pricing->plan.', '.$subscriber->currency->symbol.$plan_detail->subscription_pricing->price.' - '.$plan_detail->subscription_term->display_value;
		//$category = $plan_detail->subscription_category->title;
		
		$data['order_product'] = $subscriber;
		$data['plan_detail'] = $plan_detail;
		$data['subscription_category'] = $subscriber->subscription_category;
		$data['currency'] = $subscriber->currency;
		$data['merchant'] = $subscriber->merchant;
		$subscriptions_count = 0;
		if(isset($subscriber->merchant->subscriptions))
			$subscriptions_count = $subscriber->merchant->subscriptions()->where('payment_status', 'Success')->count();
		$data['subscriptions_count'] = $subscriptions_count;
			
		return '{"view_details": ' . json_encode($data) . '}';
	}

	public function deleted(Request $request)
	{	 
		$id=$request->id;  
		
		$subscriber = VendorSubscription::find($id);
		
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
		return redirect('admin/merchants/subscription-history')->with('message','Seltected rows are deleted successfully');		

	}
}