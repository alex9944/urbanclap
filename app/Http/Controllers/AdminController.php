<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\User;
use App\Models\Role;
use App\Models\Listing;
use App\Models\UserAccessModules;
use App\Models\VendorSubscription;
use App\Traits\ActivationTrait;
use App\Http\Controllers\Controller;
use Image;
use DB;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use App\Models\OrderBooking;

class AdminController extends Controller {

    public function index()
    {		
        $recent_subscriptions = VendorSubscription::where('payment_status', 'Success')
                ->orderBy('created_at', 'DESC')
                ->limit(5)
                ->get();
		
		/*$pending_profiles = DB::table('users')
				->select('*', 'users.id as uid')
				->leftJoin('role_user', 'users.id', '=', 'role_user.user_id')
				->where(['role_user.role_id' => 1, 'users.merchant_status' => 0])
				->get();//print_r($pending_profiles); */
				
		// statistics for 7 days
		$date = new \Carbon\Carbon;
		$date->subWeek();
		
		// User Count 
		$users_count_total = DB::table('users')
				->select('users.id as uid')
				->leftJoin('role_user', 'users.id', '=', 'role_user.user_id')
				->where(['role_user.role_id' => 1])
				->count();
		$users_count = DB::table('users')
				->select('users.id as uid')
				->leftJoin('role_user', 'users.id', '=', 'role_user.user_id')
				->where(['role_user.role_id' => 1])
				->where('users.created_at', '>=', $date->toDateTimeString() )
				->count();
		
		//Non Verified Merchants Count 
		$non_verified_merchants_count_total = DB::table('users')
				->select('users.id as uid')
				->leftJoin('role_user', 'users.id', '=', 'role_user.user_id')
				->where(['role_user.role_id' => 3, 'users.merchant_status' => 0])
				->count();
		$non_verified_merchants_count = DB::table('users')
				->select('users.id as uid')
				->leftJoin('role_user', 'users.id', '=', 'role_user.user_id')
				->where(['role_user.role_id' => 3, 'users.merchant_status' => 0])
				->where('users.created_at', '>=', $date->toDateTimeString() )
				->count();
				
		// Merchants Count		
		$merchants_count = DB::table('users')
				->select('users.id as uid')
				->leftJoin('role_user', 'users.id', '=', 'role_user.user_id')
				->where(['role_user.role_id' => 3, 'users.merchant_status' => 1])
				->where('users.created_at', '>=', $date->toDateTimeString() )
				->count();
		$merchants_count_total = DB::table('role_user')
				->select('role_id')
				->where(['role_user.role_id' => 3])
				->count();
				
		$not_in_array = ['pending', 'cancelled'];
		
		//  Total Orders
		$orders_total = OrderBooking::select('id')
					    ->whereNotIn('status', $not_in_array)
						->count();//print_r($pending_profiles);exit; 
						
	// Total Orders By Weekdays					
		$orders_total_weeks = OrderBooking::select('id')
					    ->whereNotIn('status', $not_in_array)
						->where('created_at', '>=', $date->toDateTimeString() )
					    ->count();//print_r($pending_profiles);exit;				
									
						
	 //  Total Orders Amount
		$orders_amount = OrderBooking::whereNotIn('status', $not_in_array)->sum('total_amount');	 
		
	 // Total Orders Amount By Week
	 
		$orders_amount_weeks = OrderBooking::whereNotIn('status', $not_in_array)
		                 ->where('created_at', '>=', $date->toDateTimeString())
						 ->sum('total_amount');//print_r($orders_amount_weeks);
						 	
    /* $orders_total = DB::table('orders')
	                    ->select('total', DB::raw('SUM(total) as total_sales'))
					    ->orderBy('created_at', 'DESC')
						->limit(5)
						->havingRaw('SUM(total)')
						->where('orders.created_at', '>=', $date->toDateTimeString() )
						->get();//print_r($pending_profiles);exit; */
				
		$pending_profiles = User::whereHas('roles', function ($query) {
								$query->where('role_id', '=', 3);
							})
							->where(['merchant_status' => 0])
							->orderBy('created_at', 'DESC')
							->limit(5)
							->get();//print_r($pending_profiles);exit;
							
		/*** bar graph start ***/
		$sales_daily_in_current_month = [];
		$start_date = $date->toDateString();
		$end_date = date('Y-m-d');
		$sql = 'SELECT DATE(order_booking.created_at) AS order_date, '.
		'COUNT(order_booking.id) AS total_order, '.
		'SUM(order_booking.total_items) AS total_products, '.
								//'SUM(order_booking.ord_tax_total) AS total_tax, '.
		'SUM(order_booking.total_amount) AS total_price '.
		'FROM order_booking '.
		'WHERE status NOT IN (\'pending\', \'cancelled\') AND DATE(order_booking.created_at) > ? AND DATE(order_booking.created_at) <= ? '.
		'GROUP BY DATE(order_booking.created_at)'.
		'ORDER BY order_date'
		;
		$sales_daily_in_current_month = DB::select($sql, [$start_date, $end_date]);
		
		$sales_daily_in_current_month_new = array();
		$total_revenue = 0;
		$total_no_of_orders = 0;
		$price_bar = '';
		$sep = '';
		foreach($sales_daily_in_current_month as $sales) {
			$sales_daily_in_current_month_new[$sales->order_date] = $sales;			
			$total_revenue += $sales->total_price;
			$total_no_of_orders += $sales->total_order;
			$price_bar .= $sep . $sales->total_price;
			$sep = ',';
		}
		$interval = new \DateInterval('P1D');
		$period = new \DatePeriod(
			new \DateTime($start_date),
			$interval,
			(new \DateTime($end_date))->add($interval),
			\DatePeriod::EXCLUDE_START_DATE
		);
		
		$sep = $labels_graph_bar = '';
		foreach ($period as $date) {
			$total_price = 0;
			$cur_date = $date->format('Y-m-d');
			if(isset($sales_daily_in_current_month_new[$cur_date])) {
				$sales = $sales_daily_in_current_month_new[$cur_date];
				$total_price = intval($sales->total_price);
			}
			$labels_graph_bar .= $sep . "{day: '".$cur_date."', order_amount: ".$total_price."}";	
			$sep = "," . "\n";
		}//print_r($labels_graph_bar);exit;
		/*** bar graph end ***/
	    
		// traffic user
		$new_exists_traffic = DB::table('listing_views')
		->select(DB::raw('count(*) as user_type_count, user_type'))
		->groupBy('user_type')
                ->get();//print_r($new_exists_traffic);exit;

                $traffic_data['labels']	= '';
                $traffic_data['data']	= '';
                $sep = '';
                $traffic_data['color']	= '"#26B99A","#3498DB"';
                $traffic_data['hover_color']	= '"#36CAAB","#49A9EA"';
                foreach($new_exists_traffic as $traffic)
                {
                	$traffic_data['labels']	.= $sep . '"' . ucfirst($traffic->user_type) . '"';
                	$traffic_data['data']	.= $sep . $traffic->user_type_count;
                	$sep = ',';
                }

		// traffic device
                $device_traffic = DB::table('listing_views')
                ->select(DB::raw('count(*) as device_count, device'))
                ->groupBy('device')
                ->get();
                $device_data['labels']	= '';
                $device_data['data']	= '';
                $device_data['color']	= '';
                $device_data['hover_color']	= '';
                $sep = '';
                $init_color	= 26;
                $init_hover_color	= 36;
                foreach($device_traffic as $device)
                {
                	$device_data['labels']	.= $sep . '"' . ucfirst($device->device) . '"';
                	$device_data['data']	.= $sep . $device->device_count;
                	$device_data['color'] .= $sep . '"#'.$init_color.'B99A"';
                	$device_data['hover_color'] .= $sep . '"#'.$init_hover_color.'CAAB"';
                	$sep = ',';

                	$init_color += 2;
                	$init_hover_color += 2;
                }
		//print_r($device_data);exit;

		   /*     // recent orders
                $recent_orders = OrderBooking::where('merchant_id', $merchant_id)
                ->orderBy('created_at', 'DESC')
                ->limit(5)
                ->get();
					*/
		
		
		return view('panels.admin.home', compact('recent_subscriptions', 'pending_profiles','users_count','users_count_total','non_verified_merchants_count','non_verified_merchants_count_total','merchants_count','merchants_count_total','orders_total','orders_amount','orders_total_weeks','orders_amount_weeks','total_revenue','total_no_of_orders','labels_graph_bar', 'price_bar', 'traffic_data', 'device_data'));
    }
	 public function getOrderGraph(Request $request)
            {
            	//$merchant_id = (auth()->check()) ? auth()->user()->id : null;

            	$sales_daily_in_current_month = [];
            	$start_date = $request->start_date;
            	$end_date = $request->end_date;
            	if($end_date > date('Y-m-d'))
            		$end_date = date('Y-m-d');

            	$sql = 'SELECT DATE(order_booking.created_at) AS order_date, '.
            	'COUNT(order_booking.id) AS total_order, '.
            	'SUM(order_booking.total_items) AS total_products, '.
								//'SUM(order_booking.ord_tax_total) AS total_tax, '.
            	'SUM(order_booking.total_amount) AS total_price '.
            	'FROM order_booking '.
            	'WHERE status NOT IN (\'pending\', \'cancelled\') AND DATE(order_booking.created_at) >= ? AND DATE(order_booking.created_at) <= ? '.
				'GROUP BY DATE(order_booking.created_at)'.
            	'ORDER BY order_date'
            	;
            	$sales_daily_in_current_month = DB::select($sql, [$start_date, $end_date]);//print_r($sales_daily_in_current_month);exit;

            	$sales_daily_in_current_month_new = array();
            	$total_revenue = 0;
            	$total_no_of_orders = 0;
            	$price_bar = '';
            	$sep = '';
            	foreach($sales_daily_in_current_month as $sales) {
            		$sales_daily_in_current_month_new[$sales->order_date] = $sales;			
            		$total_revenue += $sales->total_price;
            		$total_no_of_orders += $sales->total_order;
            		$price_bar .= $sep . $sales->total_price;
            		$sep = ',';
            	}
            	$interval = new \DateInterval('P1D');
            	$period = new \DatePeriod(
            		new \DateTime($start_date),
            		$interval,
            		(new \DateTime($end_date))->add($interval)
            	);

            	$sep = $labels_graph_bar = '';
            	foreach ($period as $date) {
            		$total_price = 0;
            		$cur_date = $date->format('Y-m-d');
            		if(isset($sales_daily_in_current_month_new[$cur_date])) {
            			$sales = $sales_daily_in_current_month_new[$cur_date];
            			$total_price = intval($sales->total_price);
            		}
            		$labels_graph_bar .= $sep . "{day: '".$cur_date."', order_amount: ".$total_price."}";	
            		$sep = "," . "\n";
		}//print_r($labels_graph_bar);exit;
		
		return view('panels.admin.order_graph', compact('total_no_of_orders', 'total_revenue', 'labels_graph_bar', 'price_bar'));
	}
          
	
	//Admin Users Display
	 public function users()
    {		
	//Get Users Details
	    $users = DB::table('users')
		->select('*', 'users.id as uid')
		->leftJoin('role_user', 'users.id', '=', 'role_user.user_id')
		->where('role_user.role_id', '=', 2)
		->get();
        return view('panels.admin.users', ['users' => $users]);
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
                'lastname'             	=> 'required',
                'email'                 => 'required|email|unique:users',
                'password'              => 'required|min:6|max:20',
                'cpassword' 			=> 'required|same:password',
               
            ],
            [
                'firstname.required'   	=> 'First Name is required',
                'lastname.required'    	=> 'Last Name is required',
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
				$user->first_name=$request->firstname;
				$user->last_name=$request->lastname;
				$user->email=$request->email;				
				$user->image=$imagename;
                $user->password=bcrypt($request->password);
                $user->token=str_random(64);
                $user->activated=!config('settings.activation');				
				$user->save();	
				
				$role = Role::whereName('administrator')->first();				
				$user->assignRole($role);	
								 
				$user_access_modules = new UserAccessModules;
				$user_access_modules->user_id=$user->id;
				$user_access_modules->modules='';			
				$user_access_modules->save();
				
               // $user_access_modules = DB::table('user_access_modules')
				
               // $user_access_modules->save();				
				//$this->initiateEmailActivation($user);

			return redirect('admin/users')->with('message','Users added successfully');
	}
	public function updated(Request $request)
	{	 
	
	$this->validate($request,
			 [
                'firstname'            	=> 'required',
                'lastname'             	=> 'required',
                'email'                 => 'required|email',               
               
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
				->update(['first_name' => $request->firstname,'last_name' => $request->lastname,'email' => $request->email,]);

	return redirect('admin/users')->with('message','User updated successfully');
	}


	public function deleted(Request $request)
	{	 
	$id=$request->id;  
		 $blogs = DB::table('users')
		 ->where('id', $id)
		 ->delete();
		 $status['deletedid']=$id;
		 $status['deletedtatus']='User deleted successfully';
	 return '{"delete_details": ' . json_encode($status) . '}'; 
	
	}

	public function destroy(Request $request)
		{
			$cn=count($request->selected_id);
			if($cn>0)
			{
			$data = $request->selected_id;			
				foreach($data as $id) {
				DB::table('users')->where('id', $id)->delete();			
				}			
			} 
	return redirect('admin/users')->with('message','Seltected Users are deleted successfully');			

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