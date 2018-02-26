<?php

namespace App\Http\Controllers;
use DB;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Service;
use App\Models\Category;

use Image;
use Validator;


class ServiceController extends Controller
{
    //
	public function index()
    {		
	  	$services = Service::all();
		$categories = Category::all();
	  
       	return view('panels.admin.services.index', compact('services', 'categories'));
    }
	
	public function get(Request $request)
	{
		$service = Service::find($request->id);   
		
		return '{"view_details": ' . json_encode($service) . '}';
	}
	
	public function added(Request $request)
	{
		 
		$this->validate($request,
		[		
			'category_id'            	=> 'required',
			'name'            	=> 'required',			
		],
		[		
			'category_id.required'   	=> 'Seect category',                         
			'name.required'   	=> 'Service name is required',
		]);
		 
		$Service = new Service;
		$Service->category_id = $request->category_id;
		$Service->name = $request->name;
		$Service->slug = str_slug($request->name, '-');
		$Service->page_title = $request->page_title;
		$Service->meta_keywords = $request->meta_keywords;
		$Service->meta_description = $request->meta_description;
		$Service->status = $request->status;
		$Service->save();
		
		return redirect('admin/services')->with('message','Service added successfully');
	}
	
	public function updated(Request $request)
	{	 
		$this->validate($request,
		[		
			'id'            	=> 'required',
			'category_id'            	=> 'required',
			'name'            	=> 'required',			
		],
		[		
			'id.required'   	=> 'Service id is required',
			'category_id.required'   	=> 'Seect category',                         
			'name.required'   	=> 'Service name is required',
		]);
		
		$Service = Service::find($request->id);
		
		if($Service)
		{
			$Service->category_id = $request->category_id;
			$Service->name = $request->name;
			$Service->slug = str_slug($request->name, '-');
			$Service->page_title = $request->page_title;
			$Service->meta_keywords = $request->meta_keywords;
			$Service->meta_description = $request->meta_description;
			$Service->status = $request->status;
			$Service->save();
			
			return redirect('admin/services')->with('message','Service updated successfully');
		}
		else
		{
			return redirect('admin/services')->with('error_message','Invalid Service');
		}		
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