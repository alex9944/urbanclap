<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
/*
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');
*/
Route::group(['prefix' => 'merchant', 'middleware' => 'api'], function()
{
	$a = 'merchant.';	
	Route::post('signup',['as' => $a . 'signup', 'uses' => 'Auth\RegisterController@api_register']);
    Route::post('signup_verify',['as' => $a . 'signup_verify', 'uses' => 'Auth\RegisterController@api_signup_verify']);
	Route::post('forget_password',['as' => $a . 'forget_password', 'uses' => 'Auth\ForgotPasswordController@api_forget_password']);
	Route::post('forget_password_verify',['as' => $a . 'forget_password_verify', 'uses' => 'Auth\ForgotPasswordController@api_forget_password_verify']);
	Route::post('reset_password',['as' => $a . 'reset_password', 'uses' => 'Auth\ResetPasswordController@api_reset_password']);
	Route::post('mobilelogin',['as' => $a . 'mobilelogin', 'uses' => 'ApiLoginController@login']);	
	Route::get('category',['as' => $a . 'category', 'uses' => 'ApiCategoryController@index']);
	
	//Listing
	Route::get('states',['as' => $a . 'states', 'uses' => 'ApiOnlineOrderController@get_states']);
	Route::get('cities/{id}',['as' => $a . 'cities', 'uses' => 'ApiOnlineOrderController@get_cities']);	
	Route::post('add-listing',['as' => $a . 'add-listing', 'uses' => 'Merchant\ApiListingController@added']);
	Route::post('edit-listing',['as' => $a . 'edit-listing', 'uses' => 'Merchant\ApiListingController@updated']);
	Route::post('listing-upload-image-android',['as' => $a . 'listing-upload-image-android', 'uses' => 'Merchant\ApiListingController@listing_upload_image_android']);
	Route::post('listing-upload-image-ios',['as' => $a . 'listing-upload-image-ios', 'uses' => 'Merchant\ApiListingController@listing_upload_image_ios']);
	
	//Online Shop
	Route::get('online-shop/categories',['as' => $a . 'categories', 'uses' => 'Merchant\ApiOnlineShopController@shop_category']);
	Route::get('online-shop/{user_id}',['as' => $a . 'online-shop', 'uses' => 'Merchant\ApiOnlineShopController@view_products']);	
	Route::get('online-shop/enable-service/{user_id}',['as' => $a . 'enable-service', 'uses' => 'Merchant\ApiOnlineShopController@enable']);
	Route::get('online-shop/disable-service/{user_id}',['as' => $a . 'disable-service', 'uses' => 'Merchant\ApiOnlineShopController@disable']);
	Route::get('online-shop/status-online-shop/{id}', ['as' => $a . 'online-shop/status-online-shop', 'uses' => 'Merchant\ApiOnlineShopController@status_online_shop']);//id  Product id	
	Route::get('online-shop/edit-online-shop/{id}', ['as' => $a . 'online-shop/edit-online-shop', 'uses' => 'Merchant\ApiOnlineShopController@edit_online_shop']);//id Product id	 
	Route::post('online-shop/add-product-android',['as' => $a . 'add-product-android', 'uses' => 'Merchant\ApiOnlineShopController@add_product_android']);//user_id,pro_img(file),pro_name,cat_id,pro_code,pro_price,pro_info,spl_price,stock,shipping_price
	Route::post('online-shop/add-product-android-images',['as' => $a . 'add-product-android-images', 'uses' => 'Merchant\ApiOnlineShopController@add_product_android_images']);//id,photo(file)
	Route::post('online-shop/add-product-ios',['as' => $a . 'add-product-ios', 'uses' => 'Merchant\ApiOnlineShopController@add_product_ios']);//user_id,pro_img(base64),pro_name,cat_id,pro_code,pro_price,pro_info,spl_price,stock,shipping_price
	Route::post('online-shop/add-product-ios-images',['as' => $a . 'add-product-ios-images', 'uses' => 'Merchant\ApiOnlineShopController@add_product_ios_images']);//id,photo(base64)
	Route::get('online-shop/delete-online-shop/{id}', ['as' => $a . 'online-shop/delete-online-shop',   'uses' => 'Merchant\ApiOnlineShopController@deleteOnlinshop']);//Id
	Route::get('online-shop/delete-image-table/{id}', ['as' => $a . 'online-shop/delete-image-table',   'uses' => 'Merchant\ApiOnlineShopController@deleteUploadedImageFromTable']);//Id
	Route::post('online-shop/update-product-android',['as' => $a . 'update-product-android', 'uses' => 'Merchant\ApiOnlineShopController@editProductAndroid']);//id,pro_img(file),pro_name,cat_id,pro_code,pro_price,pro_info,spl_price,stock,shipping_price
	Route::post('online-shop/update-product-ios',['as' => $a . 'update-product-ios', 'uses' => 'Merchant\ApiOnlineShopController@editProductIos']);//id,pro_img(base64),pro_name,cat_id,pro_code,pro_price,pro_info,spl_price,stock,shipping_price

	//Appointment Booking
	Route::get('appointment-booking/enable-service/{user_id}', 'Merchant\ApiAppointmentBookingController@enable');
	Route::get('appointment-booking/disable-service/{user_id}', 'Merchant\ApiAppointmentBookingController@disable');
	Route::post('appointment-booking/add',['as' => $a . 'appointment-booking/add', 'uses' => 'Merchant\ApiAppointmentBookingController@add']);//user_id,listing_id,start_time,start_time_ar,end_time,end_time_ar,time_interval
	Route::post('appointment-booking/update',['as' => $a . 'appointment-booking/update', 'uses' => 'Merchant\ApiAppointmentBookingController@update']);//id,start_time,start_time_ar,end_time,end_time_ar,time_interval
	Route::post('appointment-booking/enable-order',['as' => $a . 'appointment-booking/enable', 'uses' => 'Merchant\ApiAppointmentBookingController@enableBooking']);//id
	Route::post('appointment-booking/disable-order',['as' => $a . 'appointment-booking/disable', 'uses' => 'Merchant\ApiAppointmentBookingController@disableBooking']);//id
	
	//Online Order
	Route::get('online-order/menu-items/{user_id}', ['as' => $a . 'online-order/menu-items', 'uses' => 'Merchant\ApiOnlineOrderController@menu_items']);
	Route::post('online-order/add-menu-item', ['as' => $a . 'online-order/add-menu-items', 'uses' => 'Merchant\ApiOnlineOrderController@add_menu_item']);
	Route::post('online-order/add-delevery-fee', ['as' => $a . 'online-order/add-delevery-fee', 'uses' => 'Merchant\ApiOnlineOrderController@add_delevery_fee']);	
	Route::get('online-order/edit-menu-item/{id}', ['as' => $a . 'online-order/edit-menu-items', 'uses' => 'Merchant\ApiOnlineOrderController@edit_menu_item']);
	Route::post('online-order/update-menu-item', ['as' => $a . 'online-order/update-menu-item', 'uses' => 'Merchant\ApiOnlineOrderController@update_menu_item']);
	
	Route::get('online-order/status-menu-item/{id}', ['as' => $a . 'online-order/status-menu-item', 'uses' => 'Merchant\ApiOnlineOrderController@status_menu_item']);	
	Route::get('online-order/delete-menu-item/{id}', ['as' => $a . 'online-order/update-menu-items', 'uses' => 'Merchant\ApiOnlineOrderController@delete_menu_item']);
	Route::post('online-order/update-status', ['as' => $a . 'online-order/update-status', 'uses' => 'Merchant\ApiOnlineOrderController@update_booking_satus']);
	
	Route::get('online-order/enable-service/{user_id}',['as' => $a . 'enable-service', 'uses' => 'Merchant\ApiOnlineOrderController@enable']);
	Route::get('online-order/disable-service/{user_id}',['as' => $a . 'disable-service', 'uses' => 'Merchant\ApiOnlineOrderController@disable']);
	
	// Table Booking
	Route::get('table-booking/enable-service/{user_id}', 'Merchant\ApiTableBookingController@enable');
	Route::get('table-booking/disable-service/{user_id}', 'Merchant\ApiTableBookingController@disable');
	Route::post('table-booking/add', ['as' => $a . 'table-booking/add', 'uses' => 'Merchant\ApiTableBookingController@add']);	
	Route::post('table-booking/update', ['as' => $a . 'table-booking/update', 'uses' => 'Merchant\ApiTableBookingController@update']);
	Route::post('table-booking/enable-order',['as' => $a . 'table-booking/enable-order','uses' => 'Merchant\ApiTableBookingController@enableBooking']);
	Route::post('table-booking/disable-order',['as' => $a . 'table-booking/disable-order','uses' => 'Merchant\ApiTableBookingController@DisableBooking']);
	
	// Service Booking
	Route::get('service-booking/enable-service/{user_id}', 'Merchant\ApiServiceBookingController@enable');
	Route::get('service-booking/disable-service/{user_id}', 'Merchant\ApiServiceBookingController@disable');
	Route::post('service-booking/add', ['as' => $a . 'service-booking/add', 'uses' => 'Merchant\ApiServiceBookingController@add']);	
	Route::post('service-booking/update', ['as' => $a . 'service-booking/update', 'uses' => 'Merchant\ApiServiceBookingController@update']);
	Route::post('service-booking/enable-order',['as' => $a . 'service-booking/enable-order','uses' => 'Merchant\ApiServiceBookingController@enableBooking']);
	Route::post('service-booking/disable-order',['as' => $a . 'service-booking/disable-order','uses' => 'Merchant\ApiServiceBookingController@DisableBooking']);
	
	
	Route::get('orders/{user_id}',['as' => $a . 'orders','uses' => 'Merchant\ApiOrdersController@index']);
	Route::get('orders/pending/{user_id}',['as' => $a . 'orders/pending','uses' => 'Merchant\ApiOrdersController@pending']);
	Route::get('orders/history/{user_id}',['as' => $a . 'orders/history','uses' => 'Merchant\ApiOrdersController@history']);
	Route::post('orders/shop_order_detail', 'Merchant\ApiOrdersController@shop_order_detail');
	Route::post('orders/food_order_detail', 'Merchant\ApiOrdersController@food_order_detail');	
	Route::post('orders/table_order_detail', 'Merchant\ApiOrdersController@table_order_detail');	
	Route::post('orders/appointment_order_detail', 'Merchant\ApiOrdersController@appointment_order_detail');
	Route::get('orders/food_invoice/{id}', 'Merchant\ApiOrdersController@food_invoice');
	Route::get('orders/shop_invoice/{id}', 'Merchant\ApiOrdersController@shop_invoice');
	
	/*Route::get('online-order/booking-customers', ['as' => $a . 'online-order/booking-customers', 'uses' => 'Merchant\ApiOnlineOrderController@booking_customers']);
	Route::get('online-order/booking-customers/{id}', ['as' => $a . 'online-order/booking-customers', 'uses' => 'Merchant\ApiOnlineOrderController@booking_customers_detail']);
	
	Route::get('online-order/settings', ['as' => $a . 'online-order/settings', 'uses' => 'Merchant\ApiOnlineOrderController@settings']);
	Route::post('online-order/settings', ['as' => $a . 'online-order/settings', 'uses' => 'Merchant\ApiOnlineOrderController@update_settings']);
	
	Route::post('online-order/add', ['as' => $a . 'online-order/add', 'uses' => 'Merchant\ApiOnlineOrderController@add']);
	Route::post('online-order/update', ['as' => $a . 'online-order/update', 'uses' => 'Merchant\ApiOnlineOrderController@update']);
	Route::post('online-order/destroy_all', ['as' => $a . 'online-order/destroy_all', 'uses' => 'Merchant\ApiOnlineOrderController@destroy_all']);*/
	
	
	// subscription history
	Route::get('subscription-history/{merchant_id}',['as' => $a . 'subscription-history', 'uses' => 'Merchant\ApiSubscriptionHistoryController@index']);
	Route::get('subscription-history/vieworder/{id}',['as' => $a . 'subscription-history/vieworder', 'uses' => 'Merchant\ApiSubscriptionHistoryController@view_order']);
	
	// change plan
	Route::get('change-subscription-plan/{merchant_id}',['as' => $a . 'change-subscription-plan', 'uses' => 'Merchant\ApiSubscriptionController@change_plan']);	
	Route::post('subscription-plan-confirm',['as' => $a . 'subscription-plan-confirm', 'uses' => 'Merchant\ApiSubscriptionController@subscription_plan_confirm']);//user_id,subscription_pricing_id, subscription_term_id, payment_gateway_id,
	
	// payment
	//Route::get('subscription/payby-razor/{encrypt_order_id}', ['as' => $a . 'subscription/payby-razor',   'uses' => 'Merchant\SubscriptionController@payby_razor']);
	Route::post('subscription/payment-response', ['as' => $a . 'subscription/payment-response',   'uses' => 'Merchant\ApiSubscriptionController@payment_response']);
	//	Route::get('subscription/complete/{encrypt_order_id}',['as' => $a . 'subscription/complete', 'uses' => 'Merchant\SubscriptionController@complete_subscription']);
	
	//Gallery
	Route::post('upload-gallery-android', ['as' => $a . 'upload-gallery-android',   'uses' => 'Merchant\ApiGalleryController@imageUpload']);
	Route::post('upload-gallery-ios', ['as' => $a . 'upload-gallery-ios',   'uses' => 'Merchant\ApiGalleryController@update_gallery_ios']);
	Route::post('gallery', ['as' => $a . 'gallery',   'uses' => 'Merchant\ApiGalleryController@getUploadedImages']);
	Route::get('delete-image', ['as' => $a . 'delete-image',   'uses' => 'Merchant\ApiGalleryController@deleteUploadedImage']);
	
});
Route::group(['middleware' => 'api'], function()
{   
	// user
	Route::post('signup','Auth\RegisterController@api_register');
	Route::post('signup_verify','Auth\RegisterController@api_signup_verify');
	Route::post('forget_password','Auth\ForgotPasswordController@api_forget_password');
	Route::post('forget_password_verify','Auth\ForgotPasswordController@api_forget_password_verify');
	Route::post('reset_password','Auth\ResetPasswordController@api_reset_password');
	Route::post('mobilelogin','ApiLoginController@login');
	Route::post('save_contact', 'ApiUserController@save_contact');
	
	// profile
	Route::post('get_contacts', 'ApiUserController@get_contacts');
	Route::post('get_reviews', 'ApiUserController@get_reviews');
	Route::post('get_table_bookings', 'ApiUserController@get_table_bookings');
	Route::post('get_appointment_bookings', 'ApiUserController@get_appointment_bookings');
	Route::post('get_food_orders', 'ApiUserController@get_food_orders');
	Route::post('get_product_orders', 'ApiUserController@get_product_orders');
	Route::post('get_orders_history', 'ApiUserController@get_orders_history');
	Route::post('update_profile_data', 'ApiUserController@update_profile_data');	
	Route::post('update_photo', 'ApiUserController@update_photo');
	Route::post('update_photo_ios', 'ApiUserController@update_photo_ios');	
	Route::post('qr_scan', 'ApiUserController@qr_scan');
	
	Route::get('category','ApiCategoryController@index');
	Route::get('category/show/{id}','ApiCategoryController@show');
	Route::get('category/subcategory/{id}','ApiCategoryController@subcategory');	
	Route::get('listing','ApiListingController@index');
	Route::get('listing/show/{id}','ApiListingController@show');
	Route::post('listing/view','ApiListingController@listByLocation');
	Route::get('campaigns','ApiCampaignsController@index');
	Route::get('campaigns/show/{id}','ApiCampaignsController@show');
	Route::get('products/{id}','ApiProductsController@index');
	Route::get('products/show/{id}','ApiProductsController@show');
	//Route::post('register','ApiRegisterController@create');
	Route::get('getBookings','ApiTableBookingController@getbookings');
	Route::get('getAppointments','ApiAppointmentBookingController@getappointments');
	Route::get('getOrders','ApiOnlineOrderController@getOrders');
	Route::get('getShopOrders','ApiOnlineShopController@getShopOrders');
	Route::get('getContacts','ApiContactsController@getContacts');
	Route::post('localvendor','ApiLocalVendorController@store');
	Route::post('localvendor_ios','ApiLocalVendorController@store_ios');
	Route::post('localvendor/list','ApiLocalVendorController@index');
	Route::post('localvendor-by-category','ApiLocalVendorController@list_by_category');
	Route::post('online-order','ApiOnlineOrderController@place_order');
	Route::post('addreview','ApiReviewController@index');
	
	// booking
	Route::post('booktable','ApiTableBookingController@index');
	Route::post('bookappointment','ApiAppointmentBookingController@index');
	
	// cart
	Route::post('get_cart','ApiOnlineOrderController@getCart');
	Route::post('add_to_cart','ApiOnlineOrderController@addToCart');
	Route::post('update_cart','ApiOnlineOrderController@updateCart');
	Route::post('delete_cart/{id}','ApiOnlineOrderController@delete_cart');
	Route::post('delete_cart_by_listing_id','ApiOnlineOrderController@delete_cart_by_listing_id');
	Route::post('empty_cart','ApiOnlineOrderController@empty_cart');
	Route::post('create_order','ApiOnlineOrderController@create_order');
	Route::get('get_default_payment_gateway','ApiOnlineOrderController@get_default_payment_gateway');
	
	// billing
	Route::get('get_states','ApiOnlineOrderController@get_states');
	Route::get('get_cities/{id}','ApiOnlineOrderController@get_cities');
	Route::get('get_billing_address/{id}','ApiOnlineOrderController@get_billing_address');
	Route::post('add_billing_address','ApiOnlineOrderController@add_billing_address');
	
	// local vendor
	Route::post('local-vendor/marketing-users', 'ApiLocalVendorController@marketing_users');
	Route::post('local-vendor/marketing-users-to-add', 'ApiLocalVendorController@marketing_users_to_add');
	Route::post('local-vendor/add-marketing-user', 'ApiLocalVendorController@add_marketing_user');
	
	/*
	Route::post('password/email', 'Auth\ForgotPasswordController@getResetToken');
    Route::post('password/reset', 'Auth\ResetPasswordController@reset_api');
	Route::post('update_profile', 'ApiAllController@update_profile');
	Route::get('update_password/{uid}/{password}', 'ApiAllController@update_password');*/
	
});
