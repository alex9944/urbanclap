@extends('layouts.'.$merchant_layout)

@section('head')
<link rel="stylesheet" type="text/css" href="{{ asset($merchant_layout . '/css/jcarousel.responsive.css') }}"> 
<link rel="stylesheet" href="{{ asset($merchant_layout . '/css/fancybox.css?v=2.1.7') }}" type="text/css" media="screen" />
<link rel="stylesheet" type="text/css" href="{{ asset($merchant_layout .'/dropzone/dropzone.css') }}">
<style>
.divbox {
	cursor:pointer;
}
.wizardnew li.active span.round-tab p {
    color: white !important;
}
</style>
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
@stop

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}" />

					      	
	 <!-- *********************Listing ********************** -->
	 <div class="col-md-12">
		<div class="wizardnew">
            <div role="tabpanel" class="tab-pane" id="gallery">
								 <div class="form-group required">
									
									<div class="col-sm-12">
										<div id="dropzonePreview" class="dropzone grey_color"><p class="text-center">Drop files anyhere to upload</p></div><br />										
									</div>
								</div>
								
								
								
								<div class="gallery_block" id="editfil">
								
								</div>
								<!-- gallery end -->


					    			</div>
    </div>
	<div class="clearfix"></div>
@stop

@section('footer')


<script type="text/javascript">
	function deleteConfirm(){
		if($('.checkbox:checked').length == ''){
			alert('Please check atleast one blog');
			return false;
		}	
	}
	$(document).ready(function(){
		$('#check_all').on('click',function(){
			if(this.checked){
				$('.checkbox').each(function(){
					this.checked = true;
				});
			}else{
				$('.checkbox').each(function(){
					this.checked = false;
				});
			}
		});

		$('.checkbox').on('click',function(){
			if($('.checkbox:checked').length == $('.checkbox').length){
				$('#check_all').prop('checked',true);
			}else{
				$('#check_all').prop('checked',false);
			}
		});
	});


//Change Status Enable

$(document).on("click", ".enable", enable);
function enable(){ 
	var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
	var id= $(this).attr('id'); 
	var host="{{ url('/merchant/listing/enable/') }}";
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
		},success:enableStatus

	})
	return false;
}
function enableStatus(res){
	location.reload();
}



//Change Status Disable

$(document).on("click", ".disable", disable);
function disable(){ 
	var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
	var id= $(this).attr('id'); 
	var host="{{ url('/merchant/listing/disable/') }}";
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
		},success:disableStatus

	})
	return false;
}
function disableStatus(res){ 
	location.reload();
}


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
</script>
<script>
	var markers = [];
	var map = null, marker = null;	
	function initMap() {
		var lat = 53.507;
		var lon = -5.127;
		var zoom_level = 14;
		
		<?php
		$longitude = old('longitude', $listing->longitude);
		$lattitude = old('lattitude', $listing->lattitude);
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
			//$('#lat_long').val(latitude + ', ' + longitude);
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
			
			marker.addListener('click', function() {
				infowindow.setContent("{{old('address1', $listing->address1)}}");
				infowindow.open(map, marker);
			});
			
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
	maxFiles: 6,
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
	/*	
		this.on("complete", function(file) {
			myDropzone.removeFile(file);
			get_item_images();
		});
		
		this.on("queuecomplete", function(file) {
			//myDropzone.removeFile(file);
			get_item_images();
		});
*/
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