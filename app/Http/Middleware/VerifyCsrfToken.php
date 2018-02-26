<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
       'api/*',
	   'online-order/paypal-success',
	   'ebs-thankyou',
	   'razor-thankyou',
	   'online-order/paypal-ipn',
       'campaign/*',
	   'merchant/subscription/ebs-thankyou'
    ];
}
