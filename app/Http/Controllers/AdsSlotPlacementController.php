<?php

namespace App\Http\Controllers;
use DB;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Models\AdsSlotPlacement;
use App\Http\Controllers\Controller;



class AdsSlotPlacementController extends Controller
{
    //
	public function index()
    {		
	   $slotplacement= AdsSlotPlacement::all();
       return view('panels.admin.ads.slotplacement',['slotplacement'=>$slotplacement]);
    }
	
	public function getslotplacement(Request $request)
		{
			 $id=$request->id;  
			 $slotplacement = DB::table('ads_slot_placement')
			 ->where('id', $id)	 
			 ->first();   
		 return '{"view_details": ' . json_encode($slotplacement) . '}';
	}
		
	public function added(Request $request)
	{
		
		$this->validate($request,
			 [
                'title'            	=> 'required',               
               
            ],
            [
                'title.required'   	=> 'Position Title is required',
                          
               
            ]);
				$slotplacement = new AdsSlotPlacement;				
				$slotplacement->title=$request->title;				
				$slotplacement->save();
			return redirect('admin/ads/slotplacement')->with('message','Slot placement added successfully');
	}
	public function updated(Request $request)
	{	 
			
			$this->validate($request,
			 [
                'title'            	=> 'required',               
               
            ],
            [
                'title.required'   	=> 'Slot placement Title is required',
                          
               
            ]);
			
				
				DB::table('ads_slot_placement')
				->where('id', $request->id)
				->update(['title' => $request->title,]);

	return redirect('admin/ads/slotplacement')->with('message','Slot placement updated successfully');
	}


	public function deleted(Request $request)
	{	 
	     $id=$request->id;  
		 $slotplacement = DB::table('ads_slot_placement')
		 ->where('id', $id)
		 ->delete();
		 $status['deletedid']=$id;
		 $status['deletedtatus']='Slot placement deleted successfully';
	 return '{"delete_details": ' . json_encode($status) . '}'; 
	
	}

	public function destroy(Request $request)
		{
			$cn=count($request->selected_id);
			if($cn>0)
			{
			$data = $request->selected_id;			
				foreach($data as $id) {
				DB::table('ads_slot_placement')->where('id', $id)->delete();			
				}			
			} 
	return redirect('admin/ads/slotplacement')->with('message','Seltected slot placement are deleted successfully');			

	}
}