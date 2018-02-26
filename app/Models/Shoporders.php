<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Gloudemans\Shoppingcart\Facades\Cart;
use App\Models\Shopproducts;
use App\Models\User;

use Illuminate\Database\Eloquent\Model;

class Shoporders extends Model {

    protected $fillable = ['total', 'status'];
    protected $table = 'shop_orders';

    public function orderFields() {
        return $this->belongsToMany(Shopproducts::class)->withPivot('qty', 'total');
    }

    public static function createOrder() {

        // for order inserting to database
        print_r(\Cart::gettotal());
        $user = new User;
        $order = $user->orders->create(['total' => \Cart::gettotal(), 'status' => 'pending']);


        $cartItems = \Cart::content();
        foreach ($cartItems as $cartItem) {
            $order->orderFields()->attach($cartItem->id, ['qty' => $cartItem->qty, 'total' => $cartItem->qty * $cartItem->price]);
        }
    }

}
