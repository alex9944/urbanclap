<?php

namespace App\Http\Controllers;
use DB;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Models\CategorySlug;
use App\Http\Controllers\Controller;



class CategorySlugController extends Controller
{
    //
	public function index()
    {		
	   $categoryslug= CategorySlug::all();
       return view('panels.admin.category.slug',['categoryslug'=>$categoryslug]);
    }
	
	public function getcategoryslug(Request $request)
		{
			 $id=$request->id;  
			 $categoryslug = DB::table('category_slug')
			 ->where('id', $id)	 
			 ->first();   
		 return '{"view_details": ' . json_encode($categoryslug) . '}';
	}
		
	public function added(Request $request)
	{
		
		$this->validate($request,
			 [
                'slug'            	=> 'required|alpha_dash',               
               
            ],
            [
                'slug.required'   	=> 'Slug is required',
                'slug.alpha_dash'    	=> 'Slug must be used in hypen or underscore ',               
               
            ]);
			
			$this->validate($request,
			[
			'slug'=>'required|alpha_dash',			
			]);
			
				$categoryslug = new CategorySlug;				
				$categoryslug->slug=$request->slug;				
				$categoryslug->save();
			return redirect('admin/category/slug')->with('message','Category Slug added successfully');
	}
	public function updated(Request $request)
	{	 
			
			$this->validate($request,
			 [
                'slug'            	=> 'required',            
               
            ],
            [
                'slug.required'   	=> 'Slug is required',
              
               
            ]);
			
				
				DB::table('category_slug')
				->where('id', $request->id)
				->update(['slug' => $request->slug,]);

	return redirect('admin/category/slug')->with('message','Category Slug updated successfully');
	}


	public function deleted(Request $request)
	{	 
	     $id=$request->id;  
		 $slug = DB::table('category_slug')
		 ->where('id', $id)
		 ->delete();
		 $status['deletedid']=$id;
		 $status['deletedtatus']='Category Slug deleted successfully';
	 return '{"delete_details": ' . json_encode($status) . '}'; 
	
	}

	public function destroy(Request $request)
		{
			$cn=count($request->selected_id);
			if($cn>0)
			{
			$data = $request->selected_id;			
				foreach($data as $id) {
				DB::table('category_slug')->where('id', $id)->delete();			
				}			
			} 
	return redirect('admin/category/slug')->with('message','Seltected Category Slug are deleted successfully');			

	}
}