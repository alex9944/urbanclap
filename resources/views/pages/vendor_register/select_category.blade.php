@extends('layouts.main')

@section('content')

@include('partials.status-panel')

	<style>
	ul.shop-progress li{
		margin-right:0px; !important
	}
</style>
<link rel="stylesheet" href="{{url('')}}/assets/css/campaign.css">
	
	<!-- support start -->
	<div id="support" class="support-section p-tb70">
		<div class="container">
			<div class="row">
				<section>
					<div class="col-md-10 col-md-offset-1">
						<div class="wizard">
							<div class="wizard-inner">

								<ul class="shop-progress nav nav-tabs" role="tablist">
									<li class="active">
										<a href="#step1" data-toggle="tab" aria-controls="step1" role="tab" title="Step 1">
											<span class="thumb-icon">01</span>
											<span class="title">Business Detail </span>
										</a>
									</li>
									<li>
										<a href="#step2" data-toggle="tab" aria-controls="step2" role="tab" title="Step 2">
											<span class="thumb-icon">02</span>
											<span class="title">Personal Detail</span>
										</a>
									</li>
									<li>
										<a href="#step3" data-toggle="tab" aria-controls="step3" role="tab" title="Step 3">
											<span class="thumb-icon">03</span>
											<span class="title">Subscription</span>
										</a>
									</li>
									<li>
										<a href="#step4" data-toggle="tab" aria-controls="step4" role="tab" title="Step 4">
											<span class="thumb-icon">04</span>
											<span class="title">Payment</span>
										</a>
									</li>
								</ul>
								
							</div>

							<form name="subscriptionForm" action="{{url('vendor-register')}}" id="subscriptionForm" method="post" role="form" accept-charset="UTF-8" enctype="multipart/form-data">
								<div class="tab-content">
							
									@if ($errors->any())
										<div class="alert alert-danger">
											<ul>
												@foreach ($errors->all() as $error)
													<li>{{ $error }}</li>
												@endforeach
											</ul>
										</div>
									@endif
									
									<?php
										$category_id = old('category_id');	
										$subscription_pricing_id = old('subscription_pricing_id');
										$selected_category = '';
										$selected_plan = '';	
										$selected_cat_types = '';
									?>
									
									<div class="tab-pane active" role="tabpanel" id="step1">
										<div class="row">
											<div class="col-md-12">
												<h4>General Info</h4>
												<div class="box">
													<div class="form-group required">
														<label class="control-label col-sm-3" for="title">Listing Title</label>
														<div class="col-sm-7">
															<input type="text" class="form-control" name="title" id="title" value="{{ old('title') }}">
														</div>
													</div>
													<div class="form-group required">
														<label class="control-label col-sm-3" for="category">Main Category</label>
														<div class="col-sm-7">
															<select name="category_id" id="category_id" class="form-control category">
																<option value="">---Choose---</option>
																@foreach($categories as $category)
																	<?php
																	$category_type_arr = array();
																	$cat_types = '';											
																	$sep = '';
																	$subs_cat_types = '';
																	if($category->category_type)
																		$category_type_arr = json_decode($category->category_type);
																	if(!empty($category_type_arr)) 
																	{
																		$subs_cat_types = '<table class="table">';
																		foreach($category_types as $cat_type)
																		{
																			if(in_array($cat_type->id, $category_type_arr))
																			{
																				$cat_types .= $sep . $cat_type->name;
																				$sep = '|';
																				
																				$subs_cat_types .= '<tr><td>'.$cat_type->name.'</td></tr>';
																			}
																		}
																		$subs_cat_types .= '</table>';
																	}
																	
																	if($category_id == $category->c_id) {
																		$selected_category = $category->c_title;
																		$selected_cat_types = $subs_cat_types;
																	}
																	?>
																<option data-types="{{$cat_types}}" data-name="{{$category->c_title}}" value="{{$category->c_id}}" @if($category_id == $category->c_id) selected @endif>{{$category->c_title}} </option>
																@endforeach
															</select>
														</div>
													</div>
													<div class="form-group">
														<label class="control-label col-sm-3" for="scategory">Sub Category</label>
														<div class="col-sm-7">
															<select name="scategory" id="scategory"  class="form-control"> 
																<option value="">---Choose---</option>
																@foreach ($subcategory as $category)
																<option value="{{ $category->c_id }}" @if( old('scategory') == $category->c_id ) selected @endif>
																	{{ $category->c_title }}
																</option>
																@endforeach
															</select>
														</div>
													</div>							
													<div class="form-group tag_section" @if(empty($tags)) style="display:none;"@endif>
														<label class="control-label col-sm-3" for="tags">Tags</label>
														<div class="tag_div col-sm-7" style="text-align: left;">
															@foreach ($tags as $tag)
															@php
															$checked ='';
															if(in_array($tag->id, (array) old('tags')))
															$checked = ' checked';
															@endphp
															<input class="" type="checkbox" name="tags[]" id="tag_{{ $tag->id }}" value="{{ $tag->id }}"{{ $checked }}/> {{ $tag->name }}<br />
															@endforeach
														</div>
													</div>
													<div class="form-group required">
														<label class="col-sm-3 control-label">Banner Image</label>
														<div class="col-sm-7">
															<input class="form-control" type="file" name="photo" id="photo"  />
														</div>
														<div class="col-sm-2"><img src="" id="edit_photo" style="height:40px"></div>
													</div>
													<div class="form-group ">
														<label class="col-sm-3 control-label">Website</label>
														<div class="col-sm-7">
															<input type="text" class="form-control" name="website" id="website" value="{{ old('website') }}">
														</div>
													</div>
													<div class="form-group ">
														<label class="col-sm-3 control-label">Working Hours</label>
														<div class="col-sm-3 text-left">
															From
															<select name="start_time" id="start_time"  class="">	
																@foreach ($times as $time_value)
																<option value="{{ $time_value }}" @if( old('start_time') == $time_value ) selected @endif>
																	{{ $time_value }}
																</option>
																@endforeach
															</select>
															<select name="start_time_ar" id="start_time_ar"  class="">
																<option value="am" @if( old('start_time_ar') == 'am' ) selected @endif>AM</option>
																<option value="pm" @if( old('start_time_ar') == 'pm' ) selected @endif>PM</option>
															</select>
														</div>
														<div class="col-sm-3 text-left">
															To
															<select name="end_time" id="end_time"  class="">	
																@foreach ($times as $time_value)
																<option value="{{ $time_value }}" @if( old('end_time') == $time_value ) selected @endif>
																	{{ $time_value }}
																</option>
																@endforeach
															</select>
															<select name="end_time_ar" id="end_time_ar"  class="">
																<option value="am" @if( old('end_time_ar') == 'am' ) selected @endif>AM</option>
																<option value="pm" @if( old('end_time_ar') == 'pm' ) selected @endif>PM</option>
															</select>
														</div>
													</div>
													<div class="form-group " id="holiday_days">
														<label class="col-sm-3 control-label">Holidays</label>
														<div class="col-sm-5 text-left">
															<input type="checkbox"  name="holidays[]" class="holidays" value="Sunday">Sunday<br />
															<input type="checkbox"  name="holidays[]" class="holidays" value="Monday">Monday<br />
															<input type="checkbox"  name="holidays[]" class="holidays" value="Tuesday">Tuesday<br />
															<input type="checkbox"  name="holidays[]" class="holidays" value="Wednesday">Wednesday<br />
															<input type="checkbox"  name="holidays[]" class="holidays" value="Thursday">Thursday<br />
															<input type="checkbox"  name="holidays[]" class="holidays" value="Friday">Friday<br />
															<input type="checkbox"  name="holidays[]" class="holidays" value="Saturday">Saturday<br />
															<input type="checkbox"  name="holidays[]" class="holidays" value="no">No Holiday
														</div>
													</div>
												</div>
												<div style="clear:both;"></div><br />
												<h4>Address & Location</h4>
												<div class="box">
													<div class="form-group required">
														<label class="control-label col-sm-3">State</label>
														<div class="col-sm-7">
															<select name="states" id="states"  class="form-control states">
																<option value="">---Choose---</option>
																@foreach ($states as $state)
																<option value="{{ $state->id }}" @if( old('states') == $state->id ) selected @endif>
																	{{ $state->name }}
																</option>
																@endforeach
															</select>	
														</div>
													</div>
													<div class="form-group required">
														<label class="control-label col-sm-3">City</label>
														<div class="col-sm-7">
															<select name="cities" id="cities"  class="form-control">		
																<option value="">---Choose---</option>
																@foreach ($cities as $city)
																<option value="{{ $city->id }}" @if( old('cities') == $city->id ) selected @endif>
																	{{ $city->name }}
																</option>
																@endforeach
															</select>	
														</div>
													</div>

													<div class="form-group required">
														<label class="control-label col-sm-8">Click on the map to set lattitude and logitude</label>
													</div>

													<div class="form-group">
														<div class="col-sm-12">
															<div id="map" style="width:99%; height:300px;"></div>
														</div>
													</div>

													<div class="form-group required">
														<label class="control-label col-sm-3">Lattitude and Longitude</label>
														<div class="col-sm-7">
															<input class="form-control" type="text" name="lat_long" id="lat_long" value="{{ old('lat_long') }}"/>
															<input type="hidden" name="lattitude" id="lattitude" value="{{ old('lattitude') }}">
															<input type="hidden" name="longitude" id="longitude" value="{{ old('longitude') }}">
														</div>
													</div>

													<div class="form-group required">
														<label class="control-label col-sm-3">Address 1</label>
														<div class="col-sm-7">
															<input class="form-control" type="text" name="address1" id="address1" value="{{ old('address1') }}"/>
														</div>
													</div>
													<div class="form-group">
														<label class="control-label col-sm-3">Address 2</label>
														<div class="col-sm-7">
															<input class="form-control" type="text" name="address2" id="address2" value="{{ old('address2') }}"/>
														</div>
													</div>
													<div class="form-group required">
														<label class="control-label col-sm-3">Post Code</label>
														<div class="col-sm-7">
															<input class="form-control" type="text" name="postcode" id="postcode" value="{{ old('postcode') }}"/>
														</div>
													</div>
													<div class="form-group required">
														<label class="control-label col-sm-3">Phone No</label>
														<div class="col-sm-7">
															<input class="form-control" type="text" name="phoneno" id="phoneno" value="{{ old('phoneno') }}"/>
														</div>
													</div>
												</div>
												
												<div style="clear:both;"></div><br />
												<h4>Description</h4>
												<div class="box">
													<div class="form-group">
														<label class="col-sm-3 control-label">Meta Tag</label>
														<div class="col-sm-7">
															<textarea name="meta_tag" class="form-control" id="meta_tag">{{ old('meta_tag') }}</textarea>
														</div>
													</div>
													<div class="form-group">
														<label class="col-sm-3 control-label">Meta Description</label>
														<div class="col-sm-7">
															<textarea class="form-control" name="meta_description" id="meta_description">{{ old('meta_description') }}</textarea>
														</div>
													</div>
													<div class="form-group required">
														<label class="col-sm-3 control-label">Listing Description</label>
														<div class="col-sm-7">
															<textarea  class="tinymce" id="description" name="description">{{ old('description') }}</textarea>
														</div>
													</div>
												</div>
												
											</div>
										</div>
																	
										<div style="clear:both;">
													
											<div class="next_1_validation alert alert-danger hidden"></div><br />

											<ul class="list-inline pull-right">
												<li>
													<button type="button" class="btn btn-default next_1">Next</button>
												</li>
											</ul>
										</div>
									</div>
									<div class="tab-pane" role="tabpanel" id="step2">
										<div class="col-md-8 col-md-offset-2">
											<div class="form-group mt20">
												<input type="text" name="first_name" id="first_name" placeholder="First name" class="form-control" value="{{old('first_name')}}">
											</div>
											<div class="form-group">
												<input type="text" name="last_name" id="last_name" placeholder="Last name" class="form-control" value="{{old('last_name')}}">
											</div>
											<div class="form-group">
												<input type="text" name="email" id="email" placeholder="Email address" class="form-control" value="{{old('email')}}">
											</div>
											<div class="form-group">
												<input type="text" name="mobile" id="mobile" placeholder="Mobile No" class="form-control" value="{{old('mobile')}}">
											</div>
											<div class="form-group">
												<input type="text" name="address" id="address" placeholder="Address" class="form-control" value="{{old('address')}}">
											</div>
											<div class="form-group">
												<input type="text" name="city_name" id="city_name" placeholder="City name" class="form-control" value="{{old('city_name')}}">
											</div>
											<div class="form-group">
												<input type="text" name="postal_code" id="postal_code" placeholder="Postal code" class="form-control" value="{{old('postal_code')}}">
											</div>
											<div class="form-group">
												<input type="password" name="password" id="password" placeholder="Password" class="form-control">
											</div>
											<div class="form-group">
												<input type="password" name="password_confirmation" id="password_confirmation" placeholder="Confirm Password" class="form-control">
											</div>
											
											
										</div>

										<div style="clear:both;">

											<ul class="list-inline pull-right">
												<li>
													<button type="button" class="btn btn-default next_2">Next</button>
												</li>
											</ul>
										</div>

									</div>

									<div class="tab-pane" role="tabpanel" id="step3">
										<div class="col-md-12 ">
											<div class="row">
												@foreach($subscription_pricings as $subscription_pricing)
												<div class="col-xs-12 col-md-4">
													<?php
													$subscription_box_style = '';
													$plan_info = $subscription_pricing->plan->title . ' - ' . 
																$currency->symbol . $subscription_pricing->price .
																' for ' . $subscription_pricing->duration->title;
													if($subscription_pricing->duration->days_month=='1')
														$plan_info .= ' Days';
													else
														$plan_info .= ' Month';
																
													if($subscription_pricing_id == $subscription_pricing->id) {
														$subscription_box_style = ' style="border: #337ab7 3px solid"';
														$selected_plan = $plan_info;
													}
													?>
													<div class="panel panel-primary subscription_box" id="box_{{$subscription_pricing->id}}"<?php echo $subscription_box_style;?>>
														<div class="panel-heading">
															<h3 class="panel-title">{{$subscription_pricing->plan->title}}</h3>
														</div>
														<div class="panel-body">
															<div class="the-price">
																<h1>
																{{$currency->symbol}}{{$subscription_pricing->price}}
																<span class="subscript"> <small>for {{$subscription_pricing->duration->title}}
																@if($subscription_pricing->duration->days_month=='1') Days @else Month @endif</small></span>
																</h1>
															</div>
															<table class="table">
																<?php
																$f_id = $subscription_pricing->f_id;
																$features = explode(',', $f_id);
																$active = 'active';
																foreach($subscription_features as $subscription_feature) {
																	if(in_array($subscription_feature->id, $features)) {
																			$hidden = '';
																																						
																			if($subscription_feature->functionality_name == 'category_dependent') {
																				$hidden = ' hidden';
																			}
																			if(!empty($selected_cat_types)) {
																				$hidden = '';
																			}
																?>	
																	<tr class="{{$active.$hidden}}" id="plan_{{$subscription_feature->functionality_name}}">
																		<td>
																			<?php 
																			if(!empty($selected_cat_types) and $subscription_feature->functionality_name == 'category_dependent') {
																				echo $selected_cat_types;
																			} else {
																				echo $subscription_feature->title;
																			}
																			?>
																		</td>
																	</tr>
																<?php
																		if($active == '')
																			$active = 'active';
																		else
																			$active = '';
																	}
																}
																?>
															</table>
														</div>
														<div class="panel-footer">
															<a href="javascript:;" data-id="{{$subscription_pricing->id}}" data-unit_price="{{$subscription_pricing->price}}" class="btn btn-default select_subscription" role="button" data-plan_info="{{$plan_info}}">Select</a></div>
													</div>
												</div>
												@endforeach
												
												<div style="clear:both;">
													
													<div class="next_3_validation alert alert-danger hidden"></div>

													<ul class="list-inline pull-right">
														<li>
															<button type="button" class="btn btn-default next_3">Next</button>
														</li>
													</ul>
												</div>
												
												<input type="hidden" name="subscription_pricing_id" id="subscription_pricing_id" value="{{old('subscription_pricing_id')}}">
												<input type="hidden" name="unit_price" id="unit_price" value="{{old('unit_price')}}">
												

											</div>
										</div>
										<!--  <ul class="list-inline pull-right">
									<li><button type="button" class="btn btn-default prev-step">Previous</button></li>
									<li><button type="button" class="btn btn-default next-step">Skip</button></li>
									<li><button type="button" class="btn btn-default btn-info-full next-step">Save and continue</button></li>
								</ul> -->
									</div>
									<div class="tab-pane" role="tabpanel" id="step4">

										<p>Subscribed Category: <span id="selected_category">{{$selected_category}}</span></p>
										<p>Subscribed Plan: <span id="selected_plan">{{$selected_plan}}</span></p>
																				
										<h6 class="heading2">Subscription Term</h6>
										<div style="margin:0 0 50px 0">
										@foreach($subscription_terms as $term)
											<input type="radio" name="subscription_term_id" data-type="{{$term->term_type}}" data-value="{{$term->term_value}}" value="{{$term->id}}" class="subscription_terms" > {{$term->display_value}} 
										@endforeach
										</div>
										
										<h4>
											Payable Amount <?php echo $currency->symbol; ?>
											<span id="grand_total">0</span>
											<input type="hidden" name="paid_amount" id="paid_amount" value="0">
											<input type="hidden" name="payment_gateway_id" value="{{$payment_gateway->id}}" >
										</h4>
										
										<button type="submit" name="subscribe_order" class="btn-default">Place Order Now</button>
									</div>
									<div class="clearfix"></div>
								</div>
								
								{{ csrf_field() }}
								
							</form>
						</div>
					</div>
				</section>
			</div>
		</div>
		
		
	</div>
	<!-- end support -->


<script>
/*** add listing page ***/	

var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

// Get Sub Category
$(document).on("change", ".category", getcategory);
function getcategory(){ 
	var id = $(this).val(); 
	
	$('#scategory').html('<option value="">---Choose---</option>');
	$('.tag_div').html('');
	$('.tag_section').hide();
	
	if(id == '')
		return false;

	var host="{{ url('getsubcategory') }}";	
	$.ajax({
		type: 'POST',
		data:{'id': id,'_token':CSRF_TOKEN},
		url: host,
		dataType: "json", // data type of response		
		beforeSend: function(){
			$('.image_loader').show();
		},
		complete: function(){
			$('.image_loader').hide();
		},success:rendergetsubcategory

	})
	return false;
}
function rendergetsubcategory(res){
	var categories = res.view_details.category;
	$.each(categories, function(index, data) {
		$('#scategory').append('<option value="'+data.c_id+'">'+data.c_title+'</option>');
	});
}

// Get tags
$(document).on("change", "#scategory", gettags);
function gettags(){ 
	var id = $(this).val(); 
	
	$('.tag_div').html('');
	$('.tag_section').hide();
	
	if(id == '')
		return false;

	var host="{{ url('gettags') }}";	
	$.ajax({
		type: 'POST',
		data:{'id': id,'_token':CSRF_TOKEN},
		url: host,
		dataType: "json", // data type of response		
		beforeSend: function(){
			$('.image_loader').show();
		},
		complete: function(){
			$('.image_loader').hide();
		},success:rendergettags

	})
	return false;
}
function rendergettags(res){
	var tags = res.view_details;	
	var tag_exists = false;
	$.each(tags, function(index, data) {
		tag_exists = true;//console.log(data.name);
		$('.tag_div').append('<input class="" type="checkbox" name="tags[]" id="tag_'+data.id+'" value="'+data.id+'"/> '+data.name+'<br />');
	});
	if(tag_exists)
	{
		$('.tag_section').show();
	}
}

// Get States
$(document).on("change", ".country", getstates);
function getstates(){ 
	var id = $(this).val(); 
	
	$('#states').html('<option value="">---Choose---</option>');
	$('#cities').html('<option value="">---Choose---</option>');
	
	if(id == '')
		return false;

	var host="{{ url('getstates') }}";	
	$.ajax({
		type: 'POST',
		data:{'id': id,'_token':CSRF_TOKEN},
		url: host,
		dataType: "json", // data type of response		
		beforeSend: function(){
			$('.image_loader').show();
		},
		complete: function(){
			$('.image_loader').hide();
		},success:rendergetstates

	})
	return false;
}
function rendergetstates(res){

	$.each(res.view_details, function(index, data) {
		$('#states').append('<option value="'+data.id+'">'+data.name+'</option>');
	});   
}	

// Get Sub Cities
$(document).on("change", ".states", getcities);
function getcities(){ 
	var id = $(this).val(); 
	
	$('#cities').html('<option value="">---Choose---</option>');
	
	if(id == '')
		return false;

	var host="{{ url('getcities') }}";	
	$.ajax({
		type: 'POST',
		data:{'id': id,'_token':CSRF_TOKEN},
		url: host,
		dataType: "json", // data type of response		
		beforeSend: function(){
			$('.image_loader').show();
		},
		complete: function(){
			$('.image_loader').hide();
		},success:rendergetcities

	})
	return false;
}
function rendergetcities(res){

	$.each(res.view_details, function(index, data) {
		$('#cities').append('<option value="'+data.id+'">'+data.name+'</option>');
	});   
}

</script>

<script>
	var markers = [];
	var map = null, marker = null;	
	function initMap() {
		var lat = 20.5937;
		var lon = 78.9629;
		var zoom_level = 5;
		$lat_long = $('#lat_long').val();
		if($lat_long)
		{
			var res = $lat_long.split(",");
			if(res[0].length > 0)
				lat = $.trim(res[0]);
			if(res[1].length > 0)
				lon = $.trim(res[1]);
			
			zoom_level = 16;
		}
		
		var default_position = new google.maps.LatLng(lat, lon);
		map = new google.maps.Map(document.getElementById('map'), {
			zoom: zoom_level,
          center: default_position, //london uk -> 51.507326790649344, -0.127716064453125
          mapTypeId: google.maps.MapTypeId.ROADMAP, 
          draggableCursor: 'pointer',
          disableDefaultUI: true
      });
		
		var infowindow = new google.maps.InfoWindow();
		var service = new google.maps.places.PlacesService(map);
		var geocoder = new google.maps.Geocoder();
		
		//Add listener
		google.maps.event.addListener(map, "click", function (event) {
			var latitude = parseFloat(event.latLng.lat());
			var longitude = parseFloat(event.latLng.lng());
			//console.log( latitude + ', ' + longitude );
			$('#lat_long').val(latitude + ', ' + longitude);
			$('#lattitude').val(latitude);
			$('#longitude').val(longitude);
			
			setMapOnAll(null);
			marker = new google.maps.Marker({position: event.latLng, map: map});
			marker.setMap(map);
			markers.push(marker);

			//marker.addListener('click', function() {
			  //infowindow.open(map, marker);
			//});
			
			
			geocoder.geocode({
				'latLng': event.latLng
			}, function(results, status) {
				if (status == google.maps.GeocoderStatus.OK) {
				  if (results[0]) {//console.log(results[0].geometry.location);
				  	marker.addListener('click', function() {
				  		infowindow.setContent(results[0].formatted_address);
				  		infowindow.open(map, marker);
				  	});

				  	var address_components = results[0].address_components;//console.log(address_components);
				  	var addrss = '';
				  	var postal_code = '';
					var sep_ = '';
				  	$.each( address_components, function(index, obj) {
				  		if(obj.types[0] == 'street_number'){
				  			addrss += obj.long_name + ' ';
							sep_ = ', ';
				  		}
				  		else if(obj.types[0] == 'route'){
				  			addrss += obj.long_name;
							sep_ = ', ';
				  		}
				  		else if(obj.types[1] == 'sublocality'){
				  			addrss += sep_ + obj.long_name;
							sep_ = ', ';
				  		}
						//else if(obj.types[0] == 'locality'){
							//addrss += obj.long_name;
						//}
						else if(obj.types[0] == 'postal_code'){
							postal_code = obj.long_name;
						}						
					});

					//console.log(addrss);
					//console.log(postal_code);
					
					$('#address1').val(addrss);
					$('#postcode').val(postal_code);
					
				}
			}
		});
		}); 
		//end addListener	
		
		if( zoom_level == 16 )
		{
			setMapOnAll(null);
			marker = new google.maps.Marker({position: default_position, map: map});
			marker.setMap(map);
			markers.push(marker);
			
			geocoder.geocode({
				'latLng': default_position
			}, function(results, status) {
				if (status == google.maps.GeocoderStatus.OK) {
				  if (results[0]) {//console.log(results[0]);
				  	marker.addListener('click', function() {
				  		infowindow.setContent(results[0].formatted_address);
				  		infowindow.open(map, marker);
				  	});
				  }
				}
			});
		}

		function setMapOnAll(map) {
			for (var i = 0; i < markers.length; i++) {
				markers[i].setMap(map);
			}
		}		
		
		document.getElementById('country').addEventListener('change', function() {
			var address = $("#country option[value='"+$(this).val()+"']").text();
			if(address)
				showAddress(address, geocoder, map);
		});
		
		$("a[href='#menu_address']").on('shown.bs.tab', function(){
			google.maps.event.trigger(map, 'resize');
			//var newmapcenter = map.getCenter(); 
			//map.setCenter(newmapcenter);
			map.setCenter(marker.getPosition());
		});


		function showAddress(address, geocoder, resultsMap) {
			geocoder.geocode({'address': address}, function(results, status) {
				if (status === 'OK') {
					resultsMap.setCenter(results[0].geometry.location);

				} else {
					alert('Geocode was not successful for the following reason: ' + status);
				}
			});
		}	

	}

</script>
<script>
$(document).ready(function(){
	$("body").delegate('.category', 'change', function(){
		var category_types = $(this).find(':selected').data('types');
		var category_name = $(this).find(':selected').data('name');
				
		// update final tab
		$('#selected_category').html(category_name);
		if(category_types != '') {
			var category_types_arr = category_types.split('|');
			var cat_rows = '<table class="table">';
			$.each(category_types_arr, function(index, value) {
				cat_rows += '<tr><td>'+value+'</td></tr>';
			});
			cat_rows += '</table>';
			$('#plan_category_dependent').removeClass('hidden');
			$('#plan_category_dependent td').html(cat_rows);
		} else {
			$('#plan_category_dependent').addClass('hidden');
			$('#plan_category_dependent td').html('');
		}
		
		//$('.next_1').trigger('click');
	});
	
	$("body").delegate('.next_1', 'click', function(){
		
		if ($("#subscriptionForm").valid()) {
			$('.nav-tabs a[href="#step2"]').tab('show');
		}
	});
	
	function hideAlertBox(clsname) {
		$(clsname).addClass('hidden');
		$(clsname).html('');
		$(clsname).hide();
	}
	
	$("#subscriptionForm").validate({
		rules: {
			title: "required",
			category_id: "required",
			states: "required",
			cities: "required",
			lat_long: "required",
			address1: "required",
			postcode: "required",
			phoneno: "required",
			description: "required",
			email: {
				required: true,
				email: true
			},
			first_name: "required",
			last_name: "required",
			mobile: {
				required: true,
				minlength: 10
			},
			address: "required",
			city_name: "required",
			postal_code: "required",
			password: {
				required: true,
				minlength: 5
			},
			password_confirmation: {
				required: true,
				minlength: 5,
				equalTo: "#password"
			}
		},
		messages: {
			//category_id: "Please select category",
			first_name: "Please enter your firstname",
			last_name: "Please enter your lastname",
			mobile: {
				required: "Please enter a username",
				minlength: "Your mobile no must consist of at least 10 characters"
			},
			address: "Please enter your address",
			city_name: "Please enter your city name",
			postal_code: "Please enter your postal code",
			password: {
				required: "Please provide a password",
				minlength: "Your password must be at least 5 characters long"
			},
			password_confirmation: {
				required: "Please provide a password",
				minlength: "Your password must be at least 5 characters long",
				equalTo: "Please enter the same password as above"
			},
			email: "Please enter a valid email address"
		}
	});
	
	$("body").delegate('.next_2', 'click', function(){
		
		if ($("#subscriptionForm").valid()) {
			$('.nav-tabs a[href="#step3"]').tab('show');
		}
	});
	
	$("body").delegate('.select_subscription', 'click', function(){
		var subscription_pricing_id = $(this).data('id');
		var unit_price = $(this).data('unit_price');
		var plan_info = $(this).data('plan_info');
		$("#subscription_pricing_id").val(subscription_pricing_id);
		$('#unit_price').val(unit_price);
		update_price();
		
		hideAlertBox(".next_3_validation");
		
		$('.subscription_box').css('border', '#e4e3e2 1px solid');
		// highlight box
		$('#box_'+subscription_pricing_id).css('border', '#337ab7 3px solid');
		
		// update final tab
		$('#selected_plan').html(plan_info);
	});
	
	$("body").delegate('.next_3', 'click', function(){
		
		hideAlertBox(".next_3_validation");
		
		var subscription_pricing_id = $("#subscription_pricing_id").val();
		if(subscription_pricing_id == '') {
			$(".next_3_validation").removeClass('hidden');
			$(".next_3_validation").html('Please select subscription plan');
			$(".next_3_validation").show();
		} else {
			$('.nav-tabs a[href="#step4"]').tab('show');
		}
	});
	
	function update_price() {
		var price = $('#unit_price').val();
		var term_type = $('input[name="subscription_term_id"]:checked').attr("data-type");//$(this).data('type');
		var term_value = $('input[name="subscription_term_id"]:checked').attr("data-value");//$(this).data('value');
		
		if(term_type != '')
		{
			if(term_type == 'month') {
				price = price*term_value;
			} else {
				price = price*term_value*12;
			}
			
			$('#grand_total').html(price);
		}
	}
	//update_price();
	
	$(document).on("change", ".subscription_terms", update_price);
	
	$(document).on("change", "#address1", function() {
		$('#address').val($(this).val());
	});
	$(document).on("change", "#postcode", function() {
		$('#postal_code').val($(this).val());
	});
	$(document).on("change", "#phoneno", function() {
		$('#mobile').val($(this).val());
	});
	
});
</script>

@stop