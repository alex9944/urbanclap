<?php

namespace App\Http\Controllers\Merchant;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\MerchantServices;
use App\Models\CategoryType;
use Response;
use Validator;


class MerchantServicesController extends Controller
{	
	public function __construct()
	{
		parent::__construct();
	}	
    
	public function index()
    {		
		$user_id = (auth()->check()) ? auth()->user()->id : null;
		//$all_services = CategoryType::where('status', 1)->orderBy('name')->get();		
			
		//return view('panels.merchant.merchant_services.index', compact('user_id'));//'all_services', 
		
		return $this->_loadMerchantView('merchant_services.index');
    }
	
	public function enable(Request $request)
	{	 
		$category_type_id = $request->id;
		$merchant_service_id = $request->merchant_service_id;
		$merchant_id = (auth()->check()) ? auth()->user()->id : null;
		
		if($merchant_service_id)
		{
			$merchant_service = MerchantServices::find($merchant_service_id);
			$merchant_service->category_type_id = $category_type_id;
			$merchant_service->merchant_id = $merchant_id;
			$merchant_service->is_enable = 1;
			$merchant_service->save();
		}
		else
		{
			$merchant_service = new MerchantServices;
			$merchant_service->category_type_id = $category_type_id;
			$merchant_service->merchant_id = $merchant_id;
			$merchant_service->is_enable = 1;
			$merchant_service->save();
		}
		
		$request->session()->flash('message', 'Service status updated successfully');	
		return response()->json(['status' => 1, 'msg' => 'success']);
	}
	
	public function disable(Request $request)
	{	 
		$category_type_id = $request->id;
		$merchant_service_id = $request->merchant_service_id;
		$merchant_id = (auth()->check()) ? auth()->user()->id : null;
		
		if($merchant_service_id)
		{
			$merchant_service = MerchantServices::find($merchant_service_id);
			$merchant_service->category_type_id = $category_type_id;
			$merchant_service->merchant_id = $merchant_id;
			$merchant_service->is_enable = 0;
			$merchant_service->save();
		}
		else
		{
			$merchant_service = new MerchantServices;
			$merchant_service->category_type_id = $category_type_id;
			$merchant_service->merchant_id = $merchant_id;
			$merchant_service->is_enable = 0;
			$merchant_service->save();
		}
		
		$request->session()->flash('message', 'Service status updated successfully');	
		return response()->json(['status' => 1, 'msg' => 'success']);	
	}
}