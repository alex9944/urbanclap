<?php

namespace App\Http\Controllers\Merchant;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\PaymentSettings;
use App\Models\Currency;
use Response;
use Validator;
use DB;
use Auth;
use Image;
use Mail;


class PaymentSettingsController extends Controller
{
	
	public function index()
	{	
	
		$merchant_id = auth()->user()->id;
		
		$payment_settings = PaymentSettings::where('merchant_id', $merchant_id)->first();

		return view('panels.merchant.payment_settings.index', compact('payment_settings'));
	}
	
	public function add(Request $request)
	{
		$PaymentSettings = new PaymentSettings;
		$rules = $PaymentSettings->get_adding_rules();
		$this->validate($request, $rules['rules'], $rules['messages']);
		
		$merchant_id = (auth()->check()) ? auth()->user()->id : null;
		
		if($request->id)
		{
			$settings = PaymentSettings::find($request->id);
			$settings->account_holder_name = $request->account_holder_name;
			$settings->account_id = $request->account_id;
			$settings->bank_name = $request->bank_name;
			$settings->ifsc_code = $request->ifsc_code;
			$settings->save();
			
			$add = 'updated';
		}
		else
		{
			$settings = new PaymentSettings;
			$settings->merchant_id = $merchant_id;
			$settings->account_holder_name = $request->account_holder_name;
			$settings->account_id = $request->account_id;
			$settings->bank_name = $request->bank_name;
			$settings->ifsc_code = $request->ifsc_code;
			$settings->save();
			
			$add = 'added';
		}
		
		return redirect('merchant/payment-settings')->with('message', 'Settings '.$add.' successfully');
	}
	
	public function enable(Request $request)
	{	 
		$payment_gateway_id = $request->id;
		$settings_id = $request->settings_id;
		$merchant_id = (auth()->check()) ? auth()->user()->id : null;
		
		if($settings_id)
		{
			$settings = PaymentSettings::find($settings_id);
			$settings->payment_gateway_id = $payment_gateway_id;
			$settings->merchant_id = $merchant_id;
			$settings->status = 1;
			$settings->save();
		}
		else
		{
			$settings = new PaymentSettings;
			$settings->payment_gateway_id = $payment_gateway_id;
			$settings->merchant_id = $merchant_id;
			$settings->status = 1;
			$settings->save();
		}
		
		$request->session()->flash('message', 'Status updated successfully');	
		return response()->json(['status' => 1, 'msg' => 'success']);
	}
	
	public function disable(Request $request)
	{	 
		$payment_gateway_id = $request->id;
		$settings_id = $request->settings_id;
		$merchant_id = (auth()->check()) ? auth()->user()->id : null;
		
		if($settings_id)
		{
			$settings = PaymentSettings::find($settings_id);
			$settings->payment_gateway_id = $payment_gateway_id;
			$settings->merchant_id = $merchant_id;
			$settings->status = 0;
			$settings->save();
		}
		else
		{
			$settings = new PaymentSettings;
			$settings->payment_gateway_id = $payment_gateway_id;
			$settings->merchant_id = $merchant_id;
			$settings->status = 0;
			$settings->save();
		}
		
		$request->session()->flash('message', 'Status updated successfully');	
		return response()->json(['status' => 1, 'msg' => 'success']);	
	}
	
}