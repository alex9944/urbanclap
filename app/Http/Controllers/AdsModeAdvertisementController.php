<?php

namespace App\Http\Controllers;
use DB;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Models\AdsModeAdvertisement;
use App\Models\AdsPosition;
use App\Http\Controllers\Controller;



class AdsModeAdvertisementController extends Controller
{
    //
	public function index()
    {		
	   $modeads= AdsModeAdvertisement::all();
	   $adsposition= AdsPosition::all();
	   
       return view('panels.admin.ads.modeads',['modeads'=>$modeads,'adsposition'=>$adsposition, 'editadsposition'=>$adsposition]);
    }
	
	public function getmodeads(Request $request)
		{
			 $id=$request->id;  
			 $modeads = DB::table('ads_mode_advertisement')
			 ->where('id', $id)	 
			 ->first();   
		 return '{"view_details": ' . json_encode($modeads) . '}';
	}
		
	public function added(Request $request)
	{
		
		$this->validate($request,
			 [
                'title'            	=> 'required',               
               
            ],
            [
                'title.required'   	=> 'Title is required',
                          
               
            ]);
				$modeads = new AdsModeAdvertisement;				
				$modeads->title=$request->title;
				$modeads->a_p_id='';
				//$modeads->a_p_id=$request->a_p_id;				
				$modeads->save();
			return redirect('admin/ads/modeads')->with('message','Mode Advertisement added successfully');
	}
	public function updated(Request $request)
	{	 
			
			$this->validate($request,
			 [
                'title'            	=> 'required',               
               
            ],
            [
                'title.required'   	=> 'Title is required',
                          
               
            ]);
			
				//'a_p_id' => $request->a_p_id,
				DB::table('ads_mode_advertisement')
				->where('id', $request->id)
				->update(['title' => $request->title,]);

	return redirect('admin/ads/modeads')->with('message','Mode Advertisement updated successfully');
	}


	public function deleted(Request $request)
	{	 
	     $id=$request->id;  
		 $modeads = DB::table('ads_mode_advertisement')
		 ->where('id', $id)
		 ->delete();
		 $status['deletedid']=$id;
		 $status['deletedtatus']='Mode Advertisement deleted successfully';
	 return '{"delete_details": ' . json_encode($status) . '}'; 
	
	}

	public function destroy(Request $request)
		{
			$cn=count($request->selected_id);
			if($cn>0)
			{
			$data = $request->selected_id;			
				foreach($data as $id) {
				DB::table('ads_mode_advertisement')->where('id', $id)->delete();			
				}			
			} 
	return redirect('admin/ads/modeads')->with('message','Seltected mode advertisement are deleted successfully');			

	}
}