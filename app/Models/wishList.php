<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class wishList extends Model
{
    protected $table = 'shop_wishlist';
    
    
    protected $fillable = ['user_id','pro_id'];
}
