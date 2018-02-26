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
                <h3>Appointment Booking</h3>
              </div>

    </div>
            <div class="clearfix"></div>           

            <div class="row">

		
					<!-- <div class="col-md-12 col-xs-12">
					 <div class="x_panel">-->
					 
					 <!-- LEFT BAR Start-->
					 <div class="col-md-5 col-xs-12">
								<div class="x_panel">
								<form name="actionForm" action="{{url('merchant/appointment-booking/destroy_all')}}" method="post" onsubmit="return deleteConfirm();"/> 
									<h2>Listing <span class="pull-right"><a href="{{url('merchant/appointment-booking')}}" class="btn btn-success btn-xs add_blog"><i class="fa fa-plus"></i> Add </a>  
									<button  class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> Delete All</button>
									<input type="hidden" name="_token" value="{{csrf_token()}}">
									</span></h2>
								 <div class="x_title">
								  </div>
								
   
    
								  <table id="datatable-checkbox" class="table table-striped table-bordered bulk_action">
                      <thead>
                        <tr><th>
						<input type="checkbox" name="check_all" id="check_all" value=""/></th>						 
                          <th>Listing Title</th>
						  <th>Status</th>
                          <th>Action</th>                         
                        </tr>
                      </thead>
                      <tbody>
					@foreach ($appointment_booking_settings as $appointment_booking_setting)
						@php
							$listing = $appointment_booking_setting->listing;
						@endphp
                        <tr class="rm{{ $appointment_booking_setting->id }}">
                          <td>
						  <input type="checkbox" name="selected_id[]" class="checkbox" value="{{ $appointment_booking_setting->id }}"/>				 	  
						  </td>
                          <td>{{ $listing->title }}</td>
						  <td>{{ $appointment_booking_setting->status }}</td>
                         <td>
						 <!--<a href="#" class="btn btn-primary btn-xs"><i class="fa fa-folder"></i> View </a>-->
                            <a href="javascript:void(0);" class="edit_blog btn btn-info btn-xs" id="{{ $appointment_booking_setting->id }}" ><i class="fa fa-pencil"></i> Edit </a>
                            <a href="javascript:void(0);" class="btn btn-danger btn-xs delete_blog" id="{{ $appointment_booking_setting->id }}"><i class="fa fa-trash-o"></i> Delete </a></td>
                        </tr> 
					@endforeach
						 								
                      </tbody>
                    </table>
</form>	  
								</div>
					</div>
					 <!-- LEFT BAR End-->
					
					
					 <!-- Right BAR Start-->
					<div class="col-md-7 col-xs-12">
					<div class="x_panel" id="add_div" style="">
						<?php
							$url = url('merchant/appointment-booking/add');
							$add = 'Add';
							if(old('id'))
							{
								$url = url('merchant/appointment-booking/update');
								$add = 'Edit';
							}
						?>
						
						<h2><span id="add_edit_label">{{ $add }}</span> Settings </h2>
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
						<form id="listForm" method="post" action="{{ $url }}" class="form-horizontal">
							<input id="method" name="_method" type="hidden" value="POST">
							<input type="hidden" value="{{ old('id') }}" name="id" id="id" />
							<input type="hidden" name="_token" value="{{csrf_token()}}">
							<div class="form-group required">
								<label class="col-sm-5 control-label">Listing</label>
								<div class="col-sm-5">
									<select name="listing_id" id="listing_id"  class="form-control">	
										<option value="">Select</option>
										@foreach ($listings as $listing)
											@php
												$appointmentbookingsettings = $listing->appointmentbookingsettings;
											@endphp
											@if(! $appointmentbookingsettings)
												 <option value="{{ $listing->id }}" @if( old('listing_id') == $listing->id ) selected @endif>
												 {{ $listing->title }}
												 </option>
											@endif
										@endforeach
									</select>
								</div>
							</div>
							<div class="form-group required">
								<label class="col-sm-5 control-label">Start Time</label>
								<div class="col-sm-5">
									<select name="start_time" id="start_time"  class="">	
										@foreach ($times as $time_value)
										 <option value="{{ $time_value }}" @if( old('start_time') == $time_value ) selected @endif>
										 {{ $time_value }}
										 </option>
										@endforeach
									</select>
									<select name="start_time_ar" id="start_time_ar"  class="">
										<option value="am" @if( old('start_time_ar') == $time_value ) selected @endif>AM</option>
										<option value="pm" @if( old('start_time_ar') == $time_value ) selected @endif>PM</option>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-5 control-label">End Time</label>
								<div class="col-sm-5">
									<select name="end_time" id="end_time"  class="">	
										@foreach ($times as $time_value)
										 <option value="{{ $time_value }}" @if( old('end_time') == $time_value ) selected @endif>
										 {{ $time_value }}
										 </option>
										@endforeach
									</select>
									<select name="end_time_ar" id="end_time_ar"  class="">
										<option value="am" @if( old('end_time_ar') == $time_value ) selected @endif>AM</option>
										<option value="pm" @if( old('end_time_ar') == $time_value ) selected @endif>PM</option>
									</select>
								</div>
							</div>
							<div class="form-group required">
								<label class="col-sm-5 control-label">Time Interval Between 2 Slots</label>
								<div class="col-sm-5">
									<input class="form-control" type="text" name="time_interval" id="time_interval" value="{{ old('time_interval') }}"/> hour
								</div>
							</div>
							<div class="form-group required">
								<label class="col-sm-5 control-label">Status</label>
								<div class="col-sm-5">
									<select name="status" id="status"  class="form-control">
										<option value="1" @if( old('status') == 1 || old('status') == '' ) selected @endif>Enable</option>
										<option value="0" @if( old('status') === 0 ) selected @endif>Disable</option>
									</select>
								</div>
							</div>
							
							<button type="submit" class="btn btn-default">Submit</button>
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


// EDit Blog
 $(document).on("click", ".edit_blog", edit_blogs);
function edit_blogs(){ 
	var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
	var id= $(this).attr('id'); 

	var host = "{{ url('/merchant/appointment-booking') }}" + '/' + id + '/edit';
	var update_url = "{{url('merchant/appointment-booking/update')}}";
	$('#listForm').attr('action', update_url);
	$('#add_edit_label').html('Edit');
	
	$(".alert-danger").addClass('hidden') ;
	$(".alert-success").addClass('hidden') ;
	$.ajax({
		type: 'GET',
		//data:{'id': id},
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
	var settings = res.view_details.settings;
		
	$('#id').val(settings.id);	
	
	// append listing
	$('#listing_id').append($('<option>', {
		value: listing.id,
		text: listing.title
	}));
	$('#listing_id').val(settings.listing_id);	
	
	var datastring = settings.start_time;//console.log(datastring);
	var myArray = datastring.split(/(\d+)/).filter(Boolean);//console.log(myArray);
	$('#start_time').val(myArray[0]);
	$('#start_time_ar').val(myArray[1]);
	
	var datastring = settings.end_time;
	var myArray = datastring.split(/(\d+)/).filter(Boolean);
	$('#end_time').val(myArray[0]);
	$('#end_time_ar').val(myArray[1]);
	
	$('#time_interval').val(settings.time_interval);
	$('#status').val(settings.status);
}

$(document).on("click", ".delete_blog", deleteRow);
function deleteRow(){ 
	if (confirm("Are you sure want to delete the appointment booking?")) {
		
		var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
		var id= $(this).attr('id'); 
		var host = "{{ url('/merchant/appointment-booking') }}" + '/' + id;
		$.ajax({
			type: 'DELETE',
			data:{'_token':CSRF_TOKEN},
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
					window.location = "{{ url('merchant/appointment-booking') }}";
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

</script>

@stop