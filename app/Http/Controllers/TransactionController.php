<?php

namespace App\Http\Controllers;
use DB;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\MerchantOrder;
use App\Models\OrderBooking;
use App\Models\MerchantTransaction;

use App\Events\MerchantPaid;


class TransactionController extends Controller
{
    //
	public function pending_payments()
    {		
		$OrderBooking = new OrderBooking;
		$merchant_order_status = $OrderBooking->merchant_order_status;

		$pending_payments = MerchantOrder::where(['merchant_payment_status' => 'unpaid', 'order_status' => $merchant_order_status['completed']['id']])
							->whereIn('order_type', ['online_order', 'online_service', 'online_shopping'])
							->groupBy('listing_id')
							->select('listing_id', DB::raw('COUNT(id) AS no_of_orders, SUM(total_amount) AS total_order_amount, SUM(site_commission_amount) AS total_site_commission_amount, SUM(merchant_net_amount) AS merchant_net_amount'))
							->get();
		
		$summary = [];
		if(old('listing_id') != '')
		{
			$summary = MerchantOrder::where(['merchant_payment_status' => 'unpaid', 'order_status' => $merchant_order_status['completed']['id']])
							->whereIn('order_type', ['online_order', 'online_service', 'online_shopping'])
							->where('listing_id', old('listing_id'))
							->groupBy('listing_id')
							->select('listing_id', DB::raw('COUNT(id) AS no_of_orders, SUM(total_amount) AS total_order_amount, SUM(site_commission_amount) AS total_site_commission_amount, SUM(merchant_net_amount) AS merchant_net_amount'))
							->first();
		}
	   
		return view('panels.admin.transactions.pending_payments', compact('pending_payments', 'summary'));
    }
	
	public function get_payment_detail(Request $request)
	{
		$id = $request->id; 
		$OrderBooking = new OrderBooking;
		$merchant_order_status = $OrderBooking->merchant_order_status;
		
		$merchant_pending_payments = MerchantOrder::with('main_order')
								->where(['merchant_payment_status' => 'unpaid', 'order_status' => $merchant_order_status['completed']['id']])
								->whereIn('order_type', ['online_order', 'online_service', 'online_shopping'])
								->where('listing_id', $id)
								->get();
			
		$summary = MerchantOrder::where(['merchant_payment_status' => 'unpaid', 'order_status' => $merchant_order_status['completed']['id']])
								->whereIn('order_type', ['online_order', 'online_service', 'online_shopping'])
								->where('listing_id', $id)
								->groupBy('listing_id')
								->select('listing_id', DB::raw('COUNT(id) AS no_of_orders, SUM(total_amount) AS total_order_amount, SUM(site_commission_amount) AS total_site_commission_amount, SUM(merchant_net_amount) AS merchant_net_amount'))
								->first();
								
		$return['merchant_payments'] = $merchant_pending_payments;
		$return['summary'] = $summary;//print_r($merchant_pending_payments);exit;
		
		return \Response::json(array('success' => true , 'data' => $return));
	}
	
	public function pay_pending_payment(Request $request)
	{
		$MerchantTransaction = new MerchantTransaction;
		$rules = $MerchantTransaction->get_adding_rules();
		
		$this->validate($request, $rules['rules'], $rules['messages']);
		
		//	listing_id	paid_amount	payment_confirm_by	cheque_no	cheque_release_date	comments
		$MerchantTransaction->listing_id = $request->listing_id;
		$MerchantTransaction->paid_amount = $request->paid_amount;
		$MerchantTransaction->payment_confirm_by = $user_id = auth()->user()->id;
		$MerchantTransaction->cheque_no = $request->cheque_no;		
		$MerchantTransaction->cheque_release_date = date('Y-m-d', strtotime($request->cheque_release_date));
		$MerchantTransaction->comments = $request->comments;
		$MerchantTransaction->save();
		
		// trigger event
	   event(new MerchantPaid($MerchantTransaction));
		
		return redirect('admin/merchants/pending-payments')->with('message','Payment detail updated successfully');
	}
	
	public function payment_history()
	{
		$merchant_transactions = MerchantTransaction::all();
		
		return view('panels.admin.transactions.payment_history', compact('merchant_transactions'));
	}
	
	public function get_transaction_detail(Request $request)
	{
		$id = $request->id;
		
		$merchant_transaction = MerchantTransaction::with('orders.main_order')->find($id);
								
		$return['merchant_transaction'] = $merchant_transaction;
		
		return \Response::json(array('success' => true , 'data' => $return));
	}
	
}
?>