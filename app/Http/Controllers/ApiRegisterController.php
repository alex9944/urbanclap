<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Traits\CaptchaTrait;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Traits\ActivationTrait;
use App\Models\User;
use App\Models\Role;
use Mail;

class ApiRegisterController extends Controller
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
   // protected $redirectTo = '/user';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

      $this->middleware('guest');

  }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     *
    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(Request $request)
    {
        $user =  User::create([
            'first_name' => $request->fname,
            'last_name' => $request->lname,
            'email' => $request->email,
            'mobile_no'=>$request->mobile,
            'password' => bcrypt($request->password),
            'referred'  =>$request->referred,
            'channel'   =>$request->channel,
            'token' => str_random(64),
            'activated' => !config('settings.activation')
        ]);
        if( isset($request->user_role) and in_array($request->user_role, array('user', 'merchant')) )
        {
            $role = Role::whereName($request->user_role)->first();
        }
        else
        {
            $role = Role::whereName('user')->first();
        }
        $user->assignRole($role);

        $this->initiateEmailActivation($user);

        if( isset($request->user_role) and $request->user_role == 'merchant' )
        {
            session(['merchant_id' => $user->id]);
            Mail::send('emails.login_credentials', ['first_name' =>$request->fname,'last_name' => $request->lname,'password'=>$request->password,'email'=>$request->email], function($message)use ($request) 
            {
                $email=$request->email;
                $message->to($email,'');
                $message->subject('Login Credentials');

            });
        }
        return json_encode($user);
    }
    public function signup(Request $request){
      $response=array();
      
            $exist_user=User::get()->where('email',$request->email);
         
            if(sizeof($exist_user)<=0){
                $user =  User::create([
                    'first_name' => $request->fname,
                    'email' => $request->email,
					'mobile_no'=>$request->mobile,
					'referred'  =>$request->referred,
                    'password' => bcrypt($request->password),
                    'token' => str_random(64),
                    'activated' => !config('settings.activation')
                ]);

                $role = Role::whereName('user')->first();

                $user->assignRole($role);

                $this->initiateEmailActivation($user);
                $response['success']='Registered Successfully';
            }
            else{
                $response['error']='Email already exists';
            }
       

        return json_encode($response);
    }
	
	
}