<?php

namespace App\Http\Controllers\Merchant;

use DB;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Listing;
use App\Models\User;
use App\Models\MultiLanguage;
use App\Models\Country;
use App\Models\States;
use App\Models\Cities;
use App\Models\Category;
use App\Models\CategorySlug;
use App\Models\CategoryTags;
use App\Models\ListingImages;
use App\Models\MerchantServices;
use App\Models\TableBookingSettings;
use App\Models\ListingTags;
use App\Models\ShopProductImages;
use App\Models\GalleryImages;
use App\Http\Controllers\Controller;
use Image;
use Session;


class ApiGalleryController extends Controller
{

	

	public function imageUpload(Request $request)
	{	

$rules = [
		'user_id'	=> 'required|numeric',
		 'file'	=> 'required|image|mimes:jpeg,png,jpg,gif|max:2048',			
		];
		
		$messages = [
			'user_id.required'	=> 'User id is required',
			'file.required'	=> 'Image is required',
			'file.image'=> 'Invalid file type',
		];
		
			$validator = \Validator::make($request->all(), $rules, $messages);
				
				 if ($validator->fails()) {
					$return['data'] = $validator->errors()->toArray();
					$return['msg'] = 'Validation Issue';
					return response()->json($return);
				}
		
		$user_id = (auth()->check()) ? auth()->user()->id : null;
		$listing=Listing::where('user_id',$user_id)->first();
		$listing_id=$listing->id;		
			if($listing_id)
			{		
				$photo = $request->file('file'); //print_r($photo);
				$imagename = time() . '_' . rand(10,100) . '_' . strtolower($photo->getClientOriginalName());   
				
				// thumb img
				$destinationPath = public_path('upload/images/small');
				$thumb_img = Image::make($photo->getRealPath())->resize(120, 100);
				$thumb_img->save($destinationPath.'/'.$imagename,80);   

				// original img
				$destinationPath = public_path('upload/images/medium');
				$medium_img = Image::make($photo->getRealPath())->resize(700, 616);
				$medium_img->save($destinationPath.'/'.$imagename,80);
				
				$destinationPath = public_path('upload/images');
				$photo->move($destinationPath, $imagename);
				
				
				$GalleryImages = new GalleryImages;				
				$GalleryImages->image_name=$imagename;
				$GalleryImages->listing_id=$listing_id;		
				$GalleryImages->save();	
				
				$return = array('status' => 1, 'image_name' => $imagename, 'msg' => 'success');
				
			}
			else{
				$return = array('status' => 0, 'msg' => 'failure');		
			}
		return response()->json($return);
	}
	public function update_gallery_ios(Request $request)
	{
		$return = array('status' => 0, 'msg' => 'User Id is required.');
		
		$user_id = $request->user_id;
		
		if($user_id)
		{
			
			
			
				$rules = [
					'photo'			=> 'required'
				];				
				$messages = [
					'photo.required'			=> 'required',
				];
				
				$validator = \Validator::make($request->all(), $rules, $messages);
				
				 if ($validator->fails()) {
					$return['data'] = $validator->errors()->toArray();
					$return['msg'] = 'Validation Issue';
					return response()->json($return);
				}
				
					$listing=Listing::where('user_id',$user_id)->first();
					$listing_id=$listing->id;		
					if($listing_id)
					{	
							
						$destinationPath = public_path('/upload/images/');
						/*$image_parts = explode(";base64,",$request->photo);
						$image_type_aux = explode("image/", $image_parts[0]);
						$image_type = $image_type_aux[1];
						$image_base64 = base64_decode($image_parts[1]);*/
						$image = $request->photo;
						$image_base64 = base64_decode($image);
						
						$file = time() . '_' . rand(10,100) . '_' . strtolower('.png');
						$gfile=$destinationPath . $file;
						file_put_contents($gfile, $image_base64);
						$thumb_img = Image::make($destinationPath . $file)->crop(120, 100);
						$destinationThumPath = public_path('/upload/images/small');
						$thumb_img->save($destinationThumPath.'/'.$file,80); 
						
						$thumb_img = Image::make($destinationPath . $file)->crop(700, 616);
						$destinationMediumPath = public_path('/upload/images/medium');
						$thumb_img->save($destinationMediumPath.'/'.$file,80); 
					
						$GalleryImages = new GalleryImages;				
						$GalleryImages->image_name=$file;
						$GalleryImages->listing_id=$listing_id;		
						$GalleryImages->save();				
						$return = array('status' => 1, 'image_name' => $file, 'msg' => 'Success');
					}
		}
		
		return response()->json($return);
	}
	public function getUploadedImages(Request $request)
	{
		$user_id = $request->user_id;
		$listing=Listing::where('user_id',$user_id)->first();
		$listing_id=$listing->id;
		
		$data['listing_image']=GalleryImages::where('listing_id',$listing_id)->get();
		return response()->json($data);	
		
		}

		public function deleteUploadedImage($id)
		{	

			$GalleryImages = GalleryImages::find($id);

		// Delete a single file
				@unlink(public_path('/upload/images/small').'/'.($GalleryImages->image_name));
				@unlink(public_path('/upload/images/medium').'/'.($GalleryImages->image_name));

				$GalleryImages->delete();

				return response()->json(['status' => 1, 'msg' => 'success']);

			}

}