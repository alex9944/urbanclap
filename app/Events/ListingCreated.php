<?php

namespace App\Events;

use App\Models\Listing;
use Illuminate\Queue\SerializesModels;

class ListingCreated
{
    use SerializesModels;

    public $listing;

    /**
     * Create a new event instance.
     *
     * @param  Listing  $listing
     * @return void
     */
    public function __construct(Listing $listing)
    {
        $this->listing = $listing;
    }
}