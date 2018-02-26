<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class LocalVendorUsage extends Model
{
	protected $table = 'local_vendor_usage';
	
	public function local_vendor_marketing()
    {
        return $this->belongsTo('App\Models\LocalvendorMarketing', 'local_vendor_marketing_id');
    }
	
	public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }
	
}
	
?>