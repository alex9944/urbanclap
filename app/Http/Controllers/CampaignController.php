<?php

namespace App\Http\Controllers;
use DB;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Country;
use App\Models\States;
use App\Models\AdsPosition;
use App\Models\Usercampaigns;
use App\Models\AdsPricing;
use App\Models\PaymentGatewaySite;
use App\Models\PaymentGatewaySiteSettings;
use App\Models\AdsOrder;
use App\Models\PaypalLibray;
use App\Models\EbsLibrary;
use App\Models\User;
use Image;
use auth;

class CampaignController extends Controller
{

	public function index(){
		$category=Category::where('parent_id','0')->get();
		$country=Country::where('default','1')->value('id');
		$states=States::where('country_id',$country)->get();
		$position=AdsPosition::get();
		foreach ($position as $key => $value) {
			if($value->type="banner"){
				$pos=explode('*',$value->title);
				$value['pos_id']=$pos[0];
			}
		}

		return view('pages.campaign_create')->with(compact('states','position','category'));
	}
	public function getsubcategory(Request $request){
		$category=Category::where('parent_id',$request->id)->get();
		return '{"view_details": ' . json_encode($category) . '}';
	}
	public function getCities(Request $request)
	{
		$id=$request->id;  
		$cities = DB::table('cities')
		->where('state_id', $id)	 
		->get();   
		return '{"view_details": ' . json_encode($cities) . '}';
	}
	public function createCampaign(Request $request){
		$data= json_decode($request->campaign);

		$campaign=new Usercampaigns;
		$campaign->user_id=auth::user()->id;
		$campaign->cat_id=$data->cat_id;
		$campaign->s_cat_id=$data->s_cat_id;
		$campaign->state_id=$data->state_id;
		$campaign->city_id=$data->city_id;
		$campaign->zone=$data->zone;
		$campaign->mode=$data->mode;
		$campaign->position_id=$data->position;
		$campaign->save();
		$campaigns=Usercampaigns::get()->where('id',$campaign->id);
		foreach ($campaigns as $key => $value) {
			$pos=$value->position->title;
			$ad_pos=explode('*',$pos);
			$value['height']=$ad_pos[0];
			$value['width']=$ad_pos[1];
		}
		return response()->json($campaigns);
	}
	public function getprice(Request $request){
		$data= json_decode($request->campaign);
		if(array_key_exists("position",$data) && array_key_exists("state_id",$data) && array_key_exists("city_id",$data) && array_key_exists("mode",$data) && array_key_exists("cat_id",$data) && array_key_exists("s_cat_id",$data) && array_key_exists("zone",$data)  ){
			$price=AdsPricing::where('a_p_id',$data->position)
			->where('state_id',$data->state_id)
			->where('city_id',$data->city_id)
			->where('mode',$data->mode)
			->where('cat_id',$data->cat_id)
			->where('s_cat_id',$data->s_cat_id)
			->where('zone',$data->zone)->value('price');
		}
		else{
			$price=AdsPricing::where('default','!=','NULL')->value('default');
		}
		return '{"amount": ' . $price . '}';
	}
	public function moreDetails(Request $request){
		$campaign_id=$request->campaign_id;
		$company=$request->company;
		$url=$request->url;
		$photo = $request->file('ad_image');
		$imagename = time().'.'.$photo->getClientOriginalExtension();   
		$destinationPath = public_path('/uploads/campaigns/thumbnail');
		$thumb_img = Image::make($photo->getRealPath())->crop(100, 100);
		$thumb_img->save($destinationPath.'/'.$imagename,80);                    
		$destinationPath = public_path('/uploads/campaigns/original');
		$photo->move($destinationPath, $imagename);
		$campaign=Usercampaigns::where('id',$campaign_id)->update(['title'=>$company,'url'=>$url,'image'=>$imagename]);
		$campaigns=Usercampaigns::where('id',$campaign_id)->get();
		foreach ($campaigns as $key => $value) {
			$price=AdsPricing::where('a_p_id',$value->position_id)
			->where('state_id',$value->state_id)
			->where('city_id',$value->city_id)
			->where('mode',$value->mode)
			->where('zone',$value->zone)->value('price');
			if(empty($price)){
				$price=AdsPricing::where('default','!=','NULL')->value('default');
			}
		}
		

		$campaign_arr=array();
		foreach ($campaigns as $key => $value) {
			$campaign_arr['camp_id']=$value->id;
			$campaign_arr['state']=$value->state->name;
			$campaign_arr['city']=$value->city->name;
			$campaign_arr['zone']=$value->zone;
			$campaign_arr['image']=$value->image;
			$campaign_arr['price']=$price;
		}
		return response()->json($campaign_arr);
	}
	public function checkout(Request $request){
		$payment_gateway_id = $request->payment_gateway_id;
		$payment_gateway = PaymentGatewaySite::find($payment_gateway_id);
	
		$order=new AdsOrder;
		$order->user_id=auth::user()->id;
		$order->u_c_id=$request->campaign_id;
		$order->payment_gateway_id=$payment_gateway_id;
		$order->payment_type=$payment_gateway->name;
		$order->amount=$request->price;
		$order->status='Pending';
		$order->save();

		$encrypt_id = base64_encode('JVinoNeedifo_'.$order->id);
		if($payment_gateway_id == 1) //Ebs
		{
			return redirect('/campaign/payby-ebs/'.$encrypt_id);
		}
		else if($payment_gateway_id == 2) // Paypal
		{
			return redirect('campaign/payby-paypal/'.$encrypt_id);
		}
		return redirect('campaign/thankyou/'.$encrypt_id);
	}
	public function payby_paypal($encrypt_order_id)
	{
		$decrypt_id = base64_decode($encrypt_order_id);
		$order_id = (int) str_replace('JVinoNeedifo_', '', $decrypt_id);
		
		$order = [];
		if($order_id)
		{
			$order = AdsOrder::find($order_id);
			//$user_id = $order->user_id;
			$paypal_gateway_id  = 2;
			$user_id = (auth()->check()) ? auth()->user()->id : null;

			$paypal_setting = PaymentGatewaySiteSettings::where(['payment_gateway_site_id' => $paypal_gateway_id])->first();
			$mode = $paypal_setting->mode;
			$business_email = $paypal_setting->business_email;
			$currency_code = $paypal_setting->currency->code;
			
			$PaypalLibray = new PaypalLibray($business_email, $currency_code, $mode);
			$PaypalLibray->current_currency_code = $this->currency->code;
			$returnURL = url('campaign/paypal-success'); //payment success url
			$cancelURL = url('campaign/paypal-cancel'); //payment cancel url
			$notifyURL = url('campaign/paypal-ipn'); //ipn url
			$custom['order_id'] = $order_id;
			$custom['user_id'] = $user_id;
			$custom = json_encode($custom);
			$PaypalLibray->add_field('return', $returnURL);
			$PaypalLibray->add_field('cancel_return', $cancelURL);
			$PaypalLibray->add_field('notify_url', $notifyURL);
			$PaypalLibray->add_field('custom', $custom);
			//$PaypalLibray->add_field('quantity',  $quantity);
			$PaypalLibray->add_field('amount',  $order->amount);
			/*$PaypalLibray->add_field('on0',  'order_no');
			$PaypalLibray->add_field('os0',  $order_number);*/					
			$paypal_html = $PaypalLibray->paypal_auto_form();
			
			return view('pages.campaign_paypal_pay', compact('paypal_html'));
		}
		else
		{
			return view('pages.campaign_paypal_thankyou', compact('order'));
		}
	}
	
	function paypal_success(Request $request)
	{        
		
		//get the transaction data
        $paypalInfo = $request->all();//print_r($paypalInfo);exit;
        $custom = json_decode($paypalInfo['custom'], true);

        $this->data['order_number']    = $custom["order_id"]; 
        $this->data['txn_id'] = $paypalInfo["txn_id"];
        $this->data['payment_amt'] = $paypalInfo["mc_gross"];
        $this->data['currency_code'] = $paypalInfo["mc_currency"];
        $this->data['status'] = $paypalInfo["payment_status"];
        
        //pass the transaction data to view
        return view('pages.campaign_paypal_success')->with($this->data);
    }

    function paypal_cancel()
    {

    	return view('pages.campaign_paypal_cancel');
    }

    function paypal_ipn(Request $request)
    {
        //paypal return transaction details array
        $paypalInfo    = $request->all();//mail('jerosilinvinoth@gmail.com', 'ipn request', print_r($paypalInfo, TRUE));
        $custom = json_decode($paypalInfo['custom'], true);
        
        $data['user_id'] = $custom['user_id'];
        $data['order_id']    = $custom["order_id"];
        $data['txn_id']    = $paypalInfo["txn_id"];
        $data['payment_gross'] = $paypalInfo["payment_gross"];
        $data['currency_code'] = $paypalInfo["mc_currency"];
        $data['payer_email'] = $paypalInfo["payer_email"];
        $data['payment_status']    = $paypalInfo["payment_status"];

        $order = AdsOrder::find($data['order_id']);
        $merchant_id = $order->merchant_id;
        $paypal_gateway_id  = 2;

        $paypal_setting = PaymentGatewaySiteSettings::where([ 'payment_gateway_site_id' => $paypal_gateway_id])->first();
        $mode = $paypal_setting->mode;
        $business_email = $paypal_setting->business_email;
        $currency_code = $paypal_setting->currency->code;

        $PaypalLibray = new PaypalLibray($business_email, $currency_code, $mode);

        $paypalURL = $PaypalLibray->paypal_url;        
        $result    = $PaypalLibray->curlPost($paypalURL, $paypalInfo);

		//mail('jerosilinvinoth@gmail.com', 'ipn result', print_r($result, TRUE));exit;
        
        //check whether the payment is verified
        if(preg_match("/VERIFIED/i",$result))
        {
            //insert the transaction data into the database
        	\DB::table('paypal_log')->insert($data);

        	$OrderBooking = new AdsOrder;
        	$status ='confirmed';
        	DB::table('ads_order')
        	->where('id', $data['order_id'])
        	->update(['status' => $status]);
        }
    }
    public function payby_ebs($encrypt_subscription_id)
    {
    	$decrypt_id = base64_decode($encrypt_subscription_id);
    	$order_id = (int) str_replace('JVinoNeedifo_', '', $decrypt_id);

    	$subscription = [];
    	$response_html = '';
    	if($order_id)
    	{
    		$campaign = AdsOrder::find($order_id);
    		if($campaign->status == 'Pending')
    		{
    			$user_id = $campaign->user_id;

    			$ebsObj = new EbsLibrary;
    			$user = User::find($user_id);

    			$ebsObj->addAPIFilelds();
    			$ebsObj->addField('reference_no', $order_id);
				$ebsObj->addField('return_url', url('/campaign/ebs-thankyou')); //ebs return url
				
				$ebsObj->addField('amount', $campaign->amount);//.'.00'
				
				$ebs_html = $ebsObj->generateForm();
				
				return view('pages.campaign_payby_ebs', compact('ebs_html'));
			}
		}
		
		return view('pages.campaign_ebs_thankyou', compact('response_html'));
	}
	
	function ebs_thankyou()
	{
		
		$payment_gateway_id = 1;	// Ebs
		$gateway_settings = PaymentGatewaySiteSettings::where(['payment_gateway_site_id' => $payment_gateway_id])->first();
		
		$response_html = '';
		$secret_key = $gateway_settings->secret_key;
		
		if($_REQUEST)
		{
			$response = $_REQUEST;
			$sh = $response['SecureHash'];	
			$params = $secret_key;
			ksort($response);
			$ebsObj = new EbsTransactionSite;

			$valid_table_fields = array('BillingAddress', 'BillingCity', 'BillingCountry', 'BillingEmail', 'BillingName', 'BillingPhone', 'BillingPostalCode', 'DateCreated', 'Description', 'IsFlagged', 'MerchantRefNo', 'Mode', 'PaymentID', 'PaymentMethod', 'RequestID', 'ResponseCode', 'ResponseMessage', 'SecureHash', 'Amount', 'TransactionID');

			foreach ($response as $key => $value)
			{
				if (strlen($value) > 0 && $key!='SecureHash') {
					$params .= '|'.$value;
				}

				if(strlen($value) > 0){
					if(in_array($key, $valid_table_fields)) {
						//$datacc[$key] = $value;						
						$ebsObj->{$key} = $value;
					}
					
					if($key == 'MerchantRefNo'){							
						$MerchantRefNo=$key;
						$MerchantRefval=$value;									
					}
					if($key == 'ResponseMessage'){							
						$ResponseMessage=$key;
						$ResponseMessageval=$value;
					}
					if($key == 'TransactionID'){							
						$TransactionID=$key;
						$TransactionIDval=$value;
					}
				}
			}
			
			if($MerchantRefNo)
			{
				$data = EbsTransactionSite::where(['MerchantRefNo' => $MerchantRefval])->first();
				
				if(empty($data->id))
				{
					if($ResponseMessageval == "Transaction Successful")
					{
						// update status in subscription table
						$campaigns_order = AdsOrder::find($MerchantRefval);
						$campaigns_order->status = 'Success';
						$campaigns_order->save();	
					}
					// add ebs log
					$ebsObj->save();
					
					$response_html = '<div class="col-sm-8 col-sm-offset-3"><div class="col-sm-6"><h6>Order ID :</h6></div><div class="col-sm-6"><h6>'.$MerchantRefval.'</h6></div><div class="col-sm-6"><h6>Transaction ID :</h6></div><div class="col-sm-6"><h6>'.$TransactionIDval.'</h6></div><div class="col-sm-6"><h6>ResponseMessage :</h6></div><div class="col-sm-6"><h6>'.$ResponseMessageval.'</h6></div><div class="col-sm-6"></div><div class="col-sm-6"></div></div>';		

				}
				else
				{					
					$response_html = '<div class="col-sm-8 col-sm-offset-3"><div class="col-sm-6"><h6>Order ID :</h6></div><div class="col-sm-6"><h6>'.$MerchantRefval.'</h6></div><div class="col-sm-6"><h6>Transaction ID :</h6></div><div class="col-sm-6"><h6>'.$TransactionIDval.'</h6></div><div class="col-sm-6"><h6>ResponseMessage :</h6></div><div class="col-sm-6"><h6>'.$ResponseMessageval.'</h6></div><div class="col-sm-6"></div><div class="col-sm-6"></div></div>';							
				}
			}  

		}
		return view('pages.campaign_ebs_thankyou', compact('response_html'));

	}

}