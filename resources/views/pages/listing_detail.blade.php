@extends('layouts.'.$user_layout)

@section('header')
@section('head')

<link rel="stylesheet" type="text/css" href="{{ asset($merchant_layout . '/css/jcarousel.responsive.css') }}"> 
<link rel="stylesheet" href="{{ asset($merchant_layout . '/css/fancybox.css?v=2.1.7') }}" type="text/css" media="screen" />
@stop
<style>
section.banner-logo.bg-color {
	background: url("{{ url('') }}/uploads/listing/original/{{ $listing->image }}");
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
</style>

@if ($Products)
	<style>
  .ui-widget-header {
    background: #f57629 !important;
  }
  .ui-state-active{
    background: #0089cf !important;
  }
   .productinfo {
    position: relative;
	}
	.product-overlay {
		background: #FE980F;
		top: 0;
		display: none;
		height: 0;
		position: absolute;
		transition: height 500ms ease 0s;
		width: 100%;
		display: block;
		opacity: ;
	}
	.product-overlay .overlay-content {
		bottom: 0;
		position: absolute;
		bottom: 0;
		text-align: center;
		width: 100%;
	}
	.product-image-wrapper {
		border: 1px solid #F7F7F5;
		overflow: hidden;
		/* margin-bottom: 30px; */
	}
	.single-products {
		position: relative;
	}
	.brandLi b {
		font-size: 16px;
		color: #FE980F;
	}
	</style>
@endif
@stop

@section('content')
@include('partials.status-panel')

<!--
<meta property="og:url"    content="{{url()->current()}}" />
<meta property="og:type"   content="Needifo.com" />
<meta property="og:title"  content="{{$listing->title}}" />
<meta property="og:address" content="{{ $data['header_address'] }}" />
<meta property="og:image"  content="{{$listing->image}}" />
<meta property="og:description" content="{{$listing->description}}" /> -->



	
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
	 		<div class="col-md-12 mtbanner">
	 		</div>
	 		<div class="col-md-12 mt10">
	 			<div class="col-md-6">
	 			<h4>{{ $listing->title }}</h4>
				<h5>{{ $data['header_address'] }}</h5>
	 		</div>
	 		<div class="col-md-6">
	 			<div class="rating_box_layout">
					<div class="rating_count">Ratings:<div class="merchant_ratings ratings" data-value="{{number_format($avgrating,1)}}">{{number_format($avgrating,1)}}</div> / {{$total_reviews}} Reviews</div>
					<div class="heat-rating">
						<div class="merchant_rating_fixed rating-block one" data-value="1.0"></div>

						<div class="merchant_rating_fixed rating-block one-half" data-value="1.5"></div>

						<div class="merchant_rating_fixed rating-block two" data-value="2.0"></div>

						<div class="merchant_rating_fixed rating-block two-half" data-value="2.5"></div>

						<div class="merchant_rating_fixed rating-block three" data-value="3.0"></div>

						<div class="merchant_rating_fixed rating-block three-half" data-value="3.5"></div>

						<div class="merchant_rating_fixed rating-block four" data-value="4.0"></div>

						<div class="merchant_rating_fixed rating-block four-half" data-value="4.5"></div>

						<div class="merchant_rating_fixed rating-block five" data-value="5.0"></div>		
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
	 		<div class="col-md-12 pad0" id="tab_section">
	 			<div >
					@php
						$info_active = 'active';
					
					@endphp

					  <!-- Nav tabs -->
					  <ul class="nav nav-tabs" role="tablist">
					    @if($enable_listing_model['information'])
						<li role="presentation" class="{{ $info_active }}"><a href="#info" aria-controls="home" role="tab" data-toggle="tab">
					    <!-- tab info start -->
					    	<div class="tab-info">
					    		<i class="fa fa-id-card-o" aria-hidden="true"></i>
					    		<h3>Information</h3>
					    	</div>
					    	<!-- tab info start -->

					    </a></li>
						@endif
						
						@if($enable_listing_model['category_dependent'])
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
							@if (count($Products) != 0)
							<li role="presentation" class=""><a href="#shop" aria-controls="home" role="tab" data-toggle="tab">
							<!-- tab info start -->
								<div class="tab-info">
									<i class="fa fa-shopping-bag" aria-hidden="true"></i>
									<h3>Shop Online</h3>
								</div>
								<!-- tab info start -->

							</a></li>
							@endif
						@endif
						
						@if($enable_listing_model['review'])
					    <li role="presentation" class=""><a href="#review" aria-controls="home" role="tab" data-toggle="tab">
					    <!-- tab info start -->
					    	<div class="tab-info">
					    		<i class="fa fa-user" aria-hidden="true"></i>
					    		<h3>review</h3>
					    	</div>
					    	<!-- tab info start -->

					    </a></li>
						@endif
						
						@if($enable_listing_model['call'])
							@if($listing->phoneno)
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
						@if($enable_listing_model['save'])
					    <li role="presentation" class="save_list" data-id="{{$listing->id}}"><a href="javascript:;">
					    <!-- tab info start -->
					    	<div class="tab-info">
					    	<i class="fa fa-download" aria-hidden="true" ></i>
					    		<h3>save</h3>
					    	</div>
					    	<!-- tab info start -->

					    </a></li>
						@endif
					 
					  </ul>

					  <!-- Tab panes -->
					  <div class="tab-content">
					    <div role="tabpanel" class="tab-pane {{ $info_active }}" id="info">
					    	@include('pages.listing_detail.info')

					    </div>
						@if($orderonline_menus)
					     <div role="tabpanel" class="tab-pane " id="order">

					      	@include('pages.listing_detail.order_online')

					     </div>
						@endif
						@if ($tablebookingsettings)
					      <div role="tabpanel" class="tab-pane" id="book_a_table">
							
							@include('pages.listing_detail.book_a_table')

					      </div>
						@endif
						@if ($appointmentbookingsettings)
					      <div role="tabpanel" class="tab-pane" id="book_appointment">
							
							@include('pages.listing_detail.book_appointment')

					      </div>
						@endif
						@if ($Products)
					      <div role="tabpanel" class="tab-pane" id="shop">
							
							@include('pages.listing_detail.shop')

					      </div>
						@endif
					      <div role="tabpanel" class="tab-pane " id="review">
							
							@include('pages.listing_detail.review')

					      </div>
					        <div role="tabpanel" class="tab-pane " id="call">
					        <div class="col-md-12 pad0">
					    				
					    				<div class="click_to_text call-function slide1 mb30">
					    					Click To Reveal					    					
					    				</div>
					    				<div class="call-function slide2 click_to_phone hidden mb30">
					    					<span class="tc">@if($listing->phoneno){{$listing->phoneno}}@endif</span>
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
					    				<!-- <ul class="social-share">
					    				 	<li class="facebook">
					                           <a href=""><i class="fa fa-facebook"></i></a>
					    				 	</li>
					    				 	<li class="twitter">
					    				 		<a href=""><i class="fa fa-twitter"></i></a>
					    				 	</li>
					    				 	<li class="insta">
					    				 		<a href=""><i class="fa fa-instagram"></i></a>
					    				 	</li>
					    				 	<li class="pinrest">
					    				 		<a href=""><i class="fa fa-pinterest-p"></i></a>
					    				 	</li>
					    				 	<li class="linkedin">
					    				 		<a href=""><i class="fa fa-linkedin" aria-hidden="true"></i></a>
					    				 	</li>
					    				 	<li class="google">
					    				 		<a href=""><i class="fa fa-google-plus" aria-hidden="true"></i></a>
					    				 	</li>
					    				 	<li class="skype">
					    				 		<a href=""><i class="fa fa-skype" aria-hidden="true"></i></a>
					    				 	</li>
					    				 	<li class="rss">
					    				 		<a href=""><i class="fa fa-rss" aria-hidden="true"></i></a>
					    				 	</li>
					    				 	<li class="cloud">
					    				 		<a href=""><i class="fa fa-cloud" aria-hidden="true"></i></a>
					    				 	</li>
					    				 	<!- <li>
					    				 		<a href=""></a>
					    				 	</li>
					    				 	<li>
					    				 		<a href=""></a>
					    				 	</li>
					    				 	<li>
					    				 		<a href=""></a>
					    				 	</li> -->
					    				<!-- </ul> -->

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
								
									
								<div class="gallery_block">
								@if($galleryimages)
									@foreach($galleryimages as $gallery)
									<div class="col-sm-4">
									<a class="fancybox" rel="gallery" href="{{ url('/upload/images/medium') }}/{{$gallery->image_name}}" >
									<img src="{{ url('/upload/images/medium') }}/{{$gallery->image_name}}"  class="img-responsive" alt="" />
									</a>
									</div>
									@endforeach
								@endif
								
								
								</div>
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
	
	<div class="modal  fade"  id="modalLoginToPerform" tabindex="-1" role="dialog" aria-label="myModalLabel" aria-hidden="true">
		<div class="modal-dialog white-modal modal-sm">
			<div class="modal-content ">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><span class="icon icon-clear"></span></button>
				</div>
				<div class="modal-body">
					<div class="text-center">
						Please login to perform this request.
					</div>
				</div>
				<div class="modal-footer text-center">		       	
					<a href="{{ url('login') }}"  class="btn btn--ys btn--full btn--lg">Login</a>
				</div>
			</div>
		</div>
	</div>
	
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
					if(msg instanceof Array) {
						$.each(msg, function(index, value)
						{
							err += value + '<br />';
						});//alert(err);
					} else {
						err = msg;
					}
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
					success:function(res){
						alert(res.msg);
					}
				});
				
			}
			else{
				window.location = "{{url('/login-redirect')}}";
				return false;
			}
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
		$stock = $(this).data('stock');
		$order_type = $(this).data('order_type');
		
		
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
		
		ajax_add_to_cart(submit_url, $type, $id, $name, $price, $listing_id, $quantity, $stock, $order_type);

	}
	$(document).on("change",".cls_quantity", add_cart_by_change);
	function add_cart_by_change()
	{
		var submit_url = "{{ url('online-order/update-cart') }}";
		
		//event.preventDefault();
		$id = $(this).data('item_id');
		$name = $(this).data('name');
		$price = $(this).data('price');	
		$stock = $(this).data('stock');
		$order_type = $(this).data('order_type');
		$quantity = $(this).val();
							
		if($quantity == '') {
			alert('Please enter quantity');
			return false;
		} else if($quantity <= 0) {
			alert('Quantity should be greater than or equal to 1');
			return false;
		}
		
		ajax_add_to_cart(submit_url, 'change', $id, $name, $price, '', $quantity, $stock, $order_type);

	}
	
	function ajax_add_to_cart(submit_url, $type, $id, $name, $price, $listing_id, $quantity, $stock, $order_type)
	{
		
		var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

		$.ajax(
		{
			url: submit_url,
			type: 'POST',
			data: {add_item: 'yes', item_id: $id, name: $name, price: $price, quantity: $quantity, listing_id: $listing_id, stock: $stock, order_type: $order_type, '_token':CSRF_TOKEN},
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
		<?php
		if(Auth::user()):
		?>
		$('#review-form').validate({
			rules: {
				"comments":{
					required:true,
				}
			},
			messages: {
				comments: "Comments required",
			}
		});
		<?php else:?>
		
		<?php endif;?>
		$('.write_review_rating').click(function(){
			$('#ratings-input').val('');
			var rating=$(this).data('value');
			$('#ratings-input').val(rating);

		});
		$('#review_post').click(function(){
			var id='';
			
			if(AuthUser!=''){
				id=AuthUser;
			}
			else{
				$('#modalLoginToPerform').modal('show');
				return false;
				id='';
			}//alert(id);return false;
			
			if($('#review-form').valid()){
				var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
				
				var review_data={
					'listing_id':'{{$listing->id}}',
					'merchant_id':'{{$listing->user_id}}',
					'rating':$('#ratings-input').val(),
					//'name':$('#reviewer_name').val(),
					'comments':$('#comments').val(),
					//'location':$('#location').text(),
					//'email':$('#reviewer_email').val(),
					'user_id':id,
					'_token':CSRF_TOKEN
				};
				$.ajax({
					type:'post',
					url:"{{url('review')}}",
					dataType:'json',
					data:review_data,
					beforeSend: function(){
						$('.image_loader').show();
					},
					complete: function(){
						$('.image_loader').hide();
					},
					success:function(data){
						if(data){
							var reviews = data.reviews;
							var average = data.average;
							/*
							var review_html = '';
							$.each(reviews, function(index, review) {
								var reviewer_name = review.user.first_name;
								reviewer_name = reviewer_name.toLowerCase().replace(/\b[a-z]/g, function(letter) {
									return letter.toUpperCase();
								});
								
								review_html += '<div class="col-md-12 pad0 group showcomments">'
												+ "<span class='head-span'><div id='stars-existing' class='starrr' data-rating='"+ review.rating +"'></div></span>"
												+ '<h4>By: '+reviewer_name+'. on '+review.created_at+'</h4>'
												+ '<p>'+review.comments+'</p>'
												+ "</div>";
							});
							$('#allreviews').html(review_html);
							
							$(".starrr").starrr({
							  readOnly: true
							});
							*/
							
							$('#review_msg').html('Thank you,Your review posted Successfully..');
							$('#review_msg').show();
							//window.setTimeout(function(){location.reload()},1000)
							//location.reload();
						}
					}
				});
			}
		});
		//$('#allreviews div:first-child').toggleClass('showcomments');
		$('.showcomments').css('display','block');
		$('.starrr').removeAttr('style');
		var $group = $('.group');
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
		
		if(window.location.hash) {
			var hash = window.location.hash.substring(1);
			activaTab(hash);
		}
		function activaTab(tab){
			$('.nav-tabs a[href="#' + tab + '"]').tab('show');
			$('html, body').animate({
				scrollTop: $("#tab_section").offset().top
			}, 100);
		};
		
	});
</script>


@if ($Products)
	
	<script>
	  $(document).ready(function(){
		<?php $maxP = count($Products);
		for($i=0;$i<$maxP; $i++) {?>
		  $('#successMsg<?php echo $i;?>').hide();		  
		  <?php }?>
		  
		  $('body').delegate('.add-to-cart', 'click', function(){
			var pro_id = $(this).data('id');

			$.ajax({
			  type: 'get',
			  url: '<?php echo url('/cart/addItem');?>/'+ pro_id,
			  dataType: "json",
			  success:function(response){
				if(response.status) {				
					var data = response.data;
					ajax_update_mini_cart(data);
				} else {
					alert(response.msg);
				}
			  }
			});

		  });
		});
		
		
	  </script>
	  
	  <script type="text/javascript">
    $(document).ready(function(){
		
	  $("#slider-range").slider({
        range: true,
        min: {{$minmax->MIN_PRICE}},
        max: {{$minmax->MAX_PRICE}},
        step: 500,
       // values: [300, 1000],
        slide: function (event, ui) {
          }
        });
		
		var originalVal;
		var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
		var listing_id='<?php echo $listing->id;?>';
		var slide_change_url="{{url('')}}/productbyprice";

		$('#slider-range').slider().on('slideStart', function(ev){
			originalVal = $('#slider-range').data('slider').getValue();
		});

		$('#slider-range').slider().on('slideStop', function(ev, ui){
			var newVal = $('#slider-range').data('slider').getValue();
			if(originalVal != newVal) {				
				var data={'_token':CSRF_TOKEN,'start':newVal[0],'end':newVal[1],'listing':listing_id};
				$('#updateDiv').html('');
				filterproduct(slide_change_url,data);
			}
		});

        $('.try').click(function(){
          var brand = [];
          $('.try').each(function(){
            if($(this).is(":checked")){

              brand.push($(this).val());
            }
          });
          Finalbrand  = brand.toString();
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            var url="{{url('')}}/productbybrand";
             var listing_id='<?php echo $listing->id;?>';
            var data={'brand':Finalbrand,'_token':CSRF_TOKEN,'listing':listing_id};
             $('#updateDiv').html('');
             filterproduct(url,data);
        });
        function filterproduct(url,data){
           var filterbyprice='';
          $.ajax({
            type: 'post',
            dataType: "json",
            url: url,
            data: data,
            success: function (response) {
             /* filterbyprice+='<div class="features_items">\
                               <h2 class="title text-center">\
                              Products </h2>';*/
                                if(response.length==0){
                                  filterbyprice+=' sorry, products not found';
                                }
                                else{
                                  $.each(response,function(key,value){
										filterbyprice+='<div class="col-md-4 ">\
										  <div class="product-description">\
											 <a href="{{url('/product_details')}}/'+value.pid+'"><img src="{{url('')}}/upload/images/small/'+value.pro_img+'"></a>\
											 <h2 class="text-center"><a href="{{url('/product_details')}}/'+value.pid+'">'+value.pro_name+'</a></h2>\
											 <h4 class="text-center">by '+value.name+'</h4>';
										if(value.spl_price==0){
											filterbyprice+='<h1 class="text-center">{{$currency->symbol}}'+value.pro_price+'</h2>';
										} else {
											filterbyprice+='<h1 class="text-center">{{$currency->symbol}} <span style="text-decoration:line-through; color:#ddd">'+value.pro_price+'</span> '+value.spl_price+'</h2>';
										}
                                        filterbyprice+='<div class="lead text-center">\
										<button class="btn btn-success add-to-cart" data-id="'+value.pid+'" >Add to Cart</button>\
										</div>\
										';                                        
                                      filterbyprice+='</div>\
                                  </div>';
                                });
                      //          filterbyprice+='</div>';
                                }  
                                $('#updateDiv').html(filterbyprice);
              }
            });
        }
      });
    </script>
	
	
@endif
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
@stop