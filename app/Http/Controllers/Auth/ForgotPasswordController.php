<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;

use App\Events\ForgetPassword;
use App\Models\User;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

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
	
	/*public function sendResetLinkEmail(Request $request)
    {
        $email = $request->email;
		if($email)
		{
			$user = User::where(['email' => $email])->first();
			
			\Validator::extendImplicit('is_valid', function ($attribute, $value, $parameters, $validator) use($user) {
				return isset($user->id);
			});
		}
		$messages = [
				'email.required'		=> 'Email is required',						
				'email.email'			=> 'Invalid email',				
				'email.is_valid'		=> 'Invalid email'
			];
		$this->validate($request, ['email' => 'required|email|is_valid'], $messages);

        $user->verification_code = $this->random_code();
		$user->save();
		
		event(new ForgetPassword($user));
		
		$token = $this->broker()->createToken($user);
		
		$response['token'] = $token;
		
		return back()->with('status', trans($response));
    }*/
	
	public function api_forget_password(Request $request)
    {
		$return = array('status' => 0, 'msg' => 'Invalid Request', 'data' => []);
		
		$email = $request->email;
		
		if($email)
		{
			$user = User::where(['email' => $email])->first();
			
			$rules = [			
				'email' => 'required|email|is_valid'
			];	
			$messages = [
				'email.required'		=> 'Email is required',						
				'email.email'			=> 'Invalid email',				
				'email.is_valid'		=> 'Invalid email'
			];
			
			\Validator::extendImplicit('is_valid', function ($attribute, $value, $parameters, $validator) use($user) {
				return isset($user->id);
			});
			
			$validator = \Validator::make($request->all(), $rules, $messages);			
			
			if ($validator->fails()) {    
		
				$return['msg'] = $validator->messages();	
				
			} else {
				
				// create verification code
				$user->verification_code = $this->random_code();
				$user->save();
				
				event(new ForgetPassword($user));
				
				$token = $this->broker()->createToken($user);
			
				$return['msg'] = 'Success';
				$return['status'] = 1;
				$return['data'] = $user;
				$return['token'] = $token;
			}
		}
		
		return response()->json($return);
	}
	
	public function api_forget_password_verify(Request $request)
	{
		$return = array('status' => 0, 'msg' => 'Invalid Request', 'data' => []);
		
		$user_id = $request->user_id;
		
		if($user_id)
		{
			$user = User::where(['id' => $user_id])->where('verification_code', '!=', '')->first();
			
			$rules = [			
				'verification_code'			=> 'required|is_valid'
			];		
			$messages = [
				'verification_code.required'		=> 'Verification code is required',						
				'verification_code.is_valid'		=> 'Invalid verification code'
			];
			
			\Validator::extendImplicit('is_valid', function ($attribute, $value, $parameters, $validator) use($user) {
				return $value == $user->verification_code;
			});
			
			$validator = \Validator::make($request->all(), $rules, $messages);
			
			if ($validator->fails()) {    
		
				$return['msg'] = $validator->messages();	
				
			} else {
				
				$return['msg'] = 'Success';
				$return['status'] = 1;
				$return['data'] = $user;
			}
		}
		
		return response()->json($return);
		
	}	
	
	public function getResetToken(Request $request)
    {
		//return $request->email;
		
        $this->validate($request, ['email' => 'required|email']);
      //  if ($request->wantsJson()) {
			
            $user = User::where('email', $request->email)->first();
			//dd(DB::getQueryLog($user));
            if (!$user) {
				
              //  return response()->json(Json::response(null, trans('passwords.user')), 400);
			  $responsecode = 200;        
		  $header = array (
					'Content-Type' => 'application/json; charset=UTF-8',
					'charset' => 'utf-8'
				);  
			$data['status']='Invalid email';
			return response()->json($data , $responsecode, $header, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            }
            $token = $this->broker()->createToken($user);
			$responsecode = 200;        
      $header = array (
                'Content-Type' => 'application/json; charset=UTF-8',
                'charset' => 'utf-8'
            );  
			$data['status']=$token;
			return response()->json($data , $responsecode, $header, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
          //  return response()->json(Json::response(['token' => $token]));
       // }
    }
}