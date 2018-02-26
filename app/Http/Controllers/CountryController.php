<?php

namespace App\Http\Controllers;
use DB;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Country;
use App\Http\Controllers\Controller;
use Image;


class CountryController extends Controller
{
    //
	public function index()
    {		
	   $country= Country::all();
       return view('panels.admin.area.index',['country'=>$country]);
    }
	
	public function getcountry(Request $request)
		{
			 $id=$request->id;  
			 $country = DB::table('countries')
			 ->where('id', $id)	 
			 ->first();   
		 return '{"view_details": ' . json_encode($country) . '}';
	}
		
	public function added(Request $request)
	{
		  $this->validate($request,
			 [
                'name'          	=> 'required',
                'sortname'            => 'required',                
				
               
            ],
            [
              
                'name.required'    		=> 'Name is required',
                'sortname.required'        	=> 'Sortcode is required',
                		
               
            ]);
			
				
			
				$country = new Country;
				$country->name=$request->name;
				$country->sortname=$request->sortname;			
				$country->save();
			return redirect('admin/country')->with('message','Country added successfully');
	}
	public function updated(Request $request)
	{	 
 $this->validate($request,
			 [
                'name'          	=> 'required',
                'sortname'            => 'required',   
				
               
            ],
            [
              
                'name.required'    		=> 'Name is required',
                'sortname.required'        	=> 'Sortcode is required',	
               
            ]);			
			
				DB::table('countries')
				->where('id', $request->id)
				->update(['name' => $request->name,'sortname' => $request->sortname,]);

	return redirect('admin/country')->with('message','Country updated successfully');
	}

	public function enable(Request $request)
	{	 
	$id=$request->id;
	DB::table('countries')
	->where('id', $request->id)
	->update(['status' => 2,]);
	$status['deletedtatus']='Country status updated successfully';
	 return '{"delete_details": ' . json_encode($status) . '}'; 
	
	}
	public function disable(Request $request)
	{	 
	$id=$request->id;
	DB::table('countries')
	->where('id', $request->id)
	->update(['status' => 1,]);
	$status['deletedtatus']='Country status updated successfully';
	 return '{"delete_details": ' . json_encode($status) . '}'; 
	
	}
	public function deleted(Request $request)
	{	 
	$id=$request->id;  
		 $country = DB::table('countries')
		 ->where('id', $id)
		 ->delete();
		 $status['deletedid']=$id;
		 $status['deletedtatus']='Country deleted successfully';
	 return '{"delete_details": ' . json_encode($status) . '}'; 
	
	}

	public function destroy(Request $request)
		{
			$cn=count($request->selected_id);
				if($cn>0)
					{
					$data = $request->selected_id;			
							foreach($data as $id) {
							DB::table('countries')->where('id', $id)->delete();			
						}			
					} 
	return redirect('admin/country')->with('message','Seltected Country are deleted successfully');			

	}
}