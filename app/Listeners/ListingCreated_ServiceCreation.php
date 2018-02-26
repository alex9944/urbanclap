<?php

namespace App\Listeners;

use App\Events\ListingCreated;

use Mail;

use App\Models\MerchantServices;

class ListingCreated_ServiceCreation
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  ListingCreated  $event
     * @return void
     */
    public function handle(ListingCreated $event)
    {
        $listing = $event->listing;
		$merchant_id = $listing->user_id;
		
		$category = $listing->category;
		
		if($category->category_type)
		{
			$category_types = json_decode($category->category_type);//print_r($category_types);exit;
			
			foreach($category_types as $category_type_id)
			{
				$merchant_service = MerchantServices::where(['merchant_id' => $merchant_id, 'category_type_id' => $category_type_id])->first();
				
				//if(!$merchant_service)
				if($merchant_service == null || $merchant_service->count() <= 0)
				{
					// create service for merchant
					$new_merchant_service = new MerchantServices;
					$new_merchant_service->category_type_id = $category_type_id;
					$new_merchant_service->merchant_id = $merchant_id;
					$new_merchant_service->is_enable = ($listing->status == 'Enable') ? 1 : 0;
					$new_merchant_service->save();				
				}
			}
		}
    }
}