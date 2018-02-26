<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class MerchantTransaction extends Model
{
	protected $table = 'merchant_transaction';
	
	public function get_adding_rules()
	{
		$rules = [
			'listing_id'	=> 'required',
			'paid_amount'	=> 'required'
		];
		
		$messages = [
			'listing_id.required'	=> 'Lising Id is required',
			'paid_amount.required'	=> 'Paid amount is required'
		];
		
		return array('rules' => $rules, 'messages' => $messages);
	}
	
	public function listing()
    {
        return $this->belongsTo('App\Models\Listing', 'listing_id');
    }
	
	public function orders()
    {
        return $this->hasMany('App\Models\MerchantOrder', 'merchant_transaction_id');
    }
	
	public function moderator()
    {
        return $this->belongsTo('App\Models\User', 'payment_confirm_by');
    }
	
}
	
?>