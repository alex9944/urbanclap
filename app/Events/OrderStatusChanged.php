<?php

namespace App\Events;

use App\Models\OrderBookingDetail;
use Illuminate\Queue\SerializesModels;

class OrderStatusChanged
{
    use SerializesModels;

    public $OrderBookingDetail;

    /**
     * Create a new event instance.
     *
     * @param  OrderBookingDetail  $OrderBookingDetail
     * @return void
     */
    public function __construct(OrderBookingDetail $OrderBookingDetail)
    {
        $this->OrderBookingDetail = $OrderBookingDetail;
    }
}