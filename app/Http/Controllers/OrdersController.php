<?php

namespace App\Http\Controllers;
use DB;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Orders;
use App\Http\Controllers\Controller;
use Image;


class OrdersController extends Controller
{
    //
	public function index()
    {		
	   $orders= Orders::all();
       return view('panels.admin.orders.index',['orders'=>$orders]);
    }
	
	public function getorders(Request $request)
		{
			 $id=$request->id;  			
			$order_product = DB::table('order_product')
			->select('*', 'order_product.id as uid')
			->leftJoin('products', 'products.id', '=', 'order_product.product_id')
			->leftJoin('users', 'users.id', '=', 'order_product.merchant_id')			
			->where('order_product.product_id', $id)
			->get();
			
		 return '{"view_details": ' . json_encode($order_product) . '}';
	}
	
		
	public function getitem(Request $request)
		{
			 $id=$request->id;  			
			$order_product = DB::table('order_product')
			->select('*', 'order_product.id as uid')
			->leftJoin('products', 'products.id', '=', 'order_product.product_id')
			->leftJoin('users', 'users.id', '=', 'order_product.merchant_id')			
			->where('order_product.id', $id)
			->first();
			
		 return '{"view_details": ' . json_encode($order_product) . '}';
	}
	
	
	public function vieworder(Request $request)
		{
			 $id=$request->id;  			
			$orders = DB::table('orders')			
			->where('id', $id)
			->first();
			
		 return '{"view_details": ' . json_encode($orders) . '}';
	}
		
	public function added(Request $request)
	{
		  $this->validate($request,
			 [
                'b_title'          	=> 'required',
                'b_slug'            => 'required|alpha_dash',
                'photo'             => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:1024',
                'b_description' 	=> 'required',
				
               
            ],
            [
              
                'b_title.required'    		=> 'Blog Title is required',
                'b_slug.required'        	=> 'Blog Url is required',
                'b_slug.alpha_dash'         => 'Blog Url must be used in hypen or underscore ',
                'photo.required'        	=> 'Blog Image is required',
                'photo.image'           	=> 'Blog Image should be a jpeg,png,gif,svg',                
				'b_description.required'    => 'Blog Description is required',			
               
            ]);
			
				$photo = $request->file('photo');
				$imagename = time().'.'.$photo->getClientOriginalExtension();   
				$destinationPath = public_path('/uploads/thumbnail');
				$thumb_img = Image::make($photo->getRealPath())->resize(100, 100);
				$thumb_img->save($destinationPath.'/'.$imagename,80);                    
				$destinationPath = public_path('/uploads/original');
				$photo->move($destinationPath, $imagename);
			
				$blog = new Blog;
				$blog->b_title=$request->b_title;
				$blog->b_slug=$request->b_slug;
				$blog->b_image=$imagename;
				$blog->b_description=$request->b_description;
				$blog->b_meta_tag=$request->b_meta_tag;
				$blog->b_meta_description=$request->b_meta_description;
				$blog->save();
			return redirect('admin/blog')->with('message','Blog added successfully');
	}
	public function updated(Request $request)
	{	 
 $this->validate($request,
			 [
                'b_title'          	=> 'required',
                'b_slug'            => 'required|alpha_dash',              
                'b_description' 	=> 'required',
				
               
            ],
            [
              
                'b_title.required'    		=> 'Blog Title is required',
                'b_slug.required'        	=> 'Blog Url is required',
                'b_slug.alpha_dash'         => 'Blog Url must be used in hypen or underscore ',                          
				'b_description.required'    => 'Blog Description is required',			
               
            ]);
			$photo = $request->file('photo');
			 if($photo){
					$imagename = time().'.'.$photo->getClientOriginalExtension();   
					$destinationPath = public_path('/uploads/thumbnail');
					$thumb_img = Image::make($photo->getRealPath())->resize(100, 100);
					$thumb_img->save($destinationPath.'/'.$imagename,80);                    
					$destinationPath = public_path('/uploads/original');
					$photo->move($destinationPath, $imagename);
						  DB::table('blogs')
						->where('b_id', $request->id)
						->update(['b_image' => $imagename,]);
			 }
			
				DB::table('blogs')
				->where('b_id', $request->id)
				->update(['b_title' => $request->b_title,'b_slug' => $request->b_slug,'b_description' => $request->b_description,'b_meta_tag' => $request->b_meta_tag,'b_meta_description' => $request->b_meta_description,]);

	return redirect('admin/blog')->with('message','Blog updated successfully');
	}


	public function deleted(Request $request)
	{	 
	$id=$request->id;  
		 $blogs = DB::table('orders')
		 ->where('id', $id)
		 ->delete();
		 $status['deletedid']=$id;
		 $status['deletedtatus']='Orders deleted successfully';
	 return '{"delete_details": ' . json_encode($status) . '}'; 
	
	}

	public function destroy(Request $request)
		{
			echo $cn=count($request->selected_id);
			if($cn>0)
			{
			$data = $request->selected_id;			
				foreach($data as $id) {
				DB::table('blogs')->where('b_id', $id)->delete();			
				}			
			} 
	return redirect('admin/blog')->with('message','Seltected Blogs are deleted successfully');			

	}
}