<?php

namespace App\Http\Controllers;
use DB;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\UserAccessModules;
use App\Models\User;
use App\Http\Controllers\Controller;
use Image;


class UserAccessModulesController extends Controller
{
    //
	public function index()
    {		
	  // $useraccessmodules= UserAccessModules::all();
	   
	    $useraccessmodules = DB::table('users')
		->select('*', 'users.id as uid')
		->leftJoin('role_user', 'users.id', '=', 'role_user.user_id')
		->where('role_user.role_id', '=', 2)
		->get();
	    $accessmodules = DB::table('access_modules')
		->get();
       return view('panels.admin.roles',['useraccessmodules'=>$useraccessmodules,'accessmodules'=>$accessmodules,'editaccessmodules'=>$accessmodules]);
    }
	
	public function getroles(Request $request)
		{
			 $id=$request->id;  
			 $access_modules = DB::table('user_access_modules')
			 ->where('user_id', $id)	 
			 ->first();   
		 return '{"view_details": ' . json_encode($access_modules) . '}';
	}
		
	public function added(Request $request)
	{
		 
			return redirect('admin/roles');
	}
	public function updated(Request $request)
	{	 
 $this->validate($request,
			 [
                'modules'          	=> 'required',              
               
            ],
            [
              
                'modules.required'    		=> 'Please check atleast one module',             	
               
            ]);			
			
				DB::table('user_access_modules')
				->where('id', $request->id)
				->update(['modules' => implode(',',$request->modules),]);

	return redirect('admin/roles')->with('message','User Access Modules updated successfully');
	}


	public function deleted(Request $request)
	{	 
	$id=$request->id;  
		 $user_access_modules = DB::table('user_access_modules')
		 ->where('user_id', $id)
		 ->delete();
		 $status['deletedid']=$id;
		 $status['deletedtatus']='User Access Module deleted successfully';
	 return '{"delete_details": ' . json_encode($status) . '}'; 
	
	}

	public function destroy(Request $request)
		{
			 $cn=count($request->selected_id);
			if($cn>0)
			{
			$data = $request->selected_id;			
				foreach($data as $id) {
				DB::table('user_access_modules')->where('user_id', $id)->delete();			
				}			
			} 
	return redirect('admin/roles')->with('message','Seltected User Access Modules are deleted successfully');			

	}
}