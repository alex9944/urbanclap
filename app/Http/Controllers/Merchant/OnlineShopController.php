<?php

namespace App\Http\Controllers\Merchant;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Shopproducts;
use App\Models\Pro_cat;
use App\Models\Products_properties;
use App\Models\Listing;
use App\Models\Category;
use App\Models\CategoryType;
use App\Models\OrderBooking;
use App\Models\ShopOrderBookingDetail;
use App\Models\ShopOnlineSettings;
use App\Models\MerchantServices;
use App\Models\ShopProductImages;
use Session;
use Response;
use Validator;
use DB;
use Auth;
use Image;
use Mail;

class OnlineShopController extends Controller
{

	public function __construct()
	{
		parent::__construct();

		$this->ShopOnlineSettings = new ShopOnlineSettings;
		$this->order_online_id = $this->ShopOnlineSettings->order_online_id;
	}

	public function view_products() 
	{
		$id=Auth::user()->id;
		
		$Products = Shopproducts::where('merchant_id',$id)->get(); // now we are fetching all products
		
		$merchant_services = MerchantServices::where('merchant_id',$id)->get();
		
		// check if shop enable or not
		$shop_desable = true; // default
		$services = $this->services;
		$shop_id = $services['shop']['id'];
		foreach($merchant_services as $merchant_service) {
			$category_type_id = $merchant_service->category_type_id;
			
			if($category_type_id == $shop_id and $merchant_service->is_enable) {
				$shop_desable = false; // shop is now enable
				break;
			}
		}
		$cat_data = DB::table('shop_pro_cat')->get();
		//return view('panels.merchant.online_shop.products', compact('Products'));
		return $this->_loadMerchantView('online_shop.products', compact('Products', 'merchant_services', 'shop_desable','cat_data'));
	}
	
	public function enable()
	{	 
		$merchant_service_id = null;
		
		$services = $this->services;
		$shop_category_type_id = $services['shop']['id'];
		
		$merchant_id=Auth::user()->id;
		$merchant_services = MerchantServices::where('merchant_id',$merchant_id)->get();
		foreach($merchant_services as $merchant_service) {
			$category_type_id = $merchant_service->category_type_id;
			
			if($category_type_id == $shop_category_type_id) {
				$merchant_service_id = $merchant_service->id;
				break;
			}
		}
		
		
		$merchant_id = (auth()->check()) ? auth()->user()->id : null;
		
		if($merchant_service_id)
		{
			$merchant_service = MerchantServices::find($merchant_service_id);
			$merchant_service->category_type_id = $shop_category_type_id;
			$merchant_service->merchant_id = $merchant_id;
			$merchant_service->is_enable = 1;
			$merchant_service->save();
		}
		else
		{
			$merchant_service = new MerchantServices;
			$merchant_service->category_type_id = $shop_category_type_id;
			$merchant_service->merchant_id = $merchant_id;
			$merchant_service->is_enable = 1;
			$merchant_service->save();
		}
		
		return redirect('merchant/online-shop/products')->with('message', 'Service activated successfully');
	}
	
	public function disable()
	{	 
		$merchant_service_id = null;
		
		$services = $this->services;
		$shop_category_type_id = $services['shop']['id'];
		
		$merchant_id=Auth::user()->id;
		$merchant_services = MerchantServices::where('merchant_id',$merchant_id)->get();
		foreach($merchant_services as $merchant_service) {
			$category_type_id = $merchant_service->category_type_id;
			
			if($category_type_id == $shop_category_type_id) {
				$merchant_service_id = $merchant_service->id;
				break;
			}
		}
		
		
		$merchant_id = (auth()->check()) ? auth()->user()->id : null;
		
		if($merchant_service_id)
		{
			$merchant_service = MerchantServices::find($merchant_service_id);
			$merchant_service->category_type_id = $shop_category_type_id;
			$merchant_service->merchant_id = $merchant_id;
			$merchant_service->is_enable = 0;
			$merchant_service->save();
		}
		else
		{
			$merchant_service = new MerchantServices;
			$merchant_service->category_type_id = $shop_category_type_id;
			$merchant_service->merchant_id = $merchant_id;
			$merchant_service->is_enable = 0;
			$merchant_service->save();
		}
		
		return redirect('merchant/online-shop/products')->with('message', 'Service de-activated successfully');
	}
  
	  public function addpro_form(){
		$cat_data = DB::table('shop_pro_cat')->get();
		
		$merchant_id = Auth::user()->id;

		$listing=Listing::where('user_id', $merchant_id)->first();
		
		return view('panels.merchant.online_shop.home', compact('cat_data','listing'));
	  }
	public function status_online_shop($id)
		{		

			$menu_item = Shopproducts::find($id);
			if($menu_item->status==0)
			{
				$menu_item->status = 1;
				$menu_item->save();
				$return['status'] = 1;
			}else{
				$menu_item->status = 0;
				$menu_item->save();	
				$return['status'] = 0;
				}	
				
				$return['id'] = $id;		
				// ajax
				return Response::json(array(
					'view_details'   => $return
					));
		}
	public function edit_online_shop($id)
	{		

		$return['online_shop'] = Shopproducts::find($id);
		$return['listing'] = $return['online_shop']->listing;
		$return['pro_img'] =$return['online_shop']->images;
		// ajax
		return Response::json(array(
			'view_details'   => $return
			));
	}
  public function add_product(Request $request) 
  {    
	$original_price = (int)($request->pro_price);
	
	$merchant_id = Auth::user()->id;
	$listing=Listing::where('user_id', $merchant_id)->first();
	
    $this->products = new Shopproducts;
    $rules = $this->products->get_adding_rules();
	$rules['rules']['spl_price'] = 'numeric|max:'. ($original_price- 1);
    $this->validate($request, $rules['rules'], $rules['messages']);
	
	$photo = $request->file('pro_img'); //print_r($photo);
	$imagename = '';
	if($photo)
	{
		$imagename = time() . '_' . rand(10,100) . '_' . strtolower($photo->getClientOriginalName());   
		
		// thumb img
		$destinationPath = public_path('upload/images/small');
		$thumb_img = Image::make($photo->getRealPath())->resize(120, 100);
		$thumb_img->save($destinationPath.'/'.$imagename,80);   

		// original img
		$destinationPath = public_path('upload/images/medium');
		$medium_img = Image::make($photo->getRealPath())->resize(500, 416);
		$medium_img->save($destinationPath.'/'.$imagename,80);
		
		$destinationPath = public_path('upload/images');
		$photo->move($destinationPath, $imagename);
	}

    $this->products->pro_name = $request->pro_name;
    $this->products->listing_id=$listing->id;
    $this->products->merchant_id= $merchant_id;
    $this->products->cat_id = $request->cat_id;
    $this->products->pro_code = $request->pro_code;
    $this->products->pro_price = $request->pro_price;
    $this->products->pro_info = $request->pro_info;
    $this->products->spl_price = $request->spl_price;
    $this->products->pro_img = $imagename;
	$this->products->status = 1;
    $this->products->stock=$request->stock;
	$this->products->shipping_price=$request->shipping_price;
    $this->products->save();
	
$pid= $this->products->id;

	$listing_image_names = session('listing_image_names');
			if($listing_image_names)
				foreach($listing_image_names as $image_id => $image) {

					if($image) {
									$pimages = new ShopProductImages;							
									$pimages->proId=$pid;
									$pimages->alt_img=$image;
									$pimages->save();
						// Unset session
						Session::forget('listing_image_names.' . $image_id);
						
					}
				}
	
    $cat_data = DB::table('shop_pro_cat')->get();

    $listing = Listing::where('user_id', $merchant_id)->first();

	return redirect('merchant/online-shop/products')->with('message', 'Product added successfully.');
  }

      public function add_cat() {

        return view('panels.merchant.online_shop.addCat');
      }

    // add cat
      public function catForm(Request $request) {
        //echo $request->cat_name;
        //return 'update query here';
        $pro_cat = new pro_cat;

        $pro_cat->name = $request->cat_name;
        $pro_cat->status = '0'; // by defalt enable
        $pro_cat->save();

        $cats = DB::table('shop_pro_cat')->orderby('id', 'DESC')->get();

        return view('panels.merchant.online_shop.categories', compact('cats'));
      }

    // edit form for cat
      public function CatEditForm(Request $request) {
        $catid = $request->id;
        $cats = DB::table('shop_pro_cat')->where('id', $catid)->get();
        return view('panels.merchant.online_shop.CatEditForm', compact('cats'));
      }

    //update query to edit cat
      public function editCat(Request $request) {

        $catid = $request->id;
        $catName = $request->cat_name;
        $status = $request->status;
        DB::table('shop_pro_cat')->where('id', $catid)->update(['name' => $catName, 'status' => $status]);

        $cats = DB::table('shop_pro_cat')->orderby('id', 'DESC')->get();

        return view('panels.merchant.online_shop.categories', compact('cats'));
      }

      public function view_cats() {

        $cats = DB::table('shop_pro_cat')->get();

        return view('panels.merchant.online_shop.categories', compact('cats'));
      }

      public function ProductEditForm($id) {
        //$pro_id = $reqeust->id;
        $Products = DB::table('shop_products')->where('id', '=', $id)->get(); // now we are fetching all products
        
        return view('panels.merchant.online_shop.editPproducts', compact('Products'));
      }

    public function editProduct(Request $request) 
	{
		$original_price = (int)($request->pro_price);
		
		$this->products = new Shopproducts;
		$rules = $this->products->get_adding_rules();
		$rules['rules']['spl_price'] = 'numeric|max:'. ($original_price- 1);
		unset($rules['rules']['pro_img']);
		$this->validate($request, $rules['rules'], $rules['messages']);
		
        $proid = $request->id;
        $pro_name = $request->pro_name;
        $cat_id = $request->cat_id;
        $pro_code = $request->pro_code;
        $pro_info = $request->pro_info;
        $pro_price = $request->pro_price;
        $spl_price = $request->spl_price;
        $shipping_price = $request->shipping_price;
        $stock = $request->stock;
        
		if($request->new_arrival =='NULL'){
          $new_arrival = '1';
        }else {
          $new_arrival = $request->new_arrival;
        }

	$file = $request->file('pro_img');
			if($file) {
					$this->products = new Shopproducts;
					$rules = $this->products->get_adding_rules();
					$this->validate($request, $rules['rules'], $rules['messages']);
					$filename = $file->getClientOriginalName();
					$path=public_path('img/alt_images');
					$S_path = public_path('upload/images/small');
					$M_path = public_path('upload/images/medium');
					$img = Image::make($file->getRealPath());		
					$img->save($path . '/' . $filename);
					$img->resize(120, 100)->save($S_path . '/' . $filename);
					$img->resize(500, 416)->save($M_path . '/' . $filename);
					  DB::table('shop_products')
						->where('id', $proid)
						->update(['pro_img' => $filename]);
				}
		
        DB::table('shop_products')->where('id', $proid)->update([
          'pro_name' => $pro_name,
          'cat_id' => $cat_id,
          'pro_code' => $pro_code,
          'pro_price' => $pro_price,
          'pro_info' => $pro_info,
          'spl_price' => $spl_price,
          'shipping_price' => $shipping_price,
          'stock' => $stock

        ]);
		
	$listing_image_names = session('listing_image_names');
				if($listing_image_names)
					foreach($listing_image_names as $image_id => $image) {

						if($image) {
										$pimages = new ShopProductImages;							
										$pimages->proId=$proid;
										$pimages->alt_img=$image;
										$pimages->save();
							// Unset session
							Session::forget('listing_image_names.' . $image_id);
							
						}
					}

        return redirect('merchant/online-shop/products')->with('message', 'Product updated successfully.');
        //$Products = DB::table('pro_cat')->rightJoin('products','products.cat_id', '=', 'pro_cat.id')->get(); // now we are fetching all products
        //return view('admin.products', compact('Products'));
    }
	
	public function delete_product(Request $request)
	{			
		$item = Shopproducts::find($request->id);
		$order_booking_details = $item->order_booking_details;
		
		if($order_booking_details->count() > 0) {			
			return \Response::json(array(
					'success' => false,
					'msg'   => 'Not deleted. because related orders exists.'
					));
		}
		
		$msg = 'Invalid Id';
		
		if($item)
		{

			$item->images()->delete();
			$is_deleted = $item->delete();
			
			if($is_deleted)
			{
				\Session::flash('message', 'Product item deleted successfully.');
				
				return \Response::json(array(
					'success' => true,
					'msg'   => 'Product item deleted successfully.'
					));
			}
			$msg = 'Not deleted. because related orders exists.';
		}
		
		return \Response::json(array(
			'success' => false,
			'msg'   => $msg
			));
	}	
	  
	public function deleteUploadedImageFromTable($id)
	{	

		$ShopProductImages = ShopProductImages::find($id);
		
		// delete file from server
		@unlink(public_path('upload/images/small') . '/' . $ShopProductImages->alt_img);
		@unlink(public_path('upload/images/medium') . '/' . $ShopProductImages->alt_img);
		@unlink(public_path('upload/images') . '/' . $ShopProductImages->alt_img);
		
		$ShopProductImages->delete();

		return response()->json(['status' => 1, 'msg' => 'success']);

	}
	public function deleteUploadedImage($id)
	{	

		//Session::forget('listing_image_names.0');
		$listing_image_names = session('listing_image_names');

		if($listing_image_names)
			foreach($listing_image_names as $image_id => $image) {

				if($image_id == $id) {
			// Delete a single file
					@unlink(public_path('/upload/images/small').'/'.$image);
					@unlink(public_path('/upload/images/medium').'/'.$image);

			// Unset session
					Session::forget('listing_image_names.' . $image_id);
					break;
				}
			}

			return response()->json(['status' => 1, 'msg' => 'success']);

		//$listing_image_names = (array) session('listing_image_names');//print_r($listing_image_names);

	}
      public function ImageEditForm($id) {
        $Products = DB::table('shop_products')->where('id', '=', $id)->get(); // now we are fetching all products
        return view('panels.merchant.online_shop.ImageEditForm', compact('Products'));
      }

      public function editProImage(Request $request) {

        $proid = $request->id;

        $file = $request->file('new_image');

        $filename = time() . '.' . $file->getClientOriginalName();

        $path=public_path('img/alt_images');
        $S_path = public_path('upload/images/small');
        $M_path = public_path('upload/images/medium');
        /*$L_path = public_path('upload/images/large');*/

        $img = Image::make($file->getRealPath());
        //$img->crop(300, 150, 25, 25);
        $img->resize(120, 100)->save($S_path . '/' . $filename);
        $img->resize(500, 416)->save($M_path . '/' . $filename);
        $img->save($path . '/' . $filename);



       // $file->move($path, $filename);


        DB::table('shop_products')->where('id', $proid)->update(['pro_img' => $filename]);
        return redirect('/merchant/products');
        //echo 'done';
        //  $Products = DB::table('products')->get(); // now we are fetching all products
        //  return view('admin.products', compact('Products'));
      }

    //for delete cat
      public function deleteCat($id) {

        //echo $id;
        DB::table('shop_pro_cat')->where('id', '=', $id)->delete();


        $cats = DB::table('shop_pro_cat')->get();

        return view('panels.merchant.online_shop.categories', compact('cats'));
      }
      public function addPropertyAll(){
        return view('panels.merchant.online_shop.addProperty');
      }
      public function sumbitProperty(Request $request){

        $properties = new Products_properties;
        $properties->pro_id = $request->pro_id;
        $properties->size = $request->size;
        $properties->color = $request->color;
        $properties->p_price = $request->p_price;
        $properties->save();

        return redirect('/merchant/ProductEditForm/'.$request->pro_id);

      }

      public function editProperty(Request $request){
       $uptProts = DB::table('shop_products_properties')
       ->where('pro_id', $request->pro_id)
       ->where('id', $request->id)
       ->update($request->except('_token'));
       if($uptProts){
        return back()->with('msg', 'updated');
      }else {
        return back()->with('msg', 'check value again');
      }
    }

    public function addSale(Request $request){
      $salePrice = $request->salePrice;
      $pro_id = $request->pro_id;
      DB::table('products')->where('id', $pro_id)->update(['spl_price' => $salePrice]);
      echo 'added successfully';
    }

    public function addAlt($id){
      $proInfo = DB::table('shop_products')->where('id', $id)->get();
      return view('panels.merchant.online_shop.addAlt', compact('proInfo'));
    }

    public function submitAlt(Request $request){
     $file = $request->file('image');
      $filename  = time() . $file->getClientOriginalName(); // name of file
      $path =public_path('/img/alt_images');
      $file->move($path,$filename); // save to our local folder
      $proId = $request->pro_id;
      $add_lat = DB::table('shop_alt_images')
      ->insert(['proId' => $proId, 'alt_img' => $filename, 'status' =>0]);
      return back();
    }

    public function users(){

      $usersData = DB::table('users')
    //  ->Join('address','address.user_id','users.id')
      ->get();
      return view('admin.users',compact('usersData', $usersData));
    }
    public function updateRole(Request $request){
      $userId = $request->userID;
      $role_val = $request->role_val;

      $upd_role = DB::table('users')->where('id',$userId)->update(['admin' =>$role_val]);
      if($upd_role){
        echo "role is updated successfully";
      }
    }

    public function import_products(Request $request){
      $this->validate($request,[
        'file' => 'required|mimes:csv,txt'
      ]);

      if(($handle = fopen($_FILES['file']['tmp_name'],"r")) !== FALSE){
        fgetcsv($handle); // remove first row of excel file such as product name,price,code

        while(($data = fgetcsv($handle,1000,",")) !==FALSE){

          $addPro =  DB::table('products')->insert([
            'pro_name' => $data[0],
            'pro_code' => $data[1],
            'pro_info' => $data[2],
            'pro_img' => $data[3],
            'pro_price' => $data[4],
            'cat_id' => $data[5],
            'stock' => '10',
            'new_arrival' => '0',
            'spl_price' => '0',
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
          ]);
        }
      }

    }
    public function customer_orders()
    {
      $user_id = (auth()->check()) ? auth()->user()->id : null;

      $order_booking = OrderBooking::where(['merchant_id' => $user_id, 'order_type' => 'online_shopping'])->get();
      foreach ($order_booking as $key => $value) {
        $value->total=ShopOrderBookingDetail::where('order_id',$value->id)->sum('total_amount');
        $value->total_qty=ShopOrderBookingDetail::where('order_id',$value->id)->sum('quantity');
      }

      return view('panels.merchant.online_shop.customer_orders', compact('order_booking'));
    }

    public function order_customer_detail($id)
    {
      $order_booking = OrderBooking::find($id);

      $order_booking->total=ShopOrderBookingDetail::where('order_id',$id)->sum('total_amount');
      //$value->total_qty=OrderBookingDetail::where('order_id',$value->id)->sum('quantity');

      $OrderBooking = new OrderBooking;
      $order_status = $OrderBooking->order_status;

      if(!$order_booking)
        return redirect('merchant/customer-orders')->with('error_message', 'Invalid Id');

      return view('panels.merchant.online_shop.customer_order_details', compact('order_booking', 'order_status'));
    }

    public function update_booking_satus($id, Request $request)
    {

      $order_booking = OrderBooking::find($id);
      $order_booking->status = $request->status;
      $order_booking->save();

      return redirect('merchant/customer-orders/'.$id)->with('message', 'Status updated successfully');

    }
    public function settings()
    {
      $user_id = (auth()->check()) ? auth()->user()->id : null;

      $setting = ShopOnlineSettings::where('merchant_id', $user_id)->first();

      return view('panels.merchant.online_shop.settings', compact('setting'));
    }

    public function update_settings(Request $request)
    {
      $ShopOnlineSettings = $this->ShopOnlineSettings;
      $rules = $ShopOnlineSettings->get_adding_rules();
      $this->validate($request, $rules['rules'], $rules['messages']);

      $merchant_id = (auth()->check()) ? auth()->user()->id : null;

      if($request->id)
      {
        $settings = ShopOnlineSettings::find($request->id);
      $settings->estimated_delivery_time = '';//$request->estimated_delivery_time;
      $settings->delivery_fee = $request->delivery_fee;
      $settings->minimum_delivery_amount = 0;//$request->minimum_delivery_amount;
      $settings->save();
    }
    else
    {
      $settings = $this->ShopOnlineSettings;
      $settings->merchant_id = $merchant_id;
      $settings->estimated_delivery_time = '';//$request->estimated_delivery_time;
      $settings->delivery_fee = $request->delivery_fee;
      $settings->minimum_delivery_amount = 0;//$request->minimum_delivery_amount;
      $settings->save();
    }
    
    return redirect('merchant/online-shop/settings')->with('message', 'Settings updated successfully');
  }


}
