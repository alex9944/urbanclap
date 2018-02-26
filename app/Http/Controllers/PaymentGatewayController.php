<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\PaymentGateway;
use App\Models\PaymentGatewaySettings;
use App\Models\PaymentGatewaySite;
use App\Models\PaymentGatewaySiteSettings;
use App\Models\Currency;
use Response;
use Validator;
use DB;
use Auth;
use Image;
use Mail;


class PaymentGatewayController extends Controller
{
	
	public function merchant()
	{	
	
		$payment_gateway = PaymentGateway::orderBy('name', 'ASC')->get();
		
		$currencies = Currency::all();

		return view('panels.admin.payment_gateway.merchant', compact('payment_gateway', 'currencies'));
	}
	
	public function merchant_edit($id)
	{
		$payment_gateway = PaymentGateway::find($id);

		// ajax
		return Response::json(array(
			'view_details'   => $payment_gateway
			));
	}
	
	public function merchant_update(Request $request)
	{
		$PaymentGateway = new PaymentGateway;
		$rules = $PaymentGateway->get_adding_rules();
		$this->validate($request, $rules['rules'], $rules['messages']);
		
		// validation ok
		if($request->id)
		{
			$payment_gateway = PaymentGateway::find($request->id);
			$payment_gateway->name = $request->name;
			$payment_gateway->has_settings = $request->has_settings;
			$payment_gateway->status = $request->status;
			$payment_gateway->save();
			$msg = 'Payment gateway updated successfully';
		}
		else
		{
			$payment_gateway = new PaymentGateway;
			$payment_gateway->name = $request->name;
			$payment_gateway->has_settings = $request->has_settings;
			$payment_gateway->status = $request->status;
			$payment_gateway->save();
			$msg = 'Payment gateway added successfully';
		}
		
		return redirect('admin/payment-gateway/merchant')->with('message', $msg);
	}

	public function merchant_destroy($id)
	{
		$payment_gateway = PaymentGateway::find($id);
		
		if($payment_gateway)
		{

			$payment_gateway->delete();
			
			\Session::flash('message', 'Selected row deleted successfully.');
			
			return Response::json(array(
				'success' => true,
				'msg'   => 'Selected row deleted successfully.'
				));
		}
		
		return Response::json(array(
			'success' => false,
			'msg'   => 'Invalid Id'
			));
	}
	
	public function subscription()
	{	
	
		$payment_gateway_site = PaymentGatewaySite::orderBy('name', 'ASC')->get();
		
		$currencies = Currency::all();

		return view('panels.admin.payment_gateway.subscription', compact('payment_gateway_site', 'currencies'));
	}
	
	public function subscription_edit($id)
	{
		$payment_gateway = PaymentGatewaySite::find($id);

		// ajax
		return Response::json(array(
			'view_details'   => $payment_gateway
			));
	}
	
	public function subscription_update(Request $request)
	{
		$PaymentGatewaySite = new PaymentGatewaySite;
		$rules = $PaymentGatewaySite->get_adding_rules();
		$this->validate($request, $rules['rules'], $rules['messages']);
		
		if($request->is_default == 1)
		{
			DB::table('payment_gateway_site')->update(array('is_default' => 0));
		}
		
		// validation ok
		if($request->id)
		{
			$payment_gateway = PaymentGatewaySite::find($request->id);
			$payment_gateway->name = $request->name;
			$payment_gateway->is_default = $request->is_default;
			$payment_gateway->has_settings = $request->has_settings;
			$payment_gateway->status = $request->status;
			$payment_gateway->save();
			$msg = 'Payment gateway updated successfully';
		}
		else
		{
			$payment_gateway = new PaymentGatewaySite;
			$payment_gateway->name = $request->name;
			$payment_gateway->is_default = $request->is_default;
			$payment_gateway->has_settings = $request->has_settings;
			$payment_gateway->status = $request->status;
			$payment_gateway->save();
			$msg = 'Payment gateway added successfully';
		}
		
		return redirect('admin/payment-gateway/subscription')->with('message', $msg);
	}

	public function subscription_destroy($id)
	{
		$payment_gateway = PaymentGatewaySite::find($id);
		
		if($payment_gateway)
		{

			$payment_gateway->delete();
			
			\Session::flash('message', 'Selected row deleted successfully.');
			
			return Response::json(array(
				'success' => true,
				'msg'   => 'Selected row deleted successfully.'
				));
		}
		
		return Response::json(array(
			'success' => false,
			'msg'   => 'Invalid Id'
			));
	}
	
	public function edit_settings($id)
	{
		$settings = PaymentGatewaySiteSettings::where(['payment_gateway_site_id' => $id])->first();

		// ajax
		return Response::json(array(
			'view_details'   => $settings
			));
	}
	
	public function update_settings(Request $request)
	{
		$PaymentGatewaySiteSettings = new PaymentGatewaySiteSettings;
		$rules = $PaymentGatewaySiteSettings->get_adding_rules();
		$this->validate($request, $rules['rules'], $rules['messages']);
				
		if($request->setting_id)
		{
			$settings = PaymentGatewaySiteSettings::find($request->setting_id);
			$settings->business_email = $request->business_email;
			$settings->api_key = $request->api_key;
			$settings->secret_key = $request->secret_key;
			$settings->currency_id = $request->currency_id;
			$settings->mode = $request->mode;
			$settings->save();
		}
		else
		{
			$settings = new PaymentGatewaySiteSettings;
			$settings->payment_gateway_site_id = $request->payment_gateway_site_id;
			$settings->business_email = $request->business_email;
			$settings->api_key = $request->api_key;
			$settings->secret_key = $request->secret_key;
			$settings->currency_id = $request->currency_id;
			$settings->mode = $request->mode;
			$settings->save();
		}
		
		return redirect('admin/payment-gateway/subscription')->with('message', 'Settings updated successfully');
	}
	
}