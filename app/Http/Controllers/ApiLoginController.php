<?php

//namespace App\Http\Controllers;
namespace App\Http\Controllers;
use App\Http\Controllers\Auth;
use DB;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Http\Response;
use App\Models\User;
use App\Models\Role;
use App\Models\ListingReview;

use App\Models\Listing;

use App\Models\MultiLanguage;
use App\Models\Country;
use App\Models\States;
use App\Models\Cities;
use App\Models\Category;
use App\Models\CategorySlug;
use App\Models\OrderBooking;
use App\Models\MerchantServices;
use App\Models\Notifications;
use App\Models\MerchantOrder;



use App\Models\Contacts;
use App\Models\LocalVendorUsage;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class ApiLoginController extends Controller
{
    //
    use AuthenticatesUsers;

    /**
     * Auth guard
     *
     * @var
     */
    protected $auth;
    
    public function __construct(Guard $auth)
    {
        parent:: __construct();
        

        $this->auth = $auth;
    }

    public function login(Request $request)
    {
        $email      = $request->get('email');
        $password   = $request->get('password');
        $remember=1;
        $response=array();
       // $remember   = 1;
        try {
            if($this->auth->attempt([
                'email'     => $email,
                'password'  => $password
            ], $remember == 1 ? true : false)){
				
				if($this->auth->user()->activated == '0') {
					$response['Message']='User not activated. Please verify your account.'; 
					$response['Data']= '';
					$response['Status']= 2;
					return response()->json($response);
				} else {
                    //$this->initiateEmailActivation($user);    
					$user_data = $this->auth->user();
					$response['Message']='Success';
					if($this->auth->user()->hasRole('user')){
						$value['role']='user';
						
					}
					else if($this->auth->user()->hasRole('merchant')){
						$value['role']='merchant';
					}
					
					//Get User Data
					$response['Data']= $user_data;
					
					$response['Listing_Category_id']= $user_data->category_id;
					
					$response['Listing_Sub_Category']= Category::where('parent_id',$user_data->category_id)->get();
							
					$user_id = $this->auth->user()->id;
					
					if($value['role']=='user')
					{
							//Get User Review Count
							$review_count=ListingReview::where('user_id',$user_id)->count();
							$response['Review_Count']= $review_count;
							
							//Get User Contacts Count
							$contact_count=Contacts::where('user_id',$user_id)->count();
							$response['Contact_Count']= $contact_count;
							
							//Get User QR Code Count
							$qr_code_count=LocalVendorUsage::where(['user_id' => $user_id, 'usage_type' => 'scan'])->count();
							$response['QR_Code_Count']= $qr_code_count;
							
							//Get User Cart Count
							$total = 0;
							if($user_data->cart_data)
								$total = sizeof(unserialize($user_data->cart_data));
						
							$response['User_Cart_Count']= $total;
					}else{
						//Merchant Dashboard
						
						$response['listing'] = $listing = Listing::with('listing_city', 'listing_state', 'subcategory')->where('user_id', '=', $user_id)->first();
						
						// statistics for 7 days
						$date = new \Carbon\Carbon;
						$date->subWeek();
						
						$response['listing_reviews'] = [];
						$response['visits'] = $response['reviews'] = $response['total_amount_week'] = $response['orders'] = 0;
						$response['appointments'] = $response['table_bookings'] = 0;

						if($listing)
						{
							$response['listing_reviews'] = ListingReview::
							where('listing_id', $listing->id)
							->where('created_at', '>', $date->toDateTimeString() )
							->get();//print_r($listing_views);die;
							
							$response['reviews'] = ListingReview::
							where('listing_id', $listing->id)
							->where('created_at', '>', $date->toDateTimeString() )
							->count();
							//print_r($reviews);die;
							
							$response['visits'] = DB::table('listing_views')
							->where('merchant_id', $user_id)
							->where('created_at', '>', $date->toDateTimeString() )
							->count();
							
							$not_in_array = ['pending', 'cancelled'];
							
							$response['total_amount_week'] = MerchantOrder::
							where('listing_id', $listing->id)
							->whereNotIn('order_status', $not_in_array)
							->where('created_at', '>', $date->toDateTimeString() )
							->sum('total_amount');
							//print_r($total_amount_week);die;
							
							$response['orders'] = MerchantOrder::
							whereNotIn('order_status', $not_in_array)
							->where('listing_id', $listing->id)
							->where('created_at', '>', $date->toDateTimeString() )
							->count();

							$response['appointments'] = DB::table('appointment_booking_order')
							->where('merchant_id', $user_id)
							->where('order_date', '>', $date->toDateTimeString() )
							->count();

							$response['table_bookings'] = DB::table('table_booking_order')
							->where('merchant_id', $user_id)
							->where('order_date', '>', $date->toDateTimeString() )
							->count();
						}
						
						$response['merchant_services'] = array(
							'table'			=> false,
							'appointment'	=> false,
							'food'			=> false,
							'shop'			=> false
						);
						
						if(isset($user_data->last_subscription->subscription_pricing))
						{
							// get subscription
							$subscription_pricing = $user_data->last_subscription->subscription_pricing;
							$subscribed_category = $user_data->subscribed_category;
							
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
											$response['merchant_services']['table'] = true;
										} else if( isset($services_by_id[$category_type_id]['slug']) and $services_by_id[$category_type_id]['slug'] == 'appointment' ) {
											$response['merchant_services']['appointment'] = true;
										} else if( isset($services_by_id[$category_type_id]['slug']) and $services_by_id[$category_type_id]['slug'] == 'shop' ) {
											$response['merchant_services']['shop'] = true;
										} else if( isset($services_by_id[$category_type_id]['slug']) and $services_by_id[$category_type_id]['slug'] == 'food' ) {
											$response['merchant_services']['food'] = true;
										}
									}
								}
							}
						}

						$merchant_services = MerchantServices::where(['merchant_id' => $user_id, 'is_enable' => 1])->get();
						
						$response['food_enable'] = $response['shop_enable'] = $response['appointment_enable'] = $response['table_booking_enable'] = false;
						foreach($merchant_services as $merchant_service)
						{
							if($merchant_service->category_type->slug == 'online-order')
								$response['food_enable'] = true;
							if($merchant_service->category_type->slug == 'online-shopping')
								$response['shop_enable'] = true;
							if($merchant_service->category_type->slug == 'appointment-booking')
								$response['appointment_enable'] = true;
							if($merchant_service->category_type->slug == 'table-booking')
								$response['table_booking_enable'] = true;
						}
						
						/*** bar graph start ***/
						$sales_daily_in_current_month = [];
						$start_date = $date->toDateString();
						$end_date = date('Y-m-d');
						if($listing)
						{
							$sql = 'SELECT DATE(merchant_orders.created_at) AS order_date, '.
							'COUNT(merchant_orders.id) AS total_order, '.
							'SUM(merchant_orders.total_items) AS total_products, '.
													//'SUM(merchant_orders.ord_tax_total) AS total_tax, '.
							'SUM(merchant_orders.total_amount) AS total_price '.
							'FROM merchant_orders '.
							'WHERE listing_id = ? AND DATE(merchant_orders.created_at) > ? AND DATE(merchant_orders.created_at) <= ? '.
							'GROUP BY DATE(merchant_orders.created_at)'.
							'ORDER BY order_date'
							;
							$sales_daily_in_current_month = DB::select($sql, [$listing->id, $start_date, $end_date]);
							//print_r($sales_daily_in_current_month);die;
							
						}
						$sales_daily_in_current_month_new = array();
						$response['total_revenue'] = 0;
						$response['total_no_of_orders'] = 0;
						foreach($sales_daily_in_current_month as $sales) {
							$sales_daily_in_current_month_new[$sales->order_date] = $sales;			
							$response['total_revenue'] += $sales->total_price;
							$response['total_no_of_orders'] += $sales->total_order;
						}
						$interval = new \DateInterval('P1D');
						$period = new \DatePeriod(
							new \DateTime($start_date),
							$interval,
							(new \DateTime($end_date))->add($interval),
							\DatePeriod::EXCLUDE_START_DATE
						);
						
						$response['labels_graph_bar'] = [];
						foreach ($period as $date) {
							$total_price = 0;
							$cur_date = $date->format('Y-m-d');
							if(isset($sales_daily_in_current_month_new[$cur_date])) {
								$sales = $sales_daily_in_current_month_new[$cur_date];
								$total_price = intval($sales->total_price);
							}
							$response['labels_graph_bar'][] = array('day' => $cur_date, 'order_amount' => $total_price);
						}
						
						//print_r($labels_graph_bar);exit;
						/*** bar graph end ***/
						
						// traffic user
						$new_exists_traffic = DB::table('listing_views')
						->select(DB::raw('count(*) as user_type_count, user_type'))
						->where('merchant_id', $user_id)
						->groupBy('user_type')
						->get();//print_r($new_exists_traffic);exit;

						$response['traffic']	= array('new' => 0, 'exists' => 0);
						$total_traffic = 0;
						foreach($new_exists_traffic as $traffic)
						{
							$response['traffic'][$traffic->user_type]	+= (int)$traffic->user_type_count;
							$total_traffic += (int)$traffic->user_type_count;
						}
						if($total_traffic)
						{
							$new_total = $response['traffic']['new'];
							$exists_total = $response['traffic']['exists'];
							if($new_total)
								$response['traffic']['new'] = round(($new_total/$total_traffic) * 100);
							if($exists_total)
								$response['traffic']['exists'] = round(($exists_total/$total_traffic) * 100);
						}

						// traffic device
						$device_traffic = DB::table('listing_views')
						->select(DB::raw('count(*) as device_count, device'))
						->where('merchant_id', $user_id)
						->groupBy('device')
						->get();//print_r($device_traffic);exit;
						$response['device_traffic']	= [];
						$total_device_traffic = 0;
						foreach($device_traffic as $device)
						{
							$device_name = $device->device;
							if($device_name == '0')
								$device_name = 'Unknown';
							
							if( isset($response['device_traffic'][$device_name]) )
								$response['device_traffic'][$device_name]	+= (int)$device->device_count;
							else
								$response['device_traffic'][$device_name] = (int)$device->device_count;
							$total_device_traffic += (int)$device->device_count;
						}
						$response_device_traffic = $response['device_traffic'];
						if($total_device_traffic)
						{
							foreach($response_device_traffic as $device => $traffic)
							{
								if($traffic)
									$response['device_traffic'][$device] = round(($traffic/$total_device_traffic) * 100);
							}
						}
						//print_r($device_data);exit;

						// recent orders
						$response['recent_orders'] =[];
						if($listing)
						{
							$response['recent_orders'] = MerchantOrder::where('listing_id', $listing->id)
							->orderBy('created_at', 'DESC')
							->limit(5)
							->get();
						}
						
					}
					$response['Status']= 1;

					return response()->json($response);
				}
            }
            else{
                $response['Message']='Invalid email or password'; 
                $response['Data']= '';
				$response['Status']= 0;
                return response()->json($response); 
            }
        } catch (Exception $e) {

          
        }
		
		$response['Message']='Request failed.'; 
		$response['Data']= '';
		$response['Status']= 0;
		return response()->json($response);

    }


}