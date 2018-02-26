<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\User;
use App\Models\Role;
use App\Models\UserAccessModules;
use App\Models\GeneralUserType;
use App\Traits\ActivationTrait;
use App\Http\Controllers\Controller;
use Image;
use DB;

class GeneralUserController extends Controller {

    public function index()
    {		
        return view('panels.admin.general_users.users');
    }
	//Admin Users Display
	 public function users()
    {

		//$generaluser= GeneralUserType::all();	
	//Get Users Details
	    $users = DB::table('users')
		->select('*', 'users.id as uid')
		->leftJoin('role_user', 'users.id', '=', 'role_user.user_id')
		->where('role_user.role_id', '=', 1)
		->orderBy('users.created_at', 'Desc')
		->get();
		//'generaluser' => $generaluser,'edit_generaluser' => $generaluser
        return view('panels.admin.general_users.users', ['users' => $users,]);
    }
	
	//Admin Users Display
	public function getusers(Request $request)
		{
			 $id=$request->id;  
			 $users = DB::table('users')
			 ->where('id', $id)	 
			 ->first();   
		 return '{"view_details": ' . json_encode($users) . '}';
	}
	
	
	
	public function added(Request $request)
	{
		 
			$this->validate($request,
			 [
			   
                'firstname'            	=> 'required',
                'email'                 => 'required|email|unique:users',
				'mobile_no'                =>'required|numeric|unique:users,mobile_no',
                'password'              => 'required|min:6|max:20',
                'cpassword' 			=> 'required|same:password',
               
            ],
            [
			 
                'firstname.required'   	=> 'Name is required',
                'email.required'        => 'Email is required',
                'email.email'           => 'Email is invalid',
                'password.required'     => 'Password is required',
                'password.min'          => 'Password needs to have at least 6 characters',
                'password.max'          => 'Password maximum length is 20 characters',
				'cpassword.required'    => 'Confirm password is required',
               
            ]);
			
			
			
			$photo = $request->file('photo');
			 if($photo){
				$photo = $request->file('photo');
				$imagename = time().'.'.$photo->getClientOriginalExtension();   
				$destinationPath = public_path('/uploads/thumbnail');
				$thumb_img = Image::make($photo->getRealPath())->resize(100, 100);
				$thumb_img->save($destinationPath.'/'.$imagename,80);                    
				$destinationPath = public_path('/uploads/original');
				$photo->move($destinationPath, $imagename);
			 }else{
				$imagename="";				 
			 }
				$user = new User;
				$user->user_type=0;
				$user->first_name=$request->firstname;
				$user->mobile_no=$request->mobile_no;
				$user->email=$request->email;				
				$user->image=$imagename;
                $user->password=bcrypt($request->password);
                $user->token=str_random(64);
                $user->activated=1;				
				$user->save();	
				
				$role = Role::whereName('user')->first();				
				$user->assignRole($role);				 
							
				//$this->initiateEmailActivation($user);

			return redirect('admin/general/users')->with('message','Users added successfully');
	}
	public function updated(Request $request)
	{	 
		$user = User::find($request->id);
		
		$this->validate($request,
			 [
			 
                'firstname'            	=> 'required',
                'email'			=> 'required|email|unique:users,email,'.$user->id,
				'mobile_no'		=>'required|numeric|unique:users,mobile_no,'.$user->id,              
               
            ],
            [
			
                'firstname.required'   	=> 'First Name is required',
                'lastname.required'    	=> 'Last Name is required',
                'email.required'        => 'Email is required',
                'email.email'           => 'Email is invalid',              
               
            ]);
			
			$photo = $request->file('photo');
			 if($photo){
					$imagename = time().'.'.$photo->getClientOriginalExtension();   
					$destinationPath = public_path('/uploads/thumbnail');
					$thumb_img = Image::make($photo->getRealPath())->resize(100, 100);
					$thumb_img->save($destinationPath.'/'.$imagename,80);                    
					$destinationPath = public_path('/uploads/original');
					$photo->move($destinationPath, $imagename);
						 DB::table('users')
						->where('id', $request->id)
						->update(['image' => $imagename,]);
			 }
			
			if($request->password)
			{
				 DB::table('users')
					->where('id', $request->id)
					->update(['password' => bcrypt($request->password),]);				
			}
				DB::table('users')
				->where('id', $request->id)	
				->update(['first_name' => $request->firstname,'mobile_no' => $request->mobile_no,'email' => $request->email,]);

	return redirect('admin/general/users')->with('message','User updated successfully');
	}

	public function enable(Request $request)
	{	 
	$id=$request->id;
	DB::table('users')
	->where('id', $request->id)
	->update(['activated' => 2,]);
	$status['deletedtatus']='User status updated successfully';
	 return '{"delete_details": ' . json_encode($status) . '}'; 
	
	}
	public function disable(Request $request)
	{	 
	$id=$request->id;
	DB::table('users')
	->where('id', $request->id)
	->update(['activated' => 1,]);
	$status['deletedtatus']='User status updated successfully';
	 return '{"delete_details": ' . json_encode($status) . '}'; 
	
	}
	
	private function _delete($id, $success_msg, $is_continue = false)
	{
		$user = User::find($id);		
		$listing = $user->listing;
		$orders = $user->orders;
		$subscriptions = $user->subscriptions;
		
		$error_msg = '';
		
		if(isset($listing) and $listing) {			
			if(!$is_continue)
			{
				return \Response::json(array(
					'success' => false,
					'msg'   => 'Remove all listings before delete user.'
					));
			}
			else
			{
				$error_msg = 'Some users not deleted, because related listing exists.';
			}
		}
		if(isset($orders) and $orders->count() > 0) {				
			if(!$is_continue)
			{		
				return \Response::json(array(
					'success' => false,
					'msg'   => 'Remove all orders before delete user.'
					));
			}
			else
			{
				$error_msg = 'Some users not deleted, because related orders exists.';
			}
		}
		if(isset($subscriptions) and $subscriptions->count() > 0) {				
			if(!$is_continue)
			{		
				return \Response::json(array(
					'success' => false,
					'msg'   => 'Remove all subscriptions before delete user.'
					));
			}
			else
			{
				$error_msg = 'Some users not deleted, because related subscriptions exists.';
			}
		}
		
		if(!$is_continue)
		{
			$user->listing_reviews()->delete();
			$user->delete();
			
			\Session::flash('message', $success_msg);
				
			return \Response::json(array(
				'success' => true,
				'msg'   => $success_msg
				));
		}
		else if(!$error_msg)
		{
			$user->listing_reviews()->delete();
			$user->delete();
		}
		
		return $error_msg;
	}
	
	public function deleted(Request $request)
	{	 
		
		$id=$request->id;  
		
		$success_msg = 'Seltected row deleted successfully.';
		
		return $this->_delete($id, $success_msg); 
	
	}

	public function destroy(Request $request)
	{
		$msg = 'Seltected users are deleted successfully';
		$error_msg = '';
		
		$cn=count($request->selected_id);
		if($cn>0)
		{
			$data = $request->selected_id;			
			foreach($data as $id) {
				
				$return_msg = $this->_delete($id, $msg, true);
			
				if($return_msg)
					$error_msg = $return_msg;		
			}			
		} 
		
		if($error_msg)
			return redirect('admin/general/users')->with('error_message', $error_msg);
		
		return redirect('admin/general/users')->with('message', $msg);			

	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	/*
	protected function validator(array $data)
    {

     $data['captcha'] = $this->captchaCheck();
              'g-recaptcha-response'  => 'required',
                'captcha'               => 'required|min:1'
			   'g-recaptcha-response.required' => 'Captcha is required',
              'captcha.min'           => 'Wrong captcha, please try again.'
        $validator = Validator::make($data,
            [
                'first_name'            => 'required',
                'last_name'             => 'required',
                'email'                 => 'required|email|unique:users',
                'password'              => 'required|min:6|max:20',
                'password_confirmation' => 'required|same:password',
               
            ],
            [
                'first_name.required'   => 'First Name is required',
                'last_name.required'    => 'Last Name is required',
                'email.required'        => 'Email is required',
                'email.email'           => 'Email is invalid',
                'password.required'     => 'Password is required',
                'password.min'          => 'Password needs to have at least 6 characters',
                'password.max'          => 'Password maximum length is 20 characters',
               
            ]
        );

        return $validator;

    }
	 protected function create(array $data)
    {

        $user =  User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'token' => str_random(64),
            'activated' => !config('settings.activation')
        ]);

        $role = Role::whereName('user')->first();
        $user->assignRole($role);

        $this->initiateEmailActivation($user);

        return $user;

    }

	public function roles()
    {		
	//Get Users Details
	    $users = DB::table('users')
		->leftJoin('role_user', 'users.id', '=', 'role_user.user_id')
		->where('role_user.role_id', '=', 2)
		->get();
        return view('panels.admin.roles', ['users' => $users]);
    }*/
	
}