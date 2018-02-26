@extends('layouts.merchantmain')

@section('head')
<style>
	.tab-pane{    padding-top: 30px;}
</style>
@stop

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}" />


<div class="">

	<div class="page-title">
		<div class="title_left">
			<h3>Listing</h3>
		</div>

	</div>
	<div class="clearfix"></div>           

	<div class="row">


		<!-- Right BAR Start-->
		<div class="col-md-10 col-xs-12">
			<div class="x_panel" id="add_div" style="">
				<?php
				$url = url('merchant/listing/updated');
				$add = 'Edit';
				?>

				<h2><span id="add_edit_label">{{ $add }}</span> Listing </h2>
				<div class="x_title">
				</div>
				@if(Session::get('message')) <div class="alert alert-success" role="alert">{{Session::get('message')}} </div>@endif 
				<div class="alert alert-success hidden"></div>

					@if ($errors->any())
					<div class="alert alert-danger">
						<ul>
							@foreach ($errors->all() as $error)
							<li>{{ $error }}</li>
							@endforeach
						</ul>
					</div>
					@endif
					<ul class="nav nav-tabs">
							<li class="active"><a data-toggle="tab" href="#home">General Info</a></li>
							<li><a data-toggle="tab" href="#menu_address">Address & Location</a></li>
							<li><a data-toggle="tab" href="#menu2">SEO & Description</a></li>
							<?php /*<li><a data-toggle="tab" href="#menu3">Images</a></li>*/?>
					</ul>

					<form id="listForm" method="post" action="{{ $url }}" class="form-horizontal" accept-charset="UTF-8" enctype="multipart/form-data">
						<input type="hidden" value="{{ $listing->id }}" name="id" id="id" />
						<div class="tab-content">
							<div id="home" class="tab-pane fade in active">
								<div class="form-group required">
									<label class="control-label col-sm-3" for="title">Listing Title</label>
									<div class="col-sm-7">
										<input type="text" class="form-control" name="title" id="title" value="{{ old('title', $listing->title) }}">
									</div>
								</div>
								<div class="form-group ">
									<label class="control-label col-sm-3" for="category">Main Category</label>
									<div class="col-sm-7">
									@if($subscribed_category)
									{{$subscribed_category->c_title}}
									@endif
									</div>
								</div>
								<div class="form-group ">
									<label class="control-label col-sm-3" for="scategory">Sub Category</label>
									<div class="col-sm-7">
										<select name="scategory" id="scategory"  class="form-control"> 
											<option value="">---Choose---</option>
											@foreach ($subcategory as $category)
											<option value="{{ $category->c_id }}" @if( old('scategory', $listing->s_c_id) == $category->c_id ) selected @endif>
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
										if(in_array($tag->id, (array) old('tags', $listing->tags)))
										$checked = ' checked';
										@endphp
										<input class="" type="checkbox" name="tags[]" id="tag_{{ $tag->id }}" value="{{ $tag->id }}"{{ $checked }}/> {{ $tag->name }}<br />
										@endforeach
									</div>
								</div>
								<div class="form-group required">
									<label class="col-sm-3 control-label">Main Image</label>
									<div class="col-sm-7">
										<input class="form-control" type="file" name="photo" id="photo"  />
									</div>
									<div class="col-sm-2"><img src="" id="edit_photo" style="height:40px"></div>
								</div>
								<div class="form-group required">
									<label class="col-sm-3 control-label">Website</label>
									<div class="col-sm-7">
										<input type="text" class="form-control" name="website" id="website" value="{{ old('website', $listing->website) }}">
									</div>
								</div>
								<div class="form-group required">
									<label class="col-sm-3 control-label">Working Hours</label>
									<div class="col-sm-3">
										From
										<select name="start_time" id="start_time"  class="">	
											@foreach ($times as $time_value)
											<option value="{{ $time_value }}" @if( old('start_time', $listing->start_time) == $time_value ) selected @endif>
												{{ $time_value }}
											</option>
											@endforeach
										</select>
										<select name="start_time_ar" id="start_time_ar"  class="">
											<option value="am" @if( old('start_time_ar', $listing->start_time_ar) == $time_value ) selected @endif>AM</option>
											<option value="pm" @if( old('start_time_ar', $listing->start_time_ar) == $time_value ) selected @endif>PM</option>
										</select>
									</div>
									<div class="col-sm-3">
										To
										<select name="end_time" id="end_time"  class="">	
											@foreach ($times as $time_value)
											<option value="{{ $time_value }}" @if( old('end_time', $listing->end_time) == $time_value ) selected @endif>
												{{ $time_value }}
											</option>
											@endforeach
										</select>
										<select name="end_time_ar" id="end_time_ar"  class="">
											<option value="am" @if( old('end_time_ar', $listing->end_time_ar) == $time_value ) selected @endif>AM</option>
											<option value="pm" @if( old('end_time_ar', $listing->end_time_ar) == $time_value ) selected @endif>PM</option>
										</select>
									</div>
								</div>
								<div class="form-group required" id="holiday_days">
									<label class="col-sm-3 control-label">Holidays</label>
									<div class="col-sm-7">
										<input type="checkbox"  name="holidays[]" class="holidays" value="Sunday">Sunday
										<input type="checkbox"  name="holidays[]" class="holidays" value="Monday">Monday
										<input type="checkbox"  name="holidays[]" class="holidays" value="Tuesday">Tuesday
										<input type="checkbox"  name="holidays[]" class="holidays" value="Wednesday">Wednesday
										<br/><input type="checkbox"  name="holidays[]" class="holidays" value="Thursday">Thursday
										<input type="checkbox"  name="holidays[]" class="holidays" value="Friday">Friday
										<input type="checkbox"  name="holidays[]" class="holidays" value="Saturday">Saturday
										<br/>
										<input type="checkbox"  name="holidays[]" class="holidays" value="no">No Holiday
									</div>
								</div>
								<button type="button" data-next="menu_address" class="cls_next btn btn-primary">Next</button>
								<button type="submit" class="btn btn-default">Submit</button>
							</div>
							<div id="menu_address" class="tab-pane fade">
								<div class="form-group required">
									<label class="control-label col-sm-3">Country</label>
									<div class="col-sm-7">
										<select name="country" id="country"  class="form-control country">						  
											<option value="">---Choose---</option>
											@foreach ($country as $country)
											<option value="{{ $country->id }}" @if( old('country', $listing->country) == $country->id ) selected @endif>
												{{ $country->name }}
											</option>
											@endforeach
										</select>
									</div>	
								</div>
								<div class="form-group required">
									<label class="control-label col-sm-3">State</label>
									<div class="col-sm-7">
										<select name="states" id="states"  class="form-control states">
											<option value="">---Choose---</option>
											@foreach ($states as $state)
											<option value="{{ $state->id }}" @if( old('states', $listing->states) == $state->id ) selected @endif>
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
											<option value="{{ $city->id }}" @if( old('cities', $listing->cities) == $city->id ) selected @endif>
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
										<input class="form-control" type="text" name="lat_long" id="lat_long" value="{{ old('lat_long', $listing->lat_long) }}"/>
										<input type="hidden" name="lattitude" id="lattitude" value="{{ old('lattitude', $listing->lattitude) }}">
										<input type="hidden" name="longitude" id="longitude" value="{{ old('longitude', $listing->longitude) }}">
									</div>
								</div>

								<div class="form-group required">
									<label class="control-label col-sm-3">Address 1</label>
									<div class="col-sm-7">
										<input class="form-control" type="text" name="address1" id="address1" value="{{ old('address1', $listing->address1) }}"/>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-sm-3">Address 2</label>
									<div class="col-sm-7">
										<input class="form-control" type="text" name="address2" id="address2" value="{{ old('address2', $listing->address2) }}"/>
									</div>
								</div>
								<div class="form-group required">
									<label class="control-label col-sm-3">Post Code</label>
									<div class="col-sm-7">
										<input class="form-control" type="text" name="postcode" id="postcode" value="{{ old('postcode', $listing->postcode) }}"/>
									</div>
								</div>
								<div class="form-group required">
									<label class="control-label col-sm-3">Phone No</label>
									<div class="col-sm-7">
										<input class="form-control" type="text" name="phoneno" id="phoneno" value="{{ old('phoneno', $listing->phoneno) }}"/>
									</div>
								</div>
								<button type="button" data-next="menu2" class="cls_next btn btn-primary">Next</button>
								<button type="submit" class="btn btn-default">Submit</button>
							</div>
							<div id="menu2" class="tab-pane fade">
								<div class="form-group">
									<label class="col-sm-3 control-label">Meta Tag</label>
									<div class="col-sm-7">
										<textarea name="meta_tag" class="form-control" id="meta_tag">{{ old('meta_tag', $listing->meta_tag) }}</textarea>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label">Meta Description</label>
									<div class="col-sm-7">
										<textarea class="form-control" name="meta_description" id="meta_description">{{ old('meta_description', $listing->meta_description) }}</textarea>
									</div>
								</div>
								<div class="form-group required">
									<label class="col-sm-3 control-label">Listing Description</label>
									<div class="col-sm-7">
										<textarea  class="tinymce" id="description" name="description">{{ old('description', $listing->description) }}</textarea>
									</div>
								</div>

								<button type="submit" class="btn btn-default">Submit</button>
							</div>
							

						</div>
						<input type="hidden" name="_token" value="{{csrf_token()}}">
					</form>


					<!-- Add Form End-->
				</div>



			</div>
			<!-- Right BAR End-->
			<!--</div>
		</div>-->
		<div class="clearfix"></div>  
	</div>	

</div>

<script>
// Add Blog
$(document).on("click", ".add_blog", add_blog);
function add_blog(){  
	

}

// EDit Blog
//$(document).on("click", ".edit_blog", edit_blogs);
$(document).ready(function(){
	edit_blogs('<?php echo $listing->id;?>');
});
function edit_blogs(id){ 
	var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
	//var id= $(this).attr('id'); 

	var host="{{ url('/merchant/listing/getlisting/') }}";
	var update_url = "{{url('merchant/listing/updated')}}";
	$('#listForm').attr('action', update_url);
	$('#add_edit_label').html('Edit');
	
	$(".alert-danger").addClass('hidden') ;
	$(".alert-success").addClass('hidden') ;
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
		},success:renderListform

	})
	return false;
}
function renderListform(res)
{ 
	var listing = res.view_details.listing;
	var url="{{ url('') }}";
	var image_path=listing.image;
	
	$('#fil').html('');
	
	$('#id').val(listing.id);	
	$('#title').val(listing.title);	
	//$('#user_id').val(listing.user_id);
	//$('#language_id').val(listing.l_id);
	$('#category').val(listing.m_c_id);
	$('#lat_long').val(listing.lat_long);
	$('#lattitude').val(listing.latitude);
	$('#longitude').val(listing.longitude);
	if(listing.lat_long)
		initMap();
	
	// subcategories
	var subcategories = res.view_details.subcategories;
	var opt = '<option value="">---Choose---</option>';
	$.each(subcategories, function( index, category ) {
		opt = opt + '<option value="'+category.c_id+'">'+category.c_title+'</option>';
	});
	$('#scategory').html(opt);
	$('#scategory').val(listing.s_c_id);
	
	// tags
	$('.tag_div').html('');
	$('.tag_section').hide();
	var tags = res.view_details.tags;
	var tag_exists = false;
	$.each(tags, function(index, data) {
		tag_exists = true;//console.log(data.name);
		$('.tag_div').append('<input class="" type="checkbox" name="tags[]" id="tag_'+data.id+'" value="'+data.id+'"/> '+data.name+'<br />');
	});
	if(tag_exists)
	{
		$('.tag_section').show();
		var listing_tags = res.view_details.listing_tags;
		$.each(listing_tags, function(index, data) {
			$('#tag_'+data.category_tag_id).attr("checked", "checked");
		});
	}
	
	$('#country').val(listing.c_id);
	
	// states
	var states = res.view_details.states;
	var opt = '<option value="">---Choose---</option>';
	$.each(states, function( index, state ) {
		opt = opt + '<option value="'+state.id+'">'+state.name+'</option>';
	});
	$('#states').html(opt);
	$('#states').val(listing.state);
	
	// cities
	var cities = res.view_details.cities;
	var opt = '<option value="">---Choose---</option>';
	$.each(cities, function( index, city ) {
		opt = opt + '<option value="'+city.id+'">'+city.name+'</option>';
	});
	$('#cities').html(opt);
	$('#cities').val(listing.city);
	$('#address1').val(listing.address1);
	$('#address2').val(listing.address2);
	$('#postcode').val(listing.pincode);
	$('#phoneno').val(listing.phoneno);	
	$('#edit_photo').attr('src',url+'/uploads/listing/thumbnail/'+image_path);
	$('#meta_tag').val(listing.meta_tag);
	$('#meta_description').val(listing.meta_description);
	$('#website').val(listing.website);
	var time=listing.working_hours;
	if(time!=null){
		var timing=time.split('-');
		var datastring = timing[0];//console.log(datastring);
		var myArray = datastring.split(/(\d+)/).filter(Boolean);//console.log(myArray);
		$('#start_time').val(myArray[0]);
		$('#start_time_ar').val(myArray[1]);
		var timestring = timing[1];//console.log(datastring);
		var timingar = timestring.split(/(\d+)/).filter(Boolean);//console.log(myArray);
		$('#end_time').val(timingar[0]);
		$('#end_time_ar').val(timingar[1]);
	}
	var holiday=jQuery.parseJSON(listing.holidays);
	if(holiday!=null){
		$('.holidays').each(function(){
			for(var i=0;i<holiday.length;i++){
				if($(this).val()==holiday[i]){
					$(this).attr('checked','true');
				}
			}
		})
	}
	$(tinymce.get('description').getBody()).html(listing.description);	

	// table booking settings
	if($('#table_booking_services').length > 0)
	{
		var table_booking_setting = res.view_details.tablebookingsettings;//console.log(table_booking_setting);
		if(table_booking_setting)
		{
			$('#table_booking_service_id').val(table_booking_setting.id);
			
			var datastring = table_booking_setting.start_time;//console.log(datastring);
			var myArray = datastring.split(/(\d+)/).filter(Boolean);//console.log(myArray);
			$('#start_time').val(myArray[0]);
			$('#start_time_ar').val(myArray[1]);
			
			var datastring = table_booking_setting.end_time;
			var myArray = datastring.split(/(\d+)/).filter(Boolean);
			$('#end_time').val(myArray[0]);
			$('#end_time_ar').val(myArray[1]);
			
			$('#time_interval').val(table_booking_setting.time_interval);
			$('#people_limit').val(table_booking_setting.people_limit);
		}
	}
	
	// images
	/*var listing_images = res.view_details.listing_images;
	renderListImagesEdit(listing_images);
	get_item_images(); // get images from session
	*/
}

$(document).on("click", ".delete_blog", deleteRow);
function deleteRow(){ 
	if (confirm("Are you sure delete listing?")) {
		
		var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
		var id= $(this).attr('id'); 
		var host="{{ url('/merchant/listing/deleted/') }}";
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
			},
			success: function(del_res)
			{
				var id=del_res.delete_details.deletedid;
				$('.rm'+id).hide();
				$(".alert-success").html(del_res.delete_details.deletedtatus).removeClass('hidden');

			}

		});

	}

	return false;
}
</script>

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

var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

Dropzone.options.dropzonePreview = {
	// Prevents Dropzone from uploading dropped files immediately
	//dropzonePreview.autoDiscover = false;
	url: "{{ url('merchant/listing/image_upload') }}",
	autoProcessQueue: true,
	//maxFiles: 1,
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
				
				//get_item_images();
				
				//myDropzone.removeAllFiles();
			}
		});
		
		this.on("complete", function(file) {
			myDropzone.removeFile(file);
			//get_item_images();
		});
		
		this.on("queuecomplete", function(file) {
			//myDropzone.removeFile(file);
			get_item_images();
		});

	}
};
function get_item_images() {
	$.ajax({
		type: 'POST',
		data:{'_token': CSRF_TOKEN},
		url: "{{ url('merchant/listing/get_uploaded_images') }}",
		dataType: "json", // data type of response		
		beforeSend: function(){
			$('.image_loader').show();
		},
		complete: function(){
			$('.image_loader').hide();
		},success:renderListImages

	});
}

function renderListImagesEdit(res){ 
	//$('#fil').html('');
	var thumb_url = "{{ url('/uploads/listing/thumbnail') }}/";
	$.each(res, function(index, prolist) { 		
		if(prolist.id) {
			$('#fil').append('<div class="dz-preview dz-processing dz-image-preview dz-success" id="list_image_'+prolist.id+'"><div class="dz-details"><img data-dz-thumbnail="" alt="'+prolist.image_name+'" src="'+thumb_url+prolist.image_name+'"></div><div class="dz-progress"><span class="dz-upload" data-dz-uploadprogress="" style="width: 100%;"></span></div><div class="dz-error-message"><span data-dz-errormessage=""></span></div><a class="dz-remove" href="javascript:void(0)" onClick="delete_listing_image(\''+prolist.id+'\')">Remove</a></div>');
		}
	});
}

function delete_listing_image(id)
{
	if (confirm("This action will delete this image permanently. Are you sure want to perform this action?") == true) {
		//
		var id = id;
		$.ajax({
			url:"{{ url('merchant/listing/delete_image_table') }}/"+id,
			success:function(data)
			{

				$('#list_image_'+id).hide();
				//get_item_images();

			},

		});
	}
}

function renderListImages(res){ 
	//$('#fil').html('');
	var thumb_url = "{{ url('/uploads/listing/thumbnail') }}/";
	$.each(res, function(index, prolist) { 		
		if(prolist.id) {
			$('#fil').append('<div class="dz-preview dz-processing dz-image-preview dz-success" id="list_image_'+prolist.id+'"><div class="dz-details"><img data-dz-thumbnail="" alt="'+prolist.image_name+'" src="'+thumb_url+prolist.image_name+'"></div><div class="dz-progress"><span class="dz-upload" data-dz-uploadprogress="" style="width: 100%;"></span></div><div class="dz-error-message"><span data-dz-errormessage=""></span></div><a class="dz-remove" href="javascript:void(0)" onClick="delete_listing_image_temp(\''+prolist.id+'\')">Remove</a></div>');
		}
	});
}
function delete_listing_image_temp(id)
{
	if (true) {
		//confirm("This action will delete this image permanently. Are you sure want to perform this action?") == 
		var id = id;
		$.ajax({
			url:"{{ url('merchant/listing/delete_image') }}/"+id,
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

<script>
	var markers = [];
	var map = null, marker = null;	
	function initMap() {
		var lat = 53.507;
		var lon = -5.127;
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
<script async defer
src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBU7YTBgP-ZsBlVcYGYAfuz2os6yfTWLIg&libraries=places&callback=initMap">
</script>

@stop