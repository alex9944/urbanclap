<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pro_cat extends Model
{
     protected $fillable = ['name'];
    protected $table = 'shop_pro_cat';

    public function products() {
        return $this->belongsToMany('Product', 'pro_cat');
    }
}
