<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Queue\SerializesModels;

class SignupVerified
{
    use SerializesModels;

    public $user;

    /**
     * Create a new event instance.
     *
     * @param  User  $user
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }
}