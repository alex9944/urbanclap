<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;

use App\Models\Localvendor;
use App\Models\LocalvendorMarketing;
use App\Models\States;
use App\Models\Cities;
use Image;
use DB;

class LocalVendorController extends Controller
{
	public function localvendorindex()
	{
		$localvendor= LocalVendor::all();
		
		$users = DB::table('users')
			->select('*', 'users.id as uid')
			->leftJoin('role_user', 'users.id', '=', 'role_user.user_id')
			->where('role_user.role_id', '=', 1)
			->get();
		
		$times = array();
		for($i = 1; $i <= 12; $i++)
		{
			$times[$i] = $i;
		}
		return view ('panels.admin.local-vendor.users',['localvendor'=>$localvendor,'users' => $users,'times'=>$times]);
	}
	
	public function getvendorlisting(Request $request)
	{
		 $id=$request->id;  
		 $vendorlisting = DB::table('local_vendor')
		 ->where('id', $id)	 
		 ->first();   
		return '{"view_details": ' . json_encode($vendorlisting) . '}';
	}
	
	// LocalVendor Added	
	public function added(Request $request)
	{
		
		
		  $this->validate($request,
			 [
			 
		'user_id'		=> 'required',
		'owner_name'	=> 'required',
		'category'	    => 'required',
		'title'			=> 'required',
		'description'	=> 'required',
		'address'		=> 'required',
		'photo'			=> 'required|image|mimes:jpeg,png,jpg,gif,svg|max:1024',
		'latitude'		=> 'required',
		'longitude'		=> 'required',
		'phone'		    => 'required',	
		'website'		=> 'required',
		       
            ],
            [
			'user_id.required'		=> 'Username is required',
			'owner_name.required'	=> 'Owner Name is required',
			'category.required'	    => 'Category is required',
			'title.required'		=> 'Site Title is required',
			'description.required'	=> 'Description is required',
			'address.required'		=> 'Address  is required',		
			'phone.required'		=> 'Phone is required',
			'photo.required'		=> 'required|image|mimes:jpeg,png,jpg,gif,svg|max:1024',
			'photo.image'           => 'Image should be a jpeg,png,gif,svg', 	'latitude.required'	    => 'Latitude is required',
            'longitude.required'	=> 'Longitude is required',		
		    'website.required'	    => 'Website is required', 
					 ]);
		
				$photo = $request->file('photo');
				$imagename = time().'.'.$photo->getClientOriginalExtension();   
				$destinationPath = public_path('/uploads/localvendor/thumbnail');
				$thumb_img = Image::make($photo->getRealPath())->crop(358, 200);
				$thumb_img->save($destinationPath.'/'.$imagename,80);                    
				$destinationPath = public_path('/uploads/localvendor/original');
				$photo->move($destinationPath, $imagename);
				$a= $request->start_time;
				$b= $request->start_time_ar;
				$ab=$a.$b;
				
				$ea= $request->end_time;
				$eb= $request->end_time_ar;
				$eab=$ea.$eb;
			    $time= $ab.'-'.$eab;
			
				$localvendor = new LocalVendor;				
				$localvendor->user_id=$request->user_id;
				$localvendor->owner_name=$request->owner_name;
				$localvendor->category=$request->category;
				$localvendor->title=$request->title;
				$localvendor->description=$request->description;		
				$localvendor->address=$request->address;
				$localvendor->image=$imagename;
				$localvendor->working_hours=$time;
				$localvendor->latitude=$request->latitude;
				$localvendor->longitude=$request->longitude;
				$localvendor->phone=$request->phone;
				$localvendor->website=$request->website;
				$localvendor->save();
				
				
			return redirect('admin/local-vendor/users')->with('message','Local Vendor Added Successfully');
	} 
	
	// LocalVendor Updated
	public function updated(Request $request)
	
	{	 
		//print_r($request);
		$this->validate($request,
			 [
	    'user_id'		=> 'required',
		'owner_name'	=> 'required',
		'category'	    => 'required',
		'title'			=> 'required',
		'description'	=> 'required',	
		'address'		=> 'required',		
		'latitude'		=> 'required',
		'longitude'		=> 'required',
		'phone'		    => 'required',		
		'website'		=> 'required',
		],
            [
			'user_id.required'		=> 'Username is required',
			'owner_name.required'	=> 'Owner Name is required',
			'category.required'	    => 'Category is required',
			'title.required'		=> 'Title is required',
			'description.required'	=> 'Description is required',
			'address.required'		=> 'Address  is required',		
			'latitude.required'		=> 'Latitude is required',						
			'longitude.required'	=> 'Longitude is required',
			'phone.required'		=> 'Phone is required',
			'website.required'		=> 'Website is required',
			]);
			
			$photo = $request->file('photo');
			 if($photo){
					$imagename = time().'.'.$photo->getClientOriginalExtension();   
					$destinationPath = public_path('/uploads/localvendor/thumbnail');
					$thumb_img = Image::make($photo->getRealPath())->crop(100, 100);
					$thumb_img->save($destinationPath.'/'.$imagename,80);                    
					$destinationPath = public_path('/uploads/localvendor/original');
					$photo->move($destinationPath, $imagename);
						  DB::table('local_vendor')
						->where('id', $request->id)
						->update(['image' => $imagename,]);
			 }
			 
			 $a= $request->start_time;
				$b= $request->start_time_ar;
				$ab=$a.$b;
				
				$ea= $request->end_time;
				$eb= $request->end_time_ar;
				$eab=$ea.$eb;
			    $time= $ab.'-'.$eab;
				DB::table('local_vendor')
						->where('id', $request->id)
						->update(['working_hours' => $time,]);
			
				DB::table('local_vendor')
				->where('id', $request->id)
				->update([
				'user_id' =>$request->user_id,
				'owner_name' =>$request->owner_name,
				'category' =>$request->category,
				'title' =>$request->title,
				'description' =>$request->description,				
				'address' =>$request->address,
				'latitude' =>$request->latitude,
				'longitude' =>$request->longitude,
				'phone' =>$request->phone,
				'website' =>$request->website,
				
				]);

	return redirect('admin/local-vendor/users')->with('message','Local Vendor Updated Successfully');
	}
	
	// LocalVendor Enable
	public function enable(Request $request)
	{	 
	
	//print_r($request->id);
	$id=$request->id;
	DB::table('local_vendor')
	->where('id', $request->id)
	->update(['status' => 'Disable',]);
	$status['deletedtatus']='Local Vendor status updated successfully';
	 return '{"delete_details": ' . json_encode($status) . '}'; 
	
	}
	
	// LocalVendor Disable		
	public function disable(Request $request)
	{	 
	$id=$request->id;
	DB::table('local_vendor')
	->where('id', $request->id)
	->update(['status' => 'Enable',]);
	$status['deletedStatus']='Local Vendor status updated successfully';
	 return '{"delete_details": ' . json_encode($status) . '}'; 
	
	}
	
	// LocalVendor Deleted		
	public function deleted(Request $request)
	{	 
	$id=$request->id;  
		 $blogs = DB::table('local_vendor')
		 ->where('id', $id)
		 ->delete();
		 $status['deletedid']=$id;
		 $status['deletedtatus']='Local Vendor Deleted Successfully';
	 return '{"delete_details": ' . json_encode($status) . '}'; 
	
	}
	
	// LocalVendor Destroy		
	public function destroy(Request $request)
	{
			$cn=count($request->selected_id);
			if($cn>0)
			{
			$data = $request->selected_id;			
				foreach($data as $id) {
				DB::table('local_vendor')->where('id', $id)->delete();			
				}			
			} 
		return redirect('admin/local-vendor/users')->with('message','Selected Local Vendor are deleted successfully');			

	}
	
	// Local vendor marketing
	public function qr_code()
	{
		$qr_codes = LocalvendorMarketing::where('connected_with_shop', 0)->get();
		
		return view ('panels.admin.local-vendor.qr_code', compact('qr_codes'));
	}
	
	public function qr_code_create(Request $request)
	{
		$no_of_code_to_generate = intval($request->no_of_code_to_generate);
		
		for($i=1; $i <= $no_of_code_to_generate; $i++)
		{
			$LocalvendorMarketing = new LocalvendorMarketing;
			$LocalvendorMarketing->unique_code = $LocalvendorMarketing->generateUniqueCode();//uniqid();
			$LocalvendorMarketing->save();
			
			$msg = 'QR Code created successfully';
		}
		
		if(isset($msg) )
			return redirect('admin/local-vendor/qr-code')->with('message', $msg);
		
		return redirect('admin/local-vendor/qr-code')->with('error_message', 'Invalid request');
	}
	
	public function qr_code_status_change(Request $request)
	{
		$id = $request->id;
		$current_status = $request->status;
		
		if($current_status == 'used') // already in used, change to not used
			$is_used = 0;
		else // already in not used, change to used
			$is_used = 1;
			
		$marketing_user = LocalvendorMarketing::find($id);
		
		if($marketing_user)
		{
			$marketing_user->is_used = $is_used;
			$marketing_user->save();
			
			return \Response::json(array(
				'success' => true,
				'msg'   => 'Status Updated.'
				));
		}
		
		return \Response::json(array(
			'success' => false,
			'msg'   => 'Invalid Id'
			));
	}
	
	public function qr_code_print(Request $request)
	{
		$id = $request->id;
		$type = $request->type;
		
		$marketing_user = LocalvendorMarketing::find($id);
		
		if($marketing_user)
		{			
			if($type == 'landscape')
				$view = \View::make('panels.admin.local-vendor.qr_code_print_landscape', ['marketing_user' => $marketing_user]);
			else if($type == 'portrait')
				$view = \View::make('panels.admin.local-vendor.qr_code_print_portrait', ['marketing_user' => $marketing_user]);
			$contents = $view->render();
			
			return \Response::json(array(
				'success' => true,
				'data' => $contents,
				'msg'   => 'Status Updated.'
				));
		}
		
		return \Response::json(array(
			'success' => false,
			'msg'   => 'Invalid Id'
			));
	}
	
	public function marketing_users()
	{
		$marketing_users = LocalvendorMarketing::where('connected_with_shop', 1)->get();
		
		$country_id = $this->default_country->id;
		
		$states = States::where('country_id', $country_id)->get();
		
		$cities = [];
		if(old('state_id')) {
			$cities = Cities::where('state_id', old('state_id'))->get();
		}
		
		return view ('panels.admin.local-vendor.marketing_users', compact('marketing_users', 'states', 'cities'));
	}
	
	public function get_marketing_user(Request $request)
	{
		$marketing_user = LocalvendorMarketing::with('usage_history.user', 'transactions')->find($request->id);
		
		if($marketing_user)
		{
			$marketing_user->all_cities = Cities::where('state_id', $marketing_user->state_id)->get();
			
			$data['marketing_user'] = $marketing_user;
			
			return \Response::json(array(
				'success' => true,
				'data' => $data,
				'msg'   => 'Success'
				));
		}
		
		return \Response::json(array(
			'success' => false,
			'msg'   => 'Invalid Id'
			));
	}	
	
	public function marketing_user_update(Request $request)
	{
		
		$LocalvendorMarketing = new LocalvendorMarketing;
		$rules = $LocalvendorMarketing->get_adding_rules();
		
		$this->validate($request, $rules['rules'], $rules['messages']);
		
		$marketing_user = LocalvendorMarketing::find($request->id);
		//marketing_person_id, marketing_person_name, shop_name, customer_name, address, phone, state_id, city_id
		if($marketing_user)
		{
			$marketing_user->shop_name = $request->shop_name;
			$marketing_user->customer_name = $request->customer_name;
			$marketing_user->address = $request->address;
			$marketing_user->phone = $request->phone;		
			$marketing_user->state_id = $request->state_id;
			$marketing_user->city_id = $request->city_id;
			$marketing_user->vendor_status = $request->vendor_status;
			$marketing_user->save();
			
			return redirect('admin/local-vendor/marketing-users')->with('message','Local Vendor Updated Successfully');
		}
		
		return redirect('admin/local-vendor/marketing-users')->with('error_message','Invalid Local Vendor');
	}

	public function marketing_user_delete($id)
	{
		$marketing_user = LocalvendorMarketing::find($id);
		
		if($marketing_user)
		{

			$marketing_user->delete();			
			
			\Session::flash('message', 'Selected code deleted successfully.');
			
			return \Response::json(array(
				'success' => true,
				'msg'   => 'Selected code deleted successfully.'
				));
		}
		
		return \Response::json(array(
			'success' => false,
			'msg'   => 'Invalid Id'
			));
	}

	public function marketing_user_detach($id)
	{
		$marketing_user = LocalvendorMarketing::find($id);
		
		if($marketing_user)
		{

			//$marketing_user->delete();
			// reset
			$marketing_user->marketing_person_id = null;
			$marketing_user->marketing_person_name = null;
			$marketing_user->shop_name = null;
			$marketing_user->customer_name = null;
			$marketing_user->address = null;
			$marketing_user->phone = null;		
			$marketing_user->state_id = null;
			$marketing_user->city_id = null;
			$marketing_user->vendor_status = 0;
			$marketing_user->connected_with_shop = 0;
			$marketing_user->save();
			
			\Session::flash('message', 'Selected vendor removed from QR code successfully.');
			
			return \Response::json(array(
				'success' => true,
				'msg'   => 'Selected vendor removed from QR code successfully.'
				));
		}
		
		return \Response::json(array(
			'success' => false,
			'msg'   => 'Invalid Id'
			));
	}
	
	public function marketing_users_destroy(Request $request)
	{
		$cn = count($request->selected_id);
		if($cn > 0)
		{
			LocalvendorMarketing::destroy($request->selected_id);	
		}
		
		return redirect('admin/local-vendor/qr-code')->with('message','Seltected rows are deleted successfully');
	}
}