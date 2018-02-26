# Laravel Social and Email Authentication

This project is related to tutorial from [Needifo], you can checkout .

In the tutorial I am creating Laravel application  with email authentication and user roles, but I am also using [Laravel Socialite] for Facebook, Twitter, Google+ and GitHub logins.
At the end of this tutorial you will be able to use any other social provider in matter of seconds.

Application posses built-in email activation service, which can be stopped or started by changing ACTIVATION flag in .env file.

On registration form via email, users are required to complete Google Re-captcha.

Frontend uses Bootstrap 4 and Material Design for Bootstrap free theme.



### What is covered
Everything is covered there so new Laravel developers can grasp it quickly. I am following simple plan while I am developing:

  - [Creating views for application]
  - [Creating migrations and models related to users and roles]
  - [User seeder with some dummy users]
  - [Middleware for administrator and user roles]
  - [Routes and AuthController]
  - [Creating table and model for Social logins]
  - [Creating Social Logic]

This plan is not strict and if you are familiar with something you may just skip that part.

### Implementing new Social Providers in under 20 seconds

Main beauty is modular approach in implementing new Socialite providers. Application uses 2 routes one for redirecting user to certain social site and other to accept response from that site:

```php
$s = 'social.';
Route::get('/social/redirect/{provider}',   ['as' => $s . 'redirect',   'uses' => 'Auth\SocialController@getSocialRedirect']);
Route::get('/social/handle/{provider}',     ['as' => $s . 'handle',     'uses' => 'Auth\SocialController@getSocialHandle']);
```

To add new social provider you just need to insert new element in services.php like this:

```php
    'facebook' => [
        'client_id'     => env('FB_ID'),
        'client_secret' => env('FB_SECRET'),
        'redirect'      => env('FB_REDIRECT')
    ],

    'twitter' => [
        'client_id'     => env('TW_ID'),
        'client_secret' => env('TW_SECRET'),
        'redirect'      => env('TW_REDIRECT')
    ],
```

And now you need to create new login link using following route:
```php
route('social.redirect', ['provider' => 'provider_key_from_services']); //example
route('social.redirect', ['provider' => 'facebook']);
route('social.redirect', ['provider' => 'twitter']);
```

###Todo's
Project is not over, I will publish few more tutorials regarding this matter. You can expect:
  - ~~Handling when user disallows social app access~~
  - Taking emails of users who sign-up over Twitter and other providers which don't share that data
  - User account actions, like change password, change email etc

[Creating views for application]:http://tuts.codingo.me/laravel-social-and-email-authentication/#creating-views
[Creating migrations and models related to users and roles]:http://tuts.codingo.me/laravel-social-and-email-authentication/#migrations-users
[User seeder with some dummy users]:http://tuts.codingo.me/laravel-social-and-email-authentication/#user-role-seeders
[Middleware for administrator and user roles]:http://tuts.codingo.me/laravel-social-and-email-authentication/#middleware
[Routes and AuthController]:http://tuts.codingo.me/laravel-social-and-email-authentication/#routes
[Creating table and model for Social logins]:http://tuts.codingo.me/laravel-social-and-email-authentication/#pull-socialite
[Creating Social Logic]:http://tuts.codingo.me/laravel-social-and-email-authentication/#social-logic
[Codingo Tuts]:http://tuts.codingo.me/laravel-social-and-email-authentication
[Live Demo Here :zap:]:http://demo1.codingo.me