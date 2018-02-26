<?php

namespace App\Http\Controllers;
use DB;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Models\Testimonials;
use App\Http\Controllers\Controller;
use Image;


class TestimonialsController extends Controller
{
    //
	public function index()
    {		
	   $testimonials= Testimonials::all();
       return view('panels.admin.testimonials.index',['testimonials'=>$testimonials]);
    }
	
	public function gettestimonials(Request $request)
		{
			 $id=$request->id;  
			 $testimonials = DB::table('testimonials')
			 ->where('t_id', $id)	 
			 ->first();  
 return '{"view_details": ' . json_encode($testimonials) . '}';
	}
		
	public function added(Request $request)
	{
		
		 $this->validate($request,
			 [
                't_title'          	=> 'required',              
                'photo'             => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:1024',
                't_description' 	=> 'required',
				
               
            ],
            [
              
                't_title.required'    		=> 'Testimonials Title is required',               
                'photo.required'        	=> 'Testimonials Image is required',
                'photo.image'           	=> 'Testimonials Image should be a jpeg,png,gif,svg',                
				't_description.required'    => 'Testimonials Description is required',			
               
            ]);
			
				$photo = $request->file('photo');
				$imagename = time().'.'.$photo->getClientOriginalExtension();   
				$destinationPath = public_path('/uploads/thumbnail');
				$thumb_img = Image::make($photo->getRealPath())->resize(100, 100);
				$thumb_img->save($destinationPath.'/'.$imagename,80);                    
				$destinationPath = public_path('/uploads/original');
				$photo->move($destinationPath, $imagename);
			
				$testimonials = new testimonials;
				$testimonials->t_title=$request->t_title;				
				$testimonials->t_image=$imagename;
				$testimonials->t_description=$request->t_description;				
				$testimonials->save();
			return redirect('admin/testimonials')->with('message','Testimonials added successfully');
	}
	public function updated(Request $request)
	{	 
			 $this->validate($request,
			 [
                't_title'          	=> 'required',
                't_description' 	=> 'required',
				
               
            ],
            [
              
                't_title.required'    		=> 'Testimonials Title is required',
				't_description.required'    => 'Testimonials Description is required',			
               
            ]);
			
			$photo = $request->file('photo');
			 if($photo){
					$imagename = time().'.'.$photo->getClientOriginalExtension();   
					$destinationPath = public_path('/uploads/thumbnail');
					$thumb_img = Image::make($photo->getRealPath())->resize(100, 100);
					$thumb_img->save($destinationPath.'/'.$imagename,80);                    
					$destinationPath = public_path('/uploads/original');
					$photo->move($destinationPath, $imagename);
						  DB::table('testimonials')
						->where('t_id', $request->id)
						->update(['t_image' => $imagename,]);
			 }
			
				DB::table('testimonials')
				->where('t_id', $request->id)
				->update(['t_title' => $request->t_title,'t_description' => $request->t_description,]);

	return redirect('admin/testimonials')->with('message','Testimonials updated successfully');
	}


	public function deleted(Request $request)
	{	 
	$id=$request->id;  
		 $testimonialss = DB::table('testimonials')
		 ->where('t_id', $id)
		 ->delete();
		 $status['deletedid']=$id;
		 $status['deletedtatus']='testimonials deleted successfully';
	 return '{"delete_details": ' . jsot_encode($status) . '}'; 
	
	}

	public function destroy(Request $request)
		{
			echo $cn=count($request->selected_id);
			if($cn>0)
			{
			$data = $request->selected_id;			
				foreach($data as $id) {
				DB::table('testimonialss')->where('b_id', $id)->delete();			
				}			
			} 
	return redirect('admin/testimonials')->with('message','Seltected testimonialss are deleted successfully');			

	}
}