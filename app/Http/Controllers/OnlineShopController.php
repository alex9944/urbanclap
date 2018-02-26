<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Listing;
use App\Models\Recommends;
use App\Models\wishList;
use App\Models\Shopproducts;
use App\Models\ShopProductImages;
use App\Models\OrderOnlineSettings;
use App\Models\ShopOnlineSettings;
use App\Models\Pro_cat;
use App\Models\Products_properties;
use App\Models\Category;
use App\Models\CategoryType;
use App\Models\OrderBooking;
use App\Models\ShopOrderBookingDetail;
use App\Models\Productreview;

use Gloudemans\Shoppingcart\Facades\Cart;
use DB;
use Jenssegers\Agent\Agent;
use Cookie;
use Validator;
use Auth;
use Image;

class OnlineShopController extends Controller
{
	public function __construct(Request $request)
	{
		parent:: __construct();
    
		$this->ShopOnlineSettings = new ShopOnlineSettings;
		$this->order_online_id = $this->ShopOnlineSettings->order_online_id;
	}
	Public function product_details($id) {
			//insert command for views
		if(Auth::check()){
		 $recommends = new Recommends;
		 $recommends->uid = Auth::user()->id;
		 $recommends->pro_id = $id;
		 $recommends->save();
	   }


			//view product details
	  // $Products = DB::table('shop_products')->where('id', $id)->get();
	   		
				$Products = Shopproducts::where('id', $id)
					->where('stock', '>', 0)
					->first();
			$reviews=Productreview::where('product_id',$id)->where('approved','1')->ordered();

			$avgrating=Productreview::where('product_id',$id)->where('approved','1')->avg('rating');

			if(sizeof($reviews)>0){

				$excellentcount=Productreview::where('product_id',$id)->where('rating', 5)->where('approved','1')->count();
				$goodcount=Productreview::where('product_id',$id)->where('rating','<', 5)->where('rating','>=', 4)->where('approved','1')->count();
				$averagecount=Productreview::where('product_id',$id)->where('rating','<', 4)->where('rating','>=', '2.5')->where('approved','1')->count();
				$badcount=Productreview::where('product_id',$id)->where('rating','<', '2.5')->where('approved', 1)->count();
				$total_reviews=sizeof($reviews);
				$excellent=$excellentcount/$total_reviews*100;
				$good=$goodcount/$total_reviews*100;
				$average=$averagecount/$total_reviews*100;
				$bad=$badcount/$total_reviews*100;

			}
			else{
				$excellent='0';
				$good='0';
				$average='0';
				$bad='0';
			}
	   return view('pages.listing_detail.product_detail', compact('Products','reviews','avgrating','excellent','good','average','bad','total_reviews'));

	}
 
	public function productByPrice(Request $request)
	{                          
        //$start_end_value = $request->start_end_value;
		
		$start = $request->start; // min price value
        $end = $request->end; 
		/*if($start_end_value)
		{
			$start_end_value_arr = explode(',', $start_end_value);
			$start = isset($start_end_value[0]) ? $start_end_value[0] : '';
			$end = isset($start_end_value[1]) ? $start_end_value[1] : '';
		}*/
		
		$Products = [];
		if($start || $end)
		{
			$listing_id=$request->listing;
		   // max price value
			$Products = Shopproducts::select('*','shop_products.id as pid')
			->leftJoin('shop_pro_cat', 'shop_pro_cat.id', '=', 'cat_id')
			->where('pro_price', '>=', $start)->where('pro_price', '<=', $end)		
			->where('shop_products.stock', '>', 0)			
			->where('listing_id',$listing_id)->get();
			/*foreach ($Products as $key => $value) {
				$value->wishlist=DB::table('shop_wishlist')->where(['pro_id' => $value->id])->count();


			}*/
		}
        return response()->json($Products); //return to ajax
             //return view('front.products', compact('Products'));

      }
      public function productByBrand(Request $request){
       	$brand = $request->brand; //brand
        $listing_id=$request->listing;
		if($brand)
		{
			$Products = Shopproducts::select('*','shop_products.id as pid')
			->leftJoin('shop_pro_cat', 'shop_pro_cat.id', '=', 'cat_id')
			->whereIN('cat_id', explode( ',', $brand ))
			->where('shop_products.stock', '>', 0)
			->where('listing_id',$listing_id)->get();
		}
		else
		{
			$Products = Shopproducts::select('*','shop_products.id as pid')
			->leftJoin('shop_pro_cat', 'shop_pro_cat.id', '=', 'cat_id')
			->where('shop_products.stock', '>', 0)
			->where('listing_id',$listing_id)->get();
		}
		/*
        foreach ($Products as $key => $value) {
        	$value->wishlist=DB::table('shop_wishlist')->where(['pro_id' => $value->id])->count();
        }*/
        return response()->json($Products);
      }
      public function wishlist(Request $request){
        $wishList = new wishList;
        $wishList->user_id = Auth::user()->id;
        $wishList->pro_id = $request->pro_id;
        $wishList->save();
        $Products = Shopproducts::where('id', $request->pro_id)->get();
        return view('pages.listing_detail.product_detail', compact('Products'));
      }
      public function View_wishList() {
        $Products = DB::table('shop_wishlist')->leftJoin('shop_products', 'shop_wishlist.pro_id', '=', 'shop_products.id')->where('shop_wishlist.user_id',Auth::user()->id)->get();
        return view('pages.listing_detail.wishList', compact('Products'));
      }
      public function removeWishList($id) {

        DB::table('shop_wishlist')->where('pro_id', '=', $id)->delete();

        return back()->with('msg', 'Item Removed from Wishlist');
      }
      public function addReview(Request $request){
        DB::table('shop_reviews')->insert(
         ['person_name' => $request->person_name, 'person_email' => $request->person_email,
         'review_content' => $request->review_content,
         'created_at' => date("Y-m-d H:i:s"),'updated_at' =>date("Y-m-d H:i:s")]
       );
        return back();
      }
      public function index(){
        $cartItems = Cart::content();
        return view('cart.index', compact('cartItems'));

      }

    public function addItem(Request $request, $id)
	{		
		
        $return = array('status' => 0, 'msg' => 'Invalid Request', 'data' => '');
		
		$products = Shopproducts::find($id); // get prodcut by id
		
		if($products)
		{
			$listing_id = $products->listing_id;
					
			if(isset($request->newPrice))
			{
				$price = $request->newPrice; // if size select
			}
			else{
				$price = $products->pro_price; // default price
			}
			
			$OrderBooking = new OrderBooking;
			$request->item_id = $id;
			$request->name = $products->pro_name;
			$request->price = $price;
			$request->quantity = 1;
			$request->user_id = (auth()->check()) ? auth()->user()->id : null;
			$request->listing_id = $listing_id;
			$request->order_type = 'shop';
			$request->img = $products->pro_img;
			$request->stock = $products->stock;
			
			$return = $OrderBooking->add_to_cart($request);
			
			if($return['status'] == 1)
			{
				$items = \Cart::getContent();

				$view = \View::make('pages.listing_detail.cart', ['online_order_items' => $items]);
				$contents = $view->render();

				$return['data'] = $contents;
			}
		}

		return response()->json($return);
    }

	public function destroy($id){
	  Cart::remove($id);
		return back(); // will keep same page
	}

    public function update(Request $request, $id)
    {
         $qty = $request->qty;
         $proId = $request->proId;
         $rowId = $request->rowId;
            Cart::update($rowId,$qty); // for update
            $cartItems = Cart::content(); // display all new data of cart
            return view('cart.upCart', compact('cartItems'))->with('status', 'cart updated');
           

    }
	public function addpro_form()
	{
		$users = DB::table('users')
		->select('*', 'users.id as uid')
		->leftJoin('role_user', 'users.id', '=', 'role_user.user_id')
		->where('role_user.role_id', '=', 3)
		->get();

		$cat_data = DB::table('shop_pro_cat')->get();

		/*$listing_dat=DB::table('listing')->get();
		$listing=array();
		foreach ($listing_dat as $key => $value) {
			$cat_type=DB::table('category')->where('c_id',$value->m_c_id)->get();
			$types=json_decode($cat_type->category_type);
			if(in_array($this->order_online_id, $types)){
				array_push($listing_val, $value);
			}
		}*/
		
		$order_online_id = $this->order_online_id;
		
		$listing = Listing::whereHas('category', function($query) use($order_online_id) {
			$query->where('category_type', 'like', '%'.$order_online_id.'%');
		})->where(['status' => 'Enable'])->get();
		
		return view('panels.admin.online_shop.home', compact('cat_data','listing','users'));
	}

	public function add_product(Request $request) 
	{
		$this->products = new Shopproducts;
		$rules = $this->products->get_adding_rules();
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
		
		$listing = Listing::find($request->list_id);

		$this->products->pro_name = $request->pro_name;
		$this->products->listing_id=$request->list_id;
		$this->products->merchant_id=$listing->user_id;
		$this->products->cat_id = $request->cat_id;
		$this->products->pro_code = $request->pro_code;
		$this->products->pro_price = $request->pro_price;
		$this->products->pro_info = $request->pro_info;
		$this->products->spl_price = $request->spl_price;
		$this->products->pro_img = $imagename;
		$this->products->stock=$request->stock;
		$this->products->shipping_price=$request->shipping_price;
		$this->products->save();
		
		return redirect('admin/products')->with('message','Product added successfully');

		/*$cat_data = DB::table('shop_pro_cat')->get();
		$listing=DB::table('listing')->get();
		$users = DB::table('users')
		->select('*', 'users.id as uid')
		->leftJoin('role_user', 'users.id', '=', 'role_user.user_id')
		->where('role_user.role_id', '=', 3)
		->get();
		return view('panels.admin.online_shop.home', compact('cat_data','listing','users'))->with('message', 'Product added successfully');*/
	}

	public function view_products() 
	{

		$Products = DB::table('shop_pro_cat')->rightJoin('shop_products', 'shop_products.cat_id', '=', 'shop_pro_cat.id')->get(); // now we are fetching all products

		return view('panels.admin.online_shop.products', compact('Products'));
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
	
	public function delete_all_products(Request $request)
	{
		$msg = 'Seltected products are deleted successfully';
		$error_msg = '';
		
		$cn = count($request->selected_id);
		if($cn > 0)
		{			
			$data = $request->selected_id;			
			foreach($data as $id) {
				$item = Shopproducts::find($id);
				$order_booking_details = $item->order_booking_details;
				if($order_booking_details->count() > 0) {
					$error_msg = 'Some products are not deleted, because related orders exists.';
				} else {
					$item->images()->delete();
					$item->delete();	
				}
			}
		}
		
		if($error_msg)
			return redirect('admin/products')->with('error_message', $error_msg);
		
		return redirect('admin/products')->with('message', $msg);

	}

	public function add_cat() 
	{
		return view('panels.admin.online_shop.addCat');
	}

    // add cat
      public function catForm(Request $request) {
        //echo $request->cat_name;
        //return 'update query here';
        $pro_cat = new pro_cat;

        $pro_cat->name = $request->cat_name;
        $pro_cat->status = $request->status; // by defalt enable
        $pro_cat->save();

        $cats = DB::table('shop_pro_cat')->orderby('id', 'DESC')->get();

        return view('panels.admin.online_shop.categories', compact('cats'));
      }

    // edit form for cat
      public function CatEditForm(Request $request) {
        $catid = $request->id;
        $cats = DB::table('shop_pro_cat')->where('id', $catid)->get();
        return view('panels.admin.online_shop.CatEditForm', compact('cats'));
      }

    //update query to edit cat
      public function editCat(Request $request) {

        $catid = $request->id;
        $catName = $request->cat_name;
        $status = $request->status;
        DB::table('shop_pro_cat')->where('id', $catid)->update(['name' => $catName, 'status' => $status]);

        $cats = DB::table('shop_pro_cat')->orderby('id', 'DESC')->get();

        return view('panels.admin.online_shop.categories', compact('cats'));
      }

      public function view_cats() {

        $cats = DB::table('shop_pro_cat')->get();

        return view('panels.admin.online_shop.categories', compact('cats'));
      }

	public function ProductEditForm($id) 
	{
        //$pro_id = $reqeust->id;
        $product = Shopproducts::find($id);
        
		/*
		$listing=DB::table('listing')->get();
        $listing_val=array();
        foreach ($listing as $key => $value) {
          $cat_type=DB::table('category')->where('c_id',$value->m_c_id)->get();
          $types=json_decode($cat_type[0]->category_type);
          if(in_array($this->order_online_id, $types)){
            array_push($listing_val, $value);
          }
        }*/
		
		$cats = DB::table('shop_pro_cat')->get();
		
		$order_online_id = $this->order_online_id;
		
		$listing = Listing::whereHas('category', function($query) use($order_online_id) {
			$query->where('category_type', 'like', '%'.$order_online_id.'%');
		})->get();
		
        return view('panels.admin.online_shop.editPproducts', compact('product','listing', 'cats'));
      }

	public function editProduct(Request $request) 
	{
       
		$list_id=$request->list_id;

		$cat_id=$request->cat_id;
		$proid = $request->id;
		$pro_name = $request->pro_name;
		$cat_id = $request->cat_id;
		$pro_code = $request->pro_code;
		$pro_price = $request->pro_price;
		$pro_info = $request->pro_info;
		$spl_price = $request->spl_price;
		
		$photo = $request->file('pro_img'); //print_r($photo);
		if($photo)
		{
			$imagename = time() . '_' . rand(10,100) . '_' . strtolower($photo->getClientOriginalName());   
			
			// thumb img
			$destinationPath = public_path('upload/images/small');
			$thumb_img = Image::make($photo->getRealPath())->crop(120, 100);
			$thumb_img->save($destinationPath.'/'.$imagename,80);   

			// original img
			$destinationPath = public_path('upload/images/medium');
			$medium_img = Image::make($photo->getRealPath())->crop(500, 416);
			$medium_img->save($destinationPath.'/'.$imagename,80);
			
			$destinationPath = public_path('upload/images');
			$photo->move($destinationPath, $imagename);
			
			$Shopproducts = Shopproducts::find($proid);
			
			// delete file from server
			@unlink(public_path('upload/images/small') . '/' . $Shopproducts->pro_img);
			@unlink(public_path('upload/images/medium') . '/' . $Shopproducts->pro_img);
			@unlink(public_path('upload/images') . '/' . $Shopproducts->pro_img);
			
			$Shopproducts->pro_img = $imagename;
			$Shopproducts->save();
		}
		
		$listing = Listing::find($request->list_id);
		
		/*if($request->new_arrival =='NULL'){
			$new_arrival = '1';
		}else {
			$new_arrival = $request->new_arrival;
		}*/
		DB::table('shop_products')->where('id', $proid)->update([
		'listing_id'	=> $list_id,
		'merchant_id'	=> $listing->user_id,
		'cat_id'=> $cat_id,
		'pro_name' => $pro_name,
		'cat_id' => $cat_id,
		'pro_code' => $pro_code,
		'pro_price' => $pro_price,
		'pro_info' => $pro_info,
		'spl_price' => $spl_price,
		'stock'		=> $request->stock,
		'shipping_price' => $request->shipping_price
		]);

		return redirect('admin/products')->with('message','Product updated successfully.');
	}

      public function ImageEditForm($id) {
        $Products = DB::table('shop_products')->where('id', '=', $id)->get(); // now we are fetching all products
        return view('panels.admin.online_shop.ImageEditForm', compact('Products'));
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
        return redirect('/admin/products');
        //echo 'done';
        //  $Products = DB::table('products')->get(); // now we are fetching all products
        //  return view('admin.products', compact('Products'));
      }

    //for delete cat
      public function deleteCat($id) {

        //echo $id;
        DB::table('shop_pro_cat')->where('id', '=', $id)->delete();


        $cats = DB::table('shop_pro_cat')->get();

        return view('panels.admin.online_shop.categories', compact('cats'));
      }
      public function addPropertyAll(){
        return view('panels.admin.online_shop.addProperty');
      }
      public function sumbitProperty(Request $request){

        $properties = new Products_properties;
        $properties->pro_id = $request->pro_id;
        $properties->size = $request->size;
        $properties->color = $request->color;
        $properties->p_price = $request->p_price;
        $properties->save();

        return redirect('/admin/ProductEditForm/'.$request->pro_id);

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
      return view('panels.admin.online_shop.addAlt', compact('proInfo'));
    }

	public function submitAlt(Request $request)
	{		
		$proId = $request->pro_id;
		
		$photo = $request->file('image'); //print_r($photo);
		$imagename = time() . '_' . rand(10,100) . '_' . strtolower($photo->getClientOriginalName());   
		
		// thumb img
		$destinationPath = public_path('upload/images/small');
		$thumb_img = Image::make($photo->getRealPath())->crop(120, 100);
		$thumb_img->save($destinationPath.'/'.$imagename,80);   

		// original img
		$destinationPath = public_path('upload/images/medium');
		$medium_img = Image::make($photo->getRealPath())->crop(500, 416);
		$medium_img->save($destinationPath.'/'.$imagename,80);
		
		$destinationPath = public_path('upload/images');
		$photo->move($destinationPath, $imagename);		
		
		$add_lat = DB::table('shop_alt_images')
		->insert(['proId' => $proId, 'alt_img' => $imagename, 'status' =>0]);
		return back();		
	}
	
	public function delete_image($image_id)
	{
		$ShopProductImages = ShopProductImages::find($image_id);
		$product_id = $ShopProductImages->proId;
			
		// delete file from server
		@unlink(public_path('upload/images/small') . '/' . $ShopProductImages->alt_img);
		@unlink(public_path('upload/images/medium') . '/' . $ShopProductImages->alt_img);
		@unlink(public_path('upload/images') . '/' . $ShopProductImages->alt_img);
		
		$ShopProductImages->delete();
		
		return redirect('admin/addAlt/' . $product_id)->with('message', 'Image deleted successfully');
	}
	
    public function customer_orders()
    {
      $order_booking = OrderBooking::all();
      foreach ($order_booking as $key => $value) {
        $value->total=ShopOrderBookingDetail::where('order_id',$value->id)->sum('total_amount');
        $value->total_qty=ShopOrderBookingDetail::where('order_id',$value->id)->sum('quantity');
      }

      return view('panels.admin.online_shop.customer_orders', compact('order_booking'));
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

      return view('panels.admin.online_shop.customer_order_details', compact('order_booking', 'order_status'));
    }

    public function update_booking_satus($id, Request $request)
    {

      $order_booking = OrderBooking::find($id);
      $order_booking->status = $request->status;
      $order_booking->save();

      return redirect('admin/customer-orders/'.$id)->with('message', 'Status updated successfully');

    }

  }