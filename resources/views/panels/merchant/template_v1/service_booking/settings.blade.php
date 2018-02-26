@extends('layouts.'.$merchant_layout)

@section('head')
   <link rel="stylesheet" type="text/css" href="{{ asset($merchant_layout . '/css/outsorce.css') }}"> 
@stop
<style>
.error-ul{list-style:none;line-height:25px;}
.wrapperbox{cursor: pointer;}
</style>
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}" />

@if(Session::get('message')) <div class="alert alert-success" role="alert">{{Session::get('message')}} </div>@endif 
@if(Session::get('error_message')) <div class="alert alert-danger" role="alert">{{Session::get('error_message')}} </div>@endif 

@if ($errors->any())
<div class="alert alert-danger">
	<ul class="error-ul">
		@foreach ($errors->all() as $error)
		<li>{{ $error }}</li>
		@endforeach
	</ul>
</div>
@endif

<div class="services_app__block">
	<!--<ul class="nav nav-pills">
	
		<li class="active"><a>Service Booking</a></li>
	</ul>-->
	
	<div class="tab-content clearfix">            
		<div class="tab-pane active" id="shop">
			@if($service_disable)
			<h3 class="appmt_title">Click to Activate</h3>
			<div class="shop_block">
				<div class="divbox">
					<a href="{{ url('merchant/service-booking/enable-service') }}" class="ui-link">
						<span class="notify-badge " style="display: none;"><i class="fa fa-check" aria-hidden="true"></i></span>
						<div class="wrapperbox deactive">
							<div class="pay-content appmt_span">
								<div><i class="fa fa-calendar" aria-hidden="true"></i>
									<span class="appmt_inf"><i class="fa fa-info-circle" aria-hidden="true"></i></span>
								</div>
								<span>Service Booking</span>
							</div>
						</div>
					</a>
				</div>
			</div>
			@else
			<h3 class="appmt_title">Click to Deactivate</h3>
			<div class="appmt_block">
				<div class="divbox">
					<a href="{{ url('merchant/service-booking/disable-service') }}" class="ui-link active">
						<span class="notify-badge " style="display: none;"><i class="fa fa-check" aria-hidden="true"></i></span>
						<div class="wrapperbox">
							<div class="pay-content appmt_span">
								<div><i class="fa fa-calendar" aria-hidden="true"></i>
									<span class="appmt_inf"><i class="fa fa-info-circle" aria-hidden="true"></i></span>
								</div>
								<span>Service Booking</span>
							</div>
						</div>
					</a>
				</div>
			</div>
			
			<?php
								$url = url('merchant/service-booking/add');
								$add = 'Add';
								if(isset($table_booking_settings->id))
								{
									$url = url('merchant/service-booking/update');
									$add = 'Edit';
								}
								?>
								
			<!-- date block start -->
				
			<div class="date_block  ">
				<div class="container">
				<form id="listForm" method="post" action="{{ $url }}" class="form-inline">
									<input id="method" name="_method" type="hidden" value="POST">
									<input type="hidden" value="@if(isset($table_booking_settings->id)){{ $table_booking_settings->id }}@endif" name="id" id="id" />
									<input type="hidden" value="{{ $listing_id }}" name="listing_id" id="listing_id" />
									<input type="hidden" name="_token" value="{{csrf_token()}}">
									<input type="hidden" name="status" value="1">
								
						<div class="col-sm-6">
							<div class="form-group">
								<label for="email">Start Time:<span class="red">*</span></label>
								<?php if(isset($table_booking_settings->start_time))
													{
														$start_time_ar=substr($table_booking_settings->start_time, -2);
														$start_time=substr($table_booking_settings->start_time, 0, -2);
													}else{
														$start_time_ar='';
														$start_time='';
													}
													?>
								<select name="start_time" id="start_time"  class="form-control" >	
												@foreach ($times as $time_value)
												<option value="{{ $time_value }}" @if( $start_time == $time_value ) selected @endif>
													{{ $time_value }}
												</option>
												@endforeach
											</select>
													
								<select name="start_time_ar" id="start_time_ar" class="form-control">
												<option value="am" @if($start_time_ar == 'am' ) selected @endif>AM</option>
												<option value="pm" @if($start_time_ar == 'pm' ) selected @endif>PM</option>
											</select>										
								
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label for="email">End Time:<span class="red">*</span></label>
								<?php if(isset($table_booking_settings->end_time))
											{
											$end_time_ar=substr($table_booking_settings->end_time, -2);
											$end_time=substr($table_booking_settings->end_time, 0, -2);
											}else{
											$end_time_ar='';
											$end_time='';
											}
											?>		
								<select name="end_time" id="end_time"  class="form-control">	
												@foreach ($times as $time_value)
												<option value="{{ $time_value }}" @if( $end_time == $time_value ) selected @endif>
													{{ $time_value }}
												</option>
												@endforeach
											</select>	
																					
									<select name="end_time_ar" id="end_time_ar"  class="form-control">
												<option value="am" @if($end_time_ar == 'am' ) selected @endif >AM</option>
												<option value="pm" @if($end_time_ar == 'pm' ) selected @endif >PM</option>
											</select>		
								
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label for="email" class="tb_time">Per hour charge<span class="red">*</span></label>
								<?php
								if(isset($table_booking_settings->per_hour_charge))
									$per_hour_charge = old('per_hour_charge', $table_booking_settings->per_hour_charge);
								else
									$per_hour_charge = old('per_hour_charge');
								?>
								<input class="form-control tb_perslot_text" type="text" name="per_hour_charge" id="per_hour_charge" value="{{ $per_hour_charge }}"/> <i class="fa fa-info-circle vertical_align" aria-hidden="true"></i>
								
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label for="email" class="tb_perslot">No of people per time slot:<span class="red">*</span></label>
								<div class="input-group tb_time_text">
									<span class="input-group-btn">
		<button type="button" class="btn btn-number" data-type="minus" data-field="people_limit">
		<span class="glyphicon glyphicon-plus"></span>
									</button>
									</span>
									<?php
									if(isset($table_booking_settings->people_limit))
										$people_limit = old('people_limit', $table_booking_settings->people_limit);
									else
										$people_limit = old('people_limit');
									?>
									<input class="form-control input-number" min="1" max="100" type="text" name="people_limit" id="people_limit" value="{{$people_limit}}"/>
								
									<span class="input-group-btn">
		<button type="button" class="btn btn-number" data-type="plus" data-field="people_limit">
		  <span class="glyphicon glyphicon-minus"></span>
									</button>
									</span>
								</div>
								<i class="fa fa-info-circle vertical_align" aria-hidden="true"></i>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label for="email" class="tb_time">Extra Hour charge:<span class="red">*</span></label>
								<?php
								if(isset($table_booking_settings->extra_hour_charge))
									$extra_hour_charge = old('extra_hour_charge', $table_booking_settings->extra_hour_charge);
								else
									$extra_hour_charge = old('extra_hour_charge');
								?>
								<input class="form-control tb_perslot_text" type="text" name="extra_hour_charge" id="extra_hour_charge" value="{{ $extra_hour_charge }}"/> <i class="fa fa-info-circle vertical_align" aria-hidden="true"></i>
								
							</div>
						</div>
						<div class="clearfix"></div>
								 <div class="row">
      	 <div class="col-md-12">
						 <div class="form-group" style="display:block;">
            <label for="holidays" class="col-md-12 col-sm-12 col-xs-12">Holidays<span class="red">*</span></label>
		
         
			
			
		<?php 
			$holidays = (array) old('holidays', $table_booking_settings->holidays);
			if ($holidays) {
				$res=json_decode($table_booking_settings->holidays, true);
				  $holidays=array_map('trim',$res);
			}
					//print_r($trimmed_array);
			?>
		    <div class="col-md-2 col-sm-3 btn-width">
           	 <div class="divbox">
  				<a @if(in_array('Sunday', $holidays))class="active"@endif>
					<span class="notify-badge" style="display: none;"><i class="fa fa-check" aria-hidden="true"></i></span>
      		            <input type="checkbox" name="holidays[]" id="holidays" class ="holidays check" style=" display: none;" value = "Sunday" @if(in_array('Sunday', $holidays)) checked @endif > 
      					   <div class="wrapperbox" >
      						 <div class="pay-content">
      							 <span>Sunday</span> 
      						 </div> 
      					</div>
					</a>
				</div>
           </div>
           <div class="col-md-2  col-sm-3  btn-width">
           	<div class="divbox">
  				<a @if(in_array('Monday', $holidays))class="active"@endif>
					 <span class="notify-badge " style="display: none;"><i class="fa fa-check" aria-hidden="true"></i></span>
      		              <input type="checkbox" name="holidays[]"  class="holidays check" value="Monday" style=" display: none;" @if(in_array('Monday', $holidays)) checked @endif >
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
  				<a @if(in_array('Tuesday', $holidays))class="active"@endif>
					 <span class="notify-badge " style="display: none;"><i class="fa fa-check" aria-hidden="true"></i></span>
      		             <input type="checkbox" name="holidays[]" class="holidays check" value="Tuesday" style="display: none;"  @if(in_array('Tuesday', $holidays)) checked @endif >
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
  				<a @if(in_array('Wednesday', $holidays))class="active"@endif>
					 <span class="notify-badge " style="display: none;"><i class="fa fa-check" aria-hidden="true"></i></span>
      		              <input type="checkbox" name="holidays[]" class="holidays check" value="Wednesday"style="display: none;" @if(in_array('Wednesday', $holidays)) checked @endif >
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
  				<a @if(in_array('Thursday', $holidays))class="active"@endif>
				<span class="notify-badge " style="display: none;"><i class="fa fa-check" aria-hidden="true"></i></span>
      		        <input type="checkbox" name="holidays[]" class="holidays check" value="Thursday"style="display: none;"  @if(in_array('Thursday', $holidays)) checked @endif>
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
  			  <a  @if(in_array('Friday', $holidays))class="active"@endif>
				<span class="notify-badge " style="display: none;"><i class="fa fa-check" aria-hidden="true"></i></span>
      		            <input type="checkbox" name="holidays[]" class="holidays check" value="Friday" style="display: none;" @if(in_array('Friday', $holidays)) checked @endif>
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
  				<a  @if(in_array('Saturday', $holidays))class="active"@endif>
				<span class="notify-badge " style="display: none;"><i class="fa fa-check" aria-hidden="true"></i></span>
      		           <input type="checkbox" name="holidays[]" class="holidays check" value="Saturday" style="display: none;"  @if(in_array('Saturday', $holidays)) checked @endif>
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
						<!-- holiday block start -->
						<?php /*<div class="holiday_block paymentsel col-sm-12">
							<label for="email">Holiday<span class="red">*</span></label>
							<ul>
								<li>
									<div class="divbox">
										<a href="#" class="ui-link active">
											<span class="notify-badge " style="display: none;"><i class="fa fa-check" aria-hidden="true"></i></span>
											<div class="wrapperbox holiday_box">
												<div class="pay-content holiday_box_content">
													<span>Sunday</span>
												</div>
											</div>
										</a>
									</div>
								</li>
								<li>
									<div class="divbox">
										<a href="#" class="ui-link">
											<span class="notify-badge " style="display: none;"><i class="fa fa-check" aria-hidden="true"></i></span>
											<div class="wrapperbox holiday_box">
												<div class="pay-content holiday_box_content">
													<span>Moday</span>
												</div>
											</div>
										</a>
									</div>
								</li>
								<li>
									<div class="divbox">
										<a href="#" class="ui-link">
											<span class="notify-badge " style="display: none;"><i class="fa fa-check" aria-hidden="true"></i></span>
											<div class="wrapperbox holiday_box">
												<div class="pay-content holiday_box_content">
													<span>Tuesday</span>
												</div>
											</div>
										</a>
									</div>
								</li>
								<li>
									<div class="divbox">
										<a href="#" class="ui-link">
											<span class="notify-badge " style="display: none;"><i class="fa fa-check" aria-hidden="true"></i></span>
											<div class="wrapperbox holiday_box">
												<div class="pay-content holiday_box_content">
													<span>wednesday</span>
												</div>
											</div>
										</a>
									</div>
								</li>
								<li>
									<div class="divbox">
										<a href="#" class="ui-link">
											<span class="notify-badge " style="display: none;"><i class="fa fa-check" aria-hidden="true"></i></span>
											<div class="wrapperbox holiday_box">
												<div class="pay-content holiday_box_content">
													<span>Thursday</span>
												</div>
											</div>
										</a>
									</div>
								</li>
								<li>
									<div class="divbox">
										<a href="#" class="ui-link">
											<span class="notify-badge " style="display: none;"><i class="fa fa-check" aria-hidden="true"></i></span>
											<div class="wrapperbox holiday_box">
												<div class="pay-content holiday_box_content">
													<span>Friday</span>
												</div>
											</div>
										</a>
									</div>
								</li>
								<li>
									<div class="divbox">
										<a href="#" class="ui-link">
											<span class="notify-badge " style="display: none;"><i class="fa fa-check" aria-hidden="true"></i></span>
											<div class="wrapperbox holiday_box">
												<div class="pay-content holiday_box_content">
													<span>Saturday</span>
												</div>
											</div>
										</a>
									</div>
								</li>
							</ul>
						</div>
						*/?>
						<!-- holiday block end -->
						<div class="clearfix"></div>
						<div class="text-center appmt_btn">
							<button class="appmt_btn_save">Save</button>
						</div>
					</form>
				</div>
			</div>
			<!-- date block end -->
			@endif
		</div>            
	</div>
</div>

@stop

@section('footer')
<script>


// EDit Blog
$(document).on("click", ".edit_blog", edit_blogs);
function edit_blogs(){ 
	var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
	var id= $(this).attr('id'); 

	var host = "{{ url('/merchant/table-booking') }}" + '/' + id + '/edit';
	var update_url = "{{url('merchant/table-booking/update')}}";
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
	$('#people_limit').val(settings.people_limit);
		$('#status').val(settings.status);
}

$(document).on("click", ".delete_blog", deleteRow);
function deleteRow(){ 
	if (confirm("Are you sure want to delete the setting?")) {
		
		var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
		var id= $(this).attr('id'); 
		var host = "{{ url('/merchant/table-booking') }}" + '/' + id;
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
	});

</script>

@stop