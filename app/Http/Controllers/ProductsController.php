<?php

namespace App\Http\Controllers;
use DB;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Products;
use App\Models\User;
use App\Models\MultiLanguage;
use App\Models\Country;
use App\Models\States;
use App\Models\Cities;
use App\Models\Category;
use App\Models\CategorySlug;
use App\Http\Controllers\Controller;
use Image;


class ProductsController extends Controller
{
    //
	public function index()
    {		
	   $products= products::all();
	   $language= MultiLanguage::all();
	   $country= Country::all();
	   //$category= Category::all();
	   $category = DB::table('category')
			 ->where('parent_id', '0')	 
			 ->get();
	    $users = DB::table('users')
			->select('*', 'users.id as uid')
			->leftJoin('role_user', 'users.id', '=', 'role_user.user_id')
			->where('role_user.role_id', '=', 3)
			->get();
		
		
		$subcategory = DB::table('category')
			 ->where('parent_id','!=', '0')
			 ->get();
		$editstates = DB::table('states')			 
			 ->get();
		$editcities = DB::table('cities')			 
			 ->get();
			 
       return view('panels.admin.merchants.products',['products'=>$products,'prod'=>$products,'users' => $users,'language' => $language,'country' => $country,'category' => $category,'editusers' => $users,'editlanguage' => $language,'editcountry' => $country,'editcategory' => $category,'editsubcategory' => $subcategory,'editstates' => $editstates,'editcities' => $editcities]);
    }
	
	public function getproducts(Request $request)
		{
			 $id=$request->id;  
			 $products = DB::table('products')
			 ->where('id', $id)	 
			 ->first();   
		 return '{"view_details": ' . json_encode($products) . '}';
	}
	
	public function img_save_to_file(Request $request)
		{
			// $id=$request->id;  
			 //$products = DB::table('products')
			// ->where('id', $id)	 
			// ->first();   
		// return '{"view_details": ' . json_encode($products) . '}';
				//	$photo = $request->file('img');
				//	$imagename = time().'.'.$photo->getClientOriginalExtension();   
				//$destinationPath = public_path('/uploads/products/thumbnail');
				//$thumb_img = Image::make($photo->getRealPath())->crop(100, 100);
				//	$thumb_img->save($destinationPath.'/'.$imagename,80);                    
				//	$destinationPath = public_path('/uploads/products/original');
				//	$photo->move($destinationPath, $imagename);
				
				 //$imagePath = "temp/";
	$imagePath = public_path('uploads/products/original/');

	$allowedExts = array("gif", "jpeg", "jpg", "png", "GIF", "JPEG", "JPG", "PNG");
	$temp = explode(".", $_FILES["img"]["name"]);
	$extension = end($temp);
	
	//Check write Access to Directory

	if(!is_writable($imagePath)){
		$response = Array(
			"status" => 'error',
			"message" => 'Can`t upload File; no write Access'
		);
		print json_encode($response);
		return;
	}
	
	if ( in_array($extension, $allowedExts))
	  {
	  if ($_FILES["img"]["error"] > 0)
		{
			 $response = array(
				"status" => 'error',
				"message" => 'ERROR Return Code: '. $_FILES["img"]["error"],
			);			
		}
	  else
		{
			
	      $filename = $_FILES["img"]["tmp_name"];
		  list($width, $height) = getimagesize( $filename );

		  move_uploaded_file($filename,  $imagePath . $_FILES["img"]["name"]);
//$imagePath 
		  $response = array(
			"status" => 'success',
			"url" => url('/').'/uploads/products/original/'.$_FILES["img"]["name"],
			"width" => $width,
			"height" => $height
		  );
		  
		}
	  }
	else
	  {
	   $response = array(
			"status" => 'error',
			"message" => 'something went wrong, most likely file is to large for upload. check upload_max_filesize, post_max_size and memory_limit in you php.ini',
		);
	  }
	  
	  print json_encode($response);
	  
	
	}
	
	public function img_crop_to_file(Request $request)
		{
	$imgUrl = $_POST['imgUrl'];
// original sizes
$imgInitW = $_POST['imgInitW'];
$imgInitH = $_POST['imgInitH'];
// resized sizes
$imgW = $_POST['imgW'];
$imgH = $_POST['imgH'];
// offsets
$imgY1 = $_POST['imgY1'];
$imgX1 = $_POST['imgX1'];
// crop box
$cropW = $_POST['cropW'];
$cropH = $_POST['cropH'];
// rotation angle
$angle = $_POST['rotation'];

$jpeg_quality = 100;
$imgname="croppedImg_".rand();
 $output_filename = public_path('uploads/products/original/')."/".$imgname;
//$output_filename = "temp/croppedImg_".rand();

// uncomment line below to save the cropped image in the same location as the original image.
//$output_filename = dirname($imgUrl). "/croppedImg_".rand();

$what = getimagesize($imgUrl);

switch(strtolower($what['mime']))
{
    case 'image/png':
        $img_r = imagecreatefrompng($imgUrl);
		$source_image = imagecreatefrompng($imgUrl);
		$type = '.png';
        break;
    case 'image/jpeg':
        $img_r = imagecreatefromjpeg($imgUrl);
		$source_image = imagecreatefromjpeg($imgUrl);
		error_log("jpg");
		$type = '.jpeg';
        break;
    case 'image/gif':
        $img_r = imagecreatefromgif($imgUrl);
		$source_image = imagecreatefromgif($imgUrl);
		$type = '.gif';
        break;
    default: die('image type not supported');
}


//Check write Access to Directory

if(!is_writable(dirname($output_filename))){
	$response = Array(
	    "status" => 'error',
	    "message" => 'Can`t write cropped File'
    );	
}else{

    // resize the original image to size of editor
    $resizedImage = imagecreatetruecolor($imgW, $imgH);
	imagecopyresampled($resizedImage, $source_image, 0, 0, 0, 0, $imgW, $imgH, $imgInitW, $imgInitH);
    // rotate the rezized image
    $rotated_image = imagerotate($resizedImage, -$angle, 0);
    // find new width & height of rotated image
    $rotated_width = imagesx($rotated_image);
    $rotated_height = imagesy($rotated_image);
    // diff between rotated & original sizes
    $dx = $rotated_width - $imgW;
    $dy = $rotated_height - $imgH;
    // crop rotated image to fit into original rezized rectangle
	$cropped_rotated_image = imagecreatetruecolor($imgW, $imgH);
	imagecolortransparent($cropped_rotated_image, imagecolorallocate($cropped_rotated_image, 0, 0, 0));
	imagecopyresampled($cropped_rotated_image, $rotated_image, 0, 0, $dx / 2, $dy / 2, $imgW, $imgH, $imgW, $imgH);
	// crop image into selected area
	$final_image = imagecreatetruecolor($cropW, $cropH);
	imagecolortransparent($final_image, imagecolorallocate($final_image, 0, 0, 0));
	imagecopyresampled($final_image, $cropped_rotated_image, 0, 0, $imgX1, $imgY1, $cropW, $cropH, $cropW, $cropH);
	// finally output png image
	//imagepng($final_image, $output_filename.$type, $png_quality);
	imagejpeg($final_image, $output_filename.$type, $jpeg_quality);
	$response = Array(
	    "status" => 'success',
		"image_name" => $imgname.$type,
	    "url" => url('/').'/uploads/products/original/'.$imgname.$type
    );
}
print json_encode($response);
	}
	
	
	public function getcategory(Request $request)
		{
			 $id=$request->id;  
			 $category = DB::table('category')
			 ->where('c_type', $id)	 
			 ->get();   
		 return '{"view_details": ' . json_encode($category) . '}';
	}
	public function getsubcategory(Request $request)
		{
			 $id=$request->id;  
			 $category = DB::table('category')
			 ->where('parent_id', $id)	 
			 ->get();   
		 return '{"view_details": ' . json_encode($category) . '}';
	}
	
	public function getstates(Request $request)
		{
			 $id=$request->id;  
			 $states = DB::table('states')
			 ->where('country_id', $id)	 
			 ->get();   
		 return '{"view_details": ' . json_encode($states) . '}';
	}
	
	public function getcities(Request $request)
		{
			 $id=$request->id;  
			 $cities = DB::table('cities')
			 ->where('state_id', $id)	 
			 ->get();   
		 return '{"view_details": ' . json_encode($cities) . '}';
	}
		
	public function added(Request $request)
	{
		
		
		  $this->validate($request,
			 [
			 
			'merchant_id'		=> 'required',
			'language_id'	=> 'required',
			'category'		=> 'required',
			'scategory'		=> 'required',
			'title'			=> 'required',
			'country'		=> 'required',
			'states'		=> 'required',
			'cities'		=> 'required',
			'photo'			=> 'required|image|mimes:jpeg,png,jpg,gif,svg|max:1024',	
			'description'	=> 'required',		
               
            ],
            [
			'merchant_id.required'		=> 'Username is required',
			'language_id.required'	=> 'Language is required',
			'category.required'		=> 'Category is required',
			'scategory.required'		=> 'Subcategory is required',
			'title.required'			=> 'Title is required',
			'country.required'		=> 'Country is required',
			'states.required'		=> 'States is required',
			'cities.required'		=> 'Cities is required',			
			'photo.required'			=> 'required|image|mimes:jpeg,png,jpg,gif,svg|max:1024',
			'photo.image'           	=> 'Image should be a jpeg,png,gif,svg', 			
			'description.required'	=> 'required',
                     ]);
			
				$photo = $request->file('photo');
				$imagename = time().'.'.$photo->getClientOriginalExtension();   
				$destinationPath = public_path('/uploads/products/thumbnail');
				$thumb_img = Image::make($photo->getRealPath())->crop(100, 100);
				$thumb_img->save($destinationPath.'/'.$imagename,80);                    
				$destinationPath = public_path('/uploads/products/original');
				$photo->move($destinationPath, $imagename);
			
				$products = new products;				
				$products->merchant_id=$request->merchant_id;
				$products->l_id=$request->language_id;
				$products->c_type=$request->c_type;
				$products->m_c_id=$request->category;
				$products->s_c_id=$request->scategory;				
				$products->price=$request->price;
				$products->discount_price=$request->discount_price;
				$products->weight=$request->weight;
				$products->shipping_amount=$request->shipping_amount;
				$products->delivery_days=$request->delivery_days;				
				$products->name=$request->title;
				$products->c_id=$request->country;
				$products->state=$request->states;
				$products->city=$request->cities;				
				$products->image=$imagename;
				$products->meta_tag=$request->meta_tag;
				$products->meta_description=$request->meta_description;
				$products->description=$request->description;		
				$products->save();
			return redirect('admin/merchants/products')->with('message','Products added successfully');
	}
	public function updated(Request $request)
	{	 
 $this->validate($request,
			 [
			 
		'merchant_id'		=> 'required',
		'language_id'	=> 'required',
		'category'		=> 'required',
		'scategory'		=> 'required',
		'title'			=> 'required',
		'country'		=> 'required',
		'states'		=> 'required',
		'cities'		=> 'required',		
		'description'	=> 'required',		
               
            ],
            [
			'merchant_id.required'		=> 'Username is required',
			'language_id.required'	=> 'Language is required',
			'category.required'		=> 'Category is required',
			'scategory.required'		=> 'Subcategory is required',
			'title.required'			=> 'Title is required',
			'country.required'		=> 'Country is required',
			'states.required'		=> 'States is required',
			'cities.required'		=> 'Cities is required',							
			'description.required'	=> 'required',
                     ]);
			$photo = $request->file('photo');
			 if($photo){
					$imagename = time().'.'.$photo->getClientOriginalExtension();   
					$destinationPath = public_path('/uploads/products/thumbnail');
					$thumb_img = Image::make($photo->getRealPath())->crop(100, 100);
					$thumb_img->save($destinationPath.'/'.$imagename,80);                    
					$destinationPath = public_path('/uploads/products/original');
					$photo->move($destinationPath, $imagename);
						  DB::table('products')
						->where('id', $request->id)
						->update(['image' => $imagename,]);
			 }
			
				DB::table('products')
				->where('id', $request->id)
				->update([
				'merchant_id' =>$request->merchant_id,
				'l_id' =>$request->language_id,
				'c_type'=>$request->c_type,
				'm_c_id' =>$request->category,
				's_c_id' =>$request->scategory,
				'name' =>$request->title,
				'c_id' =>$request->country,
				'state' =>$request->states,
				'city' =>$request->cities,
				'price'=>$request->price,
				'discount_price'=>$request->discount_price,
				'weight'=>$request->weight,
				'shipping_amount'=>$request->shipping_amount,
				'delivery_days'=>$request->delivery_days,
				'meta_tag' =>$request->meta_tag,
				'meta_description' =>$request->meta_description,
				'description' =>$request->description,				
				]);

	return redirect('admin/merchants/products')->with('message','products updated successfully');
	}
	public function enable(Request $request)
		{	 
		$id=$request->id;
		DB::table('products')
		->where('id', $request->id)
		->update(['status' => 'Disable',]);
		$status['deletedtatus']='products status updated successfully';
		 return '{"delete_details": ' . json_encode($status) . '}'; 
		
		}
	public function disable(Request $request)
		{	 
		$id=$request->id;
		DB::table('products')
		->where('id', $request->id)
		->update(['status' => 'Enable',]);
		$status['deletedtatus']='products status updated successfully';
		 return '{"delete_details": ' . json_encode($status) . '}'; 
		
		}

	public function deleted(Request $request)
		{	 
		$id=$request->id;  
			 $blogs = DB::table('products')
			 ->where('id', $id)
			 ->delete();
			 $status['deletedid']=$id;
			 $status['deletedtatus']='products deleted successfully';
		 return '{"delete_details": ' . json_encode($status) . '}'; 
		
		}

	public function destroy(Request $request)
	{
			$cn=count($request->selected_id);
			if($cn>0)
			{
			$data = $request->selected_id;			
				foreach($data as $id) {
				DB::table('products')->where('id', $id)->delete();			
				}			
			} 
		return redirect('admin/merchants/products')->with('message','Seltected products are deleted successfully');			

	}
}