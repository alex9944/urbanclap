<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Cookie;
use Illuminate\Routing\Controller;
use Illuminate\Cookie\CookieJar;

class locationController extends Controller
{
    public function showcookies(){
      echo cookie::get('name');
    }
    public function ceklogin(){
      $response = new \Illuminate\Http\Response();
      $response->withCookie(cookie('name', 'my value', 60));
      return $response;
      exit;
    }
}