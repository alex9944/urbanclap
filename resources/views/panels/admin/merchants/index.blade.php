@extends('layouts.adminmain')

@section('head')

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

		
					<!-- <div class="col-md-12 col-xs-12">
					<div class="x_panel">-->

						<!-- LEFT BAR Start-->
						<div class="col-md-6 col-xs-12">
							<div class="x_panel">
								<form name="actionForm" action="{{url('admin/merchants/listing/destroy')}}" method="post" onsubmit="return deleteConfirm();"/> 
								<h2>All Listing <span class="pull-right"><a href="{{url('admin/merchants/listing')}}" class="btn btn-success btn-xs add_blog"><i class="fa fa-plus"></i> Add </a>  
									<button  class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> Delete All</button>
									<input type="hidden" name="_token" value="{{csrf_token()}}">
								</span></h2>
								<div class="x_title">
								</div>



								<table id="datatable-checkbox" class="table table-striped table-bordered bulk_action">
									<thead>
										<tr><th>
											<input type="checkbox" name="check_all" id="check_all" value=""/></th>		
											<th>Merchant Id</th>				 
											<th>Merchant Name</th>
											<th>Listing Title</th>
											<th>Action</th>                         
										</tr>
									</thead>
									<tbody>
									
										@foreach ($listing as $listing)
										<tr class="rm{{ $listing->id }}">
											<td>
												<input type="checkbox" name="selected_id[]" class="checkbox" value="{{ $listing->id }}"/>				 	  
											</td>
											<td>{{ $listing->user_id }}</td>
											<td>{{ isset($listing->listing_merchant->first_name) ? $listing->listing_merchant->first_name : '' }}</td>
											<td>{{ $listing->title }}</td>
											<td>
												@if($listing->status=='Enable')
												<a href="#" class=" btn btn-primary btn-xs enable" id="{{ $listing->id }}"><i class="glyphicon glyphicon-ok"></i> Enable </a>
												@else
												<a href="#" class=" btn btn-primary btn-xs disable" id="{{ $listing->id }}" ><i class="glyphicon glyphicon-remove"></i> Disable </a>
												@endif
												<!--<a href="#" class="btn btn-primary btn-xs"><i class="fa fa-folder"></i> View </a>-->
												<a href="javascript:void(0);" class="edit_blog btn btn-info btn-xs" id="{{ $listing->id }}" ><i class="fa fa-pencil"></i> Edit </a>
												<a href="javascript:void(0);" class="btn btn-danger btn-xs delete_blog" id="{{ $listing->id }}"><i class="fa fa-trash-o"></i> Delete </a></td>
											</tr> 
											@endforeach

										</tbody>
									</table>
								</form>	  
							</div>
						</div>
						<!-- LEFT BAR End-->


						<!-- Right BAR Start-->
						<div class="col-md-6 col-xs-12">

							<div class="x_panel" id="edit_div">
								<?php
								$id = '';
								$add = 'Add';
								$url = url('admin/merchants/listing/added');
								if(old('id') != '')
								{
									$id = old('id');
									$add = 'Edit';
									$url = url('admin/merchants/listing/updated');
								}
								?>
								
								<h2><span id="add_div_label">{{$add}}</span> Listing </h2>
								<div class="x_title">
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
								
								<!-- Edit Form Start-->
								<form id="addListing" method="POST" action="{{$url}}"  enctype="multipart/form-data" class="form-horizontal">
									<input type="hidden" value="{{old('id')}}" name="id" id="id" />
									<input type="hidden" value="{{old('merchant_name')}}" name="merchant_name" id="merchant_name" />
									<div id="reportArea">

										<div class="form-group">
											<label class="col-sm-3 control-label"> Merchant Name</label>
											<div class="col-sm-6">
												<select name="user_id" id="user_id"  class="form-control">				  
													<option value="">---Choose---</option>
													@foreach ($users as $user)
													<option value="{{ $user->id }}" @if( old('user_id') == $user->id ) selected @endif>
														{{ $user->first_name }}
													</option>
													@endforeach
													@if(old('merchant_name') and old('user_id') and old('id'))
														<option value="{{ old('user_id') }}" selected>
															{{ old('merchant_name') }}
														</option>
													@endif
												</select>	
											</div>
										</div>

										<?php /*<div class="form-group">
											<label class="col-sm-3 control-label"> Language</label>
											<div class="col-sm-6">
												<select name="language_id" id="edit_language_id"  class="form-control">			  
													@foreach ($editlanguage as $language)
													<option value="{{ $language->id }}">
														{{ $language->title }}
													</option>
													@endforeach
												</select>						 

												<span class="error">{{ ($errors->has('language_id')) ? $errors->first('language_id') : ''}}</span>
											</div>
										</div>	*/?>	
										
										<div class="form-group">
											<label class="col-sm-3 control-label"> Sub Category</label>
											<div class="col-sm-6">
												<select name="scategory" id="scategory"  class="form-control"> 
													<option value="">---Choose---</option>
													@foreach ($subcategory as $editsubcategory)
													<option value="{{ $editsubcategory->c_id }}" @if( old('scategory') == $editsubcategory->c_id ) selected @endif>
														{{ $editsubcategory->c_title }}
													</option>
													@endforeach
												</select>	
											</div>
										</div>
										<div class="form-group ">
											<label class="col-sm-3 control-label">Website</label>
											<div class="col-sm-6">
												<input type="text" class="form-control website" name="website" id="website" value="{{ old('website') }}">
											</div>
										</div>
										<div class="form-group ">
											<label class="col-sm-3 control-label">Working Hours</label>
											<div class="col-sm-3">
												From
												<select name="start_time" id="start_time"  class="start_time">	
													@foreach ($times as $time_value)
													<option value="{{ $time_value }}" @if( old('start_time') == $time_value ) selected @endif>
														{{ $time_value }}
													</option>
													@endforeach
												</select>
												<select name="start_time_ar" id="start_time_ar"  class="start_time_ar">
													<option value="am" @if( old('start_time_ar') == 'am' ) selected @endif>AM</option>
													<option value="pm" @if( old('start_time_ar') == 'pm' ) selected @endif>PM</option>
												</select>
											</div>
											<div class="col-sm-3">
												To
												<select name="end_time" id="end_time"  class="end_time">	
													@foreach ($times as $time_value)
													<option value="{{ $time_value }}" @if( old('end_time') == $time_value ) selected @endif>
														{{ $time_value }}
													</option>
													@endforeach
												</select>
												<select name="end_time_ar" id="end_time_ar"  class="end_time_ar">
													<option value="am" @if( old('end_time_ar') == 'am' ) selected @endif>AM</option>
													<option value="pm" @if( old('end_time_ar') == 'pm' ) selected @endif>PM</option>
												</select>
											</div>
										</div>
										<div class="form-group " id="holiday_days">
											<label class="col-sm-3 control-label">Holidays</label>
											<div class="col-sm-7">
												<input type="checkbox"  name="holidays[]" class="holidays" value="Sunday"@if(in_array('Sunday', old('holidays', []))) checked @endif>Sunday
												<input type="checkbox"  name="holidays[]" class="holidays" value="Monday"@if(in_array('Monday', old('holidays', []))) checked @endif>Monday
												<input type="checkbox"  name="holidays[]" class="holidays" value="Tuesday"@if(in_array('Tuesday', old('holidays', []))) checked @endif>Tuesday
												<input type="checkbox"  name="holidays[]" class="holidays" value="Wednesday"@if(in_array('Wednesday', old('holidays', []))) checked @endif>Wednesday
												<br/><input type="checkbox"  name="holidays[]" class="holidays" value="Thursday"@if(in_array('Thursday', old('holidays', []))) checked @endif>Thursday
												<input type="checkbox"  name="holidays[]" class="holidays" value="Friday"@if(in_array('Friday', old('holidays', []))) checked @endif>Friday
												<input type="checkbox"  name="holidays[]" class="holidays" value="Saturday"@if(in_array('Saturday', old('holidays', []))) checked @endif>Saturday
												<br/>
												<input type="checkbox"  name="holidays[]" class="holidays" value="no"@if(in_array('no', old('holidays', []))) checked @endif>No Holiday
											</div>
										</div>

										<div class="form-group required">
											<label class="col-sm-3 control-label"> Listing Title</label>
											<div class="col-sm-6">
												<input class="form-control" type="text" name="title" id="title" value="{{ old('title') }}"/>
											</div>
										</div>
										<div class="form-group required">
											<label class="col-sm-3 control-label">Country</label>
											<div class="col-sm-6">
												<select name="country" id="country"  class="form-control edit_country">						  
													@foreach ($country as $country)
													<option value="{{ $country->id }}" @if( old('country', $default_country->id) == $country->id ) selected @endif>
														{{ $country->name }}
													</option>
													@endforeach
												</select>	
											</div>
										</div>
										<div class="form-group required">
											<label class="col-sm-3 control-label">States</label>
											<div class="col-sm-6">
												<select name="states" id="states"  class="form-control edit_states">
													<option value="">
														---Choose---
													</option>
													@foreach ($states as $editstates)
													<option value="{{ $editstates->id }}" @if( old('states') == $editstates->id ) selected @endif>
														{{ $editstates->name }}
													</option>
													@endforeach
												</select>
											</div>
										</div>
										<div class="form-group required">
											<label class="col-sm-3 control-label">Cities</label>
											<div class="col-sm-6">
												<select name="cities" id="cities"  class="form-control">		
													<option value="">
														---Choose---
													</option>
													@foreach ($cities as $editcities)
													<option value="{{ $editcities->id }}" @if( old('cities') == $editcities->id ) selected @endif>
														{{ $editcities->name }}
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
											<label class="control-label col-sm-3">Lattitude</label>
											<div class="col-sm-7">
												<input class="form-control" type="text" name="lattitude" id="lattitude" value="{{ old('lattitude') }}"/>
											</div>
										</div>
										<div class="form-group required">
											<label class="control-label col-sm-3">Longitude</label>
											<div class="col-sm-7">
												<input class="form-control" type="text" name="longitude" id="longitude" value="{{ old('longitude') }}"/>
											</div>
										</div>
										<div class="form-group required">
											<label class="col-sm-3 control-label">Address</label>
											<div class="col-sm-6">
												<input class="form-control" type="text" name="address1" id="address1" value="{{ old('address1') }}"/>                        
											</div>
										</div>
										<div class="form-group hidden">
											<label class="col-sm-3 control-label">Address 2</label>
											<div class="col-sm-6">
												<input class="form-control" type="text" name="address2" id="address2" value="{{ old('address2') }}"/>

											</div>
										</div>
										<div class="form-group required">
											<label class="col-sm-3 control-label">Postcode</label>
											<div class="col-sm-6">
												<input class="form-control" type="text" name="postcode" id="postcode" value="{{ old('postcode') }}"/>
											</div>
										</div>
										<div class="form-group required">
											<label class="col-sm-3 control-label">Phone No</label>
											<div class="col-sm-6">
												<input class="form-control" type="text" name="phoneno" id="phoneno" value="{{ old('phoneno') }}"/>
											</div>
										</div>			
										<div class="form-group required">
											<label class="col-sm-3 control-label">Image</label>
											<div class="col-sm-6">
												<div class="col-sm-9">
													<input class="form-control" type="file" name="photo" id="photo"  /></div>
													<div class="col-sm-3"><img src="" id="edit_photo" style="height:40px">
													</div>


												</div>
											</div>
											<div class="form-group">
												<label class="col-sm-3 control-label">Meta Tag</label>
												<div class="col-sm-6">
													<textarea name="meta_tag" class="form-control" id="meta_tag">{{ old('meta_tag') }}</textarea>
												</div>
											</div>
											<div class="form-group">
												<label class="col-sm-3 control-label">Meta Description</label>
												<div class="col-sm-6">
													<textarea class="form-control" name="meta_description" id="meta_description">{{ old('meta_description') }}</textarea>
												</div>
											</div>
											<div class="form-group required">
												<label class="col-sm-3 control-label" style="text-align:left">Description</label>
												<div class="col-sm-6">
													<textarea  class="form-control" id="description" name="description">{{ old('description') }}</textarea>
												</div>
											</div>
											
											<div class="form-group">
												<label class="col-sm-3 control-label">Delivery Charge</label>
												<div class="col-sm-6">
													<input class="form-control" type="text" name="delivery_charge" id="delivery_charge" value="{{ old('delivery_charge') }}"/>
												</div>
											</div>	

											<input type="hidden" name="_token" value="{{csrf_token()}}">
											<div class="ln_solid"></div>
											<div class="form-group">
												<div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">						
													<input class="btn btn-success" type="submit" name="submit" id="submit" value="Submit"/>

												</div>
											</div>



											<div class="clearfix visible-lg"></div>
										</div>
									</form>


									<!-- Edit Form End-->




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
	var id= $(this).attr('id');  
	$('#edit_div').hide();
	$('#add_div').fadeIn("slow");	
	
	$('#add_div_label').html('Add');
	
	var url = "{{url('admin/merchants/listing/added')}}";
	$('#addListing').attr('action', url);
	
	$(".alert-danger").addClass('hidden') ;
	$(".alert-success").addClass('hidden') ;

}

// EDit Blog
$(document).on("click", ".edit_blog", edit_blogs);
function edit_blogs(){ 
	var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
	var id= $(this).attr('id'); 
	
	var url = "{{url('admin/merchants/listing/updated')}}";
	$('#addListing').attr('action', url);

	var host="{{ url('admin/merchants/listing/getlisting/') }}";
	$('#add_div').hide();
	$('#edit_div').fadeIn("slow");
	
	$('#add_div_label').html('Edit');
	
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
function renderListform(res){ 
	//console.log(res);
	var url="{{ url('') }}";
	
	var listing = res.view_details.listing;
	var merchant = listing.listing_merchant;
	
	// append user
	$('#user_id').html('');
	$('#user_id').append($('<option>', {
		value: merchant.id,
		text: merchant.first_name
	}));
	$('#user_id').val(merchant.id);
	$('#merchant_name').val(merchant.first_name);
	
	var subcategory = new Object();
	subcategory.view_details = res.view_details.subcategory;	
	$('#scategory').html('<option value="">---Choose---</option>');	
	render_subcategories(subcategory);
	
	var states = new Object();
	var cities = new Object();
	states.view_details = res.view_details.states;
	cities.view_details = res.view_details.cities;
	$('#states').html('<option value="">---Choose---</option>');
	$('#cities').html('<option value="">---Choose---</option>');
	rendereditgetstates(states);
	rendereditgetcities(cities);
	
	var image_path=listing.image;
	$('#id').val(listing.id);	
	$('#title').val(listing.title);	
	$('#user_id').val(listing.user_id);
	//$('#edit_language_id').val(listing.l_id);
	//$('#category').val(listing.m_c_id);
	$('#scategory').val(listing.s_c_id);
	$('#country').val(listing.c_id);
	$('#states').val(listing.	state);
	$('#cities').val(listing.city);
	$('#address1').val(listing.address1);
	//$('#edit_address2').val(listing.address2);
	$('#postcode').val(listing.pincode); 
	$('#phoneno').val(listing.phoneno);	
	$('#edit_photo').attr('src',url+'/uploads/listing/thumbnail/'+image_path);
	$('#meta_tag').val(listing.meta_tag);
	$('#meta_description').val(listing.meta_description);
	$('#website').val(listing.website);
	//$('#lat_long').val(listing.lat_long);
	$('#lattitude').val(listing.lattitude);
	$('#longitude').val(listing.longitude);
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
	$('#description').val(listing.description);
	//$(tinymce.get('description').getBody()).html(listing.description);	
	
	var ordersettings = res.view_details.ordersettings;
	if(ordersettings != null)
		$('#delivery_charge').val(ordersettings.delivery_fee);
	
	// trigger city change
	set_marker(listing.lattitude, listing.longitude, listing.address1);
}

$(document).on("click", ".delete_blog", deleteRow);
function deleteRow(){ 
	if (confirm("Are you sure want to delete the listing?")) {
		
		var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
		var id= $(this).attr('id'); 
		var host = "{{ url('admin/merchants/listing/deleted') }}";
		$.ajax({
			type: 'POST',
			data:{_token:CSRF_TOKEN, id: id},
			url: host,
			dataType: "json", // data type of response		
			beforeSend: function(){
				$('.image_loader').show();
			},
			complete: function(){
				$('.image_loader').hide();
			},
			success: function(res)
			{
				if(res.success)
				{
					window.location = "{{ url('admin/merchants/listing') }}";
				}
				else
				{
					alert(res.msg);
				}

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
		} else {
			if (confirm("Are you sure delete the all selected listings?"))
				return true;
			else
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
	var host="{{ url('admin/merchants/listing/enable/') }}";
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
	var host="{{ url('admin/merchants/listing/disable/') }}";
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

// get subcategories
$(document).on("change", "#user_id", get_subcategories);
function get_subcategories(){ 
	var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
	var id= $(this).val();
	
	$('#scategory').html('<option value="">---Choose---</option>');
	
	if(id == '') {		
		return false;
	}

	var host="{{ url('admin/merchants/listing/getsubcategory/') }}";	
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
		},success:render_subcategories

	})
	return false;
}
function render_subcategories(res){

	$.each(res.view_details, function(index, data) {
		if (index==0) {
			$('#scategory').append('<option value="'+data.c_id+'">'+data.c_title+'</option>');
		}else {
			$('#scategory').append('<option value="'+data.c_id+'">'+data.c_title+'</option>');
		};
	});   
}  

// Get States
$(document).on("change", ".edit_country", editgetstates);
function editgetstates(){ 
	var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
	var id= $(this).val(); 
	
	$('#states').html('<option value="">---Choose---</option>');
	$('#cities').html('<option value="">---Choose---</option>');
	
	if(id == '')
		return false;

	var host="{{ url('admin/merchants/listing/getstates/') }}";	
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
		},success:rendereditgetstates

	})
	return false;
}
function rendereditgetstates(res){

	$.each(res.view_details, function(index, data) {
		if (index==0) {
			$('#states').append('<option value="'+data.id+'">'+data.name+'</option>');
		}else {
			$('#states').append('<option value="'+data.id+'">'+data.name+'</option>');
		};
	});   
}	  



// Get Sub Cities
$(document).on("change", ".edit_states", editgetcities);
function editgetcities(){ 
	var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
	var id= $(this).val();
	
	$('#cities').html('<option value="">---Choose---</option>');
	
	if(id == '')
		return false;

	var host="{{ url('admin/merchants/listing/getcities/') }}";	
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
		},success:rendereditgetcities

	})
	return false;
}
function rendereditgetcities(res){

	$.each(res.view_details, function(index, data) {
		if (index==0) {
			$('#cities').append('<option value="'+data.id+'">'+data.name+'</option>');
		}else {
			$('#cities').append('<option value="'+data.id+'">'+data.name+'</option>');
		};
	});   
}	

</script>
<script>
	var markers = [];
	var map = null, marker = null;
	var set_map_on;
	function initMap() {
		var lat = 53.507;
		var lon = -5.127;
		var zoom_level = 5;
		
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
			
			set_map_on(null);
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
		
		if( zoom_level == 16 )
		{
			set_map_on(null);
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
		else
		{
			var address = $("#country option:selected").text();
			if(address)
				showAddress(address, geocoder, map);
		}

		set_map_on = function setMapOnAll(map) {
			for (var i = 0; i < markers.length; i++) {
				markers[i].setMap(map);
			}
		}	

		document.getElementById('country').addEventListener('change', function() {
			var address = $("#country option[value='"+$(this).val()+"']").text();
			if(address)
				showAddress(address, geocoder, map);
		});
		
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
	
	function set_marker(lat, lon, address) {
		var location = new google.maps.LatLng(lat, lon);
		/*var mapCanvas = document.getElementById('map');
		var mapOptions = {
			center: location,
			zoom: 16,
			mapTypeId: google.maps.MapTypeId.ROADMAP, 
			draggableCursor: 'pointer',
			disableDefaultUI: true
		}
		map = new google.maps.Map(mapCanvas, mapOptions);
		*/
		
		set_map_on(null);
		marker = new google.maps.Marker({position: location, map: map});
		marker.setMap(map);
		map.setZoom(16);
		map.setCenter(marker.getPosition());
		markers.push(marker);
		
		var contentString = '<div class="info-window">' +
		'<h3>Address</h3>' +
		'<div class="info-content">' +
		"<p>" + address + "</p>" +
		'</div>' +
		'</div>';

		var infowindow = new google.maps.InfoWindow({
			content: contentString,
			maxWidth: 400
		});
		
		marker.addListener('click', function () {
			infowindow.open(map, marker);
		});
	}

</script>
<script async defer
src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBU7YTBgP-ZsBlVcYGYAfuz2os6yfTWLIg&libraries=places&callback=initMap">
</script>
@stop