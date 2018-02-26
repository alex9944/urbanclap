<?php

namespace App\Http\Controllers;
use DB;
use Illuminate\Http\Request;
use App\Models\Localvendor;
use App\Models\LocalvendorMarketing;

use Input;
use Image;
use Carbon\Carbon;
use App;

class ApiLocalVendorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
		// $localvendor= LocalVendor::all();
		/*$localvendor =DB::table('local_vendor')
               // ->leftjoin('categories', 'articles.id', '=', 'categories.id')
                ->leftjoin('users', 'users.id', '=', 'local_vendor.user_id')
                ->select('local_vendor.user_id','users.first_name','local_vendor.category','local_vendor.owner_name','local_vendor.title', 'local_vendor.description', 'local_vendor.address', 'local_vendor.image', 'local_vendor.latitude', 'local_vendor.longitude', 'local_vendor.phone', 'local_vendor.website', 'local_vendor.working_hours','local_vendor.status')
                ->get();*/
				
		$site_settings = $this->site_settings;
		$latitude = $request->latitude;
		$longitude = $request->longitude;
		
		$active_hour = $site_settings->user_local_vendor_active_time;
		
		$sql_only = ' DATE_ADD(created_at, INTERVAL '.$active_hour.' HOUR) > "'.date('Y-m-d H:i:s').'"';
		
		$localvendor = Localvendor::with('user')->whereRaw($sql_only)->get();//->where(['status' => 1])
	 
	 
		$data['localvendor']=$localvendor;
	 
		$responsecode = 200;        
		$header = array (
			'Content-Type' => 'application/json; charset=UTF-8',
			'charset' => 'utf-8'
		);       
		return response()->json($data , $responsecode, $header, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);	

	}
	
	public function list_by_category(Request $request)
	{
		$return = array('status' => 0, 'msg' => 'Category is required.');
		
		$category = $request->category;
		$latitude = $request->latitude;
		$longitude = $request->longitude;
		
		if($category)
		{
			$site_settings = $this->site_settings;
		
			$active_hour = $site_settings->user_local_vendor_active_time;
			
			$sql_only = ' DATE_ADD(created_at, INTERVAL '.$active_hour.' HOUR) > "'.date('Y-m-d H:i:s').'"';
			//$sql_only = ' DATE_ADD(created_at, INTERVAL '.$active_hour.' HOUR) > NOW()';
			
			$localvendor = Localvendor::with('user')->whereRaw($sql_only)->where(['category' => $category])->get();
			
			$return = array('status' => 1,'data' => $localvendor, 'msg' => 'Success');
		}
		
		return response()->json($return);
	}

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $return = array('status' => 0, 'msg' => 'User Id is required.');
		
		$rules = [
			'user_id'		=> 'required',
			'owner_name'	=> 'required',
			'category'	    => 'required',
			'title'			=> 'required',
			'description'	=> 'required',
			'address'		=> 'required',
			'photo'			=> 'required|image|mimes:jpeg,png,jpg,gif,svg|max:1024',
			'latitude'		=> 'required',
			'longitude'		=> 'required',
			'phone'		    => 'required',	
			'website'		=> 'required',
		];				
		$messages = [
			'user_id.required'		=> 'Username is required',
			'owner_name.required'	=> 'Owner Name is required',
			'category.required'	    => 'Category is required',
			'title.required'		=> 'Site Title is required',
			'description.required'	=> 'Description is required',
			'address.required'		=> 'Address  is required',		
			'phone.required'		=> 'Phone is required',
			'photo.required'		=> 'required|image|mimes:jpeg,png,jpg,gif,svg|max:1024',
			'photo.image'           => 'Image should be a jpeg,png,gif,svg', 	'latitude.required'	    => 'Latitude is required',
            'longitude.required'	=> 'Longitude is required',		
		    'website.required'	    => 'Website is required',
		];
		
		$validator = \Validator::make($request->all(), $rules, $messages);
		
		if ($validator->fails()) {
			$return['data'] = $validator->errors()->toArray();
			$return['msg'] = 'Validation Issue';
			return response()->json($return);
		}
		
		// validation ok
		
		
		$photo = $request->file('photo');	   
	   
		$imagename = time().'.'.$photo->getClientOriginalExtension(); 
		$destinationPath = public_path('/uploads/localvendor/thumbnail');
		$thumb_img = Image::make($photo->getRealPath())->crop(358, 200);
		$thumb_img->save($destinationPath.'/'.$imagename,80);		
		$destinationPath = public_path('/uploads/localvendor/original');
		$photo->move($destinationPath, $imagename);
		
		try {
			
			$localvendor=new Localvendor;
			
			$localvendor->user_id=$request->user_id;
			$localvendor->owner_name=$request->owner_name;
			$localvendor->category=$request->category;
			$localvendor->title=$request->title;
			$localvendor->description=$request->description;		
			$localvendor->address=$request->address;
			$localvendor->image=$imagename;
			$localvendor->working_hours=$request->working_hours;
			$localvendor->latitude=$request->latitude;
			$localvendor->longitude=$request->longitude;
			$localvendor->phone=$request->phone;
			$localvendor->website=$request->website;
			
			/*
			$localvendor->user_id=$request->input('user_id');
			$localvendor->category=$request->input('category');
			$localvendor->owner_name=$request->input('owner_name');
			$localvendor->title=$request->input('title');
			$localvendor->description=$request->input('description');
			$localvendor->address=$request->input('address');
			$localvendor->image=$imagename;
			$localvendor->latitude=$request->input('latitude');
			$localvendor->longitude=$request->input('longitude');
			$localvendor->phone=$request->input('phone');
			$localvendor->website=$request->input('website');
			$localvendor->working_hours=$request->input('working_hours');
			$localvendor->listing_date=$listing_date;
			$localvendor->start_time=$strtTime;
			$localvendor->end_time=$end_time;
			*/
			
			$localvendor->save();
			
			$return['data'] = $localvendor;
			$return['msg'] = 'Success';
			$return['status'] = 1;
		}  catch (\Exception $e) {
			$return['msg'] = 'Failed';
		}
    
		return response()->json($return);
	}
	
	  public function store_ios(Request $request)
    {

        $return = array('status' => 0, 'msg' => 'User Id is required.');
		
		$rules = [
			'user_id'		=> 'required',
			'owner_name'	=> 'required',
			'category'	    => 'required',			
			'title'			=> 'required',
			'photo'	    => 'required',
			'description'	=> 'required',
			'address'		=> 'required',		
			'latitude'		=> 'required',
			'longitude'		=> 'required',
			'phone'		    => 'required',	
			'website'		=> 'required',
		];				
		$messages = [
			'user_id.required'		=> 'Username is required',
			'owner_name.required'	=> 'Owner Name is required',
			'category.required'	    => 'Category is required',
			'title.required'		=> 'Site Title is required',
			'photo.required'		=> 'Photo is required',
			'description.required'	=> 'Description is required',
			'address.required'		=> 'Address  is required',		
			'latitude.required'	    => 'Latitude is required',
            'longitude.required'	=> 'Longitude is required',		
		    'website.required'	    => 'Website is required',
		];
		
		$validator = \Validator::make($request->all(), $rules, $messages);
		
		if ($validator->fails()) {
			$return['data'] = $validator->errors()->toArray();
			$return['msg'] = 'Validation Issue';
			return response()->json($return);
		}
		
		// validation ok
		
		/*
		$photo = $request->file('photo');	   
	   
		$imagename = time().'.'.$photo->getClientOriginalExtension(); 
		$destinationPath = public_path('/uploads/localvendor/thumbnail');
		$thumb_img = Image::make($photo->getRealPath())->crop(358, 200);
		$thumb_img->save($destinationPath.'/'.$imagename,80);		
		$destinationPath = public_path('/uploads/localvendor/original');
		$photo->move($destinationPath, $imagename);
		*/
		$destinationPath = public_path('/uploads/localvendor/original/');
					/*$image_parts = explode(";base64,",$request->photo);
					$image_type_aux = explode("image/", $image_parts[0]);
					$image_type = $image_type_aux[1];
					$image_base64 = base64_decode($image_parts[1]);	*/		
					$image = $request->photo;
					$image_base64 = base64_decode($image);
					$file = uniqid() . '.png';
					$gfile=$destinationPath . $file;
					file_put_contents($gfile, $image_base64);
					$thumb_img = Image::make($destinationPath . $file)->crop(358, 200);
					$destinationThumPath = public_path('/uploads/localvendor/thumbnail');
				    $thumb_img->save($destinationThumPath.'/'.$file,80); 
				
		try {
			
			$localvendor=new Localvendor;
			
			$localvendor->user_id=$request->user_id;
			$localvendor->owner_name=$request->owner_name;
			$localvendor->category=$request->category;
			$localvendor->title=$request->title;
			$localvendor->description=$request->description;		
			$localvendor->address=$request->address;
			$localvendor->image=$file;
			$localvendor->working_hours=$request->working_hours;
			$localvendor->latitude=$request->latitude;
			$localvendor->longitude=$request->longitude;
			$localvendor->phone=$request->phone;
			$localvendor->website=$request->website;
			
			/*
			$localvendor->user_id=$request->input('user_id');
			$localvendor->category=$request->input('category');
			$localvendor->owner_name=$request->input('owner_name');
			$localvendor->title=$request->input('title');
			$localvendor->description=$request->input('description');
			$localvendor->address=$request->input('address');
			$localvendor->image=$imagename;
			$localvendor->latitude=$request->input('latitude');
			$localvendor->longitude=$request->input('longitude');
			$localvendor->phone=$request->input('phone');
			$localvendor->website=$request->input('website');
			$localvendor->working_hours=$request->input('working_hours');
			$localvendor->listing_date=$listing_date;
			$localvendor->start_time=$strtTime;
			$localvendor->end_time=$end_time;
			*/
			
			$localvendor->save();
			
			$return['data'] = $localvendor;
			$return['msg'] = 'Success';
			$return['status'] = 1;
		}  catch (\Exception $e) {
			$return['msg'] = 'Failed';
		}
    
		return response()->json($return);
	}
	

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }
    public function subcategory($id)
    {


    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
	
	// Local vendor marketing
	
	public function marketing_users(Request $request)
	{
		$return = array('status' => 0, 'data' => '', 'msg' => 'Invalid request');
		
		$rules = [
			'marketing_person_id'	=> 'required'
		];
		
		$validator = \Validator::make($request->all(), $rules);
		
		if ($validator->fails()) {
            $return['data'] = $validator->errors()->toArray();
			$return['msg'] = 'Validation Issue';
			return response()->json($return);
        }
		
		$return = array('status' => 1, 'msg' => 'Success');
		$return['data'] = LocalvendorMarketing::with('state', 'city')->where('marketing_person_id', $request->marketing_person_id)->get();
		
		return response()->json($return);
	}
	
	public function marketing_users_to_add()
	{
		$marketing_users = LocalvendorMarketing::where('connected_with_shop', 0)->get();
		
		return response()->json($marketing_users);
	}
	
	public function add_marketing_user(Request $request) // update
	{		
		$return = array('status' => 0, 'data' => '', 'msg' => 'Invalid unique code');
		
		$LocalvendorMarketing = new LocalvendorMarketing;
		$rules = $LocalvendorMarketing->get_adding_rules();
		
		$validator = \Validator::make($request->all(), $rules['rules'], $rules['messages']);

        if ($validator->fails()) {
            $return['data'] = $validator->errors()->toArray();
			$return['msg'] = 'Validation Issue';
			return response()->json($return);
        }
		
		$marketing_user = LocalvendorMarketing::where('unique_code', $request->unique_code)->first();
		//marketing_person_id, marketing_person_name, shop_name, customer_name, address, phone, state_id, city_id
		if($marketing_user)
		{
			$marketing_user->marketing_person_id = $request->marketing_person_id;
			$marketing_user->marketing_person_name = $request->marketing_person_name;
			$marketing_user->shop_name = $request->shop_name;
			$marketing_user->customer_name = $request->customer_name;
			$marketing_user->address = $request->address;
			$marketing_user->phone = $request->phone;		
			$marketing_user->state_id = $request->state_id;
			$marketing_user->city_id = $request->city_id;
			$marketing_user->vendor_status = 1;
			$marketing_user->connected_with_shop = 1;
			$marketing_user->save();
			
			$return = array('status' => 1, 'msg' => 'Success');
		}
		
		return response()->json($return);
	}
}
