<?php

namespace App\Http\Controllers;
use DB;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Models\Currency;
use App\Models\MultiLanguage;
use App\Http\Controllers\Controller;
use Image;


class CurrencyController extends Controller
{
    //
	public function index()
    {	


		$currency = DB::table('currency')
		->select('*', 'currency.id as cid')
		->leftJoin('countries', 'countries.id', '=', 'currency.c_id')		
		->get();	
	 
	    $countries = DB::table('countries')
			 ->where('status', '1')	 
			 ->get(); 

		return view('panels.admin.currency.index',['currency'=>$currency,'countries'=>$countries,'edit_countries'=>$countries,]);
    }
	
	public function getcurrency(Request $request)
		{
			 $id=$request->id;  
			 $currency = DB::table('currency')
			 ->where('id', $id)	 
			 ->first();   
		 return '{"view_details": ' . json_encode($currency) . '}';
	}

	public function added(Request $request)
	{
		 
			$this->validate($request,
			 [
                'c_id'            	=> 'required',
				'symbol'            	=> 'required',
				'code'            	=> 'required', 				
               
            ],
            [
                'c_id.required'   	=> 'Country is required', 
				'symbol.required'   	=> 'Symbol is required', 
                'code.required'   	=> 'Code is required', 				
               
            ]);

				$currency = new Currency;
				$currency->c_id=$request->c_id;
				$currency->symbol=$request->symbol;
				$currency->code=$request->code;					
				$currency->save();
			return redirect('admin/currency')->with('message','Currency added successfully');
	}
	public function updated(Request $request)
	{	 
			$this->validate($request,
			 [
                'c_id'            	=> 'required',
				'symbol'            	=> 'required',
				'code'            	=> 'required', 				
               
            ],
            [
                'c_id.required'   	=> 'Country is required', 
				'symbol.required'   	=> 'Symbol is required', 
                'code.required'   	=> 'Code is required', 				
               
            ]);
			
			
			
				DB::table('currency')
				->where('id', $request->id)
				->update(['c_id' => $request->c_id,'symbol' => $request->symbol,'code' => $request->code,]);

	return redirect('admin/currency')->with('message','Currency updated successfully');
	}


	public function deleted(Request $request)
	{	 
	$id=$request->id;  
		 $category = DB::table('currency')
		 ->where('id', $id)
		 ->delete();
		 $status['deletedid']=$id;
		 $status['deletedtatus']='Currency deleted successfully';
	 return '{"delete_details": ' . json_encode($status) . '}'; 
	
	}

	public function destroy(Request $request)
		{
			echo $cn=count($request->selected_id);
			if($cn>0)
			{
			$data = $request->selected_id;			
				foreach($data as $id) {
				DB::table('currency')->where('id', $id)->delete();			
				}			
			} 
	return redirect('admin/currency')->with('message','Seltected currency are deleted successfully');			

	}
}