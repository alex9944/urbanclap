<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\FoodMenu;
use App\Models\FoodMenuMerchant;
use Response;
use Validator;
use DB;
use Auth;
use Image;
use Mail;


class FoodMenuController extends Controller
{
	
	public function index()
	{	
		$food_menus = FoodMenu::all();

		return view('panels.admin.food-menu.index', compact('food_menus'));
	}
	
	public function add(Request $request)
	{		
		$this->FoodMenu = new FoodMenu;
		$rules = $this->FoodMenu->get_adding_rules();
		$this->validate($request, $rules['rules'], $rules['messages']);
		
		$user_id = (auth()->check()) ? auth()->user()->id : null;
		
		// add
		$image_name = '';
		$photo = $request->file('image_name');
		if($photo)
		{
			$image_name = time() . '_' . rand(10,100) . '_' . strtolower($photo->getClientOriginalName());  
			// thumb img
			$destinationPath = public_path('uploads/food_menu/thumbnail');
			$thumb_img = Image::make($photo->getRealPath())->fit(100);
			$thumb_img->save($destinationPath.'/'.$image_name,80);   

			// original img
			$destinationPath = public_path('uploads/food_menu/original');
			$photo->move($destinationPath, $image_name);
		}
		
		$this->FoodMenu->name = $request->name;
		$this->FoodMenu->image_name = $image_name;
		$this->FoodMenu->added_by = 'admin';
		$this->FoodMenu->status = $request->status;
		$this->FoodMenu->save();

		return redirect('admin/food-menu')->with('message', 'Menu added successfully');
	}
	
	public function edit($id)
	{		

		$food_menu = FoodMenu::find($id);
		return Response::json(array(
			'view_details'   => $food_menu
			));
	}
	
	public function update(Request $request)
	{	
		$this->FoodMenu = new FoodMenu;
		$rules = $this->FoodMenu->get_adding_rules();
		$this->validate($request, $rules['rules'], $rules['messages']);
		
		$food_menu = FoodMenu::find($request->id);

		// update
		$image_name = '';
		$photo = $request->file('image_name');
		if($photo)
		{
			$image_name = time() . '_' . rand(10,100) . '_' . strtolower($photo->getClientOriginalName());  
			// thumb img
			$destinationPath = public_path('uploads/food_menu/thumbnail');
			$thumb_img = Image::make($photo->getRealPath())->fit(100);
			$thumb_img->save($destinationPath.'/'.$image_name,80);   

			// original img
			$destinationPath = public_path('uploads/food_menu/original');
			$photo->move($destinationPath, $image_name);
			
			$food_menu->image_name = $image_name;
		}
		
		$food_menu->name = $request->name;
		$food_menu->status = $request->status;
		$food_menu->save();
		
		return redirect('admin/food-menu')->with('message', 'Menu updated successfully');
	}
	
	public function destroy_all(Request $request)
	{
		$cn = count($request->selected_id);
		if($cn > 0)
		{
			FoodMenu::destroy($request->selected_id);	
		}
		
		return redirect('admin/food-menu')->with('message','Seltected rows are deleted successfully');
	}
	
	public function destroy($id)
	{
		$settings = FoodMenu::find($id);
		
		if($settings)
		{

			$settings->delete();
			
			\Session::flash('message', 'Selected row deleted successfully.');
			
			return Response::json(array(
				'success' => true,
				'msg'   => 'Seltected row deleted successfully.'
				));
		}
		
		return Response::json(array(
			'success' => false,
			'msg'   => 'Invalid Id'
			));
	}	
}