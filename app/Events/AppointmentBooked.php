<?php

namespace App\Events;

use App\Models\AppointmentBookingOrder;
use Illuminate\Queue\SerializesModels;

class AppointmentBooked
{
    use SerializesModels;

    public $AppointmentBookingOrder;

    /**
     * Create a new event instance.
     *
     * @param  AppointmentBookingOrder  $AppointmentBookingOrder
     * @return void
     */
    public function __construct(AppointmentBookingOrder $AppointmentBookingOrder)
    {
        $this->AppointmentBookingOrder = $AppointmentBookingOrder;
    }
}