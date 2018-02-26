<?php

namespace App\Http\Controllers;
use DB;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Models\News;
use App\Http\Controllers\Controller;
use Image;


class NewsController extends Controller
{
    //
	public function index()
    {		
	   $news= News::all();
       return view('panels.admin.news.index',['news'=>$news]);
    }
	
	public function getnews(Request $request)
		{
			 $id=$request->id;  
			 $news = DB::table('news')
			 ->where('n_id', $id)	 
			 ->first();   
		 return '{"view_details": ' . json_encode($news) . '}';
	}
		
	public function added(Request $request)
	{
		 $this->validate($request,
			 [
                'n_title'          	=> 'required',
                'n_slug'            => 'required|alpha_dash',
                'photo'             => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:1024',
                'n_description' 	=> 'required',
				
               
            ],
            [
              
                'n_title.required'    		=> 'News Title is required',
                'n_slug.required'        	=> 'News Url is required',
                'n_slug.alpha_dash'         => 'News Url must be used in hypen or underscore ',
                'photo.required'        	=> 'News Image is required',
                'photo.image'           	=> 'News Image should be a jpeg,png,gif,svg',                
				'n_description.required'    => 'News Description is required',			
               
            ]);
		
				$photo = $request->file('photo');
				$imagename = time().'.'.$photo->getClientOriginalExtension();   
				$destinationPath = public_path('/uploads/thumbnail');
				$thumb_img = Image::make($photo->getRealPath())->resize(100, 100);
				$thumb_img->save($destinationPath.'/'.$imagename,80);                    
				$destinationPath = public_path('/uploads/original');
				$photo->move($destinationPath, $imagename);
			
				$news = new News;
				$news->n_title=$request->n_title;
				$news->n_slug=$request->n_slug;
				$news->n_image=$imagename;
				$news->n_description=$request->n_description;
				$news->n_meta_tag=$request->n_meta_tag;
				$news->n_meta_description=$request->n_meta_description;
				$news->save();
			return redirect('admin/news')->with('message','News added successfully');
	}
	public function updated(Request $request)
	{	 $this->validate($request,
			 [
                'n_title'          	=> 'required',
                'n_slug'            => 'required|alpha_dash',             
                'n_description' 	=> 'required',
				
               
            ],
            [
              
                'n_title.required'    		=> 'News Title is required',
                'n_slug.required'        	=> 'News Url is required',
                'n_slug.alpha_dash'         => 'News Url must be used in hypen or underscore ',                      
				'n_description.required'    => 'News Description is required',			
               
            ]);
					
			$photo = $request->file('photo');
			 if($photo){
					$imagename = time().'.'.$photo->getClientOriginalExtension();   
					$destinationPath = public_path('/uploads/thumbnail');
					$thumb_img = Image::make($photo->getRealPath())->resize(100, 100);
					$thumb_img->save($destinationPath.'/'.$imagename,80);                    
					$destinationPath = public_path('/uploads/original');
					$photo->move($destinationPath, $imagename);
						  DB::table('news')
						->where('n_id', $request->id)
						->update(['n_image' => $imagename,]);
			 }
			
				DB::table('news')
				->where('n_id', $request->id)
				->update(['n_title' => $request->n_title,'n_slug' => $request->n_slug,'n_description' => $request->n_description,'n_meta_tag' => $request->n_meta_tag,'n_meta_description' => $request->n_meta_description,]);

	return redirect('admin/news')->with('message','News updated successfully');
	}


	public function deleted(Request $request)
	{	 
	$id=$request->id;  
		 $newss = DB::table('news')
		 ->where('n_id', $id)
		 ->delete();
		 $status['deletedid']=$id;
		 $status['deletedtatus']='News deleted successfully';
	 return '{"delete_details": ' . json_encode($status) . '}'; 
	
	}

	public function destroy(Request $request)
		{
			echo $cn=count($request->selected_id);
			if($cn>0)
			{
			$data = $request->selected_id;			
				foreach($data as $id) {
				DB::table('newss')->where('b_id', $id)->delete();			
				}			
			} 
	return redirect('admin/news')->with('message','Seltected newss are deleted successfully');			

	}
}