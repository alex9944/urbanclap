<?php

namespace App\Http\Controllers;
use DB;
use Image;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Http\Requests;
use App\Models\AdsPosition;
use App\Http\Controllers\Controller;



class AdsPositionController extends Controller
{
    //
	public function index()
	{		
		$adsposition= AdsPosition::all();
		return view('panels.admin.ads.adsposition',['adsposition'=>$adsposition]);
	}
	
	public function getposition(Request $request)
	{
		$id=$request->id;  
		$adsposition = DB::table('ads_position')
		->where('id', $id)	 
		->first();   
		return '{"view_details": ' . json_encode($adsposition) . '}';
	}

	public function added(Request $request)
	{
		$this->validate($request,
			[
				'title'            	=> 'required',     
				//'type'          	=>'required',
				'ad_d_image'		=> 'required',
				'ad_m_image'		=> 'required',
			],
			[
				'title.required'   	=> 'Position Title is required',
				'type.required'		=> 'Type is required',
				'ad_d_image.required' => 'Image required',
				'ad_d_image.dimensions'=> 'Image size not Valid',
				'ad_m_image.required' => 'Image required',
				'ad_m_image.dimensions'=> 'Image size not Valid'

			]);
		$d_photo = $request->file('ad_d_image');
		$d_imagename = Str::slug($request->title) . '_ad_desktop.'.$d_photo->getClientOriginalExtension();   
		$destinationPath = public_path('/assets/images/Ad');		
		$d_photo->move($destinationPath, $d_imagename);

		$m_photo = $request->file('ad_m_image');
		$m_imagename = Str::slug($request->title) . '_ad_mobile.'.$m_photo->getClientOriginalExtension();   
		$destinationPath = public_path('/assets/images/Ad');
		$m_photo->move($destinationPath, $m_imagename);

		$adsposition = new AdsPosition;				
		$adsposition->title=$request->title;
		$adsposition->slug = Str::slug($request->title);
		//$adsposition->type=$request->type;	
		$adsposition->d_image=$d_imagename;
		$adsposition->m_image=$m_imagename;	
		$adsposition->save();
		return redirect('admin/ads/position')->with('message','Position added successfully');
	}
	public function updated(Request $request)
	{	 
		
		$this->validate($request,
			[
				'title'            	=> 'required',
				//'type'               =>'required',
				

			],
			[
				'title.required'   	=> 'Position Title is required',
				'type.required'		=> 'Type is required',
				
			]);
			
		
		$update_data = [
			'title' => $request->title,
			'slug' => Str::slug($request->title)
		];

		$d_photo = $request->file('edit_d_image');
		if($d_photo)
		{
			$d_imagename = $update_data['slug'] . '_ad_desktop.' . $d_photo->getClientOriginalExtension();
			$destinationPath = public_path('/assets/images/Ad');
			//$thumb_img = Image::make($d_photo->getRealPath())->crop(100, 100);
			//$thumb_img->save($destinationPath.'/'.$d_imagename,80); 
			$d_photo->move($destinationPath, $d_imagename);
			$update_data['d_image'] = $d_imagename;
		}

		$m_photo = $request->file('edit_m_image');
		if($m_photo)
		{
			$m_imagename = $update_data['slug'] . '_ad_mobile.'.$m_photo->getClientOriginalExtension();   
			$destinationPath = public_path('/assets/images/Ad');
			//$thumb_img = Image::make($m_photo->getRealPath())->crop(100, 100);
			//$thumb_img->save($destinationPath.'/'.$m_imagename,80); 
			$m_photo->move($destinationPath, $m_imagename);
			$update_data['m_image'] = $m_imagename;
		}
		DB::table('ads_position')
		->where('id', $request->id)
		->update($update_data);

		return redirect('admin/ads/position')->with('message','Position updated successfully');
	}


	public function deleted(Request $request)
	{	 
		$id=$request->id;  
		$ads_position = DB::table('ads_position')
		->where('id', $id)->first();		
		
		@unlink(public_path('/assets/images/Ad').'/'.($ads_position->d_image));
		@unlink(public_path('/assets/images/Ad').'/'.($ads_position->m_image));
		
		$ads_position->delete();
		
		$status['deletedid']=$id;
		$status['deletedtatus']='Position deleted successfully';
		return '{"delete_details": ' . json_encode($status) . '}'; 

	}

	public function destroy(Request $request)
	{
		$cn=count($request->selected_id);
		if($cn>0)
		{
			$data = $request->selected_id;			
			foreach($data as $id) {
				$ads_position = DB::table('ads_position')
				->where('id', $id)->first();		
				
				@unlink(public_path('/assets/images/Ad').'/'.($ads_position->d_image));
				@unlink(public_path('/assets/images/Ad').'/'.($ads_position->m_image));
				
				$ads_position->delete();		
			}			
		} 
		return redirect('admin/ads/position')->with('message','Seltected Position are deleted successfully');			

	}
}