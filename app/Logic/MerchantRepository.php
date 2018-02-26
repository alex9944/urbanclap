<?php

namespace App\Logic;

use App\Models\User;
use DB;

class MerchantRepository
{
	var $subscription_grace_period = 5;
	
    public function downgradeExpiredSubscribers()
    {
		// get merchant users
		$subscribers = User::with(['roles' => function($q){
							$q->where('name', 'merchant');
						}])
						->where('merchant_status', 1)
						->get();
		
		foreach($subscribers as $subscriber)
		{
			if(isset($subscriber->last_subscription) and $subscriber->last_subscription)
			{
				$expired_time = strtotime($subscriber->last_subscription->expired_date);
				$extend_expired_time = date('Y-m-d H:i:s', strtotime("+".$this->subscription_grace_period." days", $expired_time));
				$current_time = date('Y-m-d H:i:s');
				
				if($extend_expired_time < $current_time) // is expired
				{
					$this->_downgrade($subscriber);
				}
			}
			else
			{
				// not subscribed yet
				$this->_downgrade($subscriber);
				
			}
		}
	}
	
	private function _downgrade($user)
	{
		$user->update(['merchant_status' => 0]);
		
		$user->listing->status = 'Disable';
		$user->listing->save();
	}
}