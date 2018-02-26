<?php

namespace App\Http\Controllers;
use DB;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Models\Menu;
use App\Http\Controllers\Controller;
use Image;


class MenuController extends Controller
{
    //
	public function index()
    {		
		$menu = DB::table('menu')
				 ->where('parent_id', 0)
				 ->orderby('order_by', 'asc')		 
				->get();
				// ->toSql();
				// dd($menu);
		$submenu = DB::table('menu') 
				 ->get();
		$editsubmenu = DB::table('menu') 
				 ->get();
		$menutype = DB::table('menu_type') 
				 ->get();
		$editmenutype = DB::table('menu_type') 
				 ->get();
    

	
       return view('panels.admin.menu.index',['menu'=>$menu,'submenu'=>$submenu,'editsubmenu'=>$editsubmenu,'menutype'=>$menutype,'editmenutype'=>$editmenutype]);
    }
	
	public function getmenu(Request $request)
		{
		$id=$request->id;  
		$menu = DB::table('menu')
				->where('id', $id)	 
				->first();   
		 return '{"view_details": ' . json_encode($menu) . '}';
	}
	
	public static function get_child_menu($id)
	{			
			 $menus = DB::table('menu')
			 ->where('parent_id', $id)	 
			 ->get(); 
			 
			$tit='';			 
				 foreach($menus as $menu)
				 {			 
						$tit .= '<hr style="margin-top: 10px;margin-bottom: 10px;"><table style="width:100%"><tr class="rm'.$menu->id.'"><td><input type="checkbox" name="selected_id[]" class="checkbox" value="'.$menu->id.'"/></td><td>'.$menu->title.'</td><td ><a href="javascript:void(0);" class="edit_menu btn btn-info btn-xs" id="'.$menu->id.'" ><i class="fa fa-pencil"></i> Edit </a><a href="javascript:void(0);" class="btn btn-danger btn-xs delete_menu" id="'.$menu->id.'"><i class="fa fa-trash-o"></i> Delete </a></td></tr></table>'; 	 
				 }
			echo $tit;
	}
		
	public function added(Request $request)
	{
		 
			$this->validate($request,
			 [
                'm_t_id'            	=> 'required',
                'title'             	=> 'required',
                'slug'                  => 'required|alpha_dash',
                'parent_id'             => 'required',
                'dynamic_page_type' 	=> 'required',
				'order_by' 				=> 'required',
               
            ],
            [
                'm_t_id.required'   	=> 'Menu Type is required',
                'title.required'    	=> 'Menu Title is required',
                'slug.required'        => 'Menu Url is required',
                'slug.alpha_dash'           => 'Menu Url must be used in hypen or underscore ',
                'parent_id.required'     => 'Sub Menu is required',               
				'dynamic_page_type.required'    => 'Page Type is required',
				'order_by.required'    => 'Menu Order is required',
               
            ]);		
	
						
				
				$menu = new Menu;
				$menu->title=$request->title;
				$menu->slug=$request->slug;
				$menu->m_t_id=implode(',',$request->m_t_id);				
				$menu->parent_id=$request->parent_id;				
				$menu->order_by=$request->order_by;	
					// dynamic page type		
                    $dynamic_page_type_selected	=$request->dynamic_page_type;				
					if($request->dynamic_page_type != 'other') {	
					
						$menu->$dynamic_page_type_selected = 1;						
					}	
				$menu->save();
	
			return redirect('admin/menu')->with('message','Menu added successfully');
	}
	public function updated(Request $request)
	{	 
	
	
		$this->validate($request,
			 [
                'm_t_id'            	=> 'required',
                'title'             	=> 'required',
                'slug'                  => 'required|alpha_dash',
                'parent_id'             => 'required',
                'dynamic_page_type' 	=> 'required',
				'order_by' 				=> 'required',
               
            ],
            [
                'm_t_id.required'   	=> 'Menu Type is required',
                'title.required'    	=> 'Menu Title is required',
                'slug.required'        => 'Menu Url is required',
                'slug.alpha_dash'           => 'Menu Url must be used in hypen or underscore ',
                'parent_id.required'     => 'Sub Menu is required',               
				'dynamic_page_type.required'    => 'Page Type is required',
				'order_by.required'    => 'Menu Order is required',
               
            ]);
		
			
			$menu = new Menu;
			$menu->title=$request->title;
			$menu->slug=$request->slug;
			$menu->m_t_id=implode(',',$request->m_t_id);				
			$menu->parent_id=$request->parent_id;				
			$menu->order_by=$request->order_by;	
			// dynamic page type		
			$dynamic_page_type_selected	=$request->dynamic_page_type;				
			if($request->dynamic_page_type != 'other') {	
				
				$menu->$dynamic_page_type_selected = 1;	
				DB::table('menu')
				->where('id', $request->id)
				->update([$dynamic_page_type_selected => 1]);						
			}
			DB::table('menu')
			->where('id', $request->id)
			->update(['title' => $request->title,'slug' => $request->slug,'m_t_id' => implode(',',$request->m_t_id),'parent_id' => $request->parent_id,'order_by' => $request->order_by]);

		return redirect('admin/menu')->with('message','Menu updated successfully');
	}


	public function deleted(Request $request)
	{	 
	$id=$request->id;  
		 $menu = DB::table('menu')
		 ->where('id', $id)
		 ->delete();
		 $status['deletedid']=$id;
		 $status['deletedtatus']='Menu deleted successfully';
	 return '{"delete_details": ' . json_encode($status) . '}'; 
	
	}

	public function destroy(Request $request)
		{
			echo $cn=count($request->selected_id);
			if($cn>0)
			{
			$data = $request->selected_id;			
				foreach($data as $id) {
				DB::table('menu')->where('id', $id)->delete();			
				}			
			} 
	return redirect('admin/menu')->with('message','Seltected Menu are deleted successfully');			

	}
}