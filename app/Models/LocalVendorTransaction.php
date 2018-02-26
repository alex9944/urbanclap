<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class LocalVendorTransaction extends Model
{
	protected $table = 'local_vendor_transaction';
	
	public function local_vendor_marketing()
    {
        return $this->belongsTo('App\Models\LocalvendorMarketing', 'local_vendor_marketing_id');
    }
	
	public function moderator()
    {
        return $this->belongsTo('App\Models\User', 'payment_confirm_by');
    }
	
}
	
?>