<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionFeatures extends Model
{
    protected $table = 'subscription_features';
	
	var $listing_models = array(
		'information'			=> 'Information',
		'category_dependent'	=> 'Category Based Functionality',
		'review'				=> 'Review',
		'call'					=> 'Call',
		'map'					=> 'Map',
		'share'					=> 'Share',
		'gallery'				=> 'Gallery',
		'save'					=> 'Save'
	);
}
