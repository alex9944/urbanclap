<?php

namespace App\Http\Controllers;
use DB;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Country;
use App\Models\States;
use App\Models\Cities;
use App\Http\Controllers\Controller;
use Image;


class CitiesController extends Controller
{
    //
	public function index()
    {		
	   $country= Country::all();	 
	   $states= States::all();
	   $cities= Cities::all();
	   
	    /*$country = DB::table('countries')
			 ->where('status', '1')
			 ->get();  
			 */
       return view('panels.admin.area.cities',['country'=>$country,'editcountry'=>$country,'editstates'=>$states,'states'=>$states,'cities'=>$cities]);
    }
	
	public function getcities(Request $request)
		{
		$id=$request->id; 	 
			 
		$cities = DB::table('cities')
		->select('*', 'cities.id as cid')
		->leftJoin('states', 'cities.state_id', '=', 'states.id')
		->where('cities.id', $id)	 
		->first();
		 return '{"view_details": ' . json_encode($cities) . '}';
	}
		
	public function added(Request $request)
	{
		  $this->validate($request,
			 [
                'cname'          	=> 'required',
                'name'            => 'required',                
				
               
            ],
            [
              
                'cname.required'    		=> 'Country Name is required',
                'name.required'        	=> 'State Name is required',
                		
               
            ]);
			
				
			
				$states = new States;
				$states->country_id=$request->cname;
				$states->name=$request->name;			
				$states->save();
			return redirect('admin/states')->with('message','States added successfully');
	}
	public function updated(Request $request)
	{	 
 $this->validate($request,
			 [
                'cname'          	=> 'required',
                'name'            => 'required',   
				
               
            ],
            [
              
                'cname.required'    		=> 'Country Name is required',
                'name.required'        	=> 'State Name is required',	
               
            ]);			
			
				DB::table('states')
				->where('id', $request->id)
				->update(['name' => $request->name,'country_id' => $request->cname,]);

	return redirect('admin/states')->with('message','States updated successfully');
	}

	
	public function deleted(Request $request)
	{	 
	$id=$request->id;  
		 $states = DB::table('states')
		 ->where('id', $id)
		 ->delete();
		 $status['deletedid']=$id;
		 $status['deletedtatus']='States deleted successfully';
	 return '{"delete_details": ' . json_encode($status) . '}'; 
	
	}

	public function destroy(Request $request)
		{
			$cn=count($request->selected_id);
				if($cn>0)
					{
					$data = $request->selected_id;			
							foreach($data as $id) {
							DB::table('states')->where('id', $id)->delete();			
						}			
					} 
	return redirect('admin/country')->with('message','Seltected states are deleted successfully');			

	}
}