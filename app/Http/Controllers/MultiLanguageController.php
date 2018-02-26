<?php

namespace App\Http\Controllers;
use DB;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Models\MultiLanguage;
use App\Http\Controllers\Controller;



class multilanguageController extends Controller
{
    //
	public function index()
    {		
	   $multilanguage= MultiLanguage::all();
       return view('panels.admin.multilanguage.index',['multilanguage'=>$multilanguage]);
    }
	
	public function getmultilanguage(Request $request)
		{
			 $id=$request->id;  
			 $multilanguage = DB::table('multilanguage')
			 ->where('id', $id)	 
			 ->first();   
		 return '{"view_details": ' . json_encode($multilanguage) . '}';
	}
		
	public function added(Request $request)
	{
		
			$this->validate($request,
			[
			'title'=>'required',
			'code'=>'required',			
			]);
			
				$multilanguage = new MultiLanguage;				
				$multilanguage->title=$request->title;
				$multilanguage->code=$request->code;				
				$multilanguage->save();
			return redirect('admin/multilanguage')->with('message','Multilanguage Slug added successfully');
	}
	public function updated(Request $request)
	{	 
			$this->validate($request,
			[
			'title'=>'required',
			'code'=>'required',		
			]);
				
				DB::table('multilanguage')
				->where('id', $request->id)
				->update(['title' => $request->title,'code' => $request->code,]);

	return redirect('admin/multilanguage')->with('message','Multilanguage Slug updated successfully');
	}


	public function deleted(Request $request)
	{	 
	     $id=$request->id;  
		 $slug = DB::table('multilanguage')
		 ->where('id', $id)
		 ->delete();
		 $status['deletedid']=$id;
		 $status['deletedtatus']='multilanguage Slug deleted successfully';
	 return '{"delete_details": ' . json_encode($status) . '}'; 
	
	}

	public function destroy(Request $request)
		{
			$cn=count($request->selected_id);
			if($cn>0)
			{
			$data = $request->selected_id;			
				foreach($data as $id) {
				DB::table('multilanguage')->where('id', $id)->delete();			
				}			
			} 
	return redirect('admin/multilanguage')->with('message','Seltected multilanguage Slug are deleted successfully');			

	}
}