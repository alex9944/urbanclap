<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Address;
use App\Models\Shoporders;
use DB;

class CheckoutController extends Controller {

    public function index() {
        // check for user login
      $cartItems = \Cart::getcontent(); 
      return view('pages.checkout', compact('cartItems'));
  }

  public function formvalidate(Request $request) {
    $this->validate($request, [
        'fullname' => 'required|min:5|max:35',
        'address'   =>'required',
        'pincode' => 'required|numeric',
        'city' => 'required|min:5|max:25',
        'state' => 'required|min:5|max:25',
        'country' => 'required']);

       // $userid = Auth::user()->id;
    $order =  Shoporders::create(['total' => \Cart::gettotal(), 'status' => 'pending']);
    $cartItems = \Cart::getcontent();
    foreach ($cartItems as $cartItem) {
        
        DB::insert('insert into shop_orders_products (`qty`, `orders_id`, `products_id`, `tax`, `total`) values (?, ?,?,?,?)', [$cartItem->quantity, $order->id,$cartItem->id, '0',$cartItem->quantity * $cartItem->price]);
     
    }
    $address = new Address;
    $address->order_id=$order->id;
    $address->fullname = $request->fullname;
    $address->address=$request->address;
    $address->state = $request->state;
    $address->city = $request->city;
    $address->country = $request->country;

        //$address->user_id = $userid;
    $address->pincode = $request->pincode;
    $address->payment_type = $request->pay;
    $address->save();


    //Shoporders::createOrder();

    \Cart::clear();
    return redirect('/checkout/thankyou');
}

}
