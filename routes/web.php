<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

$s = 'public.';
Route::get('/', ['as' => $s . 'home',   'uses' => 'PagesController@getHome']);
Route::get('/free-listing', ['as' => $s . 'free-listing',   'uses' => 'Auth\RegisterController@freeListing']);

// verify code
Route::get('/verify', ['as' => $s . 'verify', 'uses' => 'Auth\RegisterController@verify']);
Route::post('/signup-verify', ['as' => $s . 'verify', 'uses' => 'Auth\RegisterController@signup_verify']);
Route::get('/resend-verification-code', ['as' => $s . 'verify', 'uses' => 'Auth\RegisterController@resend_verify_code']);

Route::get('/vendor-register',         ['as' => $s . 'vendor-register',   'uses' => 'PagesController@vendorRegister']);
Route::post('/vendor-register',         ['as' => $s . 'vendor-register',   'uses' => 'PagesController@vendorSubscription']);
Route::get('/payby-ebs/{encrypt_order_id}', ['as' => $s . 'payby-ebs',   'uses' => 'PagesController@payby_ebs']);
Route::get('/ebs-thankyou', ['as' => $s . 'ebs-thankyou',   'uses' => 'PagesController@ebs_thankyou']);
Route::post('/ebs-thankyou', ['as' => $s . 'ebs-thankyou',   'uses' => 'PagesController@ebs_thankyou']);

Route::get('/payby-razor/{encrypt_order_id}', ['as' => $s . 'payby-razor',   'uses' => 'PagesController@payby_razor']);
Route::post('/razor-thankyou', ['as' => $s . 'razor-thankyou',   'uses' => 'PagesController@razor_thankyou']);

Route::post('/getcities', ['as' => $s . 'getcities',   'uses' => 'Merchant\ListingController@getcities']);
Route::post('/getstates', ['as' => $s . 'getstates',   'uses' => 'Merchant\ListingController@getstates']);
Route::post('/getsubcategory', ['as' => $s . 'getsubcategory',   'uses' => 'Merchant\ListingController@getsubcategory']);
Route::post('/gettags', ['as' => $s . 'gettags',   'uses' => 'Merchant\ListingController@gettags']);
Route::get('/contact-us',         ['as' => $s . 'contact',   'uses' => 'PagesController@getContact']);
Route::post('/keyword_suggestion',         ['as' => $s . 'keyword_suggestion',   'uses' => 'PagesController@keywordSuggestion']);
Route::get('/listing',         ['as' => $s . 'listing',   'uses' => 'PagesController@getListing']);
Route::get('/save_vcard/{id}',         ['as' => $s . 'save_vcard',   'uses' => 'PagesController@saveVcard']);

Route::get('/category/{slug}', ['as' => $s . 'category',   'uses' => 'PagesController@getCategory']);
Route::get('/listing/{slug}', ['as' => $s . 'listing',   'uses' => 'PagesController@getListing']);
Route::get('/listing/get_reviews/{listing_id}', ['as' => $s . 'listing',   'uses' => 'PagesController@get_reviews']);
Route::post('/listing/get_reviews/{listing_id}', ['as' => $s . 'listing',   'uses' => 'PagesController@get_reviews']);


// Local Vendor Listing 
Route::get('/local-vendor-listing', ['as' => $s . 'listing',   'uses' => 'PagesController@getLocalVendorListing']);

Route::get('/getlocalvendor',['as' => $s . 'listing',   'uses' => 'PagesController@getLocalVendor']);
Route::get('/getlocationlist', ['as' => $s . 'getlocationlist',   'uses' => 'PagesController@getlocationlist']);
Route::get('/getsubcatlist', ['as' => $s . 'getsubcatlist',   'uses' => 'PagesController@getsubcatlist']);

Route::post('/store', ['as' => $s . 'store',   'uses' => 'PagesController@ContactStore']);

Route::get('/getlocation', ['as' => $s . 'location', 'uses' => 'PagesController@getLocation']);
Route::post('/book-a-table', ['as' => $s . 'book-a-table',   'uses' => 'PagesController@bookTable']);
Route::post('/book-appointment', ['as' => $s . 'book-appointment',   'uses' => 'PagesController@bookAppointment']);
Route::post('/online-order/add-to-cart', ['as' => $s . 'online-order/add-to-cart',   'uses' => 'OnlineOrderController@ajaxAddToCart']);
Route::post('/online-order/update-cart', ['as' => $s . 'online-order/update-cart',   'uses' => 'OnlineOrderController@ajaxUpdateCart']);
Route::get('/online-order/cart', ['as' => $s . 'online-order/cart',   'uses' => 'OnlineOrderController@cart']);
Route::get('/online-order/checkout', ['as' => $s . 'online-order/checkout',   'uses' => 'OnlineOrderController@checkout']);
Route::post('/online-order/place-order', ['as' => $s . 'online-order/place-order',   'uses' => 'OnlineOrderController@place_order']);
Route::get('/online-order/thankyou/{encrypt_order_id}', ['as' => $s . 'online-order/thankyou',   'uses' => 'OnlineOrderController@thankyou']);
Route::get('/online-order/empty-cart', ['as' => $s . 'online-order/empty-cart',   'uses' => 'OnlineOrderController@empty_cart']);
Route::get('/online-order/delete-cart/{listing_id}', ['as' => $s . 'online-order/delete-cart',   'uses' => 'OnlineOrderController@delete_cart']);

Route::get('/online-order/payby-paypal/{encrypt_order_id}', ['as' => $s . 'online-order/payby-paypal',   'uses' => 'OnlineOrderController@payby_paypal']);
Route::post('/online-order/paypal-success', ['as' => $s . 'online-order/paypal-success',   'uses' => 'OnlineOrderController@paypal_success']);
Route::get('/online-order/paypal-cancel', ['as' => $s . 'online-order/paypal-cancel',   'uses' => 'OnlineOrderController@paypal_cancel']);
Route::post('/online-order/paypal-ipn', ['as' => $s . 'online-order/paypal-ipn',   'uses' => 'OnlineOrderController@paypal_ipn']);

Route::get('/online-order/payby-razor/{encrypt_order_id}', ['as' => $s . 'online-order/payby-razor',   'uses' => 'OnlineOrderController@payby_razor']);
Route::post('/online-order/payment-response', ['as' => $s . 'online-order/payment-response',   'uses' => 'OnlineOrderController@payment_response']);

// cms
Route::get('/page/{slug}', ['as' => $s . 'page', 'uses' => 'PageController@cms']);

$s = 'social.';
Route::get('/social/redirect/{provider}',   ['as' => $s . 'redirect',   'uses' => 'Auth\SocialController@getSocialRedirect']);
Route::get('/social/handle/{provider}',     ['as' => $s . 'handle',     'uses' => 'Auth\SocialController@getSocialHandle']);

Route::group(['prefix' => 'admin', 'middleware' => 'auth:administrator'], function()
{
	$a = 'admin.';
	Route::get('/', ['as' => $a . 'home', 'uses' => 'AdminController@index']);	
	//Route::get('/users', ['as' => $a . 'users', 'uses' => 'AdminController@users']);
	//Route::get('/get_users', ['as' => $a . 'get_users', 'uses' => 'AdminController@getUsers']);
	
	//Route::get('/roles', ['as' => $a . 'users', 'uses' => 'AdminController@roles']);
	
	// Services
	Route::get('services', ['uses' => 'ServiceController@index']);
	Route::post('services/added', ['uses' => 'ServiceController@added']);
	Route::post('services/updated', ['uses' => 'ServiceController@updated']);
	Route::post('services/get', ['uses' => 'ServiceController@get']);
	Route::post('services/deleted', ['uses' => 'ServiceController@deleted']);
	Route::post('services/destroy', ['uses' => 'ServiceController@destroy']);
	
	// Questions
	Route::get('questions', ['uses' => 'QuestionController@index']);
	Route::post('questions/added', ['uses' => 'QuestionController@added']);
	Route::post('questions/updated', ['uses' => 'QuestionController@updated']);
	Route::post('questions/get', ['uses' => 'QuestionController@get']);
	Route::post('questions/deleted', ['uses' => 'QuestionController@deleted']);
	Route::post('questions/destroy', ['uses' => 'QuestionController@destroy']);
	Route::post('questions/question_detail_deleted', ['uses' => 'QuestionController@question_detail_deleted']);
	
	Route::get('/settings', ['as' => $a . 'settings', 'uses' => 'SiteSettingsController@index']);
	Route::get('/settings/{tab}', ['uses' => 'SiteSettingsController@index']);
	Route::post('/settings/update', ['as' => $a . 'settings/update', 'uses' => 'SiteSettingsController@update']);
	Route::post('/settings/service', ['as' => $a . 'settings/service', 'uses' => 'SiteSettingsController@service']);
	Route::post('/settings/localvendor-user', ['as' => $a . 'settings/localvendor-user', 'uses' => 'SiteSettingsController@localvendor_user']);
	Route::post('/settings/localvendor-merchant', ['as' => $a . 'settings/localvendor-merchant', 'uses' => 'SiteSettingsController@localvendor_merchant']);
	Route::post('/get_order_graph', ['as' => $a . 'get_order_graph', 'uses' => 'AdminController@getOrderGraph']);
	//Admin Multilanguage Root
	Route::get('/multilanguage', ['as' => $a . 'multilanguage', 'uses' => 'MultiLanguageController@index']);
	Route::post('multilanguage/added', ['as' => $a . 'added', 'uses' => 'MultiLanguageController@added']);
	Route::post('multilanguage/updated', ['as' => $a . 'updated', 'uses' => 'MultiLanguageController@updated']);
	Route::post('multilanguage/deleted', ['as' => $a . 'deleted', 'uses' => 'MultiLanguageController@deleted']);
	Route::post('multilanguage/destroy', ['as' => $a . 'destroy', 'uses' => 'MultiLanguageController@destroy']);	
	Route::post('multilanguage/getmultilanguage', ['as' => $a . 'getmultilanguage', 'uses' => 'MultiLanguageController@getmultilanguage']);
	
	// Admin Reports Root
	Route::get('reports/onlineorder', ['as' => $a . 'reports/onlineorder', 'uses' => 'ReportsController@onlineorder']);
	Route::post('reports/onlineorder', ['as' => $a . 'reports/onlineorder_post', 'uses' => 'ReportsController@onlineorder']);
	Route::get('reports/onlineshoping',['as' => $a . 'reports/onlineshoping' ,'uses' => 'ReportsController@onlineshoping']);
	Route::post('reports/onlineshoping',['as' => $a . 'reports/onlineshoping_post' ,'uses' => 'ReportsController@onlineshoping']);
	
	
	//Admin Country Root
	Route::get('/country', ['as' => $a . 'country', 'uses' => 'CountryController@index']);
	Route::post('country/added', ['as' => $a . 'added', 'uses' => 'CountryController@added']);
	Route::post('country/updated', ['as' => $a . 'updated', 'uses' => 'CountryController@updated']);
	Route::post('country/deleted', ['as' => $a . 'deleted', 'uses' => 'CountryController@deleted']);
	Route::post('country/destroy', ['as' => $a . 'destroy', 'uses' => 'CountryController@destroy']);	
	Route::post('country/getcountry', ['as' => $a . 'getcountry', 'uses' => 'CountryController@getcountry']);
	Route::post('country/enable', ['as' => $a . 'enable', 'uses' => 'CountryController@enable']);
	Route::post('country/disable', ['as' => $a . 'deleted', 'uses' => 'CountryController@disable']);

	
	
	//Admin currency Root
	Route::get('/currency', ['as' => $a . 'currency', 'uses' => 'CurrencyController@index']);
	Route::post('currency/added', ['as' => $a . 'added', 'uses' => 'CurrencyController@added']);
	Route::post('currency/updated', ['as' => $a . 'updated', 'uses' => 'CurrencyController@updated']);
	Route::post('currency/deleted', ['as' => $a . 'deleted', 'uses' => 'CurrencyController@deleted']);
	Route::post('currency/destroy', ['as' => $a . 'destroy', 'uses' => 'CurrencyController@destroy']);	
	Route::post('currency/getcurrency', ['as' => $a . 'getcurrency', 'uses' => 'CurrencyController@getcurrency']);
	
	//Admin Subscription Plan Root
	
	Route::get('subscription/plan', ['as' => $a . 'plan', 'uses' => 'SubscriptionPlanController@index']);
	Route::post('subscription/plan/added', ['as' => $a . 'added', 'uses' => 'SubscriptionPlanController@added']);
	Route::post('subscription/plan/updated', ['as' => $a . 'updated', 'uses' => 'SubscriptionPlanController@updated']);
	Route::post('subscription/plan/deleted', ['as' => $a . 'deleted', 'uses' => 'SubscriptionPlanController@deleted']);
	Route::post('subscription/plan/destroy', ['as' => $a . 'destroy', 'uses' => 'SubscriptionPlanController@destroy']);	
	Route::post('subscription/plan/getplan', ['as' => $a . 'getplan', 'uses' => 'SubscriptionPlanController@getplan']);
	
	//Admin Subscription Duration Root
	
	Route::get('subscription/duration', ['as' => $a . 'duration', 'uses' => 'SubscriptionDurationController@index']);
	Route::post('subscription/duration/added', ['as' => $a . 'added', 'uses' => 'SubscriptionDurationController@added']);
	Route::post('subscription/duration/updated', ['as' => $a . 'updated', 'uses' => 'SubscriptionDurationController@updated']);
	Route::post('subscription/duration/deleted', ['as' => $a . 'deleted', 'uses' => 'SubscriptionDurationController@deleted']);
	Route::post('subscription/duration/destroy', ['as' => $a . 'destroy', 'uses' => 'SubscriptionDurationController@destroy']);	
	Route::post('subscription/duration/getduration', ['as' => $a . 'getduration', 'uses' => 'SubscriptionDurationController@getduration']);
	
	//Admin Subscription Features Root
	
	Route::get('subscription/features', ['as' => $a . 'features', 'uses' => 'SubscriptionFeaturesController@index']);
	Route::post('subscription/features/added', ['as' => $a . 'added', 'uses' => 'SubscriptionFeaturesController@added']);
	Route::post('subscription/features/updated', ['as' => $a . 'updated', 'uses' => 'SubscriptionFeaturesController@updated']);
	Route::post('subscription/features/deleted', ['as' => $a . 'deleted', 'uses' => 'SubscriptionFeaturesController@deleted']);
	Route::post('subscription/features/destroy', ['as' => $a . 'destroy', 'uses' => 'SubscriptionFeaturesController@destroy']);	
	Route::post('subscription/features/getfeatures', ['as' => $a . 'getfeatures', 'uses' => 'SubscriptionFeaturesController@getfeatures']);
	
	//Admin Subscription Pricing Root
	
	Route::get('subscription/pricing', ['as' => $a . 'pricing', 'uses' => 'SubscriptionPricingController@index']);
	Route::post('subscription/pricing/added', ['as' => $a . 'added', 'uses' => 'SubscriptionPricingController@added']);
	Route::post('subscription/pricing/updated', ['as' => $a . 'updated', 'uses' => 'SubscriptionPricingController@updated']);
	Route::post('subscription/pricing/deleted', ['as' => $a . 'deleted', 'uses' => 'SubscriptionPricingController@deleted']);
	Route::post('subscription/pricing/destroy', ['as' => $a . 'destroy', 'uses' => 'SubscriptionPricingController@destroy']);	
	Route::post('subscription/pricing/getpricing', ['as' => $a . 'getpricing', 'uses' => 'SubscriptionPricingController@getpricing']);
	Route::post('subscription/pricing/getcurrency', ['as' => $a . 'getcurrency', 'uses' => 'SubscriptionPricingController@getcurrency']);
	
	
	//Admin States Root
	Route::get('/states', ['as' => $a . 'states', 'uses' => 'StatesController@index']);
	Route::post('states/added', ['as' => $a . 'added', 'uses' => 'StatesController@added']);
	Route::post('states/updated', ['as' => $a . 'updated', 'uses' => 'StatesController@updated']);
	Route::post('states/deleted', ['as' => $a . 'deleted', 'uses' => 'StatesController@deleted']);
	Route::post('states/destroy', ['as' => $a . 'destroy', 'uses' => 'StatesController@destroy']);	
	Route::post('states/getstates', ['as' => $a . 'getstates', 'uses' => 'StatesController@getstates']);
	Route::post('states/enable', ['as' => $a . 'enable', 'uses' => 'StatesController@enable']);
	Route::post('states/disable', ['as' => $a . 'deleted', 'uses' => 'StatesController@disable']);	

	//Admin Cities Root
	Route::get('/cities', ['as' => $a . 'cities', 'uses' => 'CitiesController@index']);
	Route::post('cities/added', ['as' => $a . 'added', 'uses' => 'CitiesController@added']);
	Route::post('cities/updated', ['as' => $a . 'updated', 'uses' => 'CitiesController@updated']);
	Route::post('cities/deleted', ['as' => $a . 'deleted', 'uses' => 'CitiesController@deleted']);
	Route::post('cities/destroy', ['as' => $a . 'destroy', 'uses' => 'CitiesController@destroy']);	
	Route::post('cities/getcities', ['as' => $a . 'getcities', 'uses' => 'CitiesController@getcities']);
	Route::post('cities/enable', ['as' => $a . 'enable', 'uses' => 'CitiesController@enable']);
	Route::post('cities/disable', ['as' => $a . 'deleted', 'uses' => 'CitiesController@disable']);		
	
	//Admin Users Access Modules Root
	Route::get('/roles', ['as' => $a . 'roles', 'uses' => 'UserAccessModulesController@index']);
	Route::post('roles/added', ['as' => $a . 'added', 'uses' => 'UserAccessModulesController@added']);
	Route::post('roles/updated', ['as' => $a . 'updated', 'uses' => 'UserAccessModulesController@updated']);
	Route::post('roles/deleted', ['as' => $a . 'deleted', 'uses' => 'UserAccessModulesController@deleted']);
	Route::post('roles/destroy', ['as' => $a . 'destroy', 'uses' => 'UserAccessModulesController@destroy']);	
	Route::post('roles/getroles', ['as' => $a . 'getroles', 'uses' => 'UserAccessModulesController@getroles']);
	
	//Admin Users Root
	Route::get('/users', ['as' => $a . 'users', 'uses' => 'AdminController@users']);
	Route::post('users/added', ['as' => $a . 'added', 'uses' => 'AdminController@added']);
	Route::post('users/updated', ['as' => $a . 'updated', 'uses' => 'AdminController@updated']);
	Route::post('users/deleted', ['as' => $a . 'deleted', 'uses' => 'AdminController@deleted']);
	Route::post('users/destroy', ['as' => $a . 'destroy', 'uses' => 'AdminController@destroy']);	
	Route::post('users/getusers', ['as' => $a . 'getusers', 'uses' => 'AdminController@getusers']);
	
	//Admin Ads Position Root
	Route::get('ads/position', ['as' => $a . 'position', 'uses' => 'AdsPositionController@index']);
	Route::post('ads/position/added', ['as' => $a . 'added', 'uses' => 'AdsPositionController@added']);
	Route::post('ads/position/updated', ['as' => $a . 'updated', 'uses' => 'AdsPositionController@updated']);
	Route::post('ads/position/deleted', ['as' => $a . 'deleted', 'uses' => 'AdsPositionController@deleted']);
	Route::post('ads/position/destroy', ['as' => $a . 'destroy', 'uses' => 'AdsPositionController@destroy']);	
	Route::post('ads/position/getposition', ['as' => $a . 'getposition', 'uses' => 'AdsPositionController@getposition']);
	
	
	//Admin Ads Slot Placement Root
	Route::get('ads/slotplacement', ['as' => $a . 'slotplacement', 'uses' => 'AdsSlotPlacementController@index']);
	Route::post('ads/slotplacement/added', ['as' => $a . 'added', 'uses' => 'AdsSlotPlacementController@added']);
	Route::post('ads/slotplacement/updated', ['as' => $a . 'updated', 'uses' => 'AdsSlotPlacementController@updated']);
	Route::post('ads/slotplacement/deleted', ['as' => $a . 'deleted', 'uses' => 'AdsSlotPlacementController@deleted']);
	Route::post('ads/slotplacement/destroy', ['as' => $a . 'destroy', 'uses' => 'AdsSlotPlacementController@destroy']);	
	Route::post('ads/slotplacement/getslotplacement', ['as' => $a . 'getslotplacement', 'uses' => 'AdsSlotPlacementController@getslotplacement']);
	
	
	//Admin Ads Mode Advertisement Root
	Route::get('ads/modeads', ['as' => $a . 'modeads', 'uses' => 'AdsModeAdvertisementController@index']);
	Route::post('ads/modeads/added', ['as' => $a . 'added', 'uses' => 'AdsModeAdvertisementController@added']);
	Route::post('ads/modeads/updated', ['as' => $a . 'updated', 'uses' => 'AdsModeAdvertisementController@updated']);
	Route::post('ads/modeads/deleted', ['as' => $a . 'deleted', 'uses' => 'AdsModeAdvertisementController@deleted']);
	Route::post('ads/modeads/destroy', ['as' => $a . 'destroy', 'uses' => 'AdsModeAdvertisementController@destroy']);	
	Route::post('ads/modeads/getmodeads', ['as' => $a . 'getmodeads', 'uses' => 'AdsModeAdvertisementController@getmodeads']);
	
	//Admin Ads Duration Root
	Route::get('ads/duration', ['as' => $a . 'duration', 'uses' => 'AdsDurationController@index']);
	Route::post('ads/duration/added', ['as' => $a . 'added', 'uses' => 'AdsDurationController@added']);
	Route::post('ads/duration/updated', ['as' => $a . 'updated', 'uses' => 'AdsDurationController@updated']);
	Route::post('ads/duration/deleted', ['as' => $a . 'deleted', 'uses' => 'AdsDurationController@deleted']);
	Route::post('ads/duration/destroy', ['as' => $a . 'destroy', 'uses' => 'AdsDurationController@destroy']);	
	Route::post('ads/duration/getduration', ['as' => $a . 'getduration', 'uses' => 'AdsDurationController@getduration']);
	
	
	//Admin Ads Pricing Root
	Route::get('ads/pricing', ['as' => $a . 'pricing', 'uses' => 'AdsPricingController@index']);
	Route::post('ads/pricing/added', ['as' => $a . 'added', 'uses' => 'AdsPricingController@added']);
	Route::post('ads/pricing/updated', ['as' => $a . 'updated', 'uses' => 'AdsPricingController@updated']);
	Route::post('ads/pricing/deleted', ['as' => $a . 'deleted', 'uses' => 'AdsPricingController@deleted']);
	Route::post('ads/pricing/destroy', ['as' => $a . 'destroy', 'uses' => 'AdsPricingController@destroy']);	
	Route::post('ads/pricing/getpricing', ['as' => $a . 'getpricing', 'uses' => 'AdsPricingController@getpricing']);
	
	
	
	
	//Admin Category Slug Root
	Route::get('category/slug', ['as' => $a . 'slug', 'uses' => 'CategorySlugController@index']);
	Route::post('category/slug/added', ['as' => $a . 'added', 'uses' => 'CategorySlugController@added']);
	Route::post('category/slug/updated', ['as' => $a . 'updated', 'uses' => 'CategorySlugController@updated']);
	Route::post('category/slug/deleted', ['as' => $a . 'deleted', 'uses' => 'CategorySlugController@deleted']);
	Route::post('category/slug/destroy', ['as' => $a . 'destroy', 'uses' => 'CategorySlugController@destroy']);	
	Route::post('category/slug/getcategoryslug', ['as' => $a . 'getcategoryslug', 'uses' => 'CategorySlugController@getcategoryslug']);
	
	//Admin Products Orders Root
	Route::get('/orders', ['as' => $a . 'orders', 'uses' => 'OrdersController@index']);
	Route::post('orders/added', ['as' => $a . 'added', 'uses' => 'OrdersController@added']);
	Route::post('orders/updated', ['as' => $a . 'updated', 'uses' => 'OrdersController@updated']);
	Route::post('orders/deleted', ['as' => $a . 'deleted', 'uses' => 'OrdersController@deleted']);
	Route::post('orders/destroy', ['as' => $a . 'destroy', 'uses' => 'OrdersController@destroy']);	
	Route::post('orders/getorders', ['as' => $a . 'getorders', 'uses' => 'OrdersController@getorders']);
	Route::post('orders/vieworder', ['as' => $a . 'vieworder', 'uses' => 'OrdersController@vieworder']);
	Route::post('orders/getitem', ['as' => $a . 'getitem', 'uses' => 'OrdersController@getitem']);
	
	//Admin Booking Appointments Root
	Route::get('/appointments', ['as' => $a . 'appointments', 'uses' => 'BookingAppointmentsController@index']);
	Route::post('appointments/added', ['as' => $a . 'added', 'uses' => 'BookingAppointmentsController@added']);
	Route::post('appointments/updated', ['as' => $a . 'updated', 'uses' => 'BookingAppointmentsController@updated']);
	Route::post('appointments/deleted', ['as' => $a . 'deleted', 'uses' => 'BookingAppointmentsController@deleted']);
	Route::post('appointments/destroy', ['as' => $a . 'destroy', 'uses' => 'BookingAppointmentsController@destroy']);	
	Route::post('appointments/getorders', ['as' => $a . 'getorders', 'uses' => 'BookingAppointmentsController@getorders']);
	Route::post('appointments/vieworder', ['as' => $a . 'vieworder', 'uses' => 'BookingAppointmentsController@vieworder']);
	Route::post('appointments/getitem', ['as' => $a . 'getitem', 'uses' => 'BookingAppointmentsController@getitem']);
	
	
	//Admin Category Root
	Route::get('/category', ['as' => $a . 'category', 'uses' => 'CategoryController@index']);
	Route::post('category/added', ['as' => $a . 'added', 'uses' => 'CategoryController@added']);
	Route::post('category/updated', ['as' => $a . 'updated', 'uses' => 'CategoryController@updated']);
	Route::post('category/deleted', ['as' => $a . 'deleted', 'uses' => 'CategoryController@deleted']);
	Route::post('category/destroy', ['as' => $a . 'destroy', 'uses' => 'CategoryController@destroy']);	
	Route::post('category/getcategory', ['as' => $a . 'getcategory', 'uses' => 'CategoryController@getcategory']);	
	Route::post('category/get_tags', ['as' => $a . 'get_tags', 'uses' => 'CategoryController@getTags']);	
	Route::post('category/post_tags', ['as' => $a . 'post_tags', 'uses' => 'CategoryController@postTags']);
	
	//Admin Menus Root
	Route::get('/menu', ['as' => $a . 'menu', 'uses' => 'MenuController@index']);
	Route::post('menu/added', ['as' => $a . 'added', 'uses' => 'MenuController@added']);
	Route::post('menu/updated', ['as' => $a . 'updated', 'uses' => 'MenuController@updated']);
	Route::post('menu/deleted', ['as' => $a . 'deleted', 'uses' => 'MenuController@deleted']);
	Route::post('menu/destroy', ['as' => $a . 'destroy', 'uses' => 'MenuController@destroy']);	
	Route::post('menu/getmenu', ['as' => $a . 'getmenu', 'uses' => 'MenuController@getmenu']);
	
	//Admin Pages Root
	Route::get('/page', ['as' => $a . 'page', 'uses' => 'PageController@index']);
	Route::post('page/added', ['as' => $a . 'added', 'uses' => 'PageController@added']);
	Route::post('page/updated', ['as' => $a . 'updated', 'uses' => 'PageController@updated']);
	Route::post('page/deleted', ['as' => $a . 'deleted', 'uses' => 'PageController@deleted']);
	Route::post('page/destroy', ['as' => $a . 'destroy', 'uses' => 'PageController@destroy']);	
	Route::post('page/getpage', ['as' => $a . 'getpage', 'uses' => 'PageController@getpage']);
	
	//Admin Blogs Root
	Route::get('/blog', ['as' => $a . 'blog', 'uses' => 'BlogController@index']);
	Route::post('blog/added', ['as' => $a . 'added', 'uses' => 'BlogController@added']);
	Route::post('blog/updated', ['as' => $a . 'updated', 'uses' => 'BlogController@updated']);
	Route::post('blog/deleted', ['as' => $a . 'deleted', 'uses' => 'BlogController@deleted']);
	Route::post('blog/destroy', ['as' => $a . 'destroy', 'uses' => 'BlogController@destroy']);	
	Route::post('blog/getblogs', ['as' => $a . 'getblogs', 'uses' => 'BlogController@getblogs']);
	
	//Admin News Root
	Route::get('/news', ['as' => $a . 'news', 'uses' => 'NewsController@index']);
	Route::post('news/added', ['as' => $a . 'added', 'uses' => 'NewsController@added']);
	Route::post('news/updated', ['as' => $a . 'updated', 'uses' => 'NewsController@updated']);
	Route::post('news/deleted', ['as' => $a . 'deleted', 'uses' => 'NewsController@deleted']);
	Route::post('news/destroy', ['as' => $a . 'destroy', 'uses' => 'NewsController@destroy']);	
	Route::post('news/getnews', ['as' => $a . 'getnews', 'uses' => 'NewsController@getnews']);
	
	//Admin Testimonials Root
	Route::get('/testimonials', ['as' => $a . 'testimonials', 'uses' => 'TestimonialsController@index']);
	Route::post('testimonials/added', ['as' => $a . 'added', 'uses' => 'TestimonialsController@added']);
	Route::post('testimonials/updated', ['as' => $a . 'updated', 'uses' => 'TestimonialsController@updated']);
	Route::post('testimonials/deleted', ['as' => $a . 'deleted', 'uses' => 'TestimonialsController@deleted']);
	Route::post('testimonials/destroy', ['as' => $a . 'destroy', 'uses' => 'TestimonialsController@destroy']);	
	Route::post('testimonials/gettestimonials', ['as' => $a . 'gettestimonials', 'uses' => 'TestimonialsController@gettestimonials']);
	
	//Admin Merchants Users Root
	Route::get('merchants/users', ['as' => $a . 'users', 'uses' => 'MerchantUserController@users']);
	Route::post('merchants/users/added', ['as' => $a . 'added', 'uses' => 'MerchantUserController@added']);
	Route::post('merchants/users/updated', ['as' => $a . 'updated', 'uses' => 'MerchantUserController@updated']);
	Route::post('merchants/users/deleted', ['as' => $a . 'deleted', 'uses' => 'MerchantUserController@deleted']);
	Route::post('merchants/users/destroy', ['as' => $a . 'destroy', 'uses' => 'MerchantUserController@destroy']);
	Route::post('merchants/users/enable', ['as' => $a . 'enable', 'uses' => 'MerchantUserController@enable']);
	Route::post('merchants/users/disable', ['as' => $a . 'deleted', 'uses' => 'MerchantUserController@disable']);	
	Route::post('merchants/users/getusers', ['as' => $a . 'getusers', 'uses' => 'MerchantUserController@getusers']);	
	Route::get('merchants/deactivate-and-refund/{id}', ['as' => $a . 'deactivate-and-refund', 'uses' => 'MerchantUserController@deactivate_and_refund']);
	
	
	//Admin Merchants Products Root

	Route::get('merchants/products', ['as' => $a . 'products', 'uses' => 'ProductsController@index']);
	Route::post('merchants/products/added', ['as' => $a . 'added', 'uses' => 'ProductsController@added']);
	Route::post('merchants/products/updated', ['as' => $a . 'updated', 'uses' => 'ProductsController@updated']);
	Route::post('merchants/products/deleted', ['as' => $a . 'deleted', 'uses' => 'ProductsController@deleted']);
	Route::post('merchants/products/destroy', ['as' => $a . 'destroy', 'uses' => 'ProductsController@destroy']);
	Route::post('merchants/products/enable', ['as' => $a . 'enable', 'uses' => 'ProductsController@enable']);
	Route::post('merchants/products/disable', ['as' => $a . 'deleted', 'uses' => 'ProductsController@disable']);
	Route::post('merchants/products/getproducts', ['as' => $a . 'getproducts', 'uses' => 'ProductsController@getproducts']);
	Route::post('merchants/products/getsubcategory', ['as' => $a . 'getsubcategory', 'uses' => 'ProductsController@getsubcategory']);
	Route::post('merchants/products/getcategory', ['as' => $a . 'getcategory', 'uses' => 'ProductsController@getcategory']);
	Route::post('merchants/products/getstates', ['as' => $a . 'getstates', 'uses' => 'ProductsController@getstates']);
	Route::post('merchants/products/getcities', ['as' => $a . 'getcities', 'uses' => 'ProductsController@getcities']);
	
	Route::post('merchants/img_save_to_file', ['as' => $a . 'img_save_to_file', 'uses' => 'ProductsController@img_save_to_file']);
	
	Route::post('merchants/img_crop_to_file', ['as' => $a . 'img_crop_to_file', 'uses' => 'ProductsController@img_crop_to_file']);
	Route::post('merchants/img_crop_to_file', ['as' => $a . 'img_crop_to_file', 'uses' => 'ProductsController@img_crop_to_file']);
	

	//Admin Merchants Listing Root
	Route::get('merchants/listing', ['as' => $a . 'listing', 'uses' => 'ListingController@index']);
	Route::post('merchants/listing/added', ['as' => $a . 'added', 'uses' => 'ListingController@added']);
	Route::post('merchants/listing/updated', ['as' => $a . 'updated', 'uses' => 'ListingController@updated']);
	Route::post('merchants/listing/deleted', ['as' => $a . 'deleted', 'uses' => 'ListingController@deleted']);
	Route::post('merchants/listing/destroy', ['as' => $a . 'destroy', 'uses' => 'ListingController@destroy']);
	Route::post('merchants/listing/enable', ['as' => $a . 'enable', 'uses' => 'ListingController@enable']);
	Route::post('merchants/listing/disable', ['as' => $a . 'deleted', 'uses' => 'ListingController@disable']);
	Route::post('merchants/listing/getlisting', ['as' => $a . 'getlisting', 'uses' => 'ListingController@getlisting']);
	Route::post('merchants/listing/getsubcategory', ['as' => $a . 'getsubcategory', 'uses' => 'ListingController@getsubcategory']);
	Route::post('merchants/listing/getstates', ['as' => $a . 'getstates', 'uses' => 'ListingController@getstates']);
	Route::post('merchants/listing/getcities', ['as' => $a . 'getcities', 'uses' => 'ListingController@getcities']);
	
	// Admin Merchants Reviews Roots
	
	Route::get('merchants/reviews', ['as' => $a . 'reviews', 'uses' => 'ReviewsController@index']);
	Route::post('merchants/reviews/reviews', ['as' => $a . 'show', 'uses' => 'ReviewsController@show']);
	//Route::post('merchants/reviews/added', ['as' => $a . 'added', 'uses' => 'ReviewsController@added']);
	Route::post('merchants/reviews/updated', ['as' => $a . 'updated', 'uses' => 'ReviewsController@updated']);
	Route::post('merchants/reviews/enable', ['as' => $a . 'enable', 'uses' => 'ReviewsController@approveReview']);
	Route::post('merchants/reviews/disable', ['as' => $a . 'disable', 'uses' => 'ReviewsController@rejectReview']);
	Route::post('merchants/reviews/deleted', ['as' => $a . 'deleted', 'uses' => 'ReviewsController@deleted']);
	Route::post('merchants/reviews/destroy', ['as' => $a . 'destroy', 'uses' => 'ReviewsController@destroy']);
	
	
	//Admin Merchants Campaigns Root
	Route::get('merchants/campaigns', ['as' => $a . 'campaigns', 'uses' => 'CampaignsController@index']);
	Route::post('merchants/campaigns/added', ['as' => $a . 'added', 'uses' => 'CampaignsController@added']);
	Route::post('merchants/campaigns/updated', ['as' => $a . 'updated', 'uses' => 'CampaignsController@updated']);
	Route::post('merchants/campaigns/deleted', ['as' => $a . 'deleted', 'uses' => 'CampaignsController@deleted']);
	Route::post('merchants/campaigns/destroy', ['as' => $a . 'destroy', 'uses' => 'CampaignsController@destroy']);
	Route::post('merchants/campaigns/enable', ['as' => $a . 'enable', 'uses' => 'CampaignsController@enable']);
	Route::post('merchants/campaigns/disable', ['as' => $a . 'deleted', 'uses' => 'CampaignsController@disable']);
	Route::post('merchants/campaigns/getcampaigns', ['as' => $a . 'getcampaigns', 'uses' => 'CampaignsController@getcampaigns']);
	Route::post('merchants/campaigns/getstates', ['as' => $a . 'getstates', 'uses' => 'CampaignsController@getstates']);
	Route::post('merchants/campaigns/getcities', ['as' => $a . 'getcities', 'uses' => 'CampaignsController@getcities']);
	
	//Admin Merchants Appointments
	Route::post('merchants/appointments/add', ['as' => $a . 'merchants/appointments/add', 'uses' => 'AppointmentBookingController@add']);
	Route::post('merchants/appointments/update', ['as' => $a . 'merchants/appointments/update', 'uses' => 'AppointmentBookingController@update']);
	Route::post('merchants/appointments/destroy_all', ['as' => $a . 'merchants/appointment-booking/destroy_all', 'uses' => 'AppointmentBookingController@destroy_all']);
	Route::get('merchants/allappointments', ['as' => $a . 'merchants/allappointments',   'uses' => 'AppointmentBookingController@getAppointments']);
	Route::post('merchants/appointments/enable',['as' => $a . 'merchants/appointments/enable',   'uses' => 'AppointmentBookingController@enableBooking']);
	Route::post('merchants/appointments/disable',['as' => $a . 'merchants/appointments/disable',   'uses' => 'AppointmentBookingController@DisableBooking']);
	Route::resource('merchants/appointments', 'AppointmentBookingController');
	
	// subscription history
	Route::get('merchants/subscription-history',['as' => $a . 'merchants/subscription-history', 'uses' => 'SubscriptionHistoryController@index']);
	Route::post('merchants/subscription-history/vieworder',['as' => $a . 'merchants/subscription-history/vieworder', 'uses' => 'SubscriptionHistoryController@view_order']);
	
	// transactions
	Route::get('merchants/pending-payments',['as' => $a . 'merchants/pending-payments', 'uses' => 'TransactionController@pending_payments']);
	Route::post('merchants/get-payment-detail',['as' => $a . 'merchants/get-payment-detail', 'uses' => 'TransactionController@get_payment_detail']);
	Route::post('merchants/marketing-user-pay',['as' => $a . 'merchants/marketing-user-pay', 'uses' => 'TransactionController@pay_pending_payment']);
	Route::get('merchants/payment-history',['as' => $a . 'merchants/payment-history', 'uses' => 'TransactionController@payment_history']);
	Route::post('merchants/get-transaction-detail',['as' => $a . 'merchants/get-transaction-detail', 'uses' => 'TransactionController@get_transaction_detail']);
	
	// Online order
	Route::post('food-menu/add', ['as' => $a . 'food-menu/add', 'uses' => 'FoodMenuController@add']);
	Route::post('food-menu/update', ['as' => $a . 'food-menu/update', 'uses' => 'FoodMenuController@update']);
	Route::post('food-menu/destroy_all', ['as' => $a . 'food-menu/destroy_all', 'uses' => 'FoodMenuController@destroy_all']);
	Route::resource('food-menu', 'FoodMenuController');	
	Route::get('menu-items',['as' => $a . 'menu-items', 'uses' => 'OnlineOrderController@menu_items']);
	Route::get('getlisting',['as' => $a . 'menu-items', 'uses' => 'OnlineOrderController@getlisting']);

	//Admin Online order
	Route::post('online-order/add-menu-item', ['as' => $a . 'online-order-menu/add', 'uses' => 'OnlineOrderController@add_menu_item']);
	Route::get('online-order/edit-menu-item/{id}', ['as' => $a . 'online-order/edit-menu-items', 'uses' => 'OnlineOrderController@edit_menu_item']);
	Route::post('online-order/update-menu-item', ['as' => $a . 'online-order/update-menu-items', 'uses' => 'OnlineOrderController@update_menu_item']);
	Route::get('online-order/delete-menu-item/{id}', ['as' => $a . 'online-order/update-menu-items', 'uses' => 'OnlineOrderController@delete_menu_item']);
	Route::post('online-order/delete-all-menu-items', ['as' => $a . 'online-order/delete-all-menu-items', 'uses' => 'OnlineOrderController@menu_item_destroy_all']);

	//admin booking 
	Route::get('online-order/booking-customers', ['as' => $a . 'online-order/booking-customers', 'uses' => 'OnlineOrderController@booking_customers']);
	Route::get('online-order/booking-customers/{id}', ['as' => $a . 'online-order/booking-customers', 'uses' => 'OnlineOrderController@booking_customers_detail']);
	Route::post('online-order/booking-customers/{id}', ['as' => $a . 'online-order/booking-customers', 'uses' => 'OnlineOrderController@update_booking_satus']);

	//Admin Merchants Table Booking
	Route::post('merchants/bookings/add', ['as' => $a . 'bookings/add', 'uses' => 'TableBookingController@add']);
	Route::post('merchants/bookings/update', ['as' => $a . 'bookings/update', 'uses' => 'TableBookingController@update']);
	Route::post('merchants/bookings/destroy_all', ['as' => $a . 'bookings/destroy_all', 'uses' => 'TableBookingController@destroy_all']);
	Route::get('merchants/allbookings', ['as' => $a . 'allbookings',   'uses' => 'TableBookingController@getBookings']);
	Route::post('merchants/bookings/enable',['as' => $a . 'bookings/enable',   'uses' => 'TableBookingController@enableBooking']);
	Route::post('merchants/bookings/disable',['as' => $a . 'bookings/disable',   'uses' => 'TableBookingController@DisableBooking']);
	Route::resource('merchants/bookings', 'TableBookingController');
	
	//Admin General Users Root
	Route::get('general/users', ['as' => $a . 'users', 'uses' => 'GeneralUserController@users']);
	Route::post('general/users/added', ['as' => $a . 'added', 'uses' => 'GeneralUserController@added']);
	Route::post('general/users/updated', ['as' => $a . 'updated', 'uses' => 'GeneralUserController@updated']);
	Route::post('general/users/deleted', ['as' => $a . 'deleted', 'uses' => 'GeneralUserController@deleted']);
	Route::post('general/users/destroy', ['as' => $a . 'destroy', 'uses' => 'GeneralUserController@destroy']);
	Route::post('general/users/enable', ['as' => $a . 'enable', 'uses' => 'GeneralUserController@enable']);
	Route::post('general/users/disable', ['as' => $a . 'deleted', 'uses' => 'GeneralUserController@disable']);	
	Route::post('general/users/getusers', ['as' => $a . 'getusers', 'uses' => 'GeneralUserController@getusers']);
	
	
	//Admin online shop
	Route::get('/addProduct', 'OnlineShopController@addpro_form');
	Route::get('/products', 'OnlineShopController@view_products');
	Route::post('/add_product', 'OnlineShopController@add_product');
	Route::post('/delete-product', 'OnlineShopController@delete_product');
	Route::post('/delete-all-products', 'OnlineShopController@delete_all_products');
	Route::get('/product/delete_image/{id}', 'OnlineShopController@delete_image');
	Route::get('/addCat', 'OnlineShopController@add_cat');
	Route::Post('/catForm', 'OnlineShopController@catForm');
	Route::get('/categories', 'OnlineShopController@view_cats');
	Route::get('/CatEditForm/{id}', 'OnlineShopController@CatEditForm');
	Route::post('/editCat', 'OnlineShopController@editCat');
	Route::get('ProductEditForm/{id}', 'OnlineShopController@ProductEditForm');
	Route::post('editProduct', 'OnlineShopController@editProduct');
	Route::get('EditImage/{id}', 'OnlineShopController@ImageEditForm');
	Route::post('editProImage', 'OnlineShopController@editProImage');
	Route::get('deleteCat/{id}', 'OnlineShopController@deleteCat');
	Route::get('/addProperty/{id}', function($id){
		return view('admin.addProperty')->with('id', $id);
	});
	Route::get('/addPropertyAll', 'OnlineShopController@addPropertyAll');

	Route::post('sumbitProperty','OnlineShopController@sumbitProperty');
	Route::post('editProperty','OnlineShopController@editProperty');
	Route::get('addSale', 'AdminController@addSale');

	Route::get('addAlt/{id}', 'OnlineShopController@addAlt');
	Route::post('submitAlt','OnlineShopController@submitAlt');
	Route::get('/users','AdminController@users');
	Route::get('/updateRole','AdminController@updateRole');

	Route::get('/customer-orders','OnlineShopController@customer_orders');
	Route::get('/customer-orders/{id}', ['as' => $a . 'online-order/booking-customers', 'uses' => 'OnlineShopController@order_customer_detail']);
	Route::post('/customer-orders/{id}', ['as' => $a . 'online-order/booking-customers', 'uses' => 'OnlineShopController@update_booking_satus']);
	
	Route::get('payment-gateway/merchant', ['as' => $a . 'payment-gateway/merchant', 'uses' => 'PaymentGatewayController@merchant']);
	Route::get('payment-gateway/merchant/{id}', ['as' => $a . 'payment-gateway/merchant', 'uses' => 'PaymentGatewayController@merchant_edit']);
	Route::post('payment-gateway/merchant', ['as' => $a . 'payment-gateway/merchant', 'uses' => 'PaymentGatewayController@merchant_update']);
	Route::delete('payment-gateway/merchant/{id}', ['as' => $a . 'payment-gateway/merchant', 'uses' => 'PaymentGatewayController@merchant_destroy']);
	
	Route::get('payment-gateway/subscription', ['as' => $a . 'payment-gateway/subscription', 'uses' => 'PaymentGatewayController@subscription']);
	Route::get('payment-gateway/subscription/{id}', ['as' => $a . 'payment-gateway/subscription', 'uses' => 'PaymentGatewayController@subscription_edit']);
	Route::post('payment-gateway/subscription', ['as' => $a . 'payment-gateway/subscription', 'uses' => 'PaymentGatewayController@subscription_update']);
	Route::delete('payment-gateway/subscription/{id}', ['as' => $a . 'payment-gateway/subscription', 'uses' => 'PaymentGatewayController@subscription_destroy']);
	
	Route::get('payment-gateway/edit-settings/{id}', ['as' => $a . 'payment-gateway/edit_settings', 'uses' => 'PaymentGatewayController@edit_settings']);
	Route::post('payment-gateway/update-settings', ['as' => $a . 'payment-gateway/update_settings', 'uses' => 'PaymentGatewayController@update_settings']);
	
	// local vendor	
	Route::get('local-vendor/users', ['as' => $a . 'local-vendor/users',   'uses' => 'LocalVendorController@localvendorindex']);
	Route::post('local-vendor/users/vendorlisting', ['as' => $a . 'local-vendor/users/vendorlisting',   'uses' => 'LocalVendorController@getvendorlisting']);
	Route::post('local-vendor/users/added', ['as' => $a . 'local-vendor/users/added',   'uses' => 'LocalVendorController@added']);
	Route::post('local-vendor/users/updated', ['as' => $a . 'local-vendor/users/updated',   'uses' => 'LocalVendorController@updated']);
	Route::post('local-vendor/users/enable', ['as' => $a . 'local-vendor/users/enable',   'uses' => 'LocalVendorController@enable']);
	Route::post('local-vendor/users/deleted', ['as' => $a . 'local-vendor/users/deleted',   'uses' => 'LocalVendorController@deleted']);
	Route::post('local-vendor/users/disable', ['as' => $a . 'local-vendor/users/disable',   'uses' => 'LocalVendorController@disable']);
	Route::post('local-vendor/users/destroy', ['as' => $a . 'local-vendor/users/destroy',   'uses' => 'LocalVendorController@destroy']);
	
	Route::get('local-vendor/qr-code', ['as' => $a . 'local-vendor/qr-code',   'uses' => 'LocalVendorController@qr_code']);
	Route::post('local-vendor/qr-code-create', ['as' => $a . 'local-vendor/qr-code-create',   'uses' => 'LocalVendorController@qr_code_create']);
	Route::post('local-vendor/qr-code-status-change', ['as' => $a . 'local-vendor/qr-code-status-change',   'uses' => 'LocalVendorController@qr_code_status_change']);
	Route::post('local-vendor/qr-code-print', ['as' => $a . 'local-vendor/qr-code-print',   'uses' => 'LocalVendorController@qr_code_print']);
	Route::delete('local-vendor/marketing-user-delete/{id}', ['as' => $a . 'local-vendor/marketing-user-delete',   'uses' => 'LocalVendorController@marketing_user_delete']);
	Route::post('local-vendor/marketing-users/destroy', ['as' => $a . 'local-vendor/marketing-users/destroy',   'uses' => 'LocalVendorController@marketing_users_destroy']);
	Route::get('local-vendor/marketing-users', ['as' => $a . 'local-vendor/marketing-users',   'uses' => 'LocalVendorController@marketing_users']);
	Route::post('local-vendor/get-marketing-user', ['as' => $a . 'local-vendor/get-marketing-user',   'uses' => 'LocalVendorController@get_marketing_user']);
	Route::post('local-vendor/marketing-user-update', ['as' => $a . 'local-vendor/marketing-user-update',   'uses' => 'LocalVendorController@marketing_user_update']);
	Route::delete('local-vendor/marketing-user-detach/{id}', ['as' => $a . 'local-vendor/marketing-user-detach',   'uses' => 'LocalVendorController@marketing_user_detach']);

});

Route::group(['prefix' => 'user', 'middleware' => 'auth:all'], function()
{
	$a = 'user.';
	Route::get('/', ['as' => $a . 'home', 'uses' => 'UserController@getHome']);
	Route::get('/profile', ['as' => $a . 'profile', 'uses' => 'UserController@getProfile']);
	Route::post('/update-profile', ['as' => $a . 'update-profile', 'uses' => 'UserController@update']);
	Route::get('/change-password', ['as' => $a . 'change-password', 'uses' => 'UserController@changePassword']);
	Route::post('/change-password', ['as' => $a . 'change-password', 'uses' => 'UserController@storeChangePassword']);
	Route::get('/appointment-booking', ['as' => $a . 'appointment-booking', 'uses' => 'UserController@appointmentBooking']);
	Route::get('/table-booking', ['as' => $a . 'table-booking', 'uses' => 'UserController@tableBooking']);
	Route::get('/online-orders', ['as' => $a . 'online-orders', 'uses' => 'UserController@onlineOrder']);
	Route::get('/online-order-detail/{id}', ['as' => $a . 'online-order-detail', 'uses' => 'UserController@onlineOrderDetail']);
	Route::get('/online-shoppings', ['as' => $a . 'online-shoppings', 'uses' => 'UserController@onlineShopping']);
	Route::get('/online-shopping-detail/{id}', ['as' => $a . 'online-order-detail', 'uses' => 'UserController@onlineShoppingDetail']);
	Route::post('/saveContact',['as' => $a . 'contact', 'uses' => 'PagesController@saveContact']);
	Route::get('/myContacts',['as' => $a . 'contact', 'uses' => 'UserController@myContact']);
	Route::get('/removecontact/{id}',['as' => $a . 'contact', 'uses' => 'UserController@removecontact']);
	Route::get('/myCampaigns',['as' => $a . 'contact', 'uses' => 'UserController@myCampaigns']);
	Route::group(['middleware' => 'activated'], function ()
	{
		$m = 'activated.';
		Route::get('protected', ['as' => $m . 'protected', 'uses' => 'UserController@getProtected']);
	});

});

Route::group(['prefix' => 'merchant', 'middleware' => 'auth:merchant'], function()
{
	$a = 'merchant.';
	Route::get('/', ['as' => $a . 'home', 'uses' => 'MerchantController@getHome']);
	Route::get('/profile', ['as' => $a . 'profile', 'uses' => 'MerchantController@profile']);
	Route::post('/profile', ['as' => $a . 'profile', 'uses' => 'MerchantController@profile']);
	Route::get('/change-password', ['as' => $a . 'change-password', 'uses' => 'MerchantController@changePassword']);
	Route::post('/change-password', ['as' => $a . 'change-password', 'uses' => 'MerchantController@storeChangePassword']);
	Route::post('/get_order_graph', ['as' => $a . 'get_order_graph', 'uses' => 'MerchantController@getOrderGraph']);
	Route::get('/notification',['as'=>$a.'notification','uses'=>'MerchantController@getNotification']);
	Route::get('/read_status',['as'=>$a.'notification','uses'=>'MerchantController@changeStatus']);
	Route::get('/allnotifications',['as'=>$a.'notification','uses'=>'MerchantController@allNotifications']);
	Route::get('/all_notification',['as'=>$a.'notification','uses'=>'MerchantController@all_Notification']);
	//merchant mobileno Verification Route
	//Route::get('verify', ['as' => $a . 'verify', 'uses' => 'Merchant\ListingController@merchantProfile']);
	Route::post('getmobile', ['as' => $a . 'mobile', 'uses' => 'Merchant\ListingController@getMerchantData']);
	Route::post('updateOtp',['as' => $a . 'update', 'uses' => 'Merchant\ListingController@updateMerchantOtp']);
	Route::post('updateValid',['as' => $a . 'update', 'uses' => 'Merchant\ListingController@updateMerchantValid']);
	Route::post('checkOtp',['as' => $a . 'update', 'uses' => 'Merchant\ListingController@CheckValidOtp']);
//Current location of user
	

	//Merchants Listing Root
	Route::get('listing', ['as' => $a . 'listing', 'uses' => 'Merchant\ListingController@index']);
	Route::post('listing/added', ['as' => $a . 'added', 'uses' => 'Merchant\ListingController@added']);
	Route::post('listing/updated', ['as' => $a . 'updated', 'uses' => 'Merchant\ListingController@updated']);
	Route::post('listing/deleted', ['as' => $a . 'deleted', 'uses' => 'Merchant\ListingController@deleted']);
	Route::post('listing/destroy', ['as' => $a . 'destroy', 'uses' => 'Merchant\ListingController@destroy']);
	Route::post('listing/enable', ['as' => $a . 'enable', 'uses' => 'Merchant\ListingController@enable']);
	Route::post('listing/disable', ['as' => $a . 'deleted', 'uses' => 'Merchant\ListingController@disable']);
	Route::post('listing/getlisting', ['as' => $a . 'getlisting', 'uses' => 'Merchant\ListingController@getlisting']);
	Route::post('listing/getsubcategory', ['as' => $a . 'getsubcategory', 'uses' => 'Merchant\ListingController@getsubcategory']);
	Route::post('listing/getstates', ['as' => $a . 'getstates', 'uses' => 'Merchant\ListingController@getstates']);
	Route::post('listing/getcities', ['as' => $a . 'getcities', 'uses' => 'Merchant\ListingController@getcities']);
	
	// add listing
	/*Route::get('/add-listing', ['as' => $a . 'add-listing',   'uses' => 'Merchant\ListingController@addListing']);
	Route::post('/add-listing', ['as' => $a . 'add-listing',   'uses' => 'Merchant\ListingController@postListing']);*/
	Route::post('/listing/image_upload', ['as' => $a . 'listing/image_upload',   'uses' => 'Merchant\ListingController@imageUpload']);
	Route::post('/listing/get_uploaded_images', ['as' => $a . 'listing/get_uploaded_images',   'uses' => 'Merchant\ListingController@getUploadedImages']);
	Route::get('/listing/delete_image/{id}', ['as' => $a . 'listing/delete_image',   'uses' => 'Merchant\ListingController@deleteUploadedImage']);
	Route::get('/listing/delete_image_table/{id}', ['as' => $a . 'listing/delete_image_table',   'uses' => 'Merchant\ListingController@deleteUploadedImageFromTable']);

	// Adding Gallery
	Route::post('/gallery/image_upload', ['as' => $a . 'gallery/image_upload',   'uses' => 'Merchant\GalleryController@imageUpload']);
	Route::post('/gallery/get_uploaded_images', ['as' => $a . 'gallery/get_uploaded_images',   'uses' => 'Merchant\GalleryController@getUploadedImages']);
	Route::get('/gallery/delete_image/{id}', ['as' => $a . 'gallery/delete_image',   'uses' => 'Merchant\GalleryController@deleteUploadedImage']);

	
	//Admin Merchants Campaigns Root
	Route::get('campaigns', ['as' => $a . 'campaigns', 'uses' => 'Merchant\CampaignsController@index']);
	Route::post('campaigns/added', ['as' => $a . 'added', 'uses' => 'Merchant\CampaignsController@added']);
	Route::post('campaigns/updated', ['as' => $a . 'updated', 'uses' => 'Merchant\CampaignsController@updated']);
	Route::post('campaigns/deleted', ['as' => $a . 'deleted', 'uses' => 'Merchant\CampaignsController@deleted']);
	Route::post('campaigns/destroy', ['as' => $a . 'destroy', 'uses' => 'Merchant\CampaignsController@destroy']);
	Route::post('campaigns/enable', ['as' => $a . 'enable', 'uses' => 'Merchant\CampaignsController@enable']);
	Route::post('campaigns/disable', ['as' => $a . 'deleted', 'uses' => 'Merchant\CampaignsController@disable']);
	Route::post('campaigns/getcampaigns', ['as' => $a . 'getcampaigns', 'uses' => 'Merchant\CampaignsController@getcampaigns']);
	Route::post('campaigns/getstates', ['as' => $a . 'getstates', 'uses' => 'Merchant\CampaignsController@getstates']);
	Route::post('campaigns/getcities', ['as' => $a . 'getcities', 'uses' => 'Merchant\CampaignsController@getcities']);
	
	//Service Booking
    Route::get('service-booking/enable-service', ['as' => $a . 'service-booking/enable-service', 'uses' => 'Merchant\ServiceBookingController@enable']);
	Route::get('service-booking/disable-service', ['as' => $a . 'service-booking/disable-service', 'uses' => 'Merchant\ServiceBookingController@disable']);
	Route::get('service-booking', ['as' => $a . 'service-booking', 'uses' => 'Merchant\ServiceBookingController@settings']);
	Route::post('service-booking/add', ['as' => $a . 'service-booking/add', 'uses' => 'Merchant\ServiceBookingController@add']);	
	Route::post('service-booking/update', ['as' => $a . 'service-booking/update', 'uses' => 'Merchant\ServiceBookingController@update']);
	Route::post('service-booking/destroy_all', ['as' => $a . 'service-booking/destroy_all', 'uses' => 'Merchant\ServiceBookingController@destroy_all']);	
	Route::post('service-booking/enable-order',['as' => $a . 'service-booking/enable-order', 'uses' => 'Merchant\ServiceBookingController@enableBooking']);
	Route::post('service-booking/disable-order',['as' => $a . 'service-booking/disable-order', 'uses' => 'Merchant\ServiceBookingController@DisableBooking']);
	/*Route::group(['middleware' => 'activated'], function ()
	{
		$m = 'activated.';
		Route::get('protected', ['as' => $m . 'protected', 'uses' => 'MerchantController@getProtected']);
	});*/
	
	// orders
	Route::get('orders', 'Merchant\OrdersController@index');
	Route::get('orders/pending', 'Merchant\OrdersController@pending');
	Route::get('orders/history', 'Merchant\OrdersController@history');
	Route::post('orders/shop_order_detail', 'Merchant\OrdersController@shop_order_detail');
	Route::post('orders/food_order_detail', 'Merchant\OrdersController@food_order_detail');	
	Route::post('orders/table_order_detail', 'Merchant\OrdersController@table_order_detail');	
	Route::post('orders/appointment_order_detail', 'Merchant\OrdersController@appointment_order_detail');
	Route::get('orders/food_invoice/{id}', 'Merchant\OrdersController@food_invoice');
	Route::get('orders/shop_invoice/{id}', 'Merchant\OrdersController@shop_invoice');	
	
	Route::post('services/enable', ['as' => $a . 'get_languages', 'uses' => 'Merchant\MerchantServicesController@enable']);
	Route::post('services/disable', ['as' => $a . 'disable', 'uses' => 'Merchant\MerchantServicesController@disable']);
	Route::resource('services', 'Merchant\MerchantServicesController');
	
	
	
	Route::get('appointment-booking/settings', 'Merchant\AppointmentBookingController@settings');
	Route::get('appointment-booking/enable-service', 'Merchant\AppointmentBookingController@enable');
	Route::get('appointment-booking/disable-service', 'Merchant\AppointmentBookingController@disable');
	Route::post('appointment-booking/add', ['as' => $a . 'appointment-booking/add', 'uses' => 'Merchant\AppointmentBookingController@add']);
	Route::post('appointment-booking/update', ['as' => $a . 'appointment-booking/update', 'uses' => 'Merchant\AppointmentBookingController@update']);
	Route::post('appointment-booking/destroy_all', ['as' => $a . 'appointment-booking/destroy_all', 'uses' => 'Merchant\AppointmentBookingController@destroy_all']);
	//Route::resource('appointment-booking', 'Merchant\AppointmentBookingController');
	
	Route::post('appointment-booking/enable-order',['uses' => 'Merchant\AppointmentBookingController@enableBooking']);
	Route::post('appointment-booking/disable-order',['uses' => 'Merchant\AppointmentBookingController@DisableBooking']);

	
	//Route::resource('table-booking', 'Merchant\TableBookingController');
	Route::get('table-booking/settings', 'Merchant\TableBookingController@settings');
	Route::get('table-booking/enable-service', 'Merchant\TableBookingController@enable');
	Route::get('table-booking/disable-service', 'Merchant\TableBookingController@disable');
	Route::post('table-booking/add', ['as' => $a . 'table-booking/add', 'uses' => 'Merchant\TableBookingController@add']);	
	Route::post('table-booking/update', ['as' => $a . 'table-booking/update', 'uses' => 'Merchant\TableBookingController@update']);
	Route::post('table-booking/destroy_all', ['as' => $a . 'table-booking/destroy_all', 'uses' => 'Merchant\TableBookingController@destroy_all']);
	
	Route::post('table-booking/enable-order',['uses' => 'Merchant\TableBookingController@enableBooking']);
	Route::post('table-booking/disable-order',['uses' => 'Merchant\TableBookingController@DisableBooking']);
	
	/*** online order ***/
	Route::get('online-order/enable-service', 'Merchant\OnlineOrderController@enable');
	Route::get('online-order/disable-service', 'Merchant\OnlineOrderController@disable');
	// menu
	Route::get('online-order/menu-lists', ['as' => $a . 'online-order/add', 'uses' => 'Merchant\OnlineOrderController@menu_lists']);
	Route::get('online-order/add-menu', ['as' => $a . 'online-order/add-menu', 'uses' => 'Merchant\OnlineOrderController@add_menu']);
	Route::post('online-order/post-menu', ['as' => $a . 'online-order/post-menu', 'uses' => 'Merchant\OnlineOrderController@post_menu']);	
	
	// menu item
	Route::get('online-order/menu-items', ['as' => $a . 'online-order/menu-items', 'uses' => 'Merchant\OnlineOrderController@menu_items']);
	Route::post('online-order/add-menu-item', ['as' => $a . 'online-order/add-menu-items', 'uses' => 'Merchant\OnlineOrderController@add_menu_item']);
	Route::post('online-order/add-delevery-fee', ['as' => $a . 'online-order/add-delevery-fee', 'uses' => 'Merchant\OnlineOrderController@add_delevery_fee']);
	
	Route::get('online-order/edit-menu-item/{id}', ['as' => $a . 'online-order/edit-menu-items', 'uses' => 'Merchant\OnlineOrderController@edit_menu_item']);
	Route::post('online-order/update-menu-item', ['as' => $a . 'online-order/update-menu-items', 'uses' => 'Merchant\OnlineOrderController@update_menu_item']);
	Route::get('online-order/status-menu-item/{id}', ['as' => $a . 'online-order/status-menu-item', 'uses' => 'Merchant\OnlineOrderController@status_menu_item']);
	
	Route::get('online-order/delete-menu-item/{id}', ['as' => $a . 'online-order/update-menu-items', 'uses' => 'Merchant\OnlineOrderController@delete_menu_item']);
	// booking
	Route::get('online-order/booking-customers', ['as' => $a . 'online-order/booking-customers', 'uses' => 'Merchant\OnlineOrderController@booking_customers']);
	Route::get('online-order/booking-customers/{id}', ['as' => $a . 'online-order/booking-customers', 'uses' => 'Merchant\OnlineOrderController@booking_customers_detail']);
	Route::post('online-order/update-status/{id}', ['as' => $a . 'online-order/update-status', 'uses' => 'Merchant\OnlineOrderController@update_booking_satus']);
	Route::get('online-order/settings', ['as' => $a . 'online-order/settings', 'uses' => 'Merchant\OnlineOrderController@settings']);
	Route::post('online-order/settings', ['as' => $a . 'online-order/settings', 'uses' => 'Merchant\OnlineOrderController@update_settings']);
	
	Route::post('online-order/add', ['as' => $a . 'online-order/add', 'uses' => 'Merchant\OnlineOrderController@add']);
	Route::post('online-order/update', ['as' => $a . 'online-order/update', 'uses' => 'Merchant\OnlineOrderController@update']);
	Route::post('online-order/destroy_all', ['as' => $a . 'online-order/destroy_all', 'uses' => 'Merchant\OnlineOrderController@destroy_all']);
	//Route::resource('online-order', 'Merchant\OnlineOrderController');
	
	Route::post('payment-settings/add', ['as' => $a . 'payment-settings/add', 'uses' => 'Merchant\PaymentSettingsController@add']);
	Route::get('payment-settings', ['as' => $a . 'payment-settings', 'uses' => 'Merchant\PaymentSettingsController@index']);


	//Online Shopping
	Route::get('online-shop/products', 'Merchant\OnlineShopController@view_products');
	Route::post('online-shop/delete-product', 'Merchant\OnlineShopController@delete_product');
	Route::get('online-shop/enable-service', 'Merchant\OnlineShopController@enable');
	Route::get('online-shop/disable-service', 'Merchant\OnlineShopController@disable');
	//Route::get('online-shop/addProduct', 'Merchant\OnlineShopController@addpro_form');
	Route::post('/add_product', 'Merchant\OnlineShopController@add_product');
	
	Route::get('online-shop/edit-online-shop/{id}', ['as' => $a . 'online-shop/edit-online-shop', 'uses' => 'Merchant\OnlineShopController@edit_online_shop']);
	Route::post('online-shop/update-online-shop', ['as' => $a . 'online-order/update-online-shop', 'uses' => 'Merchant\OnlineShopController@editProduct']);
	Route::get('online-shop/status-online-shop/{id}', ['as' => $a . 'online-shop/status-online-shop', 'uses' => 'Merchant\OnlineShopController@status_online_shop']);	
	Route::get('online-shop/delete-online-shop/{id}', ['as' => $a . 'online-shop/delete-online-shop', 'uses' => 'Merchant\OnlineShopController@delete_menu_item']);
	Route::get('online-shop/delete_image/{id}', ['as' => $a . 'listing/delete_image',   'uses' => 'Merchant\OnlineShopController@deleteUploadedImage']);
	Route::get('online-shop/delete_image_table/{id}', ['as' => $a . 'listing/delete_image_table',   'uses' => 'Merchant\OnlineShopController@deleteUploadedImageFromTable']);
	
	Route::get('gallery', ['as' => $a . 'gallery',   'uses' => 'Merchant\GalleryController@index']);
	//Route::get('online-shop/addCat', 'Merchant\OnlineShopController@add_cat');
	//Route::Post('/catForm', 'Merchant\OnlineShopController@catForm');
	//Route::get('online-shop/categories', 'Merchant\OnlineShopController@view_cats');
	//Route::get('online-shop/CatEditForm/{id}', 'Merchant\OnlineShopController@CatEditForm');
	//Route::post('/editCat', 'Merchant\OnlineShopController@editCat');
	//Route::get('online-shopProductEditForm/{id}', 'Merchant\OnlineShopController@ProductEditForm');
	Route::post('editProduct', 'Merchant\OnlineShopController@editProduct');
	Route::get('online-shopEditImage/{id}', 'Merchant\OnlineShopController@ImageEditForm');
	Route::post('editProImage', 'Merchant\OnlineShopController@editProImage');
	//Route::get('online-shopdeleteCat/{id}', 'Merchant\OnlineShopController@deleteCat');
	//Route::get('online-shop/addProperty/{id}', function($id){
		//return view('admin.addProperty')->with('id', $id);
	//});
	//Route::get('online-shop/addPropertyAll', 'Merchant\OnlineShopController@addPropertyAll');
	//Route::post('sumbitProperty','Merchant\OnlineShopController@sumbitProperty');
	//Route::post('editProperty','Merchant\OnlineShopController@editProperty');
	//Route::get('addSale', 'AdminController@addSale');
	//Route::get('addAlt/{id}', 'Merchant\OnlineShopController@addAlt');
	//Route::post('submitAlt','Merchant\OnlineShopController@submitAlt');
	Route::get('/customer-orders','Merchant\OnlineShopController@customer_orders');
	Route::get('/customer-orders/{id}', ['as' => $a . 'online-order/booking-customers', 'uses' => 'Merchant\OnlineShopController@order_customer_detail']);
	//Route::post('/customer-orders/{id}', ['as' => $a . 'online-order/booking-customers', 'uses' => 'Merchant\OnlineShopController@update_booking_satus']);

    //import products
	//Route::post('import_products','AdminController@import_products');
	//Route::post('/changeImage','MerchantController@changeImage');
	//Route::post('/changeInfo','MerchantController@changeListingInfo');
	
	// subscription history
	Route::get('subscription-history',['as' => $a . 'subscription-history', 'uses' => 'Merchant\SubscriptionHistoryController@index']);
	Route::post('subscription-history/vieworder',['as' => $a . 'subscription-history/vieworder', 'uses' => 'Merchant\SubscriptionHistoryController@view_order']);
	
	// change plan
	Route::get('change-subscription-plan',['as' => $a . 'change-subscription-plan', 'uses' => 'Merchant\SubscriptionController@change_plan']);
	Route::post('change-subscription-plan-confirm',['as' => $a . 'change-subscription-plan-confirm', 'uses' => 'Merchant\SubscriptionController@change_subscription_plan_confirm']);
	Route::post('change_subscription_plan',['as' => $a . 'change_subscription_plan', 'uses' => 'Merchant\SubscriptionController@change_subscription_plan']);
	
	// payment
	Route::get('subscription/payby-razor/{encrypt_order_id}', ['as' => $a . 'subscription/payby-razor',   'uses' => 'Merchant\SubscriptionController@payby_razor']);
	Route::post('subscription/payment-response', ['as' => $a . 'subscription/payment-response',   'uses' => 'Merchant\SubscriptionController@payment_response']);
	Route::get('subscription/complete/{encrypt_order_id}',['as' => $a . 'subscription/complete', 'uses' => 'Merchant\SubscriptionController@complete_subscription']);
	
	// renew plan
	//Route::post('renew-subscription',['as' => $a . 'renew-subscription', 'uses' => 'Merchant\SubscriptionController@renew_subscription']);
	//Route::get('subscription/payby-ebs/{encrypt_subscription_id}',['as' => $a . 'subscription/payby-ebs', 'uses' => 'Merchant\SubscriptionController@payby_ebs']);
	//Route::post('subscription/ebs-thankyou',['as' => $a . 'subscription/ebs-thankyou', 'uses' => 'Merchant\SubscriptionController@ebs_thankyou']);
	//Route::get('complete-subscription',['as' => $a . 'complete-subscription', 'uses' => 'Merchant\SubscriptionController@complete_subscription']);
	//Route::post('subscription/ebs-thankyou',['as' => $a . 'subscription/ebs-thankyou', 'uses' => 'Merchant\SubscriptionController@ebs_thankyou']);
	//Route::post('complete-subscription-process',['as' => $a . 'complete-subscription-process', 'uses' => 'Merchant\SubscriptionController@complete_subscription_process']);

	//Merchant Reviews
	//Route::get('allreviews', ['uses' => 'ReviewsController@getReviews']);
	//Route::post('reviews/approve',['uses' => 'ReviewsController@approveReview']);
	//Route::post('reviews/reject',['uses' => 'ReviewsController@rejectReview']);

	//Merchant Book a table
	//Route::get('allbookings', ['uses' => 'Merchant\TableBookingController@getBookings']);

	//Merchant Book Appointment
	//Route::get('allappointments', ['uses' => 'Merchant\AppointmentBookingController@getAppointments']);
	
});

Route::group(['middleware' => 'auth:all'], function()
{
	$a = 'authenticated.';
	Route::get('/logout', ['as' => $a . 'logout', 'uses' => 'Auth\LoginController@logout']);
	Route::get('/activate/{token}', ['as' => $a . 'activate', 'uses' => 'ActivateController@activate']);
	Route::get('/activate', ['as' => $a . 'activation-resend', 'uses' => 'ActivateController@resend']);
	Route::get('not-activated', ['as' => 'not-activated', 'uses' => function () {
		return view('errors.not-activated');
	}]);
	Route::get('/campaign', ['as' => $a . 'campaign', 'uses' => 'CampaignController@index']);
	Route::get('/campaign/subcategory', ['as' => $a . 'campaign', 'uses' => 'CampaignController@getsubcategory']);
	Route::get('/campaign/cities', ['as' => $a . 'campaign', 'uses' => 'CampaignController@getCities']);
	Route::get('/campaign/price', ['as' => $a . 'campaign', 'uses' => 'CampaignController@getprice']);
	Route::POST('/campaign/Create', ['as' => $a . 'campaign', 'uses' => 'CampaignController@createCampaign']);
	Route::post('/campaign/moredata', ['as' => $a . 'campaign', 'uses' => 'CampaignController@moreDetails']);
	Route::get('/campaign/payment', ['as' => $a . 'campaign', 'uses' => 'CampaignController@getPayment']);
	Route::post('/campaign/checkout', ['as' => $a . 'campaign', 'uses' => 'CampaignController@checkout']);
	Route::get('/campaign/payby-paypal/{encrypt_order_id}', ['as' => $a . 'online-order/payby-paypal',   'uses' => 'CampaignController@payby_paypal']);
	Route::post('/campaign/paypal-success', ['as' => $a . 'campaign/paypal-success',   'uses' => 'CampaignController@paypal_success']);
	Route::get('/campaign/paypal-cancel', ['as' => $a . 'campaign/paypal-cancel',   'uses' => 'CampaignController@paypal_cancel']);
	Route::post('/campaign/paypal-ipn', ['as' => $a . 'campaign/paypal-ipn',   'uses' => 'CampaignController@paypal_ipn']);
	Route::get('/campaign/payby-ebs/{encrypt_order_id}', ['as' => $a . 'payby-ebs',   'uses' => 'CampaignController@payby_ebs']);
	Route::get('/campaign/ebs-thankyou', ['as' => $a. 'ebs-thankyou',   'uses' => 'CampaignController@ebs_thankyou']);
	Route::post('/campaign/ebs-thankyou', ['as' => $a . 'ebs-thankyou',   'uses' => 'CampaignController@ebs_thankyou']);
});

Auth::routes(['login' => 'auth.login']);
Route::get('login-redirect', ['uses' => 'Auth\LoginController@showLoginFormRedirect']);

//WEB SERVICES

Route::get('/runschedule', function () {
    Artisan::call('schedule:run');
	echo 'Cron completed';
});

//Clear Cache facade value:
Route::get('/clear-cache', function() {
	$exitCode = Artisan::call('cache:clear');
	return '<h1>Cache facade value cleared</h1>';
});

//Reoptimized class loader:
Route::get('/optimize', function() {
	$exitCode = Artisan::call('optimize');
	return '<h1>Reoptimized class loader</h1>';
});

//Clear Route cache:
Route::get('/route-cache', function() {
	$exitCode = Artisan::call('route:cache');
	return '<h1>Route cache cleared</h1>';
});

//Clear View cache:
Route::get('/view-clear', function() {
	$exitCode = Artisan::call('view:clear');
	return '<h1>View cache cleared</h1>';
});

//Clear Config cache:
Route::get('/config-cache', function() {
	$exitCode = Artisan::call('config:cache');
	return '<h1>Clear Config cleared</h1>';
});


//Route::get('page-not-found',['as'=>'page-not-found','uses'=>'ErrorHandlerController@page_not_found']);

Route::get('/{slug}', ['as' => $s . 'home_view',   'uses' => 'PagesController@listingDetail']);
Route::post('/review',['as' => $s . 'review',   'uses' => 'ReviewsController@listingReview']);
Route::post('/product-review',['as' => $s . 'product-review',   'uses' => 'ProductreviewsController@productReview']);
Route::get('/product_details/{id}', ['as' => $s . 'product_details',   'uses' => 'OnlineShopController@product_details']);
Route::post('/productbyprice', ['as' => $s . 'productbyprice',   'uses' => 'OnlineShopController@productByPrice']);
Route::post('/productbybrand',['as' => $s . 'productbybrand',   'uses' => 'OnlineShopController@productByBrand']);
Route::post('addReview', ['as' => $s . 'addReview',   'uses' => 'OnlineShopController@addReview']);

/*wishlist*/
Route::post('/addToWishList', ['as' => $s . 'addToWishList',   'uses' => 'OnlineShopController@wishList']);
Route::get('/wishList/list', ['as' => $s . 'wishList',   'uses' => 'OnlineShopController@View_wishList']);
Route::get('/removeWishList/{id}', ['as' => $s . 'removeWishList',   'uses' => 'OnlineShopController@removeWishList']);


/* shopping cart */
Route::get('/cart', ['as' => $s . 'cart',   'uses' => 'OnlineShopController@index']);
Route::get('/cart/addItem/{id}', ['as' => $s . 'cart/addItem',   'uses' => 'OnlineShopController@addItem']);
Route::get('/cart/remove/{id}', ['as' => $s . 'cart/remove',   'uses' => 'OnlineOrderController@destroy']);
Route::get('/cart/update/{id}', ['as' => $s . 'cart/update',   'uses' => 'OnlineOrderController@update']);

//Checkout Route
Route::get('/checkout/items', 'CheckoutController@index');
Route::post('/formvalidate/items', 'CheckoutController@formvalidate');

Route::get('/checkout/thankyou', function() {
	return view('pages.thankyou');
});
