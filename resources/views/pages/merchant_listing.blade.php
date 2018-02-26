@extends('layouts.'.$user_layout)
@section('content')
@include('partials.status-panel')

<!--
<meta property="og:url"    content="{{url()->current()}}" />
<meta property="og:type"   content="Needifo.com" />
<meta property="og:title"  content="{{$listing->title}}" />
<meta property="og:address" content="{{ $data['header_address'] }}" />
<meta property="og:image"  content="{{$listing->image}}" />
<meta property="og:description" content="{{$listing->description}}" /> -->

@section('head')

<link rel="stylesheet" type="text/css" href="{{ asset($merchant_layout . '/css/jcarousel.responsive.css') }}"> 
<link rel="stylesheet" href="{{ asset($merchant_layout . '/css/fancybox.css?v=2.1.7') }}" type="text/css" media="screen" />
<link rel="stylesheet" type="text/css" href="{{ asset($merchant_layout .'/dropzone/dropzone.css') }}">
@stop

<style>
.list-image{
	position: absolute;
	top: 5%;
	width: 60px;
	left: 5%;
}
.list-image:hover{
	-moz-box-shadow: 0 0 10px #ccc;
		-webkit-box-shadow: 0 0 10px #ccc;
		box-shadow: 0 0 10px #ccc;
}
.tooltip{
	display: inline;
	position: relative;
}
.gallery_block span{position: absolute;
    top: 0px;
    right: 18px;
    font-size: 26px;
}
.gallery_block span{position: absolute;
    top: 0px;
    right: 18px;
    font-size: 26px;
}
.gallery_block span a{color:#dc0d0d;
}

</style>
<div class="main-content">
	<!-- menu start -->
 	<?php /*<section class="menu">
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
     </section>*/?>
     <!-- menu end -->
     <!-- banner-logo start -->
     <section class="banner-logo bg-color">
     	<div class="container">
     		<div class="col-md-12 mt10">
     			<img src="{{ url('') }}/uploads/listing/original/{{ $listing->image }}" class="img-responsive">
     			<img src="{{url('')}}/assets/images/camera.png" alt="change image" class="img-responsive list-image " width="25px" title="Upload new image" class="tooltip">
     		</div>

     		<div class="col-md-12 mt10">
     			<div class="col-md-6">
     				<h4>{{ $listing->title }}</h4>
     				<h5>{{ $data['header_address'] }}</h5>
     				<form id="list-image-form">
     					<input type="file" id="listing_image" name="listing_image" style="display:none;"/><br>
     					<input type="hidden" name="listing_id" value="{{$listing->id}}">
     					<input type="hidden" name="_token" value="{{csrf_token()}}">
     				</form>
     			</div>
     			<div class="col-md-6">
     				<div class="rating_box_layout">
     					<div class="rating_count">Ratings:<div class="ratings">{{number_format($avgrating,1)}}</div> / {{sizeof($reviews)}} Reviews</div>
     					<div class="heat-rating">
     						<div class="rating-block one" data-value="1.0"></div>

     						<div class="rating-block one-half" data-value="1.5"></div>

     						<div class="rating-block two" data-value="2.0"></div>

     						<div class="rating-block two-half" data-value="2.5"></div>

     						<div class="rating-block three" data-value="3.0"></div>

     						<div class="rating-block three-half" data-value="3.5"></div>

     						<div class="rating-block four" data-value="4.0"></div>

     						<div class="rating-block four-half" data-value="4.5"></div>

     						<div class="rating-block five" data-value="5.0"></div>						

     						<input type="hidden" value="4.5" id="ratings-input">
     					</div>
     				</div>
     			</div>

     		</div>
     	</div>
     </section>
     <!-- banner-logo end -->

     <!-- content-tab start -->
     <section class="tab-content-details bookatable">
     	<div class="container">
     		<div class="col-md-12 pad0">
     			<div >
     				@php
     				$info_active = 'active';

     				@endphp

     				<!-- Nav tabs -->
     				<ul class="nav nav-tabs" role="tablist">
     					<li role="presentation" class="{{ $info_active }}"><a href="#info" aria-controls="home" role="tab" data-toggle="tab">
     						<!-- tab info start -->
     						<div class="tab-info">
     							<i class="fa fa-id-card-o" aria-hidden="true"></i>
     							<h3>Information</h3>
     						</div>
     						<!-- tab info start -->

     					</a></li>
     					@if($orderonline_menus)
     					<li role="presentation" class=""><a href="#order" aria-controls="home" role="tab" data-toggle="tab">
     						<!-- tab info start -->
     						<div class="tab-info">
     							<i class="fa fa-shopping-cart" aria-hidden="true"></i>
     							<h3>order online</h3>
     						</div>
     						<!-- tab info start -->

     					</a></li>
     					@endif
     					@if ($tablebookingsettings)
     					<li role="presentation" class=""><a href="#book_a_table" aria-controls="home" role="tab" data-toggle="tab">
     						<!-- tab info start -->
     						<div class="tab-info">
     							<i class="fa fa-calendar-check-o" aria-hidden="true"></i>
     							<h3>book  a table</h3>
     						</div>
     						<!-- tab info start -->

     					</a></li>
     					@endif
     					@if ($appointmentbookingsettings)
     					<li role="presentation" class=""><a href="#book_appointment" aria-controls="home" role="tab" data-toggle="tab">
     						<!-- tab info start -->
     						<div class="tab-info">
     							<i class="fa fa-calendar-check-o" aria-hidden="true"></i>
     							<h3>book  appointment</h3>
     						</div>
     						<!-- tab info start -->

     					</a></li>
     					@endif
     					@if ($Products)
     					<li role="presentation" class=""><a href="#shop" aria-controls="home" role="tab" data-toggle="tab">
     						<!-- tab info start -->
     						<div class="tab-info">
     							<i class="fa fa-shopping-bag" aria-hidden="true"></i>
     							<h3>Shop Online</h3>
     						</div>
     						<!-- tab info start -->

     					</a></li>
     					@endif
     					<li role="presentation" class=""><a href="#review" aria-controls="home" role="tab" data-toggle="tab">
     						<!-- tab info start -->
     						<div class="tab-info">
     							<i class="fa fa-user" aria-hidden="true"></i>
     							<h3>review</h3>
     						</div>
     						<!-- tab info start -->

     					</a></li>
     					@if($listing->listing_merchant->mobile_no)
     					<li role="presentation" class=""><a href="#call" aria-controls="home" role="tab" data-toggle="tab">
     						<!-- tab info start -->
     						<div class="tab-info">
     							<i class="fa fa-phone" aria-hidden="true"></i>
     							<h3>call</h3>
     						</div>
     						<!-- tab info start -->

     					</a>
     				</li>
     				@endif
					@if($enable_listing_model['map'])
     				<li role="presentation" class=""><a href="#map-tag" aria-controls="home" role="tab" data-toggle="tab">
     					<!-- tab info start -->
     					<div class="tab-info">
     						<i class="fa fa-map-marker" aria-hidden="true"></i>
     						<h3>map</h3>
     					</div>
     					<!-- tab info start -->

     				</a></li>
					@endif
					@if($enable_listing_model['share'])
     				<li role="presentation" class=""><a href="#share" aria-controls="home" role="tab" data-toggle="tab">
     					<!-- tab info start -->
     					<div class="tab-info">
     						<i class="fa fa-share-alt" aria-hidden="true"></i>
     						<h3>share</h3>
     					</div>
     					<!-- tab info start -->

     				</a></li>
					@endif
				@if($enable_listing_model['gallery'])
					    <li role="presentation" class=""><a href="#gallery" aria-controls="home" role="tab" data-toggle="tab">
					    <!-- tab info start -->
					    	<div class="tab-info">
					    	<i class="fa fa-picture-o" aria-hidden="true"></i>
					    		<h3>Gallery</h3>
					    	</div>
					    	<!-- tab info start -->

					    </a></li>					
						@endif
     			</ul>

     			<!-- Tab panes -->
     			<div class="tab-content">
     				<div role="tabpanel" class="tab-pane {{ $info_active }}" id="info">
     					@include('pages.listing_detail.merchant_listing_info')

     				</div>
     				@if($orderonline_menus)
     				<div role="tabpanel" class="tab-pane " id="order">

     					@include('pages.listing_detail.merchant_own_listing.order_online')

     				</div>
     				@endif
     				@if ($tablebookingsettings)
     				<div role="tabpanel" class="tab-pane" id="book_a_table">

     					@include('pages.listing_detail.merchant_own_listing.book_a_table')

     				</div>
     				@endif
     				@if ($appointmentbookingsettings)
     				<div role="tabpanel" class="tab-pane" id="book_appointment">

     					@include('pages.listing_detail.merchant_own_listing.book_appointment')

     				</div>
     				@endif
     				@if ($Products)
     				<div role="tabpanel" class="tab-pane" id="shop">

     					@include('pages.listing_detail.merchant_own_listing.shop')

     				</div>
     				@endif
     				<div role="tabpanel" class="tab-pane " id="review">

     					@include('pages.listing_detail.merchant_own_listing.review')

     				</div>
     				<div role="tabpanel" class="tab-pane " id="call">
     					<div class="col-md-12 pad0">

     						<div class="click_to_text call-function slide1 mb30">
     							Click To Reveal					    					
     						</div>
     						<div class="call-function slide2 click_to_phone hidden mb30">
     							<span class="tc">9876534210</span>
     						</div>
     					</div>


     				</div>
     				<div role="tabpanel" class="tab-pane " id="map-tag">
     					<div class="col-md-12 pad0">
     						<h2> Address :</h2>
     						<address>
     							{!! $data['address'] !!}
     						</address>
     					</div>
     					<div class="col-md-12 pad0">
     						<div id="map"></div>
     					</div>


     				</div>
     				<div role="tabpanel" class="tab-pane " id="share">
     					<div class="col-md-12 pad0 mt10 mb30">	
     						<div  id="sharePopup" style="text-align: center;"></div>
					    				 	<div class="col-md-6 col-md-offset-3 ">
					    				 		<div class="form-group">
					    				 			<input type="text" name="search" placeholder="{{url('/').'/'.$listing->slug}}" class="form-control">
					    				 		</div>
					    				 	</div>


					    				 </div>
					    				</div>
					    				<div role="tabpanel" class="tab-pane " id="save">.8.</div>
   <!-- gallery start -->
								 <div role="tabpanel" class="tab-pane" id="gallery">
							
								
								
								<div class="gallery_block" id="editfil">
								
								</div>
								<!-- gallery end -->


					    			</div>

					    		</div>


					    	</div>
					    </div>
					</section>


					<!-- content-tab end -->

				</div>
@stop
								
@section('footer')	
				<?php /*<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBU7YTBgP-ZsBlVcYGYAfuz2os6yfTWLIg"></script>*/?>
				<script type="text/javascript">
					$(document).ready(function () {
		//initMap();
		
		/*** book a table ***/
		$("#table_booking_section .cls_booking_date").on('click', function(){
			$booking_date = $(this).data('booking_date');
			$('#bookForm #booking_date').val($booking_date);
			$('#bookForm #booking_time').val('');
		});
		$("#table_booking_section .cls_booking_time").on('click', function(){
			$booking_time = $(this).data('booking_time');
			$('#bookForm #booking_time').val($booking_time);
		});
		$("#table_booking_section .cls_no_of_people").on('click', function(){
			$no_of_people = $(this).data('no_of_people');
			$('#bookForm #no_of_people').val($no_of_people);
		});
		$("#bookForm button").on('click', function(){
			var post = $("#bookForm").serialize();
			
			$("#bookForm .alert").html('').addClass('hidden');
			$('.image_loader').show();
			
			$.post("{{ url('book-a-table') }}", post, function(data){
				var result = data;//$.parseJSON(data) ;
				if(result.status == 0 )
				{
					var msg = result.msg;
					var err = '';
					$.each(msg, function(index, value)
					{
						err += value + '<br />';
					});//alert(err);
					$("#bookForm .alert-danger").html(err).removeClass('hidden');
				}
				else
				{
					$('#bookForm')[0].reset();
					$("#bookForm .alert-success").html(result.msg).removeClass('hidden');
				}
				
				$('.image_loader').hide();
			})
		});
		/*** book a table ***/
		
		/*** book appointment ***/
		$("#appointment_booking_section .cls_booking_date").on('click', function(){
			$booking_date = $(this).data('booking_date');
			$('#bookAppointmentForm #booking_date').val($booking_date);
			$('#bookAppointmentForm #booking_time').val('');
		});
		$("#appointment_booking_section .cls_booking_time").on('click', function(){
			$booking_time = $(this).data('booking_time');
			$('#bookAppointmentForm #booking_time').val($booking_time);
		});
		$("#appointment_booking_section .cls_no_of_people").on('click', function(){
			$no_of_people = $(this).data('no_of_people');
			$('#bookAppointmentForm #no_of_people').val($no_of_people);
		});
		$("#bookAppointmentForm button").on('click', function(){
			var post = $("#bookAppointmentForm").serialize();
			
			$("#bookAppointmentForm .alert").html('').addClass('hidden');
			$('.image_loader').show();
			
			$.post("{{ url('book-appointment') }}", post, function(data){
				var result = data;//$.parseJSON(data) ;
				if(result.status == 0 )
				{
					var msg = result.msg;
					var err = '';
					$.each(msg, function(index, value)
					{
						err += value + '<br />';
					});
					$("#bookAppointmentForm .alert-danger").html(err).removeClass('hidden');
				}
				else
				{
					$('#bookAppointmentForm')[0].reset();
					$("#bookAppointmentForm .alert-success").html(result.msg).removeClass('hidden');
				}
				
				$('.image_loader').hide();
			})
		});
		/*** book appointment ***/

		/** Save Listing**/
		$(document).on('click','.save_list',function(){
			var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
			if("{{Auth::user()}}"){
				var id=$(this).data('id');
				$.ajax({
					type:"post",
					url:"{{url('')}}/user/saveContact",
					dataType:"json",
					data:{'listing_id':id,'_token':CSRF_TOKEN},
					success:function(){
						window.location.href = "{{url('/user')}}";
					}
				});
				
			}
			else{
				window.location.href = "{{url('/login')}}";
			}
		});
		$(document).on('click','.list-image',function(){
			$('#listing_image').trigger('click');
		});
		$(document).on('change','#listing_image',function(){
			$('#list-image-form').submit();
		});
		$('#list-image-form').submit(function(e){
			e.preventDefault();
			var formData = new FormData(this);
			var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
			$.ajax({
				type:'POST',
				url: "{{url('')}}/merchant/changeImage",
				data:formData,
				cache:false,
				contentType: false,
				processData: false,
				success:function(data){
					if(data==1){
						location.reload();
					}
				},
				error: function(data){
					console.log("error");
					console.log(data);
				}
			});
		});
	});

$(document).on("click",".add_item_via_ajax_form", add_cart);
function add_cart()
{
	var submit_url = "{{ url('online-order/add-to-cart') }}";

		//event.preventDefault();
		$type = $(this).data('type');
		$id = $(this).data('item_id');
		$name = $(this).data('name');
		$price = $(this).data('price');
		$listing_id = $(this).data('listing_id');
		
		
		$quantity = '';
		if( $type == '' )
		{
			$quantity = 1;
		}
		else if( $type == 'plus' )
		{
			$quantity = parseInt($('#quantity_'+$id).val()) + 1;
			
			$('#quantity_'+$id).val($quantity);
			submit_url = "{{ url('online-order/update-cart') }}";
		}
		else if( $type == 'minus' )
		{
			$quantity = parseInt($('#quantity_'+$id).val()) - 1;
			
			if($quantity <= 0) {
				alert('Quantity should be greater than or equal to 1');
				return false;
			}
			
			$('#quantity_'+$id).val($quantity);
			submit_url = "{{ url('online-order/update-cart') }}";
		}

		if($quantity == '') {
			alert('Please enter quantity');
			return false;
		}
		
		ajax_add_to_cart(submit_url, $type, $id, $name, $price, $listing_id, $quantity);

	}
	$(document).on("change",".cls_quantity", add_cart_by_change);
	function add_cart_by_change()
	{
		var submit_url = "{{ url('online-order/update-cart') }}";
		
		//event.preventDefault();
		$id = $(this).data('item_id');
		$name = $(this).data('name');
		$price = $(this).data('price');	
		$listing_id = $(this).data('listing_id');	
		$quantity = $(this).val();

		if($quantity == '') {
			alert('Please enter quantity');
			return false;
		} else if($quantity <= 0) {
			alert('Quantity should be greater than or equal to 1');
			return false;
		}
		
		ajax_add_to_cart(submit_url, 'change', $id, $name, $price, $listing_id, $quantity);

	}
	
	function ajax_add_to_cart(submit_url, $type, $id, $name, $price, $listing_id, $quantity)
	{
		
		var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

		$.ajax(
		{
			url: submit_url,
			type: 'POST',
			data: {add_item: 'yes', item_id: $id, name: $name, price: $price, quantity: $quantity, listing_id: $listing_id, '_token':CSRF_TOKEN},
			dataType: "json",
			success:function(response)
			{
				//response = $.parseJSON( response );
				if(response.status) {
					
					if($type == '')
					{
						$('#plus_minus_'+$id).removeClass('hidden');
						$('#add_button_'+$id).addClass('hidden');
					}
					
					var data = response.data;
					ajax_update_mini_cart(data);
				} else {
					alert(response.msg);
				}
			}
		});
	}
	
	function ajax_update_mini_cart(ajax_mini_cart)
	{
		// Replace the current mini cart with the ajax loaded mini cart data. 
		//var ajax_mini_cart = $(data).find('#mini_cart');
		$('#mini_cart').replaceWith(ajax_mini_cart);
		
		$('#modalAddToCart_online_order').modal('show');
	}
	
	$(document).on("click",".click_to_text", click_to_reveal);
	function click_to_reveal()
	{
		$('.click_to_phone').removeClass('hidden');
		$('.click_to_text').addClass('hidden');
	}
</script>
<script src="{{ asset('assets/js/jssocials.min.js') }}"></script>
<script>
	$("#sharePopup").jsSocials({
		shareIn: "popup",
		showLabel: false,
		shares: [ "facebook", "twitter", "googleplus", "linkedin", "pinterest", "whatsapp", "stumbleupon"]
	});
</script>

<script>
	var AuthUser = "{{{ (Auth::user()) ? Auth::user()->id : null }}}";
</script>
<script>
	$(document).ready(function(){
		
		$('#allreviews div:first-child').toggleClass('showcomments');
		$('.showcomments').css('display','block');
		$('.starrr').removeAttr('style');
		var $group = $('.group');
		$("#seemore").click(function() {
			if ($(this).hasClass('disable')) return false;
			var $hidden = $group.filter(':hidden').toggleClass('showcomments');
			$('.showcomments').css('display','block');
			$('.starrr').removeAttr('style');
		});
		
		var page = 1;
		$("#seemore").click(function() {
			/*if ($(this).hasClass('disable')) return false;
			var $hidden = $group.filter(':hidden').toggleClass('showcomments');
			$('.showcomments').css('display','block');
			$('.starrr').removeAttr('style');*/
			page = page+1;
			load_more(page);
		});
		
		function load_more(page)
		{
			$.ajax(
			{
				url: "{{url('listing/get_reviews/'.$listing->id)}}" + '?page=' + page,
				type: "get",
				datatype: "html",
				beforeSend: function()
				{
					$('#seemore_section').hide();
					$('.ajax-loading').show();
				}
			})
			.done(function(res)
			{
				var reviews = res.data;
				var current_page = res.current_page;
				var last_page = res.last_page;
				
				if(reviews.length == 0) {					
					$('.ajax-loading').html("No more records!");
					return;
				}
				
				var review_html = '';
				$.each(reviews, function(index, review) {
					var reviewer_name = review.user.first_name;
					reviewer_name = reviewer_name.toLowerCase().replace(/\b[a-z]/g, function(letter) {
						return letter.toUpperCase();
					});
					var jsdate = new Date(review.created_at);
					
					var f;
					var txtWords = [
						'Sun', 'Mon', 'Tues', 'Wednes', 'Thurs', 'Fri', 'Satur',
						'January', 'February', 'March', 'April', 'May', 'June',
						'July', 'August', 'September', 'October', 'November', 'December'
					];
					var _pad = function (n, c) {
						n = String(n)
						while (n.length < c) {
						  n = '0' + n
						}
						return n
					};
					f = {
						// Day
						d: function () {
						  // Day of month w/leading 0; 01..31
						  return _pad(f.j(), 2)
						},
						j: function () {
						  // Day of month; 1..31
						  return jsdate.getDate()
						},
						M: function () {
						  // Shorthand month name; Jan...Dec
						  return f.F()
							.slice(0, 3)
						},
						F: function () {
						  // Full month name; January...December
						  return txtWords[6 + f.n()]
						},
						n: function () {
						  // Month; 1...12
						  return jsdate.getMonth() + 1
						}
					};
					
					var formatChr = /\\?(.?)/gi
					var formatChrCb = function (t, s) {
						return f[t] ? f[t]() : s
					}
					var _date = function (format) {
						return format.replace(formatChr, formatChrCb)
					};
					
					var date_format = _date('M d,') + jsdate.getFullYear();
					
					review_html += '<div class="col-md-12 pad0 group showcomments">'
									+ "<span class='head-span'><div id='stars-existing' class='starrr' data-rating='"+ review.rating +"'></div></span>"
									+ '<h4>By: '+reviewer_name+'. on '+date_format+'</h4>'
									+ '<p>'+review.comments+'</p>'
									+ "</div>";
				});
				
				$('.ajax-loading').hide();
				$("#allreviews").append(review_html);
				
				if(current_page < last_page) {
					$('#seemore_section').show();
				}
				
				$(".starrr").starrr({
				  readOnly: true
				});  
			})
			.fail(function(jqXHR, ajaxOptions, thrownError)
			{
				  alert('No response from server');
			});
		}
	});
</script>
<script type="text/javascript" src="{{ asset($merchant_layout . '/js/jquery.fancybox.pack.js?v=2.1.7') }}"></script>
<script type="text/javascript">
$(document).ready(function() {
	$(".fancybox").fancybox({
		openEffect	: 'none',
		closeEffect	: 'none'
	});
});
	$(document).ready(function() {
    $("#single_1").fancybox({
          helpers: {
              title : {
                  type : 'float'
              }
          }
      });

    $("#single_2").fancybox({
    	openEffect	: 'elastic',
    	closeEffect	: 'elastic',

    	helpers : {
    		title : {
    			type : 'inside'
    		}
    	}
    });

    $("#single_3").fancybox({
    	openEffect : 'none',
    	closeEffect	: 'none',
    	helpers : {
    		title : {
    			type : 'outside'
    		}
    	}
    });

    $("#single_4").fancybox({
    	helpers : {
    		title : {
    			type : 'over'
    		}
    	}
    });
});
</script>
<script type="text/javascript" src="{{ asset($merchant_layout . '/dropzone/dropzone.min.js') }}"></script>
<script>
									
									
var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

Dropzone.options.dropzonePreview = {
	// Prevents Dropzone from uploading dropped files immediately
	//dropzonePreview.autoDiscover = false;
	url: "{{ url('merchant/gallery/image_upload') }}",
	autoProcessQueue: true,
	maxFiles: 3,
	uploadMultiple: false,
	init: function() {
		//var submitButton = document.querySelector("#submit-all")
		myDropzone = this; // closure

		this.on("sending", function(data, xhr, formData) { //alert(jQuery("#title").val());
			formData.append("_token", CSRF_TOKEN);	
			
			var d = new Date();				
			formData.append("timestamp", d.getTime());

		});
		
		this.on("success", function(file_response, obj) {console.log(obj);
			//var obj = jQuery.parseJSON(server_response);
			var status = obj.status;
			if(status == 0) {
				alert(obj.msg);
			} else {
				//$('#added_id').val(obj.id);
				
				get_item_images();
				
				//myDropzone.removeAllFiles();
			}
		});
	}
};
function get_item_images() {
	$.ajax({
		type: 'POST',
		data:{'_token': CSRF_TOKEN},
		url: "{{ url('merchant/gallery/get_uploaded_images') }}",
		dataType: "json", // data type of response		
		beforeSend: function(){
			$('.image_loader').show();
		},
		complete: function(){
			$('.image_loader').hide();
		},success:renderListImages

	});
}

function renderListImages(res){ 
$('#fil').html('');
	$('#editfil').html('');
	var thumb_url = "{{ url('/upload/images/medium') }}/";
	$.each(res, function(index, prolist) { 		
		if(prolist.id) {
			//$('#fil').append('<div class="dz-preview dz-processing dz-image-preview dz-success" id="list_image_'+prolist.id+'"><div class="dz-details"><img data-dz-thumbnail="" alt="'+prolist.image_name+'" src="'+thumb_url+prolist.image_name+'"></div><div class="dz-progress"><span class="dz-upload" data-dz-uploadprogress="" style="width: 100%;"></span></div><div class="dz-error-message"><span data-dz-errormessage=""></span></div><a class="dz-remove" href="javascript:void(0)" onClick="delete_listing_image_temp(\''+prolist.id+'\')">Remove</a></div>');
		 $('#editfil').append('<div class="col-sm-4" id="list_image_'+prolist.id+'"><a class="fancybox" rel="gallery" href="'+thumb_url+prolist.image_name+'" ><img src="'+thumb_url+prolist.image_name+'"  class="img-responsive" alt="" /></a><span><a  href="javascript:void(0)" onClick="delete_listing_image_temp(\''+prolist.id+'\')"><i class="fa fa-times" aria-hidden="true"></i></a></span></div>');
		
		}
	});
}



function delete_listing_image_temp(id)
{
	if (true) {
		//confirm("This action will delete this image permanently. Are you sure want to perform this action?") == 
		var id = id;
		$.ajax({
			url:"{{ url('merchant/gallery/delete_image') }}/"+id,
			success:function(data)
			{

				$('#list_image_'+id).hide();
				//get_item_images();

			},

		});
	}
}

$(document).ready(function(){
	get_item_images();
	
	// navigate tab on add listing page
	$('.cls_next').click(function(){
		var tab = $(this).data('next');
		activaTab(tab)
	});
	
	function activaTab(tab){
		$('.nav-tabs a[href="#' + tab + '"]').tab('show');
	};
});	
									</script>
@stop