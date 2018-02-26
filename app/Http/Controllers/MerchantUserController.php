<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\User;
use App\Models\Role;
use App\Models\UserAccessModules;
use App\Traits\ActivationTrait;
use App\Http\Controllers\Controller;
use App\Models\Listing;
use App\Models\Merchants;
use App\Models\VendorSubscription;
use App\Models\PaymentGatewaySiteSettings;
use App\Models\Category;

use App\Events\SignupVerified;
use Razorpay\Api\Api;
use Image;
use DB;
use Mail;

class MerchantUserController extends Controller {

    public function index()
    {		
        return view('panels.admin.merchants.users');
    }
	//Admin Users Display
	 public function users()
    {		
	//Get Users Details
	    $users = DB::table('users')
		->select('users.*', 'users.id as uid')
		->leftJoin('role_user', 'users.id', '=', 'role_user.user_id')
		->where('role_user.role_id', '=', 3)
		->orderBy('users.merchant_status', 'ASC')
		->orderBy('users.created_at', 'DESC')
		->get();
		
		$catObj = new Category();
		$categories = $catObj->getCategories();
		
        return view('panels.admin.merchants.users', ['users' => $users, 'categories' => $categories]);
    }
	
	//Admin Users Display
	public function getusers(Request $request)
	{
		$id=$request->id;  
		$data['users'] = Merchants::find($id); 
		$data['listing'] = $data['users']->listing;
		$data['listing_detail'] = [];
		
		if($data['listing'])
		{
			$listing_address = $data['listing']->address1;
			if($data['listing']->address2)
				$listing_address .= ', <br />'.$data['listing']->address2;
			$listing_address .= ', <br />'.$data['listing']->listing_city->name;
			$listing_address .= ', <br />'.$data['listing']->listing_state->name;
			
			$listing_detail = array('listing_category' => $data['listing']->category->c_title,
									'listing_subcategory' => isset($data['listing']->subcategory->c_title) ? $data['listing']->subcategory->c_title : '',
									'listing_website' => $data['listing']->website,
									'listing_address' => $listing_address,
									'listing_description' => $data['listing']->description,
									);
			$data['listing_detail'] = $listing_detail;
		}
		
		$subscriber = $data['users']->last_subscription;
		$data['subscription_category'] = $data['users']->subscribed_category;
		if($subscriber)
		{
			$plan_detail = json_decode($subscriber->plan_detail);
			
			$data['subscriber'] = $subscriber;
			$data['plan_detail'] = $plan_detail;
			$data['currency'] = $subscriber->currency;
			$data['merchant'] = $subscriber->merchant;
			$data['subscriptions_count'] = $subscriber->merchant->subscriptions()->where('payment_status', 'Success')->count();
		}
		
		return '{"view_details": ' . json_encode($data) . '}';
	}
	
	public function deactivate_and_refund($subscription_id)
	{
		$error_message = 'Invalid subscription!';
		
		$VendorSubscription = VendorSubscription::find($subscription_id);
		
		if($VendorSubscription)
		{
			if($VendorSubscription->paid_amount != 0 and $VendorSubscription->payment_status == 'Success')
			{
			
				$vendor = $VendorSubscription->vendor;
				$listing = $vendor->listing;
				$listing = $vendor->listing;
				
				// listing status update
				if($listing)
				{
					$merch_listing = Listing::find($listing->id);
					$merch_listing->status = 'Disable';
					$merch_listing->save();				
				}
				// merchant status update in user table
				if($vendor)
				{
					$Merchants = Merchants::find($vendor->id);
					$Merchants->merchant_status = 2; // De-activated
					$Merchants->save();	
					
					$error_message = 'Refund not made. Payment is not captured already. Captured payment only permitted for refund process.';
					
					if($vendor->razorpay_payment_id)
					{
						// refund process
						$payment_gateway_id = 3;	// razor
						$gateway_settings = PaymentGatewaySiteSettings::where(['payment_gateway_site_id' => $payment_gateway_id])->first();
						
						$api_key = $gateway_settings->api_key;
						$secret_key = $gateway_settings->secret_key;
						
						$api = new Api($api_key, $secret_key);
						
						$payment = $api->payment->fetch($vendor->razorpay_payment_id);
						
						if($payment)
						{			
							if($payment['status'] == 'captured')
							{
								$refund = $api->refund->create(array('payment_id' => $id));
								
								$VendorSubscription->refund_info = serialize($refund);
								$VendorSubscription->save();
								
								$error_message = '';
							}				
						}
					}
				}
			}
			else
			{
				$error_message = 'Free subscription Or Payment is not success already';
			}
			
			if($error_message == '') {
				return redirect('admin/merchants/users')->with('message','Selected user deactivated successfully and the subscription amount refunded to the user.');
			}
		}
		
		return redirect('admin/merchants/users')->with('error_message', $error_message);
	}
	
	public function added(Request $request)
	{
		 
			$this->validate($request,
			 [
                'category_id'           => 'required',
				'firstname'            => 'required',
				'email'                 => 'required|email|unique:users',
				'mobile_no'                =>'required|numeric|unique:users,mobile_no',
				'password'              => 'required|min:6|max:20',
				'password_confirmation' => 'required|same:password',
               
            ],
            [
                'category_id.required'   => 'Select a category',
				'firstname.required'   => 'Name is required',
				'email.required'        => 'Email is required',
				'email.email'           => 'Email is invalid',
				'mobile_no.required'       => 'Mobile No is required',
				'mobile_no.numeric'        => 'Enter Valid Mobile number',
				'password.required'     => 'Password is required',
				'password.min'          => 'Password needs to have at least 6 characters',
				'password.max'          => 'Password maximum length is 20 characters',
               
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
				$user->first_name=$request->firstname;
				$user->category_id=$request->category_id;
				$user->email=$request->email;	
				$user->mobile_no=$request->mobile_no;
				$user->website=$request->website;
				$user->bio=$request->bio;			
				$user->image=$imagename;
                $user->password=bcrypt($request->password);
                $user->token=str_random(64);
                $user->activated=1;	
				$user->merchant_status = $request->merchant_status;
				$user->save();	
				
				$role = Role::whereName('merchant')->first();				
				$user->assignRole($role);	

				// signup verified event
				event(new SignupVerified($user));
							
				//$this->initiateEmailActivation($user);

			return redirect('admin/merchants/users')->with('message','Users added successfully');
	}
	public function updated(Request $request)
	{		
		$user = User::find($request->id);
		
		$rules = [
			'category_id'           => 'required',
			'firstname'			=> 'required',
			'email'			=> 'required|email|unique:users,email,'.$user->id,
			'mobile_no'		=>'required|numeric|unique:users,mobile_no,'.$user->id,
		];				
		$messages = [
			'category_id.required'   => 'Select a category',
			'firstname.required'   		=> 'Name is required',
			'email.required'        => 'Email is required',
			'email.email' 			=> 'Email is invalid',
			'mobile_no.required'    => 'Mobile No is required',
			'mobile_no.numeric'     => 'Enter Valid Mobile number',
		];
		
		$this->validate($request, $rules, $messages);
			
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
			
			
		$merchant_status = $user->merchant_status;
		$listing_status = $request->listing_status;
			
		/*if($listing_status)
		{
			$merch_listing = Listing::where('user_id', $request->id)->first();
			$merch_listing->status = $listing_status;
			$merch_listing->save();
		}
		
		if($merchant_status != $request->merchant_status and $request->merchant_status != 0)
		{
			
			$user_arr = $user->toArray();//print_r($user_arr);exit;
			if($request->merchant_status == 1)
			{
				$user_arr['subject'] = 'Your profile approved. You can use our service.';
				$template = 'emails.user_activated';
			}
			else if($request->merchant_status == 2)
			{
				$user_arr['subject'] = 'Sorry! Your profile has been Dis-approved. Please contact us for further information.';
				$template = 'emails.user_de_activated';
			}
			// send notification for user
			Mail::send($template, $user_arr, function($message)use ($user_arr) 
			{
				$email = $user_arr['email'];
				$subject = $user_arr['subject'];
				
				$message->to($email);
				$message->subject($subject);

			});
		}*/
		//,'category_id' => $request->category_id
		DB::table('users')
			->where('id', $request->id)	
			->update(['first_name' => $request->firstname,
			'mobile_no' => $request->mobile_no,
			'email' => $request->email, 
			'website' => $request->website, 
			'bio' => $request->bio, 
			'merchant_status' => $request->merchant_status]);

		return redirect('admin/merchants/users')->with('message','User updated successfully');
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