 <script src="{{url('')}}/tinymce/js/tinymce/tinymce.min.js"></script>
 <script>tinymce.init({ selector:'textarea' });</script>
 <div class="col-md-12 pad0 ">
 	<div class="info-mation">
 		<form id="edit-info">
 			<div class="col-md-8">
 				@if($listing->listing_merchant->mobile_no)
 				<div class="col-md-12 pad0">
 					<h2><i class="fa fa-phone"></i> Call :</h2>
				<!-- <div class="click_to_text call-function slide1">
					Click To Reveal
				</div> -->
				<div class="call-function slide2 click_to_phone ">
					<span class="tc">{{$listing->listing_merchant->mobile_no}}</span>
				</div>
			</div>
			@endif
			<div class="col-md-12 pad0">
				<h2><i class="fa fa-address-book" aria-hidden="true"></i> Address :&nbsp;&nbsp;&nbsp;&nbsp;
					<i class="fa fa-floppy-o save_addrs save" aria-hidden="true" style="font-size: 15px;color: #03a9f4; display:none;"></i></h2>
					<address class="curr_addrs">
						{!! $data['address'] !!}
						<div class="form-group">
							<div class="col-sm-12">
								<div id="map-canvas" style="width:99%; height:300px;"></div>
							</div>
						</div>
						<input class="form-control" type="hidden" name="lat_long" id="lat_long" />
						<input type="hidden" name="lattitude" id="lattitude" >
						<input type="hidden" name="longitude" id="longitude" >
						<input class="form-control" type="hidden" name="address1" id="address1" />
						<input class="form-control" type="hidden" name="address2" id="address2" />
						<input class="form-control" type="hidden" name="postcode" id="postcode" />
					</address>
				</div>
				<div class="col-md-12 pad0">
					<h2><i class="fa fa-info-circle" aria-hidden="true"></i> About : &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-pencil-square-o edit_desc" aria-hidden="true" style="font-size: 15px;color: #03a9f4;"></i>
						<i class="fa fa-floppy-o save_desc save" aria-hidden="true" style="display:none;font-size: 15px;color: #03a9f4;"></i></h2> 

						<p id="old-desc">{!! $listing->description !!}</p>
						<textarea  class="tinymce" id="description" name="description" style="display:none;">{{$listing->description }}</textarea>
					</div>
				</div>
				<div class="col-md-4">
					@if($listing->working_hours)
					<div class="col-md-12 pad0">
						<h2><i class="fa fa-clock-o" aria-hidden="true"></i> Working Hours: :</h2>
						<p>{{$listing->working_hours}}
							<?php $holiday=json_decode($listing->holidays)?>
							<strong>@foreach($holiday as $value)
								{{$value}}
								@endforeach
							Holiday</strong>
						</p>
					</div>
					@endif
					@if($listing->website)
					<div class="col-md-12 pad0">
						<h2>
							<i class="fa fa-globe"></i> Website :
						</h2>
						<p>
							<a href="#" id="old-website">{{$listing->website}}</a>&nbsp;&nbsp;
							<i class="fa fa-pencil-square-o edit_website" aria-hidden="true" ></i>
							<i class="fa fa-floppy-o save_website save" aria-hidden="true" style="display:none;"></i>
						</p>
						<p>
							<input type="text" class="form-control" name="website" id="list-website" style="display:none;" value="{{$listing->website}}">
						</p>
					</div>
					@endif
			<?php /*
			<div class="col-md-12 pad0">
				<h2><i class="fa fa-usd" aria-hidden="true"></i> cost :</h2>
				<p class="red">Information Not Available</p>
			</div>
			<div class="col-md-12 pad0">
				<h2><i class="fa fa-credit-card" aria-hidden="true"></i> Payment Method :</h2>
				<p class="red">Information Not Available</p>

			</div>
			*/?>

		</div>
		<input type="hidden" name="listing_id" value="{{$listing->id}}">
		<input type="hidden" name="_token" value="{{csrf_token()}}">
	</form>
</div>
</div>
<script type="text/javascript">
	$(document)	.ready(function(){
		
		var markers = [];
		var map = null, marker = null;	
		function initialize() {

			var zoom_level = 16;


			var default_position = new google.maps.LatLng({{$data['lat']}}, {{$data['long']}});
			map = new google.maps.Map(document.getElementById('map-canvas'), {
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

				  	var address_components = results[0].address_components;console.log(address_components);
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
			$('.save_addrs').show();
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
	google.maps.event.addDomListener(window, 'load', initialize);
	$(document).on('click','.edit_website',function(){
		$('#list-website').show();
		$('#old-website').hide();
		$('.save_website').show();
		$('.edit_website').hide();
	});
	$(document).on('click','.edit_desc',function(){
		$('#old-desc').hide();
		$('#mceu_20').show();
		$('.save_desc').show();
		$('.edit_desc').hide();
	});
		/*$(document).on('click','.edit_addrs',function(){
			$('.curr_addrs').hide();
		
			$('.save_addrs').show();
			$('.edit_addrs').hide();
		});*/
		$(document).on('click','.save',function(){
			$('#edit-info').submit();
		});
		$('#edit-info').submit(function(e){
			$('#description').val();
			$('#description').val(tinymce.activeEditor.getContent());
			e.preventDefault();
			var formData = new FormData(this);

			$.ajax({
				type:'POST',
				url: "{{url('')}}/merchant/changeInfo",
				data:formData,
				cache:false,
				contentType: false,
				processData: false,
				success:function(data){
					if(data){
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
</script>
