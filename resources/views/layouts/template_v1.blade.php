<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	
    
    <?php
	$site_name = ($site_settings->site_name != '') ? $site_settings->site_name : 'Apoyou';
	$title = $site_name;
	?>
	<title>{{$title}}</title>
    <!-- Bootstrap -->
    <link href="{{ asset($merchant_layout . '/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset($merchant_layout . '/css/bootstrap-select.min.css') }}" rel="stylesheet">
    <link href="{{ asset($merchant_layout . '/css/bootstrap-slider.min.css') }}" rel="stylesheet">
	
	<!-- Listing Css -->
	<!--<link href="{{ asset($merchant_layout . '/css/listing.css') }}" rel="stylesheet"> -->
	
    <!-- owl carousel -->
    <link href="{{ asset($merchant_layout . '/css/owl.carousel.css') }}" rel="stylesheet">
    <!-- Magnific Popup -->
	 <link href="{{ asset($merchant_layout . '/css/magnific-popup.css') }}" rel="stylesheet">
	<!-- font awesome -->
    <link href="{{ asset($merchant_layout . '/css/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset($merchant_layout . '/css/Stroke-Gap-Icons-Webfont.css') }}" rel="stylesheet">

	<!-- Revolution slider -->
	<link href="{{ asset($merchant_layout . '/assets/revolution/css/settings.css') }}" rel="stylesheet" type="text/css">
	<link href="{{ asset($merchant_layout . '/assets/revolution/css/layers.css') }}" rel="stylesheet" type="text/css">
	<link href="{{ asset($merchant_layout . '/assets/revolution/css/navigation.css') }}" rel="stylesheet" type="text/css">
	
	
	<!-- main stylesheet -->
    <link href="{{ asset($merchant_layout . '/css/main.css') }}" rel="stylesheet">
    <!-- customize stylesheet -->
       <link href="{{ asset($merchant_layout . '/css/customize.css') }}" rel="stylesheet">
       <!-- Rating Plugin Css -->
	<link href="{{ asset($merchant_layout . '/assets/palette_rating/css/heat-rating.css') }}" rel="stylesheet" type="text/css">
	
	<!-- fonts -->
	<link href="https://fonts.googleapis.com/css?family=Great+Vibes%7CMontserrat:400,700%7COpen+Sans:300,300i,400,400i,600,600i,700,700i" rel="stylesheet">
<link rel="stylesheet" href="{{ asset($merchant_layout . '/css/jquery.mobile-1.4.5.min.css') }}">

   <link rel="stylesheet" type="text/css" href="{{ asset($merchant_layout . '/css/jcarousel.responsive.css') }}"> 

   <link rel="stylesheet" type="text/css" href="{{ asset($merchant_layout . '/css/dataTables.bootstrap.min.css') }}"> 
   <link rel="stylesheet" type="text/css" href="{{ asset($merchant_layout . '/css/fixedHeader.bootstrap.min.css') }}"> 
   <link rel="stylesheet" type="text/css" href="{{ asset($merchant_layout . '/css/responsive.bootstrap.min.css') }}">
   
   <!-- bootstrap-daterangepicker -->
  <link href="{{ asset($merchant_layout . '/css/daterangepicker.css') }}" rel="stylesheet">

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
	</style>
	@if($merchant_listing)
	<style>
	section.banner-logo.bg-color {
		background: url("{{ url('') }}/uploads/listing/original/{{ $merchant_listing->image }}");
		background-repeat: no-repeat;
		background-size:cover;
		background-position:center center;
		position:relative;
		min-height:474px;
	}
	section.banner-logo.bg-color::before
	{
		content:'';
		display:block;
		width:100%;
		opacity:0.5;
		background-color:#111;
		height:100%;
		position:absolute;
	}
	.mtbanner {
		margin-top: 30%;
	}
	</style>
	@endif

	@yield('head')
</head>
<body>

<?php
$user_id = $merchant_user->id;
$merchant_status = $merchant_user->merchant_status;
$last_subscription = $merchant_user->last_subscription;
?>
<img id="loading" class="image_loader" src="{{ url('') }}/admin-assets/images/3.gif" />
	<!-- header start -->
    <header id="header" class="header">
		<!-- topbar start -->
		<div class="first-header">
			<div class="container">
				<div class="row">
					<div class="col-sm-6">
						<div class="contact">
							@if($site_settings->site_email)
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
								<?php /*<a  href="{{ url('register') }}"> Sign up</a>*/?>
								<a href="javascript:;" class="dropdown-toggle" data-toggle="modal" data-target="#signupModel" role="button" aria-haspopup="true" aria-expanded="false">Sign up</a>
							</li>
							@else                   

							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{{ Auth::user()->first_name }} <i class="fa fa-angle-down"></i></a>
								<ul class="dropdown-menu">									
									@if(Auth::user()->hasRole('administrator') || Auth::user()->hasRole('merchant'))
									<li><a href="{{ Auth::user()->homeUrl() }}">Dashboard</a></li>
									<li><a href="{{url('user')}}">Profile</a></li>
									<li><a href="{{ url('/campaign') }}">Campaign</a></li>
									<li><a href="{{ url('logout') }}">Logout</a></li>
									@else
									<li><a href="{{url('user')}}">Profile</a></li>
									<li><a href="{{url('/wishList/list')}}">Wishlist <span style="color:green; font-weight: bold">({{App\Models\wishList::where(['user_id' => Auth::user()->id])->count()}})</span> </a></li>
									<li><a href="{{ url('/campaign') }}">Campaign</a></li>
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
								<img src="{{ asset($merchant_layout . '/images/logo.png') }}" alt="logo" class="brand">
							</a>
						</div>
					</div>
					<div class="col-md-6 col-md-offset-4 col-sm-5 col-xs-12">
						<?php /*<div class="search">
							<form action="#" method="post">
								<input type="text" placeholder="Inter Your Keyword. . ." class="form-control">
								<button type="submit"><i class="icon icon-Search"></i></button>
							</form>
						</div>*/?>
					</div>
					 
				</div>
			</div>
		</div><!-- /.end header second -->
		
	</header>
	<!-- end header -->
	 <!-- Main content start -->
	 <div class="main-content">
	 <!-- menu start -->
		<!-- <section class="menu">
			<div class="container">
				 <nav>
						<ul class="nav masthead-nav">
						  <li class="active"><a href="#">Home</a></li>
						  <li><a href="#">Services</a></li>
						  <li><a href="#">Contact Us</a></li>
						   <li><a href="#">Free Listing</a></li>
						</ul>
					  </nav>
			 </div>
		</section> -->
		<!-- menu end -->
		 <!-- banner-logo start -->
		@if($merchant_listing)
		<section class="banner-logo bg-color">
			<div class="container">
				<div class="col-md-12 mtbanner">
				</div>
				<div class="col-md-12 mt10">
					<div class="col-md-12 text-center">
					<!-- <h4>Poorvika mobile world</h4> -->
					<h5>
					{{$merchant_listing->address1}}@if(isset($merchant_listing->listing_city->name)), {{$merchant_listing->listing_city->name}}@endif
					</h5>
				</div>
				 

				</div>
			 </div>
		</section>
		@endif
		<!-- banner-logo end -->

		<!-- content-tab start -->
		<section class="tab-content-details">
				<div class="container">
					<div class="col-md-12 pad0">
						
						<div>
							<div class="jcarousel-wrapper">
								<div class="jcarousel">
									<ul>
									@php
										$dashboard_active = '';
										$order_active = '';
										$listing_active = '';
										$subscription_active = '';
										$service_active = '';
										if( strpos(Request::url(), 'merchant/orders') !== false ) {
											$order_active = 'active';
										} else if( strpos(Request::url(), 'merchant/listing') !== false ) {
											$listing_active = 'active';
										} else if( strpos(Request::url(), 'merchant/change-subscription-plan') !== false 
											|| strpos(Request::url(), 'merchant/change-subscription-plan-confirm') !== false 
											|| strpos(Request::url(), 'merchant/subscription/payby-razor') !== false 
											|| strpos(Request::url(), 'merchant/subscription/complete') !== false 
											|| strpos(Request::url(), 'merchant/subscription/payment-response') !== false 
										) {
											$subscription_active = 'active';
										} else if( strpos(Request::url(), 'merchant/online-order') !== false 
											|| strpos(Request::url(), 'merchant/table-booking') !== false 
											|| strpos(Request::url(), 'merchant/appointment-booking') !== false
											|| strpos(Request::url(), 'merchant/online-shop') !== false 
										) {
											$service_active = 'active';
										} else {
											$dashboard_active = 'active';
										} 
									@endphp
									<li class="{{$dashboard_active}}">
										<a href="{{ url('merchant') }}">
										<div class="col-md-12 tabbox">
											<i class="fa fa-line-chart"></i>
											<h5>Dash Board</h5>
										</div>
										</a>	 	
									</li>
									<li class="{{$order_active}}">
										<a href="{{ url('merchant/orders') }}">
										<div class="col-md-12 tabbox">
										<i class="fa fa-id-card-o" aria-hidden="true"></i>
										<h5>Orders</h5>
										</div>
										</a>	 
									</li>

									<li class="{{$listing_active}}">
										<a href="{{ url('merchant/listing') }}">
										<div class="col-md-12 tabbox">
										<i class="fa fa-list-ul" aria-hidden="true"></i>
										<h5>Listing</h5>
										</div>
										</a>	 
									</li>
									<li class="{{$subscription_active}}">
										<a href="{{ url('merchant/change-subscription-plan') }}">
										<div class="col-md-12 tabbox">
										<i class="fa fa-id-card-o" aria-hidden="true"></i>
										<h5>Subcription</h5>
										</div>
										</a> 
									</li>
									@if($enable_services)
										<?php
											// get service url
											$category_type = $subscribed_category->category_type;
											if($category_type) {
												$category_types = json_decode($category_type);
												if( isset($category_types[0]) )
													$category_type_id = $category_types[0];
											}
											if( isset($services_by_id[$category_type_id]['slug']) and $services_by_id[$category_type_id]['slug'] == 'food' )
												$service_url = url('merchant/online-order/menu-items');
											if( isset($services_by_id[$category_type_id]['slug']) and $services_by_id[$category_type_id]['slug'] == 'table' )
												$service_url = url('merchant/table-booking/settings');
											if( isset($services_by_id[$category_type_id]['slug']) and $services_by_id[$category_type_id]['slug'] == 'appointment' )
												$service_url = url('merchant/appointment-booking/settings');
											if( isset($services_by_id[$category_type_id]['slug']) and $services_by_id[$category_type_id]['slug'] == 'shop' )
												$service_url = url('merchant/online-shop/products');
											if( isset($services_by_id[$category_type_id]['slug']) and $services_by_id[$category_type_id]['slug'] == 'services' )
												$service_url = url('merchant/service-booking');
											
										?>
										@if( isset($service_url) )
										<li class="{{$service_active}}">
											<a href="{{ $service_url }}">
											<div class="col-md-12 tabbox">

											<i class="fa fa-shield" aria-hidden="true"></i>
											<h5>Services</h5>
											</div>
											</a> 
										</li>
										@endif
										
									@endif
									@if($enable_listing_model['gallery'])
									<li class="">
											<a href="{{ url('merchant/gallery') }}">
											<div class="col-md-12 tabbox">

											<i class="fa fa-shield" aria-hidden="true"></i>
											<h5>Gallery</h5>
											</div>
											</a> 
										</li>
										@endif
									</ul>
								</div>

								<a href="#" class="jcarousel-control-prev">&lsaquo;</a>
								<a href="#" class="jcarousel-control-next">&rsaquo;</a>

								<!--  <p class="jcarousel-pagination"></p> -->
							</div>

							<!-- Tab panes -->
							<div class="tab-content">
								<div role="tabpanel" class="tab-pane active" id="info">
									@yield('content')
								</div>



							</div>

						</div>
					</div>
				</div>
		</section>


	<!-- content-tab end -->

	</div>

	<!-- Main content end -->



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
							   <li><a href="{{url('page/payment-option')}}">Cancellation Policy</a></li>
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
									@if($site_settings->site_address)
									<i class="fa fa-map"></i>
									<span><?php echo nl2br($site_settings->site_address);?></span>
									@endif
								</li>
								<li>
									@if($site_settings->site_email)
									<span><i class="fa fa-envelope"></i>{{$site_settings->site_email}}</span>
									@endif									
								</li>
								<li>
									@if($site_settings->site_contact_no)
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
										@if($site_settings->facebook_url)<a href="{{$site_settings->facebook_url}}" target="_blank"><i class="fa fa-facebook"></i></a>@endif
										@if($site_settings->twitter_url)<a href="{{$site_settings->twitter_url}}" target="_blank"><i class="fa fa-twitter"></i></a>@endif
										@if($site_settings->gplus_url)<a href="{{$site_settings->gplus_url}}" target="_blank"><i class="fa fa-google-plus"></i></a>@endif
										@if($site_settings->pinterest_url)<a href="{{$site_settings->pinterest_url}}" target="_blank"><i class="fa fa-pinterest-p"></i></a>@endif
										@if($site_settings->linkedin_url)<a href="{{$site_settings->linkedin_url}}" target="_blank"><i class="fa fa-linkedin"></i></a>@endif

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
    <script src="{{ asset($merchant_layout . '/js/jquery.min.js') }}"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="{{ asset($merchant_layout . '/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset($merchant_layout . '/js/bootstrap-select.min.js') }}"></script>
    <script src="{{ asset($merchant_layout . '/js/bootstrap-slider.min.js') }}"></script>
    <script src="{{ asset($merchant_layout . '/js/jquery.syotimer.min.js') }}"></script>
    <script src="{{ asset($merchant_layout . '/js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset($merchant_layout . '/js/jquery.magnific-popup.min.js') }}"></script>
    <script src="{{ asset($merchant_layout . '/js/isotope.pkgd.min.js') }}"></script>
	<script src="{{ asset($merchant_layout . '/js/radialIndicator.js') }}"></script>
	
	<!-- REVOLUTION JS FILES -->
	<script src="{{ asset($merchant_layout . '/assets/revolution/js/jquery.themepunch.tools.min.js') }}"></script>
	<script src="{{ asset($merchant_layout . '/assets/revolution/js/jquery.themepunch.revolution.js') }}"></script>
	<script type="text/javascript" src="{{ asset($merchant_layout . '/assets/revolution/js/extensions/revolution.extension.slideanims.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset($merchant_layout . '/assets/revolution/js/extensions/revolution.extension.layeranimation.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset($merchant_layout . '/assets/revolution/js/extensions/revolution.extension.navigation.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset($merchant_layout . '/assets/revolution/js/extensions/revolution.extension.video.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset($merchant_layout . '/assets/revolution/js/extensions/revolution.extension.actions.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset($merchant_layout . '/assets/revolution/js/extensions/revolution.extension.kenburn.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset($merchant_layout . '/assets/revolution/js/extensions/revolution.extension.migration.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset($merchant_layout . '/assets/revolution/js/extensions/revolution.extension.parallax.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset($merchant_layout . '/assets/revolution/js/extensions/revolution.extension.carousel.min.js') }}"></script>
	<script src="{{ asset($merchant_layout . '/js/jssor.slider-23.0.0.min.js') }}" type="text/javascript') }}"></script>
	<!--<script src="{{ asset($merchant_layout . '/js/jquery.mobile-1.4.5.min.js') }}"></script> -->

<!-- Rating Plugin Js -->
	<script type="text/javascript" src="{{ asset($merchant_layout . '/assets/palette_rating/js/heat-rating.js') }}"></script>
    <script src="{{ asset($merchant_layout . '/js/custom.js') }}"></script>
	
	
	
      <script type="text/javascript" src="{{ asset($merchant_layout . '/js/jquery.jcarousel.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset($merchant_layout . '/js/jcarousel.responsive.js') }}"></script>

  
   <script type="text/javascript" src="{{ asset($merchant_layout . '/js/jquery.dataTables.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset($merchant_layout . '/js/dataTables.bootstrap.min.js') }}"></script>
	 <script type="text/javascript" src="{{ asset($merchant_layout . '/js/dataTables.fixedHeader.min.js') }}"></script>
	  <script type="text/javascript" src="{{ asset($merchant_layout . '/js/dataTables.responsive.min.js') }}"></script>
	   <script type="text/javascript" src="{{ asset($merchant_layout . '/js/responsive.bootstrap.min.js') }}"></script>
	  <!-- bootstrap-daterangepicker -->
<script src="{{asset($merchant_layout . '/js/moment.js') }}"></script>
<script src="{{asset($merchant_layout . '/js/daterangepicker.js') }}"></script>

  
  <?php /* <script type="text/javascript" src="{{ asset($merchant_layout . '/js/jquery.tabledit.js') }}"></script>
 <script>  $('#my-table').Tabledit({
  url: 'example.php',
  columns: {
    identifier: [0, 'id'],                    
    editable: [[0, 'col0'],[1, 'col1'], [2, 'col1'], [3, 'col3'],[4, 'col4'],[5, 'col5']]
  }
});</script>*/?>
    <script>
    'use strict';
    /*
	|----------------------------------------------------------------------------
	|   Main Slider
	|----------------------------------------------------------------------------
	*/
	var revapi;	
	revapi = jQuery("#rev_slider_1").revolution({
		sliderType:"standard",
	    sliderLayout:"fullwidth",
	    fullScreenOffsetContainer:"#header",
		responsiveLevels:[4096,1400,992,768],
		delay:9000,
		navigation: {
			touch:{
				touchenabled:"on",
				swipe_threshold: 75,
				swipe_min_touches: 1,
				swipe_direction: "horizontal",
				drag_block_vertical: false
			},
			arrows:{
			    enable:true
			},
			bullets:{
				enable:false
			}			
		},			
		gridwidth:[1140,950,720,320],
		gridheight:[600,600,500,400]		
	});
    </script>
	    <script>
		$(document).ready(function(){
		$(".loadmore").click(function(){
		$(".loading").toggle(500);
		});
		});
		</script>
		<!-- star rating -->
		<script type="text/javascript">
			// Starrr plugin (https://github.com/dobtco/starrr)
var __slice = [].slice;

(function($, window) {
    var Starrr;

    Starrr = (function() {
        Starrr.prototype.defaults = {
            rating: void 0,
            numStars: 5,
            change: function(e, value) {}
        };

        function Starrr($el, options) {
            var i, _, _ref,
                _this = this;

            this.options = $.extend({}, this.defaults, options);
            this.$el = $el;
            _ref = this.defaults;
            for (i in _ref) {
                _ = _ref[i];
                if (this.$el.data(i) != null) {
                    this.options[i] = this.$el.data(i);
                }
            }
            this.createStars();
            this.syncRating();
            this.$el.on('mouseover.starrr', 'i', function(e) {
                return _this.syncRating(_this.$el.find('i').index(e.currentTarget) + 1);
            });
            this.$el.on('mouseout.starrr', function() {
                return _this.syncRating();
            });
            this.$el.on('click.starrr', 'i', function(e) {
                return _this.setRating(_this.$el.find('i').index(e.currentTarget) + 1);
            });
            this.$el.on('starrr:change', this.options.change);
        }

        Starrr.prototype.createStars = function() {
            var _i, _ref, _results;

            _results = [];
            for (_i = 1, _ref = this.options.numStars; 1 <= _ref ? _i <= _ref : _i >= _ref; 1 <= _ref ? _i++ : _i--) {
                _results.push(this.$el.append("<i class='fa fa-star-o'></i>"));
            }
            return _results;
        };

        Starrr.prototype.setRating = function(rating) {
            if (this.options.rating === rating) {
                rating = void 0;
            }
            this.options.rating = rating;
            this.syncRating();
            return this.$el.trigger('starrr:change', rating);
        };

        Starrr.prototype.syncRating = function(rating) {
            var i, _i, _j, _ref;

            rating || (rating = this.options.rating);
            if (rating) {
                for (i = _i = 0, _ref = rating - 1; 0 <= _ref ? _i <= _ref : _i >= _ref; i = 0 <= _ref ? ++_i : --_i) {
                    this.$el.find('i').eq(i).removeClass('fa-star-o').addClass('fa-star');
                }
            }
            if (rating && rating < 5) {
                for (i = _j = rating; rating <= 4 ? _j <= 4 : _j >= 4; i = rating <= 4 ? ++_j : --_j) {
                    this.$el.find('i').eq(i).removeClass('fa-star').addClass('fa-star-o');
                }
            }
            if (!rating) {
                return this.$el.find('i').removeClass('fa-star').addClass('fa-star-o');
            }
        };

        return Starrr;

    })();
    return $.fn.extend({
        starrr: function() {
            var args, option;

            option = arguments[0], args = 2 <= arguments.length ? __slice.call(arguments, 1) : [];
            return this.each(function() {
                var data;

                data = $(this).data('star-rating');
                if (!data) {
                    $(this).data('star-rating', (data = new Starrr($(this), option)));
                }
                if (typeof option === 'string') {
                    return data[option].apply(data, args);
                }
            });
        }
    });
})(window.jQuery, window);

$(function() {
    return $(".starrr").starrr();
});

$( document ).ready(function() {
      
  $('#stars').on('starrr:change', function(e, value){
    $('#count').html(value);
  });
  
  $('#stars-existing').on('starrr:change', function(e, value){
    $('#count-existing').html(value);
  });
});

 $(document).ready(function() {
      $('.progress .progress-bar').css("width",
                function() {
                    return $(this).attr("aria-valuenow") + "%";
                }
        )
    });


		</script>

	<script type="text/javascript">
	$(document).ready(function(){
		
		$("body, html").animate({ 
			scrollTop: $('.tab-content-details').offset().top 
		}, 1);
		
		$(document).on('click','.subplan .divbox a',function(){
			$('.subplan .divbox a').removeClass("active");
			$(this).addClass("active");
		});
			 
		$(document).on('click','.subdur .divbox a',function(){
			$('.subdur .divbox a').removeClass("active");
			$(this).addClass("active");
		});
		$(document).on('click','.paymentsel .divbox a',function(){
			$('.paymentsel .divbox a').removeClass("active");
			$(this).addClass("active");
		}); 



		$(document).on('click','.planbtn',function(){
			$('.subplantogg').show();
			$('.historytogg').hide();

		});
		$(document).on('click','.historybtn ',function(){
			$('.historytogg').show();
			$('.subplantogg').hide();

		});
	});


	</script>
		
		@yield('footer')
		
  </body>

</html>