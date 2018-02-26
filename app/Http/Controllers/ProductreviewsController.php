<?php

namespace App\Http\Controllers;
use DB;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\ProductReview;
use App\Models\Notifications;
use App\Models\Listing;
use App\Models\User;
use App\Http\Controllers\Controller;
use Auth;
class ProductreviewsController extends Controller
{
	
	public function index()
	{
		
	$reviews = ProductReview::all();
		
	
	$listing = DB::table('listing')
		         ->select('*','listing.id as id')
				 ->get();
		
	$users = ProductReview::select('listing_review.user_id','users.id','users.first_name','users.email')
		->leftJoin('users', 'listing_review.user_id', '=', 'users.id')
		->get();
			
		return view('panels.admin.merchants.reviews',compact('reviews','users','listing'));
	}
	public function added(Request $request)
	{
		
		$this->validate($request,
	   [
	    'listing_id'		=> 'required',
		'email'	            => 'required',
		'name'	            => 'required',
		'comments'			=> 'required',
		'location'	        => 'required',		
		],
        [
	    'listing_id.required'		=> 'Listing Id is required',
		'email.required'	        => 'Email is required',
		'name.required'	            => 'Name is required',
		'comments.required'		    => 'Comments is required',
		'rating.required'	        => 'Rating is required',
		'location.required'		    => 'Location  is required',		
		]);
		
		$reviewobj=new ProductReview();
		$reviewobj->merchant_id=$request->merchant_id;
		$reviewobj->listing_id=$request->listing_id;
		$reviewobj->name=$request->name;
		$reviewobj->comments=$request->comments;
		$reviewobj->rating=$request->rating;
		$reviewobj->location=$request->location;
		$reviewobj->email=$request->email;
		$reviewobj->user_id=$request->user_id;
		$reviewobj->save();
	
		return redirect('admin/merchants/reviews')->with('message','Review Added Successfully');
	}
	public function updated(Request $request)
	{
		//print_r($request);
		$this->validate($request,
	   [
	    
		'comments'			=> 'required',
		'rating'            => 'required',
		
		],
        [
	    
		'comments.required'		=> 'Comments is required',
		'rating.required'	=> 'Rating is required',
			
		]);
			
				DB::table('listing_review')
				->where('r_id', $request->id)
				->update([
				'comments' =>$request->comments,
				'rating' =>$request->rating,
				'approved' =>$request->approved,
							
				]);
		
	    
		return redirect('admin/merchants/reviews')->with('message','Review Updated Successfully');
	}
	
	public function show(Request $request)
		{
			
			 $id=$request->id;  
			 $data['reviewsdetails'] = ProductReview::find($id);
			 $data['user_detail'] = $data['reviewsdetails']->user;
			 $data['listing_detail'] = $data['reviewsdetails']->listing;
			
					  
		 return '{"view_details": ' . json_encode($data) . '}';
	}
	
	public function productReview(Request $request){
		
		$user_id=$request->user_id;
		 $user=ProductReview::where('user_id',$user_id)->where('product_id', $request->product_id)->first();		
		if($user){
				$return['status'] = 2;
		}else{
			
		
					$reviewobj=new ProductReview();
					$reviewobj->merchant_id=$request->merchant_id;
					$reviewobj->product_id=$request->product_id;
					//$reviewobj->name=$request->name;
					$reviewobj->comments=strip_tags($request->comments);
					$reviewobj->rating=$request->rating;
					//$reviewobj->location=$request->location;
					//$reviewobj->email=$request->email;
					$reviewobj->user_id=$request->user_id;
					$reviewobj->approved = 1;
					$reviewobj->save();
					
					// get all reviews
					$reviews = ProductReview::with('user')->where('product_id', $request->product_id)->ordered();
					
					$avgrating=ProductReview::where('product_id',$request->product_id)->avg('rating');

							if(sizeof($reviews)>0){

								$excellentcount=ProductReview::where('product_id',$request->product_id)->where('rating', 5)->where('approved','1')->count();
								$goodcount=ProductReview::where('product_id',$request->product_id)->where('rating','<', 5)->where('rating','>=', 4)->where('approved','1')->count();
								$averagecount=ProductReview::where('product_id',$request->product_id)->where('rating','<', 4)->where('rating','>=', '2.5')->where('approved','1')->count();
								$badcount=ProductReview::where('product_id',$request->product_id)->where('rating','<', '2.5')->where('approved', 1)->count();
								$total_reviews=sizeof($reviews);
								$excellent=$excellentcount/$total_reviews*100;
								$good=$goodcount/$total_reviews*100;
								$average=$averagecount/$total_reviews*100;
								$bad=$badcount/$total_reviews*100;

							}
							else{
								$excellent='0';
								$good='0';
								$average='0';
								$bad='0';
							}
					$return['status'] = 1;
					$return['reviews'] = $reviews;
					$return['average'] = array('excellent' => $excellent, 'good' => $good, 'average' => $average, 'bad' => $bad);
		}
		return response()->json($return);	
		//return $reviewobj;
	}
	public function getReviews(){
		$user_id=Auth::user()->id;
		$allreviews=ProductReview::get()->where('merchant_id',$user_id);
		$avgrating=ProductReview::avg('rating');
		return view('panels.merchant.review',compact('allreviews','avgrating'));
	}
	public function approveReview(Request $request){
		$listingreview = ProductReview::find($request->id);
		$listingreview->approved = 1;
		$listingreview->save();
		return 1;
	}
	public function rejectReview(Request $request){
		$listingreview = ProductReview::find($request->id);
		$listingreview->approved = 0;
		$listingreview->save();
		return 1;
	}
		public function deleted(Request $request)
		{	 
		$id=$request->id;  
			 $blogs = DB::table('listing_review')
			 ->where('r_id', $id)
			 ->delete();
			 $status['deletedid']=$id;
			 $status['deletedtatus']=' Reviews Deleted Successfully';
		 return '{"delete_details": ' . json_encode($status) . '}'; 
		
		}
		public function destroy(Request $request)
	{
			$cn=count($request->selected_id);
			if($cn>0)
			{
			$data = $request->selected_id;			
				foreach($data as $id) {
				DB::table('listing_review')->where('r_id', $id)->delete();			
				}			
			} 
		return redirect('admin/merchants/reviews')->with('message','Selected Reviews are deleted successfully');			

	}
	
		
		
}