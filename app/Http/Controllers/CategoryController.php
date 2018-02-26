<?php

namespace App\Http\Controllers;
use DB;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Models\Category;
use App\Models\CategorySlug;
use App\Models\MultiLanguage;
use App\Models\CategoryType;
use App\Models\CategoryTags;
use App\Http\Controllers\Controller;
use Image;
use Validator;


class CategoryController extends Controller
{
    //
	public function index()
    {		
	  	$category= Category::all();
	  
       	return view('panels.admin.category.index',['category'=>$category,'editpcategory'=>$category,'pcategory'=>$category]);
    }
	
	public function getcategory(Request $request)
		{
			 $id=$request->id;  
			 $category = DB::table('category')
			 ->where('c_id', $id)	 
			 ->first();   
		 return '{"view_details": ' . json_encode($category) . '}';
	}
	public static function get_child_category($id)
	{			
			 $category = DB::table('category')
			 ->where('parent_id', $id)	 
			 ->get(); 
			 
			$tit='';			 
				 foreach($category as $category)
				 {			 
						$tit .= '<hr style="margin-top: 10px;margin-bottom: 10px;"><table style="width:100%"><tr class="rm'.$category->c_id.'"><td><input type="checkbox" name="selected_id[]" class="checkbox" value="'.$category->c_id.'"/></td><td>'.$category->c_title.'</td><td ><a href="javascript:void(0);" class="edit_blog btn btn-info btn-xs" id="'.$category->c_id.'" ><i class="fa fa-pencil"></i></a> <a href="javascript:void(0);" class="edit_tags btn btn-default btn-xs" id="'.$category->c_id.'" ><i class="fa fa-tags"></i></a> <a href="javascript:void(0);" class="btn btn-danger btn-xs delete_blog" id="'.$category->c_id.'"><i class="fa fa-trash-o"></i></a></td></tr></table>'; 	 
				 }
			echo $tit;
	}	
	public function added(Request $request)
	{
		 
			$this->validate($request,
			 [
			 
                'c_title'            	=> 'required',
                             
               
            ],
            [
			
                'c_title.required'   	=> 'Title is required',                         
               
            ]);
			
			/*  
			'photo'             	=> 'required|image|mimes:jpeg,png,jpg,gif,svg|max:1024',  
			'photo.required'        => 'Category image is required',
                'photo.image'           => 'Category image should be a jpeg,png,gif,svg',   
				*/
			
		$photo = $request->file('photo');
			 if($photo){
			
				$imagename = time().'.'.$photo->getClientOriginalExtension();   
				$destinationPath = public_path('/uploads/thumbnail');
				$thumb_img = Image::make($photo->getRealPath())->resize(100, 100);
				$thumb_img->save($destinationPath.'/'.$imagename,80);                    
				$destinationPath = public_path('/uploads/original');
				$photo->move($destinationPath, $imagename);
			 }else{$imagename='';}
			$category_type = '';
			 
			$category = new Category;
			$category->c_title=$request->c_title;
			$category->category_type = $category_type;				
			$category->c_l_id=0;
			$category->parent_id=0;				
			//$category->c_slug_id=$request->slug;
			$category->slug=str_slug($request->c_title, '-');
			$category->c_image=$imagename;
			$category->c_meta_tag=$request->c_meta_tag;
			$category->c_meta_description=$request->c_meta_description;
			$category->save();
			return redirect('admin/category')->with('message','Category added successfully');
	}
	public function updated(Request $request)
	{	 
			$this->validate($request,
			 [
                'c_title'            	=> 'required',                           
               
            ],
            [
                'c_title.required'   	=> 'Title is required',
               
            ]);
			
			$photo = $request->file('photo');
			 if($photo){
					$imagename = time().'.'.$photo->getClientOriginalExtension();   
					$destinationPath = public_path('/uploads/thumbnail');
					$thumb_img = Image::make($photo->getRealPath())->resize(100, 100);
					$thumb_img->save($destinationPath.'/'.$imagename,80);                    
					$destinationPath = public_path('/uploads/original');
					$photo->move($destinationPath, $imagename);
						  DB::table('category')
						->where('c_id', $request->id)
						->update(['c_image' => $imagename,]);
			 }
			
			DB::table('category')
			->where('c_id', $request->id)
			->update(['c_title' => $request->c_title,'slug' => str_slug($request->c_title, '-'),'c_meta_tag' => $request->c_meta_tag,'c_meta_description' => $request->c_meta_description,]);

	return redirect('admin/category')->with('message','Category updated successfully');
	}


	public function deleted(Request $request)
	{	 
		$id=$request->id;  
		$success_msg = 'Seltected row deleted successfully.';
		
		return $this->_delete($id, $success_msg); 
	
	}
	
	private function _delete($id, $success_msg, $is_continue = false)
	{
		$category = Category::with('subcategories', 'category_listings')->find($id);		
		$subcategories = $category->subcategories;
		$category_listings = $category->category_listings;
		if(!$subcategories->isEmpty()) {
			foreach($subcategories as $subcategory)
			{
				$subcategory_listings = Listing::where('s_c_id', $subcategory->c_id)->exists();
				break;//print_r($subcategory_listings);
			}
		}//print_r($category_listings);exit;
		
		$error_msg = '';
		
		if(!$subcategories->isEmpty()) {			
			if(!$is_continue)
			{
				return \Response::json(array(
					'success' => false,
					'msg'   => 'Remove all subcategories before delete parent category.'
					));
			}
			else
			{
				$error_msg = 'Some categories not deleted, because related subcategories exists.';
			}
		}
		if(isset($subcategory_listings) and $subcategory_listings) {				
			if(!$is_continue)
			{		
				return \Response::json(array(
					'success' => false,
					'msg'   => 'Remove all listings before delete the subcategory.'
					));
			}
			else
			{
				$error_msg = 'Some categories not deleted, because related listings exists.';
			}
		}
		if(!$category_listings->isEmpty()) {				
			if(!$is_continue)
			{		
				return \Response::json(array(
					'success' => false,
					'msg'   => 'Remove all listings before delete the category.'
					));
			}
			else
			{
				$error_msg = 'Some categories not deleted, because related listings exists.';
			}
		}
		
		if(!$is_continue)
		{
			$category->delete();
			
			\Session::flash('message', $success_msg);
				
			return \Response::json(array(
				'success' => true,
				'msg'   => $success_msg
				));
		}
		else if(!$error_msg)
		{
			$category->delete();
		}
		
		return $error_msg;
	}

	public function destroy(Request $request)
	{
		$msg = 'Seltected category are deleted successfully';
		$error_msg = '';
		
		$cn=count($request->selected_id);
		if($cn>0)
		{
			$data = $request->selected_id;			
			foreach($data as $id) {
				
				$return_msg = $this->_delete($id, $msg, true);
				
				if($return_msg)
					$error_msg = $return_msg;
			}			
		} 
		
		if($error_msg)
			return redirect('admin/category')->with('error_message', $error_msg);
		
		return redirect('admin/category')->with('message', $msg);			

	}
}