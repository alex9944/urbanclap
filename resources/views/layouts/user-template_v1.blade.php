<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php
	$site_name = ($site_settings->site_name != '') ? $site_settings->site_name : 'Urbanclap';
	$title = $site_name;
	$meta_keywords = '';
	$meta_description = '';
	if( isset($page_name) and $page_name == 'listing_detail' ) {
		$title = $listing->title . ' - ' . $site_name;
		$meta_keywords = $listing->meta_tag;
		$meta_description = $listing->meta_description;
	}
	?>
	<title>{{$title}}</title>
	<meta name="description" content="{{$meta_description}}">
	<meta name="keywords" content="{{$meta_keywords}}">
    <!-- Bootstrap -->
    <link href="{{ asset($user_layout . '/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset($user_layout . '/css/bootstrap-select.min.css') }}" rel="stylesheet">
    <link href="{{ asset($user_layout . '/css/bootstrap-slider.min.css') }}" rel="stylesheet">
	
	<!-- Listing Css -->
	<!--<link href="{{ asset($user_layout . '/css/listing.css') }}" rel="stylesheet"> -->
	
    <!-- owl carousel -->
    <link href="{{ asset($user_layout . '/css/owl.carousel.css') }}" rel="stylesheet">
    <!-- Magnific Popup -->
	 <link href="{{ asset($user_layout . '/css/magnific-popup.css') }}" rel="stylesheet">
	<!-- font awesome -->
    <link href="{{ asset($user_layout . '/css/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset($user_layout . '/css/Stroke-Gap-Icons-Webfont.css') }}" rel="stylesheet">

	<!-- Revolution slider -->
	<link href="{{ asset($user_layout . '/assets/revolution/css/settings.css') }}" rel="stylesheet" type="text/css">
	<link href="{{ asset($user_layout . '/assets/revolution/css/layers.css') }}" rel="stylesheet" type="text/css">
	<link href="{{ asset($user_layout . '/assets/revolution/css/navigation.css') }}" rel="stylesheet" type="text/css">
	
	
	<!-- main stylesheet -->
    <link href="{{ asset($user_layout . '/css/main.css') }}" rel="stylesheet">
	
	<!-- product gallery stylesheet -->
	<link href="{{ asset($user_layout . '/css/lightslider.css') }}" rel="stylesheet">
	
    <!-- customize stylesheet -->
       <link href="{{ asset($user_layout . '/css/customize.css') }}" rel="stylesheet">
       <!-- Rating Plugin Css -->
	<link href="{{ asset($user_layout . '/assets/palette_rating/css/heat-rating.css') }}" rel="stylesheet" type="text/css">
	
	<!-- fonts -->
	<link href="https://fonts.googleapis.com/css?family=Great+Vibes%7CMontserrat:400,700%7COpen+Sans:300,300i,400,400i,600,600i,700,700i" rel="stylesheet">
<link rel="stylesheet" href="{{ asset($user_layout . '/css/jquery.mobile-1.4.5.min.css') }}">

   <link rel="stylesheet" type="text/css" href="{{ asset($user_layout . '/css/jcarousel.responsive.css') }}"> 
   <link rel="stylesheet" href="{{ asset($user_layout . '/css/fancybox.css?v=2.1.7') }}" type="text/css" media="screen" />
   
   <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/jssocials.css') }}" />
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/jssocials-theme-flat.css') }}" />


	@yield('head')
	@yield('header')
	
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
								
							</li>
							@endif
							
						
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
							<h2>urbanclap</h2>
						</div>
					</div>
                    <div class="col-md-4 col-md-offset-2 col-sm-5 col-xs-12">
						<div class="search">
						
						</div>
					</div>
					
					<div class="col-md-3 col-md-offset-1 col-sm-4 col-xs-12">
						
					</div> 
				</div>
			</div>
		</div><!-- /.end header second -->
		<div class="third-header hidden">
			<div class="container">
				<div class="row">
					<div class="col-md-9">
						<!-- navbar start -->
						<nav class="navbar navbar-default">
							<!-- Brand and toggle get grouped for better mobile display -->
							<div class="navbar-header">
								<span class="toggle-text">Menu</span>
								<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
									<span class="sr-only">Toggle navigation</span>
									<span class="icon-bar"></span>
									<span class="icon-bar"></span>
									<span class="icon-bar"></span>
								</button>
								<!-- <a class="navbar-brand" href="#">Brand</a> -->
							</div>

							<!-- Collect the nav links, forms, and other content for toggling -->
						
						</nav>				
					</div>
					<div class="col-md-3">
						<div class="social text-right">
							<a href="#"><i class="fa fa-facebook"></i></a>
							<a href="#"><i class="fa fa-twitter"></i></a>
							<a href="#"><i class="fa fa-google-plus"></i></a>
							<a href="#"><i class="fa fa-facebook"></i></a>
							<a href="#"><i class="fa fa-skype"></i></a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</header>
	<!-- end header -->
	<!-- Main content start -->
	
	@yield('content')

	<!-- Main content end -->

	
	

		

	
	
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="{{ asset($user_layout . '/js/jquery.min.js') }}"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="{{ asset($user_layout . '/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset($user_layout . '/js/bootstrap-select.min.js') }}"></script>
    <script src="{{ asset($user_layout . '/js/bootstrap-slider.min.js') }}"></script>
    <script src="{{ asset($user_layout . '/js/jquery.syotimer.min.js') }}"></script>
    <script src="{{ asset($user_layout . '/js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset($user_layout . '/js/jquery.magnific-popup.min.js') }}"></script>
    <script src="{{ asset($user_layout . '/js/isotope.pkgd.min.js') }}"></script>
	
	<!-- REVOLUTION JS FILES -->
	<script src="{{ asset($user_layout . '/assets/revolution/js/jquery.themepunch.tools.min.js') }}"></script>
	<script src="{{ asset($user_layout . '/assets/revolution/js/jquery.themepunch.revolution.js') }}"></script>
	<script type="text/javascript" src="{{ asset($user_layout . '/assets/revolution/js/extensions/revolution.extension.slideanims.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset($user_layout . '/assets/revolution/js/extensions/revolution.extension.layeranimation.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset($user_layout . '/assets/revolution/js/extensions/revolution.extension.navigation.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset($user_layout . '/assets/revolution/js/extensions/revolution.extension.video.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset($user_layout . '/assets/revolution/js/extensions/revolution.extension.actions.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset($user_layout . '/assets/revolution/js/extensions/revolution.extension.kenburn.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset($user_layout . '/assets/revolution/js/extensions/revolution.extension.migration.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset($user_layout . '/assets/revolution/js/extensions/revolution.extension.parallax.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset($user_layout . '/assets/revolution/js/extensions/revolution.extension.carousel.min.js') }}"></script>
	<script src="{{ asset($user_layout . '/js/jssor.slider-23.0.0.min.js') }}" type="text/javascript') }}"></script>
	<!--<script src="{{ asset($user_layout . '/js/jquery.mobile-1.4.5.min.js') }}"></script> -->

<!-- Rating Plugin Js -->
	<script type="text/javascript" src="{{ asset($user_layout . '/assets/palette_rating/js/heat-rating.js') }}"></script>
    <script src="{{ asset($user_layout . '/js/custom.js') }}"></script>
    <script type="text/javascript" src="{{ asset($user_layout . '/js/jquery.jcarousel.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset($user_layout . '/js/jcarousel.responsive.js') }}"></script>
	
	<script src="{{ asset('assets/js/js.cookie.js') }}"></script>
	<script type="text/javascript" src="{{URL::asset('assets/js/jquery.validate.js')}}"></script>
	
	
	 <script type="text/javascript" src="{{ asset($user_layout . '/js/radialIndicator.js') }}"></script>
 <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
<script src="{{ asset($user_layout . '/js/lightslider.js') }}"></script>
 <script src="{{ asset($user_layout . '/js/jquery.elevatezoom.js') }}"></script>
 
<script>
    $(document).ready(function() {
		
		$(document).on('click', '.close_button', function() {
			$('#modalAddToCart_online_order').modal('hide');
		});
			$("#content-slider").lightSlider({
                loop:true,
                keyPress:true
            });
            $('#image-gallery').lightSlider({
               gallery:true,
      item:1,
      vertical:false,
      verticalHeight:295,
      vThumbWidth:50,
      thumbItem:8,
      thumbMargin:4,
      slideMargin:0,
                onSliderLoad: function(el) {
                    $('#image-gallery').removeClass('cS-hidden');
                    
                }  
            });

    
		});
    </script>
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
			var fraction = (rating % 1).toFixed(1);
			if(fraction == '0.5') {
				this.$el.find('i.fa-star-o').first().removeClass('fa-star-o').addClass('fa-star-half-o');
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
    $(".starrr").starrr({
  readOnly: true
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
			<script>
			
		$(document).ready(function() {

    
    var readURL = function(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('.profile-pic').attr('src', e.target.result);
            }
    
            reader.readAsDataURL(input.files[0]);
        }
    }
    

    $(".file-upload").on('change', function(){
        readURL(this);
    });
    
    $(".upload-button").on('click', function() {
       $(".file-upload").click();
    });
});

			
			
	</script>
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBU7YTBgP-ZsBlVcYGYAfuz2os6yfTWLIg&libraries=places"></script>
	<script>
	var geolocate;
	var autocomplete;
	$(document).ready(function()
	{
		
		google.maps.event.addDomListener(window, 'load', initAutocompleteLoad);
		
		var geocoder = null;
		var placeSearch, autocomplete;
		// DEFINE YOUR MAP AND MARKER 
		var map = null, marker = null; 
		
		<?php
		if( isset($page_name) and $page_name == 'listing_detail' ):
		?>
		var lat = {{ $data['lat'] }};//console.log(lat);
		var lon = {{ $data['long'] }};//console.log(lon);
		var location = new google.maps.LatLng(lat, lon);
		<?php
		endif;
		?>
	
	function initAutocompleteLoad()
	{
		
		geocoder = new google.maps.Geocoder();
		
		<?php
		if( isset($page_name) and $page_name == 'listing_detail' ):
			?>
			var mapCanvas = document.getElementById('map');
			var mapOptions = {
				center: location,
				zoom: 14,
				panControl: false,
				scrollwheel: false,
				mapTypeId: google.maps.MapTypeId.ROADMAP
			}
			map = new google.maps.Map(mapCanvas, mapOptions);

			marker = new google.maps.Marker({
				position: location,
				//map: map,
				//icon: markerImage
			});
			
			marker.setMap(map);

			var contentString = '<div class="info-window">' +
			'<h3>{{ $listing->title }}</h3>' +
			'<div class="info-content">' +
			"<p>{!! $data['address'] !!}</p>" +
			'</div>' +
			'</div>';

			var infowindow = new google.maps.InfoWindow({
				content: contentString,
				maxWidth: 400
			});

			marker.addListener('click', function () {
				infowindow.open(map, marker);
			});

			var styles = [{"featureType": "landscape", "stylers": [{"saturation": -100}, {"lightness": 65}, {"visibility": "on"}]}, {"featureType": "poi", "stylers": [{"saturation": -100}, {"lightness": 51}, {"visibility": "simplified"}]}, {"featureType": "road.highway", "stylers": [{"saturation": -100}, {"visibility": "simplified"}]}, {"featureType": "road.arterial", "stylers": [{"saturation": -100}, {"lightness": 30}, {"visibility": "on"}]}, {"featureType": "road.local", "stylers": [{"saturation": -100}, {"lightness": 40}, {"visibility": "on"}]}, {"featureType": "transit", "stylers": [{"saturation": -100}, {"visibility": "simplified"}]}, {"featureType": "administrative.province", "stylers": [{"visibility": "off"}]}, {"featureType": "water", "elementType": "labels", "stylers": [{"visibility": "on"}, {"lightness": -25}, {"saturation": -100}]}, {"featureType": "water", "elementType": "geometry", "stylers": [{"hue": "#ffff00"}, {"lightness": -25}, {"saturation": -97}]}];

			map.set('styles', styles);
			<?php
		endif;
		?>
	}
	
		//get_item_images();
		var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
		function alllocations(){
			$('#alllocation').html('');
			$('#alllocation').show();
			var directionsService = new google.maps.DirectionsService(),
			directionsDisplay = new google.maps.DirectionsRenderer(),
			autocomplete = new google.maps.places.Autocomplete(
				(document.getElementById('alllocation')),
				{types: ['geocode']});
		}
		alllocations();
		function getCookie(name)
		{
			var re = new RegExp(name + "=([^;]+)");
			var value = re.exec(document.cookie);
			return (value != null) ? unescape(value[1]) : null;
		}
		function getlocationname(pos){
			$('#location').html('');
			$('#set_loc').val('');
			$.ajax({
				type:"get",
				url:"{{url('getlocation')}}",
				data:{'latitude':pos.lat,'longitude':pos.lng,'_token':CSRF_TOKEN},
				dataType:'json',
				async:false,
				success:function(data){
					var lat = data.results[0].geometry.location.lat,
					lng = data.results[0].geometry.location.lng;
					var place=lat+','+lng;
					
					// set cookie
					Cookies.set('current_lat', place);
					
					var curr_location = '';//console.log(data.results[0].address_components);
					var address_components = data.results[0].address_components;
					$.each( address_components, function(index, obj) {
						if(obj.types[0] == 'locality'){
							curr_location = obj.long_name;
							return false;
						} else if(obj.types[0] == 'administrative_area_level_1'){
							curr_location = obj.long_name;
							return false;
						}
					});
					if(curr_location == '' && typeof data.results[0].address_components[5] != 'undefined') {
						curr_location = data.results[0].address_components[5].long_name;
					}
					
					$('#location').html(curr_location+'<i class="fa fa-angle-down curr_loc" data-loc='+place+'></i>');
					$('#set_loc').val(place);
					<?php
					if( isset($page_name) and $page_name == 'listing' ):
						?>
						initAutocomplete(place);
						<?php endif;?>
						//$('#loc_submit').trigger('click');
						//$('#loc_submit').prop('disabled',true);

					}
				});
		}
		var current_lat = Cookies.get('current_lat');
		if (typeof(current_lat)  === 'undefined'|| current_lat==null)
		{
			navigator.geolocation.getCurrentPosition(function(position) {
				//var location = current_lat;//getCookie("key");
				
				var pos = {
					lat: position.coords.latitude,
					lng: position.coords.longitude
				};
				
				
				getlocationname(pos);
				

			},
			function (error) { //console.log(error);
				//if (error.code == error.PERMISSION_DENIED)
					var city = "{{Config::get('settings.defaultcity')}}";
				
				if(typeof(city)=='undefined' || city==null){
					
					if (typeof(current_lat)  === 'undefined'|| current_lat==null)
					{
						location = current_lat;
						var loc=location.split(',');
						var pos = {
							lat: loc[0],
							lng: loc[1]
						};	
					}
					
				} else {
					
					var loc=city.split(',');
					var pos = {
						lat: loc[0],
						lng: loc[1]
					};
					
				}
				if (typeof(pos)  !== 'undefined')
					getlocationname(pos);
			});
		}
		else
		{
			var loc=current_lat.split(',');
			var pos = {
				lat: loc[0],
				lng: loc[1]
			};
			getlocationname(pos);
		}


		function initAutocomplete(place) {
			var location=place.split(',');
			var directionsService = new google.maps.DirectionsService();
			directionsDisplay = new google.maps.DirectionsRenderer();
			var bangaloreBounds = new google.maps.LatLngBounds(
				new google.maps.LatLng(location[0], location[1]),
				new google.maps.LatLng(location[0], location[1]));

			autocomplete = new google.maps.places.Autocomplete((document.getElementById('autocomplete')), {
				bounds: bangaloreBounds,
				strictBounds: true,
			});
			autocomplete.addListener('place_changed', function () {
			});
		}	
		
		$('#alllocation').change(function(){
			$('#location').html('');
			$('#set_loc').val('');
			var firstResult = $(".pac-container .pac-item:first").text();
			var place='';
			var geocoder = new google.maps.Geocoder();
			geocoder.geocode({"address":firstResult },
				function(results, status) {
					if (status == google.maps.GeocoderStatus.OK) {
						console.log(results);
						var lat = results[0].geometry.location.lat(),
						lng = results[0].geometry.location.lng(),
						placeName = results[0].address_components[0].long_name,
						latlng = new google.maps.LatLng(lat, lng);
						place=lat+','+lng;
						$('#location').html(placeName+'<i class="fa fa-angle-down curr_loc" data-loc='+place+'></i>');
						$('#set_loc').val(place);
						document.cookie="key="+place+";";
						Cookies.set('current_lat', place);
						<?php
						if( isset($page_name) and $page_name == 'listing' ):
							?>
							initAutocomplete(place);
							<?php endif;?>
							$('#alllocation').hide();
							alllocations();
							var cr_loc=$('#set_loc').val();
							var currentURL=window.location.href.split('?')[0];
							window.location.href = currentURL+"?locid="+cr_loc;
					/*var url=window.location.href; 
					window.location.href=url;*/
					
					
				}
			});
		});	


		/*** listing page start ***/
		<?php
		if( isset($page_name) and $page_name == 'listing' ):
			?>
			$("#autocomplete").change(function(){
			//var getSelectedIndex=this.value;
			//var cattype=$('#Location option:selected').text();
			var firstResult = $(".pac-container .pac-item:first").text();
			var place='';
			var cattype=$('#subCategory option:selected').data('slug');
			var catVal=($('#subCategory').val()==''?0:$('#subCategory').val());
			
			geocoder.geocode({"address":firstResult },
				function(results, status) {
					if (status == google.maps.GeocoderStatus.OK) {
						var lat = results[0].geometry.location.lat(),
						lng = results[0].geometry.location.lng(),
						placeName = results[0].address_components[0].long_name,
						latlng = new google.maps.LatLng(lat, lng);
						place=lat+','+lng;
						window.location.href = "{{ url('/listing') . $jq_slug}}?locid="+place+"&catid="+catVal+"&tag={{$tag}}";
						document.cookie="key="+place+";";
						Cookies.set('current_lat', place);
					}
					initAutocomplete(place);
				});
			
			
		});
			$("#subCategory").change(function(){
				var getSelectedIndex=this.value;
				var catVal=($('#subCategory').val()==''?0:$('#subCategory').val());
			//alert(cattype);
			var firstResult = $(".pac-container .pac-item:first").text();
			var place='';
			var geocoder = new google.maps.Geocoder();
			geocoder.geocode({"address":firstResult },
				function(results, status) {
					if (status == google.maps.GeocoderStatus.OK) {
						var lat = results[0].geometry.location.lat(),
						lng = results[0].geometry.location.lng(),
						placeName = results[0].address_components[0].long_name,
						latlng = new google.maps.LatLng(lat, lng);
						place=lat+','+lng;
						
						window.location.replace("{{ url('/listing') . $jq_slug}}?locid="+place+"&catid="+catVal+"&tag={{$tag}}");
					}
					else{
						var cr_loc=$('#set_loc').val();
						window.location.replace("{{ url('/listing') . $jq_slug}}?locid="+cr_loc+"&catid="+catVal+"&tag={{$tag}}");
					}
					/*else{
						window.location.replace("{{ url('') }}/listing/{{$slug}}?locid=0&catid="+catVal+"&name=none");
					}*/
				});

		}); 
		/*$("#fltHotelName").change(function(){
			var getSelectedIndex=this.value;
			var filter=$('#fltHotelName option:selected').text();
			var locVal=($('#Location').val()==''?0:$('#Location').val());
			var catVal=($('#subCategory').val()==''?0:$('#subCategory').val());
			window.location.href = "{{ url('') }}/listing/"+filter+"?locid="+locVal+"&catid="+catVal+"&name="+getSelectedIndex;
		});*/
		$('.radius').click(function(){
			var radius=$(this).data('km');
			var getSelectedIndex=this.value;
			var catVal=($('#subCategory').val()==''?0:$('#subCategory').val());
			//alert(cattype);
			var firstResult = $(".pac-container .pac-item:first").text();
			var place='';
			var geocoder = new google.maps.Geocoder();
			geocoder.geocode({"address":firstResult },
				function(results, status) {
					if (status == google.maps.GeocoderStatus.OK) {
						var lat = results[0].geometry.location.lat(),
						lng = results[0].geometry.location.lng(),
						placeName = results[0].address_components[0].long_name,
						latlng = new google.maps.LatLng(lat, lng);
						place=lat+','+lng;
						window.location.replace("{{ url('/listing') . $jq_slug}}?locid="+place+"&catid="+catVal+"&radius="+radius+"&tag={{$tag}}");
					}
					else{
						var cr_loc=$('#set_loc').val();
						window.location.replace("{{ url('/listing') . $jq_slug}}?locid="+cr_loc+"&catid="+catVal+"&radius="+radius+"&tag={{$tag}}");
					}
				});
		});

		geolocate = function () {
			var loc = $('#set_loc').val();
			if(loc)
			{
				var location=loc.split(',');	
				
				var circle = new google.maps.Circle({
					center: new google.maps.LatLng(location[0],  location[1]),
					radius: 2000
				});
				autocomplete.setBounds(circle.getBounds());
				
				/*if (navigator.geolocation) {
					navigator.geolocation.getCurrentPosition(function(position) {
						var geolocation = {
							lat: location[0],
							lng: location[1]
						};
						var circle = new google.maps.Circle({
							center: new google.maps.LatLng(location[0],  location[1]),
							radius: 2000
						});
						autocomplete.setBounds(circle.getBounds());

					});
				}*/
			}

		}
		<?php
	endif;
	?>
	/*** listing page end ***/
	
	
	/*** listing detail start ***/
	<?php
	if( isset($page_name) and $page_name == 'listing_detail' ):
		?>			
		
		$("a[href='#map-tag']").on('shown.bs.tab', function(){
			google.maps.event.trigger(map, 'resize');
			//var newmapcenter = map.getCenter(); 
			map.setCenter(marker.getPosition());
		});
		
		//google.maps.event.addDomListener(window, 'load', initMap);
		
		
		<?php
	endif;
	?>		
	/*** listing detail end ***/
	

});
$(document).ready(function(){
	var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
	
	$("#search_text").keyup(function(){
		
		var key = $(this).val();
		$("#keyword-list").hide();
		$("#search_text").attr('data-slug', '');
		
		$.ajax({
			type: "POST",
			url: "{{url('keyword_suggestion')}}",
			data: {'keyword':key, '_token':CSRF_TOKEN},
			beforeSend: function(){
				$("#search_text").css("background","#FFF url({{url('LoaderIcon.gif')}}) no-repeat 165px");
			},
			success: function(data){
				$("#suggesstion-box").show();
				$("#suggesstion-box").html(data);
				$("#search_text").css("background","#FFF");
				
				if(data)
					$("#keyword-list").show();
			}
		});
	});
	
	$("body").delegate('#keyword-list .dropdown-item', 'click', function(){
		var slug = $(this).data('slug');
		var txt = $(this).html();
		$("#search_text").val(txt);
		$("#search_text").attr('data-slug', slug);
		$("#suggesstion-box").hide();
	});
	<?php
	if(!isset($jq_slug))
		$jq_slug = '';
	?>
	$('form[name=srchForm]').submit(function(){
		var current_lat = Cookies.get('current_lat');
		var tag = $("#search_text").data('slug');
		var catVal=($('#subCategory').val()==''?0:$('#subCategory').val());
		var radius=$('.radius').data('km');
		if(tag)
		{
			var url = "{{url('/listing')}}"+"?tag="+tag;
			url = "{{ url('/listing') . $jq_slug}}?locid="+current_lat+"&catid="+catVal+"&radius="+radius+"&tag="+tag;
			window.location.href = url;
		}
		else
			{return false;
				var keyword = $("#search_text").val();
				if(keyword) {
					keyword = encodeURIComponent(keyword);
					window.location.href = "{{url('/listing')}}/?keyword="+keyword;
				}
			}
			return false;
		});
});
</script>
			
		@yield('footer')
		
  </body>

</html>