<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

use App\Models\User;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = 'user';

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
	
	public function api_reset_password(Request $request)
    {
		$return = array('status' => 0, 'msg' => 'Invalid Request', 'data' => []);
		
		$validator = \Validator::make($request->all(),
            [
			'token'            => 'required',
            'email'                 => 'required|email',
            'password'              => 'required|min:6|max:20',
            'password_confirmation' => 'required|same:password',
            ]
        );
		
		if ($validator->fails()) {    
		
			$return['msg'] = $validator->messages();	
			
		} else {
			
			$credentials = \Input::only(
				'email', 'password', 'password_confirmation', 'token'
			);
			
			$response = $this->broker()->reset(
				$this->credentials($request), function ($user, $password) {
					$this->api_resetPassword($user, $password);
				}
			);
			
			if ($response == Password::PASSWORD_RESET)
			{
				$user = User::where(['email' => $request->email])->first();
				
				$return['msg'] = 'Success';
				$return['status'] = 1;
				$return['data'] = $user;
			}
			else
			{
				$return['msg'] = 'Invalid credential';
			}
		}
		
		return response()->json($return);
		
	}

    /**
     * Reset the given user's password.
     *
     * @param  \Illuminate\Contracts\Auth\CanResetPassword  $user
     * @param  string  $password
     * @return void
     */
    protected function api_resetPassword($user, $password)
    {
        $user->forceFill([
            'password' => bcrypt($password),
            'remember_token' => Str::random(60),
        ])->save();

        //$this->guard()->login($user);
    }
	
	public function reset_api(Request $request)
    {
       // $this->validate($request, $this->rules(), $this->validationErrorMessages());
        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
		
		
		//return $request->email;
		
        $response = $this->broker()->reset(
            $this->credentials($request), function ($user, $password) {
                $this->resetPassword($user, $password);
            }
        );
	
       // if ($request->wantsJson()) {
      if ($response == Password::PASSWORD_RESET) {
				$responsecode = 200;        
      $header = array (
                'Content-Type' => 'application/json; charset=UTF-8',
                'charset' => 'utf-8'
            );  
			$data['status']='success';
			return response()->json($data , $responsecode, $header, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
               // return response()->json(Json::response(null, trans('passwords.reset')));
            } else {
				$responsecode = 200;        
      $header = array (
                'Content-Type' => 'application/json; charset=UTF-8',
                'charset' => 'utf-8'
            );  
			$data['status']='Invalid email';
			return response()->json($data , $responsecode, $header, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            //    return response()->json(Json::response($request->input('email'), trans($response), 202));
         //   }
        }
		
		
        // If the password was successfully reset, we will redirect the user back to
        // the application's home authenticated view. If there is an error we can
        // redirect them back to where they came from with their error message.
        //return $response == Password::PASSWORD_RESET
       // ? $this->sendResetResponse($response)
       // : $this->sendResetFailedResponse($request, $response);
    }

}