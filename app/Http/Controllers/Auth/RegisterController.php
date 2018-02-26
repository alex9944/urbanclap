<?php

namespace App\Http\Controllers\Auth;

use App\Traits\CaptchaTrait;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Traits\ActivationTrait;
use App\Models\User;
use App\Models\Role;
use App\Models\Category;
use App\Models\MerchantServices;

use App\Events\SignupVerified;
use App\Events\Registered;

use Illuminate\Http\Request;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers, ActivationTrait, CaptchaTrait;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/user';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

        parent:: __construct();
		
		$this->middleware('guest');

    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
       if( isset($data['user_role']) and $data['user_role'] == 'merchant' ){

           $validator = Validator::make($data,
            [
            'category_id'           => 'required',
            'first_name'            => 'required',
            'email'                 => 'required|email|unique:users',
            'mobile'                =>'required|numeric|unique:users,mobile_no',
            'password'              => 'required|min:6|max:20',
            'password_confirmation' => 'required|same:password',

            ],
            [
            'category_id.required'   => 'Select a category',
            'first_name.required'   => 'First Name is required',
            'email.required'        => 'Email is required',
            'email.email'           => 'Email is invalid',
            'mobile.required'       => 'Mobile No is required',
            'mobile.numeric'        => 'Enter Valid Mobile number',
            'password.required'     => 'Password is required',
            'password.min'          => 'Password needs to have at least 6 characters',
            'password.max'          => 'Password maximum length is 20 characters',

            ]
            );
       }
       else
	   {
           $validator = Validator::make($data,
            [
			'first_name'            => 'required',
            'email'                 => 'required|email|unique:users',
            'mobile'                =>'required|numeric|unique:users,mobile_no',
            'password'              => 'required|min:6|max:20',
            'password_confirmation' => 'required|same:password',

            ],
            [
			'first_name.required'   => 'First Name is required',
            'email.required'        => 'Email is required',
            'email.email'           => 'Email is invalid',
            'mobile.required'       => 'Mobile No is required',
            'mobile.numeric'        => 'Enter Valid Mobile number',
            'password.required'     => 'Password is required',
            'password.min'          => 'Password needs to have at least 6 characters',
            'password.max'          => 'Password maximum length is 20 characters',

            ]
            );
       }

      //  $data['captcha'] = $this->captchaCheck();
                //'g-recaptcha-response'  => 'required',
               // 'captcha'               => 'required|min:1'
			   // 'g-recaptcha-response.required' => 'Captcha is required',
              //  'captcha.min'           => 'Wrong captcha, please try again.'

       return $validator;

   }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        if( isset($data['user_role']) and $data['user_role'] == 'merchant' )
		{
            $user =  User::create([
                'first_name' => $data['first_name'],
                'category_id' => $data['category_id'],
                'email' => $data['email'],
                'mobile_no'=>$data['mobile'],
                'referral_vendor_code'=>$data['referral_vendor_code'],//'BAN_529761',//
                'password' => bcrypt($data['password']),
                'token' => str_random(64),
                'verification_code' => $this->random_code(),
                'activated' => 0 //!config('settings.activation')
                ]);
        }
        else
		{
            $user =  User::create([
                'first_name' => $data['first_name'],
                'email' => $data['email'],
                'mobile_no'=>$data['mobile'],
                'referral_vendor_code'=>$data['referral_vendor_code'],//'BAN_529761',//
                'password' => bcrypt($data['password']),
                'token' => str_random(64),
                'verification_code' => $this->random_code(),
                'activated' => 0 // !config('settings.activation')
                ]);
        }
        

        if( isset($data['user_role']) and in_array($data['user_role'], array('user', 'merchant')) )
        {
           $role = Role::whereName($data['user_role'])->first();
		}
		else
		{
		   $role = Role::whereName('user')->first();
		}
		$user->assignRole($role);

		//$this->initiateEmailActivation($user);

		session(['user_id' => $user->id]);

		$this->redirectTo='/verify';

		return $user;

   }
   
	public function register(Request $request)
	{
		$this->validator($request->all())->validate();

		event(new Registered($user = $this->create($request->all())));

		//$this->guard()->login($user);

		return $this->registered($request, $user)
						?: redirect($this->redirectPath());
	}
   
	public function api_register(Request $request)
	{
		$return = array('status' => 0, 'msg' => 'Invalid Request', 'data' => []);
		//$this->validator($request->all())->validate();
		$validator = $this->validator($request->all());
		
		if ($validator->fails()) {    
		
			$return['msg'] = $validator->messages();	
			
		} else {
			
			event(new Registered($user = $this->create($request->all())));
			
			$this->registered($request, $user);
		
			$return['msg'] = 'Success';
			$return['status'] = 1;
			$return['data'] = $user;
		}

		return response()->json($return);
	}
	
	public function api_signup_verify(Request $request)
	{
		$return = array('status' => 0, 'msg' => 'Invalid Request', 'data' => []);
		
		$user_id = $request->user_id;
		
		if($user_id)
		{
		
			$user = User::where(['id' => $user_id, 'activated' => 0])->where('verification_code', '!=', '')->first();
			
			if($user)
			{
			
				$rules = [			
					'verification_code'			=> 'required|is_valid'
				];		
				$messages = [
					'verification_code.required'		=> 'Verification code is required',						
					'verification_code.is_valid'		=> 'Invalid verification code'
				];
				
				Validator::extendImplicit('is_valid', function ($attribute, $value, $parameters, $validator) use($user) {
					return $value == $user->verification_code;
				});
				
				$validator = Validator::make($request->all(), $rules, $messages);
				
				if ($validator->fails()) {    
		
					$return['msg'] = $validator->messages();	
					
				} else {
					
					// update user status
					$user->activated = 1;
					if ($user->hasRole('merchant'))
						$user->merchant_status = 1;
					$user->save();
		   
					// signup verified event
					event(new SignupVerified($user));
					
					//$this->guard()->login($user);
					
					if ($user->hasRole('merchant'))
					{
						//Merchant Dashboard
						$return['Listing_Category_id']= $user->category_id;
					
						$return['Listing_Sub_Category']= Category::where('parent_id',$user->category_id)->get();
						
						$return['listing'] = null;
						
						// statistics for 7 days
						$date = new \Carbon\Carbon;
						$date->subWeek();
						
						$return['listing_reviews'] = [];
						$return['visits'] = $return['reviews'] = $return['total_amount_week'] = $return['orders'] = 0;
						$return['appointments'] = $return['table_bookings'] = 0;
						$return['merchant_services'] = array(
							'table'			=> false,
							'appointment'	=> false,
							'food'			=> false,
							'shop'			=> false
						);
						
						if(isset($user->last_subscription->subscription_pricing))
						{
							// get subscription
							$subscription_pricing = $user->last_subscription->subscription_pricing;
							$subscribed_category = $user->subscribed_category;
							
							$features = explode(',', $subscription_pricing->f_id);
							$category_dependent_id = 7;		
							if(in_array($category_dependent_id, $features) and $subscribed_category) { 
								// merchant can access category services
								$category_type = $subscribed_category->category_type;
								$services_by_id = $this->services_by_id;
								
								if($category_type) {
									$category_types = json_decode($category_type);
									foreach($category_types as $category_type_id)
									{
										if( isset($services_by_id[$category_type_id]['slug']) and $services_by_id[$category_type_id]['slug'] == 'table' ) {
											$return['merchant_services']['table'] = true;
										} else if( isset($services_by_id[$category_type_id]['slug']) and $services_by_id[$category_type_id]['slug'] == 'appointment' ) {
											$return['merchant_services']['appointment'] = true;
										} else if( isset($services_by_id[$category_type_id]['slug']) and $services_by_id[$category_type_id]['slug'] == 'shop' ) {
											$return['merchant_services']['shop'] = true;
										} else if( isset($services_by_id[$category_type_id]['slug']) and $services_by_id[$category_type_id]['slug'] == 'food' ) {
											$return['merchant_services']['food'] = true;
										}
									}
								}
							}
						}
						
						$return['food_enable'] = $return['shop_enable'] = $return['appointment_enable'] = $return['table_booking_enable'] = false;
						
						
						/*** bar graph start ***/
						$sales_daily_in_current_month = [];
						$start_date = $date->toDateString();
						$end_date = date('Y-m-d');
						
						$sales_daily_in_current_month_new = array();
						$return['total_revenue'] = 0;
						$return['total_no_of_orders'] = 0;
						$interval = new \DateInterval('P1D');
						$period = new \DatePeriod(
							new \DateTime($start_date),
							$interval,
							(new \DateTime($end_date))->add($interval),
							\DatePeriod::EXCLUDE_START_DATE
						);
						
						$return['labels_graph_bar'] = [];
						foreach ($period as $date) {
							$total_price = 0;
							$cur_date = $date->format('Y-m-d');
							
							$return['labels_graph_bar'][] = array('day' => $cur_date, 'order_amount' => $total_price);
						}
						
						//print_r($labels_graph_bar);exit;
						/*** bar graph end ***/
						
						// traffic user
						$return['traffic']	= array('new' => 0, 'exists' => 0);						

						// traffic device
						$return['device_traffic']	= [];
						
						// recent orders
						$return['recent_orders'] =[];
						
					} 
					else
					{						
						$return['Review_Count']= 0;						
						$return['Contact_Count']= 0;						
						$return['QR_Code_Count']= 0;						
						$return['User_Cart_Count']= 0;
					}
				
					$return['msg'] = 'Success';
					$return['status'] = 1;
					$return['data'] = $user;
				}
				
			}
		}
		
		return response()->json($return);
	}

	public function freeListing()
	{
		return view('auth.free_listing');
	}
		
	public function verify()
	{
		$user_id = session('user_id');
		
		if($user_id)
		{
		
			$user = User::find($user_id);
			
			if($user)
			{
				return view('auth.verify');
			}
		}
		
		return redirect('login');
	}
	
	public function signup_verify(Request $request)
	{
		$user_id = session('user_id');
		
		if($user_id)
		{
		
			$user = User::find($user_id);
			
			if($user)
			{
			
				$rules = [			
					'verification_code'			=> 'required|is_valid'
				];		
				$messages = [
					'verification_code.required'		=> 'Verification code is required',						
					'verification_code.is_valid'		=> 'Invalid verification code'
				];
				
				Validator::extendImplicit('is_valid', function ($attribute, $value, $parameters, $validator) use($user) {
					return $value == $user->verification_code;
				});

				$this->validate($request, $rules, $messages);
				
				// update user status
				$user->activated = 1;
				if ($user->hasRole('merchant'))
					$user->merchant_status = 1;
				$user->save();
	   
				// signup verified event
				event(new SignupVerified($user));
				
				$this->guard()->login($user);
				
				if ($user->hasRole('merchant'))
					$this->redirectTo='/merchant/listing';
				
				return redirect($this->redirectPath());
			}
		}
		
		return redirect('login');
	}
	
	public function resend_verify_code()
	{
		$user_id = session('user_id');
		
		if($user_id)
		{
		
			$user = User::find($user_id);
			
			if($user)
			{	   
				// update code
				$user->verification_code = $this->random_code();
				$user->save();
				
				//$this->_sendVerifyCodeMail($user);
				//$this->_sendSMSToUser($user);
				
				return redirect('/verify')->with('success_message',"Verification code has been sent to your registered email. Please check.");
			}
		}
		
		return redirect('/login')->with('message',"Invalid User");
	}
	
	private function _sendSMSToUser($user)
	{
		$msg = 'Please use the verification code to complete the signup progress: ' . $user->verification_code;
		
		$mobile_no = $user->mobile_no;
		$verification_code = $user->verification_code;
		
		if($mobile_no and $verification_code) {
		
			try {
				
				$sms_content = rawurlencode($msg);
				
				//$sms_content='Your%20verification%20code%20is%20'.$mail_content['verification_code'].'%20This%2code%20is%20working%20on%2012%20hours';
				
				//$url = "http://trans.smsfresh.co/api/sendmsg.php?user=malathy&pass=WiFiNIM@2017&sender=IMARTS&phone=".$mobile_no."&text=".$sms_content."&priority=ndnd&stype=normal";
				
				$url = "http://trans.smsfresh.co/api/sendmsg.php?user=needifo&pass=123456&sender=APOYOU&phone=".$mobile_no."&text=".$sms_content."&priority=ndnd&stype=normal";
			
				$ch = curl_init();
				// Disable SSL verification
				//curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				// Will return the response, if false it print the response
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				// Set the url
				curl_setopt($ch, CURLOPT_URL,$url);
				// Execute
				$result=curl_exec($ch);
				// Closing
				curl_close($ch);
				
			} catch(\Exception $e) {
				//print_r($e->getMessage());
			}
		}
	}
	
	private function _sendVerifyCodeMail($user)
	{
		$settings = $this->site_settings;
		
		$mail_content = array();
		$mail_content['email'] = $user->email;
		$mail_content['name'] = $user->first_name;
		$mail_content['verification_code'] = $user->verification_code;
		$mail_content['site_name'] = ($settings->site_name != '') ? $settings->site_name : 'Needifo';
		// admin email
		if( isset($settings->notification_email) )
			$mail_content['admin_mail'] = $settings->notification_email;
		\Mail::send('emails.resend_verification_code', $mail_content, function($message)use ($mail_content) 
		{
			$email = $mail_content['email'];
			$message->to($email,'');
			
			if(isset($mail_content['admin_mail']))
				$message->bcc($mail_content['admin_mail'],'');
			
			$site_name = $mail_content['site_name'];
			$message->subject($site_name . ' | Your Verification Code');

		});
	}
 

}