<?php

namespace App\Events;

use App\Models\MerchantTransaction;
use Illuminate\Queue\SerializesModels;

class MerchantPaid
{
    use SerializesModels;

    public $merchant_transaction;

    public function __construct(MerchantTransaction $merchant_transaction)
    {
        $this->merchant_transaction = $merchant_transaction;
    }
}