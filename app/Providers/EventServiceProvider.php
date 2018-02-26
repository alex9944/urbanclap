<?php

namespace App\Providers;

use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\Registered' => [
            'App\Listeners\Registered_VerifyCode',
        ],
		'App\Events\SignupVerified' => [
            //'App\Listeners\SendWelcomeMail',
            'App\Listeners\SignupVerified_FreeSubscription',
            'App\Listeners\SignupRefarral',
        ],
        'App\Events\ForgetPassword' => [
            'App\Listeners\ForgetPassword_VerifyCode',
        ],
        /*'App\Events\TableBooked' => [
            'App\Listeners\TableBooked_Email',
        ],
        'App\Events\AppointmentBooked' => [
            'App\Listeners\AppointmentBooked_Email',
        ],*/
        'App\Events\OrderCompleted' => [
			'App\Listeners\OrderCompleted_Commission',
			'App\Listeners\OrderCompleted_StockUpdate',
            //'App\Listeners\OrderCompleted_Email',
        ],
        /*'App\Events\OrderStatusChanged' => [
            'App\Listeners\OrderStatusChanged_Email',
        ],
        'App\Events\TableBookedStatus' => [
            'App\Listeners\TableBookedStatus_Email',
        ],
        'App\Events\AppointmentBookedStatus' => [
            'App\Listeners\AppointmentBookedStatus_Email',
        ],*/
        'App\Events\QrScan' => [
            'App\Listeners\QrScan_Commission',
        ],
        'App\Events\ListingCreated' => [
            'App\Listeners\ListingCreated_ServiceCreation',
        ],
		'App\Events\MerchantPaid' => [
            //'App\Listeners\MerchantPaid_Notify',
            'App\Listeners\MerchantPaid_Update',
        ],
    ];

    /**
     * Register any other events for your application.
     *
     * @param  \Illuminate\Contracts\Events\Dispatcher  $events
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
