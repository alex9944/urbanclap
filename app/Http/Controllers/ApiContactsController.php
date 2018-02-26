<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use DB;
use App\Models\Category;
use App\Models\Listing;
use App\Models\CategoryType;
use App\Models\ListingViews;
use App\Models\Contacts;
use Jenssegers\Agent\Agent;
use Cookie;
use Validator;
use auth;

class ApiContactsController extends Controller
{

	public function getContacts(){
		$response=array();
		$user_id=$_GET['id'];
		$allcontacts=array();
		$contacts=Contacts::get()->where('user_id',$user_id);
		if(sizeof($contacts)>0){
			$mycontact=array();
			foreach($contacts as $contact){
				$mycontact['id']=$contact->id;
				$mycontact['listing_id']=$contact->listing_id;
				$mycontact['listing_title']=$contact->listing->title;
				$mycontact['website']=$contact->listing->website;
				$mycontact['address']=$contact->listing->address1;
				$mycontact['pincode']=$contact->listing->pincode;
				$mycontact['phone']=$contact->listing->phoneno;
				array_push($allcontacts,$mycontact);
			}
			$data['contacts']=$allcontacts;
		}
		else{
			$data['contacts']='No cotacts found ';
		}
		
		$responsecode = 200;        
		$header = array (
			'Content-Type' => 'application/json; charset=UTF-8',
			'charset' => 'utf-8'
		);       
		return response()->json($data , $responsecode, $header, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
		
	}
}