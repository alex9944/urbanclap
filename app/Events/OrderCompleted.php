<?php

namespace App\Events;

use App\Models\OrderBooking;
use Illuminate\Queue\SerializesModels;

class OrderCompleted
{
    use SerializesModels;

    public $OrderBooking;

    /**
     * Create a new event instance.
     *
     * @param  OrderBooking  $OrderBooking
     * @return void
     */
    public function __construct(OrderBooking $OrderBooking)
    {
        $this->OrderBooking = $OrderBooking;
    }
}