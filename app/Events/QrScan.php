<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;

class QrScan
{
    use SerializesModels;

    public $user;
	public $local_vendor_marketing;

    /**
     * Create a new event instance.
     *
     * @param  Object  $obj
     * @return void
     */
    public function __construct(\stdClass $obj)
    {
        $this->user = $obj->user;
        $this->local_vendor_marketing = $obj->local_vendor_marketing;
    }
}