<?php

namespace App\Http\Controllers;
use DB;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Models\Page;
use App\Models\Menu;
use App\Http\Controllers\Controller;
use Image;


class PageController extends Controller
{
    // front end
	public function cms($slug)
	{
		$menu = Menu::where('slug', $slug)->first();
		
		return view('pages.cms', compact('menu'));
	}
	
	//
	public function index()
    {		
		$page= Page::all();	 
		$submenu = Menu::doesntHave('page')->get();
		$editsubmenu = Menu::all();	   
		return view('panels.admin.page.index', compact('page','submenu','editsubmenu'));
    }
	
	public function getpage(Request $request)
		{
			 $id=$request->id;  
			 $page = DB::table('pages')
			 ->where('id', $id)	 
			 ->first();   
		 return '{"view_details": ' . json_encode($page) . '}';
	}
		
	public function added(Request $request)
	{
		  $this->validate($request,
			 [
                'title'          	=> 'required',
                'menu_id'            => 'required',               
                'description' 	=> 'required',
				
               
            ],
            [
              
                'title.required'    		=> 'Title is required',
                'menu_id.required'        	=> 'Menu is required',                          
				'description.required'    => 'Description is required',			
               
            ]);
			
			
			   	$page = new Page;
				$page->title=$request->title;
				$page->menu_id=$request->menu_id;				
				$page->description=$request->description;
				$page->meta_tag=$request->meta_tag;
				$page->meta_description=$request->meta_description;
				$page->save();
			return redirect('admin/page')->with('message','Page added successfully');
	}
	public function updated(Request $request)
	{	 
	$this->validate($request,
			 [
                'title'          	=> 'required',
                'menu_id'            => 'required',               
                'description' 	=> 'required',
				
               
            ],
            [
              
                'title.required'    		=> 'Title is required',
                'menu_id.required'        	=> 'Menu is required',                          
				'description.required'    => 'Description is required',			
               
            ]);	
			
			   DB::table('pages')
				->where('id', $request->id)
				->update(['title' => $request->title,'menu_id' => $request->menu_id,'description' => $request->description,'meta_tag' => $request->meta_tag,'meta_description' => $request->meta_description,]);

	return redirect('admin/page')->with('message','Page updated successfully');
	}


	public function deleted(Request $request)
	{	 
	$id=$request->id;  
		 $page = DB::table('pages')
		 ->where('id', $id)
		 ->delete();
		 $status['deletedid']=$id;
		 $status['deletedtatus']='Page deleted successfully';
	 return '{"delete_details": ' . json_encode($status) . '}'; 
	
	}

	public function destroy(Request $request)
		{
			$cn=count($request->selected_id);
			if($cn>0)
			{
			$data = $request->selected_id;			
				foreach($data as $id) {
				DB::table('pages')->where('id', $id)->delete();			
				}			
			} 
	return redirect('admin/page')->with('message','Seltected pages are deleted successfully');			

	}
}