<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Category;
use DB;
use Hash;
class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Auth guard
     *
     * @var
     */
    protected $auth;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = 'user';

    /**
     * Create a new controller instance.
     *
     * LoginController constructor.
     * @param Guard $auth
     */
    public function __construct(Guard $auth)
    {
        parent:: __construct();
		
		$this->middleware('guest', ['except' => 'logout']);
        $this->auth = $auth;
		$catObj = new Category();
			$categories = $catObj->getCategories();
			view()->share(['categories' => $categories,]);
    }
	
	public function showLoginFormRedirect()
	{
		if(!session()->has('url.intended'))
		{
			session(['url.intended' => url()->previous()]);
		}
		
		return view('auth.login');    
	}

    public function login(Request $request)
    {
        $email      = $request->get('email');
        $password   = $request->get('password');
        $remember   = $request->get('remember');		
	
		 $this->validate($request,
			 [
                'email'          	=> 'required|email',
                'password'            => 'required', 
               
            ],
            [
              
                'loginemail.required'    		=> 'Email is required',
				 'loginemail.email'    		=> 'Email is invalid',
                'loginpassword.required'        	=> 'Password is required',             	
               
            ]);
			
       /* $pass=md5($password);

        $repizee=DB::connection('mysql2')->select(DB::raw("SELECT * FROM tbl_users WHERE email = '$email' AND password='$pass'"));*/

        if ($this->auth->attempt([
            'email'     => $email,
            'password'  => $password
            ], $remember == 1 ? true : false)) 
		{
			
			if($this->auth->user()->activated == '0') {
				
				$user_id = $this->auth->user()->id;
				Auth::logout();
				
				session(['user_id' => $user_id]);
				
				return redirect('/verify')->with('warning',"Please activate your account.");
				//return back()->with('warning',"First please active your account.");
				
            } else {
					   //General User Check
				if ( $this->auth->user()->hasRole('user')) {
					//return redirect()->route('user.home');
					return redirect()->intended('user');
				}
				if ($this->auth->user()->hasRole('merchant')) {
					
					/*
					$merchant=DB::table('users')
					->where('email',$email)
					->where('verified','1')->get();

					if(sizeof($merchant)>0){
						 return redirect()->route('merchant.home');
					}
					else{
						$id=$this->auth->user()->id;
						session(['merchant_id' => $id]); 
						return view('panels.merchant.front.profile');
					}
					*/
					//return redirect()->route('merchant.home');
					return redirect()->intended('merchant');
				}
				//Admin User Check
				if ( $this->auth->user()->hasRole('administrator')) {
					//return redirect()->route('admin.home');
					return redirect()->intended('admin');
				}
			}

        }
        else {

            return redirect()->back()
			->with('message',"Incorrect email or password.")
            ->withInput();
        }

    }

}