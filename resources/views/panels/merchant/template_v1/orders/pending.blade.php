@extends('layouts.'.$merchant_layout)

@section('head')
<link href="{{ asset($merchant_layout . '/css/order.css') }}" rel="stylesheet">
@stop

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}" />

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

<?php
$category_type = $subscribed_category->category_type;
$enable_table_booking = $merchant_services['table'];
$enable_appointment = $merchant_services['appointment'];
?>

<div>
    <div class="row">
        <div class="col-md-12">
            
			@include('panels.merchant.'.$merchant_layout.'.orders.head_tab')
			
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="orderList">
                                @if( isset($table_booking_order) and $enable_table_booking )
									<h3>Table</h3>
									@foreach($table_booking_order as $booking_order)
									@if( isset($booking_order->table_booking) )
										<?php
											$table_booking = $booking_order->table_booking;
										?>
										<div class="row">
											<div class="col-md-12">
												<div class="order_list_tv table-responsive">
													<span>Order No. {{ $booking_order->invoice_id }}</span>
													<table class="table">
														<thead>
															<tr>
																<th>Customer Name</th>
																<th>E-Mail</th>
																<th>Mobile</th>
																<th>No. of People</th>
																<th>Booked Date</th>
																<th>Booked Time</th>
																<th>Additional Request</th>
															</tr>
														</thead>
														<tbody>
															<tr>
																<td>{{ $table_booking->name }}</td>
																<td>{{ $table_booking->email }}</td>
																<td>{{ $table_booking->phone_number }}</td>
																<td>{{ $table_booking->total_peoples }}</td>
																<td>{{ $table_booking->booked_date }}</td>
																<td>{{ $table_booking->booked_time }}</td>
																<td>{{ $table_booking->additional_request }}</td>
															</tr>
														</tbody>
													</table>
												</div>
												<div class="col-md-12">
													<div class="col-md-6">

													</div>
													<div class="col-md-6 col-xs-12 product-ctrls">
														<div class="col-md-3 col-xs-6 pull-right decline">
															<a href="javascript:;" class="action_table_booking" data-type="decline" data-id="{{ $table_booking->id }}">Decline</a>
														</div>
														<div class="col-md-3 col-xs-6 pull-right accept">
															<a href="javascript:;" class="action_table_booking" data-type="accept" data-id="{{ $table_booking->id }}">Accept</a>
														</div>
													</div>
												</div>
											</div>
										</div>
									@endif
									@endforeach	
									@if(!isset($table_booking))
										<div class="row">
											<div class="col-md-12">
												<div class="alert alert-warning">No records!</div>
											</div>
										</div>
									@endif							
								@endif
                            </div>
                        </div>
						
						<div class="col-md-12">
                            <div class="orderList">
                                @if( isset($appointment_booking_order) and $enable_appointment )
									<h3>Appointment</h3>
									@foreach($appointment_booking_order as $booking_order)
									@if( isset($booking_order->appointment_booking) )
										<?php
											$appointment_booking = $booking_order->appointment_booking;
										?>
										<div class="row">
											<div class="col-md-12">
												<div class="order_list_tv table-responsive">
													<span>Order No. {{ $booking_order->invoice_id }}</span>
													<table class="table">
														<thead>
															<tr>
																<th>Customer Name</th>
																<th>E-Mail</th>
																<th>Mobile</th>
																<th>Booked Date</th>
																<th>Booked Time</th>
																<th>Additional Request</th>
															</tr>
														</thead>
														<tbody>
															<tr>
																<td>{{ $appointment_booking->name }}</td>
																<td>{{ $appointment_booking->email }}</td>
																<td>{{ $appointment_booking->phone_number }}</td>
																<td>{{ $appointment_booking->booked_date }}</td>
																<td>{{ $appointment_booking->booked_time }}</td>
																<td>{{ $appointment_booking->additional_request }}</td>
															</tr>
														</tbody>
													</table>
												</div>
												<div class="col-md-12">
													<div class="col-md-6">

													</div>
													<div class="col-md-6 col-xs-12 product-ctrls">
														<div class="col-md-3 col-xs-6 pull-right decline">
															<a href="javascript:;" class="action_appointment_booking" data-type="decline" data-id="{{ $appointment_booking->id }}">Decline</a>
														</div>
														<div class="col-md-3 col-xs-6 pull-right accept">
															<a href="javascript:;" class="action_appointment_booking" data-type="accept" data-id="{{ $appointment_booking->id }}">Accept</a>
														</div>
													</div>
												</div>
											</div>
										</div>
									@endif
									@endforeach		
									@if(!isset($appointment_booking))
										<div class="row">
											<div class="col-md-12">
												<div class="alert alert-warning">No records!</div>
											</div>
										</div>
									@endif
								@endif
                            </div>
                        </div>
                    </div>
                </div>
                
                
            </div>

        </div>

    </div>
</div>

@stop

@section('footer')
<script>
$(document).ready(function() {
	
});
$(document).on("click", ".action_table_booking", function() {
	var id = $(this).data('id');
	var type = $(this).data('type');
	if(type == '' || id == '') {
		alert('Invalid request');
		return false;
	}
	
	var host = "{{ url('merchant/table-booking/enable-order') }}";
	if(type == 'decline') {
		host = "{{ url('merchant/table-booking/disable-order') }}";
		if (confirm("Are you sure decline the order?")) {			
			status_update(id, host);
		}
	} else if (confirm("Are you sure accept the order?")) {
		status_update(id, host);
	}
	return false;
});

function status_update(id, host)
{
	var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
	
	$.ajax({
		type: 'POST',
		data:{id:id, '_token':CSRF_TOKEN},
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
				window.location = "{{ url('merchant/orders/pending') }}";
				return false;
			}
			else
			{
				alert(res.msg);
			}

		}

	});
}


$(document).on("click", ".action_appointment_booking", function() {
	var id = $(this).data('id');
	var type = $(this).data('type');
	if(type == '' || id == '') {
		alert('Invalid request');
		return false;
	}
	
	var host = "{{ url('merchant/appointment-booking/enable-order') }}";
	if(type == 'decline') {
		host = "{{ url('merchant/appointment-booking/disable-order') }}";
		if (confirm("Are you sure decline the order?")) {			
			status_update(id, host);
		}
	} else if (confirm("Are you sure accept the order?")) {
		status_update(id, host);
	}
	return false;
});
</script>
@stop