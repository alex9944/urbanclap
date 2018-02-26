<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Apoyou - Error</title>
    <!-- Bootstrap -->
    <link href="{{ asset($user_layout . '/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset($user_layout . '/css/bootstrap-select.min.css') }}" rel="stylesheet">
    <link href="{{ asset($user_layout . '/css/bootstrap-slider.min.css') }}" rel="stylesheet">
	
	
    <link href="{{ asset($user_layout . '/css/font-awesome.min.css') }}" rel="stylesheet">
	<!-- main stylesheet -->
    <link href="{{ asset($user_layout . '/css/main.css') }}" rel="stylesheet">
    <!-- customize stylesheet -->
       <link href="{{ asset($user_layout . '/css/customize.css') }}" rel="stylesheet">
      
	<!-- fonts -->
	<link href="https://fonts.googleapis.com/css?family=Great+Vibes%7CMontserrat:400,700%7COpen+Sans:300,300i,400,400i,600,600i,700,700i" rel="stylesheet">
<link rel="stylesheet" href="{{ asset($user_layout . '/css/jquery.mobile-1.4.5.min.css') }}">

   
	
	<style>
	.image_loader{
		display:none;
		position: absolute;
		top: 50%;
		left: 50%;
		z-index: 999;
	}
	.form-group.required .control-label:after {
		content:"*";
		color:red;
	}
	
	
	.hidden-xs-down{display:none;}
	
	.sf-reset { font: 12px Verdana, Arial, sans-serif; color: #333; line-height: 24px; }
            .sf-reset .clear { clear:both; height:0; font-size:0; line-height:0; }
            .sf-reset .clear_fix:after { display:block; height:0; clear:both; visibility:hidden; }
            .sf-reset .clear_fix { display:inline-block; }
            .sf-reset * html .clear_fix { height:1%; }
            .sf-reset .clear_fix { display:block; }
            .sf-reset, .sf-reset .block { margin: auto }
            .sf-reset abbr { border-bottom: 1px dotted #000; cursor: help; }
            .sf-reset p { font-size:14px; line-height:20px; color:#868686; padding-bottom:20px }
            .sf-reset strong { font-weight:bold; }
            .sf-reset a { color:#6c6159; cursor: default; }
            .sf-reset a img { border:none; }
            .sf-reset a:hover { text-decoration:underline; }
            .sf-reset em { font-style:italic; }
            .sf-reset h1, .sf-reset h2 { font: 20px Georgia, "Times New Roman", Times, serif }
            .sf-reset .exception_counter { background-color: #fff; color: #333; padding: 6px; float: left; margin-right: 10px; float: left; display: block; }
            .sf-reset .exception_title { margin-left: 3em; margin-bottom: 0.7em; display: block; }
            .sf-reset .exception_message { margin-left: 3em; display: block; }
            .sf-reset .traces li { font-size:12px; padding: 2px 4px; list-style-type:decimal; margin-left:20px; }
            .sf-reset .block { background-color:#FFFFFF; padding:10px 28px; margin-bottom:20px;
                -webkit-border-bottom-right-radius: 16px;
                -webkit-border-bottom-left-radius: 16px;
                -moz-border-radius-bottomright: 16px;
                -moz-border-radius-bottomleft: 16px;
                border-bottom-right-radius: 16px;
                border-bottom-left-radius: 16px;
                border-bottom:1px solid #ccc;
                border-right:1px solid #ccc;
                border-left:1px solid #ccc;
                word-wrap: break-word;
            }
            .sf-reset .block_exception { background-color:#ddd; color: #333; padding:20px;
                -webkit-border-top-left-radius: 16px;
                -webkit-border-top-right-radius: 16px;
                -moz-border-radius-topleft: 16px;
                -moz-border-radius-topright: 16px;
                border-top-left-radius: 16px;
                border-top-right-radius: 16px;
                border-top:1px solid #ccc;
                border-right:1px solid #ccc;
                border-left:1px solid #ccc;
                overflow: hidden;
                word-wrap: break-word;
            }
            .sf-reset a { background:none; color:#868686; text-decoration:none; }
            .sf-reset a:hover { background:none; color:#313131; text-decoration:underline; }
            .sf-reset ol { padding: 10px 0; }
            .sf-reset h1 { background-color:#FFFFFF; padding: 15px 28px; margin-bottom: 20px;
                -webkit-border-radius: 10px;
                -moz-border-radius: 10px;
                border-radius: 10px;
                border: 1px solid #ccc;
            }
	</style>
	
	<meta name="csrf-token" content="{{ csrf_token() }}" />
</head>
<body>
	
	<img id="loading" class="image_loader" src="{{ url('') }}/admin-assets/images/3.gif" />

	<!-- header start -->
    <header id="header" class="header">
		<!-- topbar start -->
		<div class="first-header">
			<div class="container">
				<div class="row">
					<div class="col-sm-6">
						<div class="contact">
							@if(isset($site_settings) and $site_settings->site_email)
							<span><i class="fa fa-envelope"></i>{{$site_settings->site_email}}</span>
							@endif
						</div>
					</div>
					<div class="col-sm-6">
						<ul class="user text-right list-inline">
							@if(!Auth::check())
							<?php /*<li>
								<a  href="{{ url('vendor-register') }}"> Vendor Sign up</a>
							</li>*/?>
							<li>
								<a  href="{{ url('login') }}"> Sign in</a>
							</li>
							<li>
								<a href="{{ url('login') }}">Sign up</a>
							</li>
							@else                   

							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{{ Auth::user()->first_name }} <i class="fa fa-angle-down"></i></a>
								<ul class="dropdown-menu">									
									@if(Auth::user()->hasRole('administrator') || Auth::user()->hasRole('merchant'))
									<li><a href="{{ Auth::user()->homeUrl() }}">@if(Auth::user()->hasRole('administrator')) Admin @else Dashboard @endif</a></li>
									<li><a href="{{url('user')}}">User Dashboard</a></li>
									<?php /*<li><a href="{{ url('/campaign') }}">Campaign</a></li>*/?>
									<li><a href="{{ url('logout') }}">Logout</a></li>
									@else
									<li><a href="{{url('user')}}">Dashboard</a></li>
									<?php /*<li><a href="{{url('/wishList/list')}}">Wishlist <span style="color:green; font-weight: bold">({{App\Models\wishList::where(['user_id' => Auth::user()->id])->count()}})</span> </a></li>
									<li><a href="{{ url('/campaign') }}">Campaign</a></li>*/?>
									<li><a href="{{ url('user/profile') }}">Edit Profile</a></li>
									<li><a href="{{url('user/change-password')}}">Update Password</a></li>
									
									<li><a href="{{ url('logout') }}">Logout</a></li>
									@endif
									
									
								</ul>
								@php
								//print_r(Auth::user());
								//exit;
								@endphp
							</li>
							@endif
							
							<li>
								<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" id="location" aria-haspopup="true" aria-expanded="false"> </a>
								<ul class="dropdown-menu">
									<li>
										<input type="text" class="form-control" name="loc" id="alllocation">
									</li>
								</ul>
								<form method="get" id="location_form" name="location_form" style="display:none;">
									<input type="hidden" id="set_loc" name="currentloc">
									<input type="submit" id="loc_submit">
								</form>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>	<!-- /.end topbar -->
		<!-- header second start -->
		<div class="second-header">
			<div class="container">
				<div class="row">
					<div class="col-md-2 col-sm-3 col-xs-12">
						<div class="logo">
							<a href="{{ url('/') }}">
								<img src="{{ asset($user_layout . '/images/logo.png')}}" alt="logo" class="brand">
							</a>
						</div>
					</div>
                    <div class="col-md-4 col-md-offset-2 col-sm-5 col-xs-12">
						<div class="search">
							<?php /*<form name="srchForm" action="#" method="post">
								<input name="keyword" data-slug="" id="search_text" type="text" placeholder="Enter Your Keyword. . ." class="form-control"  autocomplete="off">
								<button type="submit"><i class="icon icon-Search"></i></button>
								<div id="suggesstion-box"></div>
							</form>*/?>
						</div>
					</div>
					
					<div class="col-md-3 col-md-offset-1 col-sm-4 col-xs-12">
						
					</div> 
				</div>
			</div>
		</div><!-- /.end header second -->
		
	</header>
	<!-- end header -->
	<div id="support" class="support-section p-tb70">
		<div class="container">
			<div class="row mt-xl">
				<div class="col-md-12">

					<?php /*
					<div class="well">
						<h5 style="margin:0;">Whoops, looks like something went wrong. Please try sometimes later.</h5>
					</div>
					<div style="text-align:left;">
						@if(config('app.debug') and isset($exception) )
							<?php
							//$exception = $exception->toJson();
							echo $exception;
							?>
						@endif
					</div>
					*/?>
					
					<div class="sf-reset" style="text-align:left;">
						@if(isset($exception) )
							<?php
							echo $exception;
							?>
						@endif
					</div>
					
				</div>
			</div>

		</div>
		
	</div>



	<footer id="footer" class="footer-section p-t100">
		<div class="main-footer">
			<div class="container">
				<div class="row">
					<div class="col-md-3 col-sm-6">
						<div class="widget widget_nav_menu">
							<h4 class="widget_title">MY ACCOUNT</h4>
							<ul class="menu user_menu">
								<li><a data-hash="order_history" href="{{url('user#order_history')}}">Orders</a></li>
								<li><a data-hash="saved" href="{{url('user#saved')}}">My Contacts</a></li>
								<li><a data-hash="reviews" href="{{url('user#reviews')}}">My Reviews</a></li>
							</ul>
						</div>
					</div>
					<div class="col-md-3 col-sm-6">
						<div class="widget widget_nav_menu">
							<h4 class="widget_title">ORDERS</h4>
							<ul class="menu">
								<li><a href="{{url('page/cancellation-policy')}}">Cancellation Policy</a></li>
							   <li><a href="{{url('page/delivery-and-shipping-policy')}}">Delivery and Shipping Policy</a></li>
							</ul>
						</div>
					</div>
					<div class="col-md-3 col-sm-6">
						<div class="widget widget_nav_menu">
							<h4 class="widget_title">INFORMATION</h4>
							<ul class="menu">
								<li><a href="{{url('page/about-us')}}">About us</a></li>
								<li><a href="{{url('page/privacy-policy')}}">Privacy Policy</a></li>
								<li><a href="{{url('page/disclaimer-policy')}}">Disclaimer Policy</a></li>
								<li><a href="{{url('page/terms-and-conditions')}}">Terms and Conditions</a></li>
							</ul>
						</div>
					</div>
					<div class="col-md-3 col-sm-6">
						<div class="widget widget_nav_menu">
							<h4 class="widget_title">CONTACT US</h4>
							<ul>
								<li>
									@if(isset($site_settings) and $site_settings->site_address)
									<i class="fa fa-map"></i>
									<span><?php echo nl2br($site_settings->site_address);?></span>
									@endif
								</li>
								<li>
									@if(isset($site_settings) and $site_settings->site_email)
									<span><i class="fa fa-envelope"></i>{{$site_settings->site_email}}</span>
									@endif									
								</li>
								<li>
									@if(isset($site_settings) and $site_settings->site_contact_no)
									<span><i class="fa fa-phone"></i>{{$site_settings->site_contact_no}}</span>
									@endif
								</li>
							</ul>
						</div>
					</div>
					<div class="col-sm-12">
						<div class="footer-info">
							<div class="row">
								<div class="col-sm-6">
									<div class="social">										
										@if(isset($site_settings) and $site_settings->facebook_url)<a href="{{$site_settings->facebook_url}}" target="_blank"><i class="fa fa-facebook"></i></a>@endif
										@if(isset($site_settings) and $site_settings->twitter_url)<a href="{{$site_settings->twitter_url}}" target="_blank"><i class="fa fa-twitter"></i></a>@endif
										@if(isset($site_settings) and $site_settings->gplus_url)<a href="{{$site_settings->gplus_url}}" target="_blank"><i class="fa fa-google-plus"></i></a>@endif
										@if(isset($site_settings) and $site_settings->pinterest_url)<a href="{{$site_settings->pinterest_url}}" target="_blank"><i class="fa fa-pinterest-p"></i></a>@endif
										@if(isset($site_settings) and $site_settings->linkedin_url)<a href="{{$site_settings->linkedin_url}}" target="_blank"><i class="fa fa-linkedin"></i></a>@endif

									</div>
								</div>
								<div class="col-sm-6">
									<div class="payment-method text-right">
										<a><img src="{{ asset('assets/images/payment/01.png')}}" alt="payment"></a>
										<a><img src="{{ asset('assets/images/payment/02.png')}}" alt="payment"></a>
										<a><img src="{{ asset('assets/images/payment/03.png')}}" alt="payment"></a>
										<a><img src="{{ asset('assets/images/payment/04.png')}}" alt="payment"></a>
										<a><img src="{{ asset('assets/images/payment/05.png')}}" alt="payment"></a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="scroll"><i class="fa fa-angle-up"></i></div>
		<div class="copyright text-center">
			<div class="container">
				<p>Copyright © 2017, <a>Needifo</a> All Rights Reseved.</p>
			</div>
		</div>
	</footer>

		

	
	
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="{{ asset($user_layout . '/js/jquery.min.js') }}"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="{{ asset($user_layout . '/js/bootstrap.min.js') }}"></script>
    
	
 
		
  </body>
</html>