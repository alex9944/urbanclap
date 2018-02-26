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


class GalleryController extends Controller
{

	public function index()
	{	

		$user_id = (auth()->check()) ? auth()->user()->id : null;
		$listing = Listing::where('user_id', '=', $user_id)->first();
		
		$merchant_services = MerchantServices::where(['merchant_id' => $user_id, 'is_enable' => 1])->get();
		
		// times
		$times = array();
		for($i = 1; $i <= 12; $i++)
		{
			$times[$i] = $i;
		}
		
		$country = Country::all();
		$categories = Category::where('parent_id', '0')->get();
		$subcategory = array();
		$states = array();
		$cities = array();
		$tags = array();
		
		if($this->subscribed_category)
		{
			$category_id = $this->subscribed_category->c_id;		
			$subcategory = Category::where('parent_id','=', $category_id)->get();
		}
		
		if( old('scategory') != '')
		{
			$scategory = Category::where('c_id', old('scategory'))->first();
			$tags = $scategory->category_tags;
		}
		
		if( old('country') != '')
		{
			$states = States::where('country_id','=', old('country'))->get();
		}else if($listing){
			$states = States::where('country_id','=', $listing->c_id)->get();
		}
		if( old('states') != '')
		{
			$cities = Cities::where('state_id','=', old('states'))->get();
		}else if($listing){
			$cities = Cities::where('state_id','=', $listing->state)->get();
		}

		//return view('panels.merchant.merchants.index', compact('listing', 'country', 'categories', 'subcategory', 'states', 'cities', 'tags', 'merchant_services', 'times'));
		
		if($listing) // update
		{
			$compact_array = compact('listing', 'country', 'categories', 'subcategory', 'states', 'cities', 'tags', 'merchant_services', 'times','images');
			return $this->_loadMerchantView('gallery.index', $compact_array); 
			
		}
		
	}

	public function imageUpload(Request $request)
	{		
		$this->validate($request, [

			'file' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',

			]);
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
		
		/*$listing_image_names = (array) $request->session()->get('listing_image_names');
		$image_id = count($listing_image_names)+1;
		$listing_image_names[$image_id] = $imagename;
		$request->session()->put('listing_image_names', $listing_image_names);
		$request->session()->save(); */
		
		return response()->json(['status' => 1, 'msg' => 'success']);
	}
	else{
		return response()->json(['status' => 0, 'msg' => 'failure']);
	}
	}
	
	public function getUploadedImages(Request $request)
	{
		$user_id = (auth()->check()) ? auth()->user()->id : null;
		$listing=Listing::where('user_id',$user_id)->first();
		$listing_id=$listing->id;
		
		$listing_image=GalleryImages::where('listing_id',$listing_id)->get();
		$data = array();
		$cnt = 0;
		if($listing_image)
			foreach($listing_image as $image) {
				$data[$cnt] = new \stdClass();
				$data[$cnt]->id = $image->id;
				$data[$cnt]->image_name = $image->image_name;
				$cnt++;
			}

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

			public function deleteUploadedImageFromTable($id)
			{	

				$listing_image = ListingImages::find($id);

		// Delete a single file
				@unlink(public_path('/upload/images/small').'/'.($listing_image->image_name));
				@unlink(public_path('/upload/images/medium').'/'.($listing_image->image_name));

				$listing_image->delete();

				return response()->json(['status' => 1, 'msg' => 'success']);

			}
}