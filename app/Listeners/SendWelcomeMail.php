<?php

namespace App\Listeners;

use App\Events\SignupVerified;

use Mail;

class SendWelcomeMail
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
     * @param  OrderShipped  $event
     * @return void
     */
    public function handle(SignupVerified $event)
    {
        // Access the order using $event->order...
    }
}