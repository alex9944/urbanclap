<?php

namespace App\Events;

use App\Models\TableBookingOrder;
use Illuminate\Queue\SerializesModels;

class TableBookedStatus
{
    use SerializesModels;

    public $TableBookingOrder;

    /**
     * Create a new event instance.
     *
     * @param  TableBookingOrder  $TableBookingOrder
     * @return void
     */
    public function __construct(TableBookingOrder $TableBookingOrder)
    {
        $this->TableBookingOrder = $TableBookingOrder;
    }
}