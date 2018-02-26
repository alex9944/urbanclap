<?php

namespace App\Http\Controllers;
use DB;
use Illuminate\Http\Request;
use App\Models\ListingReview;
use App\Models\Notifications;
use App\Models\Listing;
class ApiReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $response['result']=array();
        $data= $request->json()->all();
        $reviewobj=new ListingReview();
        $reviewobj->merchant_id=$data['merchant_id'];
        $reviewobj->listing_id=$data['listing_id'];
        //$reviewobj->name=$data['name'];
        $reviewobj->comments=$data['comments'];
        $reviewobj->rating=$data['rating'];
        $reviewobj->location=$data['location'];
        //$reviewobj->email=$data['email'];
        $reviewobj->user_id=$data['user_id'];
		$reviewobj->approved = 1;
        $reviewobj->save();
        if(sizeof($reviewobj)>0){
            $title=Listing::where('id',$reviewobj->listing_id)->value('title');
            /*$notification=new Notifications;
            $notification->name=$data['name'];
            $notification->type='Review';
            $notification->message=$data['name'].' has added a review for '. $title;
            $notification->listing_id=$data['listing_id'];
            $notification->merchant_id=$data['merchant_id'];
            $notification->save();*/
            $result=array('Message'=>'Review added successfully');
        }
        else{
             $result=array('Message'=>'Review Not added');
        }
        $response['result']=$result;
        return response()->json($response);	

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
     $category=Category::all()->where('c_id',$id)->first(); 
     $data['category'][]=$category;		
     $responsecode = 200;        
     $header = array (
        'Content-Type' => 'application/json; charset=UTF-8',
        'charset' => 'utf-8'
    );  
     return response()->json($data, $responsecode, $header, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);	
 }
 public function subcategory($id)
 {

     $subcategory =DB::table('category')->where('parent_id',$id)->get();
			//dd(DB::getQueryLog($duration));			
     $data['subcategory']=$subcategory;			
     $responsecode = 200;        
     $header = array (
        'Content-Type' => 'application/json; charset=UTF-8',
        'charset' => 'utf-8'
    );       
     return response()->json($data , $responsecode, $header, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); 	
 }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
