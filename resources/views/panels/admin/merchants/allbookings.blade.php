@extends('layouts.adminmain')

@section('head')

@stop

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}" />


<div class="">
	<div class="page-title">
		<div class="title_left">
			<h3>Bookings</h3>
		</div>

	</div>
	<div class="clearfix"></div>           

	<div class="row">


		<!-- <div class="col-md-12 col-xs-12">
		<div class="x_panel">-->

			<!-- LEFT BAR Start-->
			<div class="col-md-12 col-xs-12">
				<div class="x_panel">

					@if(Session::get('message')) <div class="alert alert-success" role="alert">{{Session::get('message')}} </div>@endif 

					<form name="actionForm" action="{{url('admin/services/destroy_all')}}" method="post"> 
						<h2> &nbsp;
							<span class="pull-right">

							</span>
						</h2>
						<div class="x_title"></div>

						<table id="datatable-checkbox" class="table table-striped table-bordered bulk_action">
							<thead>
								<tr>
									<th>User Name</th>
									<th>Listing Name</th>					 
									<th>Name</th>
									<th>Email</th>
									<th>Booked Date</th>
									<th>Booked Time</th>
									<th>Total Peoples</th> 
									<th>Mobile No</th>    
									<th>Additional Request</th>   
									<th>Status</th>
									<th>Action</th>                
								</tr>
							</thead>
							<tbody>
								@foreach ($allBookings as $booking)
								<tr>
										<td>{{@$booking->user->first_name}}</td>
										<td>{{@$booking->listing->title}}
										<td>{{$booking->name}}</td>
										<td>{{$booking->email}}</td>
										<td>{{Carbon\Carbon::parse($booking->booked_date)->format('M d, Y')}}</td>
										<td>{{$booking->booked_time}}</td>
										<td>{{$booking->total_peoples}}</td>
										<td>{{$booking->phone_number}}</td>
										<td>{{$booking->additional_request}}</td>
										<td>
											@if($booking->status==0)
											<span class="alert-danger">Disabled</span>
											@else
											<span class="alert-success">Enabled</span>
											@endif
										</td>
										<td>
											@if($booking->status==0)
											<a href="#" class="enable btn btn-primary btn-xs" id="{{ $booking->id }}" >Enable</a>
											@else
											<a href="#" class="disable btn btn-primary btn-xs" id="{{ $booking->id }}" >Disable</a>
											@endif
										</td>
									</tr>
									@endforeach

								</tbody>
							</table>
						</form>	  
					</div>
				</div>



				<div class="clearfix"></div>  

			</div>
		</div>

		<script>
			$(document).ready(function(){

				//$('#all-bookings').DataTable();
//Change Status Enable

$(document).on("click", ".enable", enable);
function enable(){ 
	var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
	var id= $(this).attr('id'); 
	var host="{{ url('admin/merchants/bookings/enable') }}";
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
	var host="{{ url('admin/merchants/bookings/disable') }}";
	$.ajax({
		type: 'POST',
		data:{'id': id, '_token':CSRF_TOKEN},
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
});
</script>

@stop