@extends('layouts.adminmain')

@section('head')

@stop

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}" />


<div class="">
	<div class="page-title">
		<div class="title_left">
			<h3>Booking Customers</h3>
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
					@if(Session::get('error_message')) <div class="alert alert-danger" role="alert">{{Session::get('error_message')}} </div>@endif 

					<form name="actionForm" action="" method="post"> 
						<h2> &nbsp;
							<span class="pull-right">

							</span>
						</h2>
						<div class="x_title"></div>

						<table id="bookings" class="table table-striped table-bordered bulk_action">
							<thead>
								<tr>			
									<th>Order Date</th>		 
									<th>Customer Name</th>
									<th>Email</th>
									<th>Mobile No</th> 
									<th>Total Items</th>
									<th>Total Amount</th>    
									<th>Payment Gateway</th>   
									<th>Status</th>
									<th>Action</th>                
								</tr>
							</thead>
							<tbody>
								@foreach ($order_booking as $booking)
								<tr>
									<td>{{Carbon\Carbon::parse($booking->created_at)->format('M d, Y hA')}}</td>
									<td>{{$booking->name}}</td>
									<td>{{$booking->email}}</td>
									<td>{{$booking->phone_number}}</td>
									<td>{{$booking->total_items}}</td>
									<td>{{$booking->total_amount}}</td>
									<td>{{$booking->payment_type}}</td>
									<td>{{$booking->status}}</td>
									<td>
										<a href="{{ url('admin/online-order/booking-customers/'.$booking->id) }}" class="btn btn-primary btn-xs">Detail</a> 
										<?php /*<a href="#" class="btn btn-danger btn-xs" id="{{ $booking->id }}" >Delete</a>*/?>
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
	$('#bookings').DataTable({"aaSorting": []});
});
</script>

@stop