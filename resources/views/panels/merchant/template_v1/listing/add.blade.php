@extends('layouts.'.$merchant_layout)

@section('head')

<link href="{{ asset($merchant_layout . '/css/listing.css') }}" rel="stylesheet">
<style>
.divbox {
	cursor:pointer;
}
.wizardnew li.active span.round-tab p {
    color: white !important;
}
</style>
@stop

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}" />

					      	
	 <!-- *********************Listing ********************** -->
	 <div class="col-md-12"> <!-- col-md-offset-1 -->
		<div class="wizardnew">
            <div class="wizardnew-inner">
                <div class="connecting-line"></div>
                <ul class="nav nav-tabs" role="tablist">

                    <li role="presentation" class="active">
                        <a href="#step1" data-toggle="tab" aria-controls="step1" role="tab" title="Step 1">
                            <span class="round-tab">
                              <p>General info</p>
                            </span>
                        </a>
                    </li>

                    <li role="presentation" class=" ">
                        <a href="#menu_address" data-toggle="tab" aria-controls="step2" role="tab" title="Step 2">
                            <span class="round-tab">
                              <p>Address & Location</p>
                            </span>
                        </a>
                    </li>
                    <li role="presentation" class=" ">
                        <a href="#step3" data-toggle="tab" aria-controls="step3" role="tab" title="Step 3">
                            <span class="round-tab">
                                <p>SEO & About</p>
                            </span>
                        </a>
                    </li>
 
                </ul>
            </div>

			@if(Session::get('message')) <div class="alert alert-success" role="alert">{{Session::get('message')}} </div>@endif 
			@if(Session::get('error_message')) <div class="alert alert-danger" role="alert">{{Session::get('error_message')}} </div>@endif 

					@if ($errors->any())
					<div class="alert alert-danger">
						<ul>
							@foreach ($errors->all() as $error)
							<li>{{ $error }}</li>
							@endforeach
						</ul>
					</div>
					@endif
               

			
      <form id="listForm" method="post" action="{{ url('merchant/listing/added') }}" class="form-horizontal" accept-charset="UTF-8" enctype="multipart/form-data">
                <div class="tab-content">
				
				
				
				<!-- **************General info************** -->
             <div class="tab-pane active" role="tabpanel" id="step1">
                  <div class="col-md-12">
                      <div class="col-md-12">
					       <h4 class="text-color">Main Category <a class='my-tool-tip tooltip-cus' data-toggle="tooltip" data-placement="top" title="Write something here !!!"><i class="fa fa-info-circle icon-color" aria-hidden="true"></i></a></h4>
					  </div>
                   </div>

             <div class="col-md-12">          
                <div class="col-md-12">
			       <h4 class="blue">@if($subscribed_category){{$subscribed_category->c_title}}
			      @endif
                   </h4>

                        		
      <div class="row">
        <div class="col-md-6 col-sm-6">
          <div class="">
            <label for="form-elem-1">Listing  Title</label>
			<input type="text" class="form-control" name="title" id="title" value="{{ old('title') }}">
          </div>
          <div class="form-group">
            <label for="form-elem-2" class="col-md-12" style="margin-top: 15px;">Working Hours</label>
			
      <div class="col-md-6" style="left: -15px;">    
	  
        <div class="form-group1  ">
          <label for="staticEmail" class="col-sm-2 col-form-label mt10">From </label>
             <div class="col-sm-10">
            <select class="form-control1" name="start_time" id="start_time">	
					@foreach ($times as $time_value)
					<option value="{{ $time_value }}" @if( old('start_time') == $time_value ) selected @endif>
						{{ $time_value }}
					</option>
					@endforeach
		</select>
		<select name="start_time_ar" id="start_time_ar"  class="form-control1">
			<option value="am" @if( old('start_time_ar') == 'am' ) selected @endif>AM</option>
			<option value="pm" @if( old('start_time_ar') == 'pm' ) selected @endif>PM</option>
		</select>
    </div>
  </div>
   
</div>
      <div class="col-md-6" style="left: 17px;">    
          <div class="form-group1  ">
             <label for="staticEmail" class="col-sm-2 col-form-label mt10">To</label>
               <div class="col-sm-10">
                   <select name="end_time" id="end_time"  class="form-control1">	
		              @foreach ($times as $time_value)
		               <option value="{{ $time_value }}" @if( old('end_time') == $time_value ) selected @endif>
		               {{ $time_value }}
		               </option>
		              @endforeach
		           </select>
		           <select name="end_time_ar" id="end_time_ar"  class="form-control1">
		              <option value="am" @if( old('end_time_ar') == 'am' ) selected @endif>AM</option>
		              <option value="pm" @if( old('end_time_ar') == 'pm' ) selected @endif>PM</option>
		          </select>
                </div>
           </div>
        </div>
          </div>
           
        </div>
		
		
        <div class="col-md-6 col-sm-6">
         <div class="form-group">
            <label for="form-elem-2">Sub Category</label>
            <select class="form-control" id="scategory" name="scategory">
            	<option value="">---Choose---</option>
				   @foreach ($subcategory as $category)
				 <option value="{{ $category->c_id }}" @if( old('scategory') == $category->c_id ) selected @endif>
					{{ $category->c_title }}
				</option>
				  @endforeach
            </select>
          </div>
          <div class="form-group">
               <label for="form-elem-2">Website</label>
                  <input type="text" class="form-control" name="website" id="website" value="{{ old('website') }}">
          </div>
        </div>
		
      <div class="form-group">
			<label class="col-sm-2" style="left:14px;top:10px;">Banner Image :</label>				<div class="col-sm-4">
							<input class="form-control" type="file" name="photo" id="photo"  />
							 <p>(Image Size:Width:1350px | Height:475px.)</p>
									</div>
									<!--<div class="col-sm-2"><img src="" id="edit_photo" style="height:40px"></div>  -->
								</div> 
								
							
      <div class="row">
      	 <div class="col-md-12" style="margin-left:15px;">
    	 <div class="form-group">
           <label for="holidays" class="  col-md-12 col-sm-12 col-xs-12">Holidays</label>
           <!--<div class="col-md-2 col-sm-3 btn-width">
           	 <div class="divbox">
  				<a  class="active">
				     <span class="notify-badge" style="display: none;"><i class="fa fa-check" aria-hidden="true"></i></span>
      		             <input type="checkbox" name="holidays[]" class="holidays check nocheck " style="display: none;" value="NO">
      					       <div class="  wrapperbox" >
      						      <div class="pay-content">
										<span>No</span> 
      						      </div> 
      					      </div>
			</a>
		  </div>
       </div> -->
		   
           <div class="col-md-2  col-sm-3  btn-width">
           	    <div class="divbox">
  				   <a   class="active">
					   <span class="notify-badge" style="display: none;"><i class="fa fa-check" aria-hidden="true"></i></span>
      		                 <input type="checkbox" name="holidays[]" id="holidays" class="holidays nocheck " style="display: none;" value="Sunday">
      					           <div class="  wrapperbox" >
      						          <div class="pay-content">	 
      						             <span>Sunday</span> 
      						          </div> 
      					            </div>
					</a>
			   </div>
           </div>
		   
          <div class="col-md-2  col-sm-3  btn-width">
           	  <div class="divbox">
  				  <a class="">
					  <span class="notify-badge " style="display: none;"><i class="fa fa-check" aria-hidden="true"></i></span>
      		              <input type="checkbox" name="holidays[]"  class="holidays check" value="Monday" style=" display: none;">
      					        <div class="wrapperbox" >
      						             <div class="pay-content">
      							             <span>Monday</span> 
      						              </div> 
      					         </div>
					</a>
				</div>
           </div>
		   <div class="col-md-2  col-sm-3  btn-width">
		      <div class="divbox">
  				  <a class="">
					<span class="notify-badge " style="display: none;"><i class="fa fa-check" aria-hidden="true"></i></span>
      		               <input type="checkbox" name="holidays[]" class="holidays check" value="Tuesday" style="display: none;">
      					        <div class="  wrapperbox" >
      						       <div class="pay-content">
      							       <span>Tuesday</span> 
      						      </div> 
                               </div>
					        </a>
				       </div>
				</div>
		   
           <div class="col-md-2  col-sm-3  btn-width">
           	<div class="divbox">
  				<a class="">
					 <span class="notify-badge " style="display: none;"><i class="fa fa-check" aria-hidden="true"></i></span>
      		              <input type="checkbox" name="holidays[]" class="holidays check" value="Wednesday"style="display: none;">
      					         <div class="  wrapperbox" >
      						        <div class="pay-content">
      						      <span>Wednesday</span> 
      						</div> 
      					</div>
					</a>
				</div>
           </div>
		   
           <div class="col-md-2  col-sm-3  btn-width">
           	<div class="divbox">
  			  <a   class="">
				<span class="notify-badge " style="display: none;"><i class="fa fa-check" aria-hidden="true"></i></span>
      		          <input type="checkbox" name="holidays[]" class="holidays check" value="Thursday"style="display: none;">
      					<div class="  wrapperbox" >
      						<div class="pay-content">	 
      						    <span>Thursday</span> 
      						</div> 
      					</div>
					</a>
				</div>
           </div>
		   
          <div class="col-md-2  col-sm-3  btn-width"> 
           	<div class="divbox">
  			   <a   class="">
			      <span class="notify-badge " style="display: none;"><i class="fa fa-check" aria-hidden="true"></i></span>
      		             <input type="checkbox" name="holidays[]" class="holidays check" value="Friday" style="display: none;">
      					      <div class="  wrapperbox" >
      						       <div class="pay-content">
      							 <span>Friday</span> 
      						</div> 
      					</div>
					</a>
				</div>
           </div>
		   
           <div class="col-md-2  col-sm-3  btn-width">
           	<div class="divbox">
  			  <a   class="">
			     <span class="notify-badge " style="display: none;"><i class="fa fa-check" aria-hidden="true"></i></span>
      		         <input type="checkbox" name="holidays[]" class="holidays check" value="Saturday"style="display: none;">
      					<div class="  wrapperbox" >
      						<div class="pay-content">
      							<span>Saturday</span> 
      						</div> 
      					</div>
					</a>
				</div>
           </div>
		   
          </div>
        </div>  
      </div>  
      </div>

                      <?php /*<div class="row">
                            <div class="col-md-3 col-md-offset-4 text-right">
                                    <div class="form-group">
                                            <button type="submit" class="btn btn-default">Save & Continue</button>
                                     </div>
                            </div>
                       </div> */?>
    
               </div>
            </div>
         </div>
		 
		 
		 
		 <!-- **************Address info************** -->
               <div class="tab-pane" role="tabpanel" id="menu_address">
                       <div class="col-md-12">
                          <div class="col-md-12"> 
                              <div class="col-md-12">
                                     <div class="col-md-12"> 

                        		
      <div class="row">
        <div class="col-md-6 col-sm-6">
          <div class="form-group-top">
            <label for="form-elem-2">Country</label>
           <input class="form-control" value="{{$default_country->name}}" readonly />
          </div>
           <div class="form-group-top">
            <label for="form-elem-2">City</label>
            <select name="cities" id="cities"  class="form-control">		
				<option value="">---Choose---</option>
				   @foreach ($cities as $city)
      <option value="{{ $city->id }}" @if( old('cities') == $city->id ) selected @endif>
			       {{ $city->name }}
					</option>
					@endforeach
			</select>	
          </div>
          <div class="form-group-top">
            <label for="form-elem-2">Post Code</label>
            <input class="form-control" type="text" name="postcode" id="postcode" value="{{ old('postcode') }}"/>
          </div>
		  <div class="form-group-top">
            <label for="form-elem-2">Lattitude</label>
            <input class="form-control" type="text" name="lattitude" id="lattitude" value="{{ old('lattitude') }}"/>
          </div>
        </div>
		
		
        <div class="col-md-6 col-sm-6">
         <div class="form-group-top">
            <label for="form-elem-2">State</label>
            <select class="form-control states" id="states" name="states">
             <option value="">---Choose---</option>
				@foreach ($states as $state)
				<option value="{{ $state->id }}" @if( old('states') == $state->id ) selected @endif>
				{{ $state->name }}
				</option>
			  @endforeach            
			</select>
          </div>
          <div class="form-group-top">
            <label for="form-elem-2">Address</label>
            <input class="form-control" type="text" id="address1" name="address1" value="{{old('address1')}}" />
          </div>
          <div class="form-group-top">
            <label for="form-elem-2">Phone No</label>
            <input class="form-control" type="text" name="phoneno" id="phoneno" value="{{ old('phoneno') }}"/>
          </div>
		  <div class="form-group-top">
            <label for="form-elem-2">Longitude</label>
            <input class="form-control" type="text" name="longitude" id="longitude" value="{{ old('longitude') }}"/>
          </div>
        </div>
      
      <div class="row">
      	 <div class="col-md-12 form-group-top">
    	          <a href="">Click on map to set lattitude and longitude</a>
    	 </div>  
    	 <div class="form-group">
			<div class="col-sm-12">
					<div id="map" style="width:100%; height:300px;"></div>
			</div>
          </div>
      </div>  
      </div>

               <?php /*<div class="row">
                      <div class="col-md-3 col-md-offset-4 text-right">
                           <div class="form-group">
                                 <button type="submit" class="btn btn-default">Save</button>
                            </div>
                      </div>
                </div> */?>
              </div>
          </div>
       </div>
      </div>
    </div>
	
	
	<!-- ************ About SEO ************** -->
     <div class="tab-pane" role="tabpanel" id="step3">       
      <div class="col-md-12">     		
      <div class="row">
        <div class="col-md-6 col-sm-6">
          <div class="form-group">
            <label for="form-elem-2">Meta Tag <?php /*<a class='my-tool-tip tooltip-cus' data-toggle="tooltip" data-placement="top" title="Write something here !!!"><i class="fa fa-info-circle icon-color" aria-hidden="true"></i></a>*/?></label>
              <input type="text" name="meta_tag" class="form-control" id="meta_tag" value="{{ old('meta_tag') }}" /> 
          </div>
          <div class="form-group">
            <label for="form-elem-2">Meta Description <?php /*<a class='my-tool-tip tooltip-cus' data-toggle="tooltip" data-placement="top" title="Write something here !!!"><i class="fa fa-info-circle icon-color" aria-hidden="true"></i></a>*/?></label>
              <input class="form-control" name="meta_description" id="meta_description" value="{{ old('meta_description') }}">
          </div>
          <div class="form-group">
            <label for="form-elem-2">Company Description</label>
              <textarea  class="form-control" id="description" name="description">{{ old('description') }}</textarea>
          </div>
        </div>
      </div>

                     <div class="row">
                           <div class="col-md-3 col-md-offset-4 text-right">
                                  <div class="form-group">
                                     <button type="submit" class="btn btn-default">Save</button>
                                   </div>
                            </div>
		               <input type="hidden" name="_token" value="{{csrf_token()}}">
                   </div>
	           </div>
          </div>
                     
                    
                </div>
            </form>
        </div>
    </div>
	<div class="clearfix"></div>
@stop

@section('footer')

<script type="text/javascript">

/*** add listing page ***/	
// Get Sub Category
$(document).on("change", ".category", getcategory);
function getcategory(){ 
	var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
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
	var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
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
	var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
	
	var id = '{{$default_country->id}}';

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
	var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
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
<script type="text/javascript">
    $(document).ready(function(){
		// initially load
		getstates();
				
        $(document).on('click','.btn-width .divbox a',function(){
			
			//$(".holidays").closest("a").removeClass("active");
			//$(".holidays").prop('checked', false);
			
			if($(this).hasClass('active')){

				$(this).removeClass("active");
				$(this).children( ".check " ).prop('checked', false);
			}
			else{
				$(this).children( ".check " ).prop('checked', true);
				$(this).addClass("active");
			}
		});
					
					
		<!-- ***********ToolTip****  -->
		$("a.my-tool-tip").tooltip();

    });

	var markers = [];
	var map = null, marker = null;	
	function initMap() {
		var lat = 53.507;
		var lon = -5.127;
		var zoom_level = 14;
		
		<?php
		$longitude = old('longitude', $site_settings->city_longitude);
		$lattitude = old('lattitude', $site_settings->city_lattitude);
		?>
		<?php if($lattitude):?>
		lat = "{{$lattitude}}";
		<?php endif;?>
		<?php if($longitude):?>
		lon = "{{$longitude}}";
		<?php endif;?>
		
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
				  		if(obj.types[0] == 'premise'){
				  			addrss += obj.long_name + ' ';
							sep_ = ', ';
				  		}
						else if(obj.types[0] == 'street_number'){
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

		function setMapOnAll(map) {
			for (var i = 0; i < markers.length; i++) {
				markers[i].setMap(map);
			}
		}	
		
		document.getElementById('states').addEventListener('change', function() {
			var address = $("#states option[value='"+$(this).val()+"']").text();
			if(address)
				showAddress(address, geocoder, map);
		});
		
		document.getElementById('cities').addEventListener('change', function() {
			var address = $("#cities option[value='"+$(this).val()+"']").text();
			if(address)
			{
				showAddress(address, geocoder, map);
				map.setZoom(14);
			}
		});
		
		$("a[href='#menu_address']").on('shown.bs.tab', function(){
			google.maps.event.trigger(map, 'resize');
			//var newmapcenter = map.getCenter(); 
			//map.setCenter(newmapcenter);
			//map.setCenter(marker.getPosition());
			
			setMapOnAll(null);
			marker = new google.maps.Marker({position: default_position});
			marker.setMap(map);
			markers.push(marker);
			<?php if(old('address1')):?>
			marker.addListener('click', function() {
				infowindow.setContent("{{old('address1')}}");
				infowindow.open(map, marker);
			});
			<?php endif;?>
			
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
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBU7YTBgP-ZsBlVcYGYAfuz2os6yfTWLIg&libraries=places&callback=initMap">
</script>

@stop