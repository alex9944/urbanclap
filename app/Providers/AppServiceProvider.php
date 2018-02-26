<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('exists_atleast_one', function ($attribute, $value_as_array, $parameters, $validator) {
            $empty_all = true; // error
			foreach($value_as_array as $fld => $value)
			{
				if(!empty($value)) { // no error
					$empty_all = false;
					break;
				}
			}
			
			return !$empty_all;
        });
		
		Validator::replacer('exists_atleast_one', function ($message, $attribute, $rule, $parameters) {
			return str_replace([':attr'], $parameters, $message);
		});
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
