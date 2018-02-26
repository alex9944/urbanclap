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
$enable_food = $merchant_services['food'];
$enable_shop = $merchant_services['shop'];
// default active
$active = 'active';
$active_tab = '';
?>

<div>
    <div class="row">
        <div class="col-md-12">
            
			@include('panels.merchant.'.$merchant_layout.'.orders.head_tab')
			
            <div class="tab-content">
                                
                <div role="tabpanel" class="active tab-pane mt10">
                    <div class="row">
                        <div class="col-xs-12 col-md-12">
                            <div class="row">
                                <div class="col-md-3"></div>
                                <div class="col-md-6 col-sm-9 col-xs-11 orderHistoryList">
                                    @if($enable_food)
                                    <div class="col-md-3 col-xs-3">
										<a class="btn btn-primary {{$active}}" data-toggle="tab" href="#food">FOOD</a>
                                    </div>
									<?php  
									$active = '';
									if(!$active_tab)
										$active_tab = 'food';
									?>
									@endif
									@if($enable_shop)
                                    <div class="col-md-3 col-xs-3">                                        
                                        <a class="btn  btn-primary {{$active}}" data-toggle="tab" href="#shop">SHOPPING</a>										
                                    </div>
									<?php  
									$active = '';
									if(!$active_tab)
										$active_tab = 'shop';
									?>
									@endif
									@if($enable_table_booking)
                                    <div class="col-md-3 col-xs-3">                                        
                                        <a class="btn  btn-primary {{$active}}" data-toggle="tab" href="#table">TABLE</a>										
                                    </div>
									<?php  
									$active = '';
									if(!$active_tab)
										$active_tab = 'table';
									?>
									@endif
									@if($enable_appointment)
                                    <div class="col-md-3 col-xs-3">                                        
                                        <a class="btn  btn-primary {{$active}}" data-toggle="tab" href="#appointment">APPOINTMENT</a>										
                                    </div>
									<?php  
									$active = '';
									if(!$active_tab)
										$active_tab = 'appointment';
									?>
									@endif
                                </div>
                                <div class="col-md-3">
                                    <div class="col-md-3 col-sm-2 col-xs-12" style="
    font-size: 25px;
    padding: 6px;
    color: #a2a0a0;
    text-align: center;
"><i class="fa fa-filter" aria-hidden="true">Filter</i></div>
                                </div>
                            </div>

                            <div class="col-md-12 tab-content">
                                <div class="tab-pane @if($active_tab == 'food') active @endif" id="food">
                                    @if($enable_food)
										@foreach($food_orders as $order)
										@foreach($order->merchant_orders as $merchant_order)
										<div class="col-md-12 pad10">
											<div class="col-md-6 col-sm-6">
												<div class=" ">
													<div class="form-group ">
														<label for="name" class="col-lg-4 col-xs-4">Order ID:</label>
														<div class="col-lg-8">
															<span>{{$order->main_order->invoice_id}}</span>
														</div>
													</div>
												</div>
												<div class=" ">
													<div class="form-group ">
														<label for="name" class="col-lg-4 col-xs-4">Order Date:</label>
														<div class="col-lg-8">
															<span><?php echo date('F d,Y', strtotime($order->created_at));?></span>
														</div>
													</div>
												</div>
												<div class=" ">
													<div class="form-group ">
														<label for="name" class="col-lg-4 col-xs-4">Order Status:</label>
														<div class="col-lg-8">
															<span>{{$merchant_order_status[$merchant_order->order_status]['name']}}</span>
														</div>
													</div>
												</div>
												<div class=" ">
													<div class="form-group ">
														<label for="name" class="col-lg-4 col-xs-4">Customer Detail :</label>
														<div class="col-lg-8">
															<span>{{$order->name}}, {{$order->email}}, {{$order->phone_number}}</span>
														</div>
													</div>
												</div>
											</div>
											<div class="col-md-6 col-sm-6">
												<div class="col-md-offset-4 col-md-4 mt10">
													<button class="btn btn-primary btn-sm food_view" data-id="{{ $merchant_order->id }}">View detail</button>
												</div>
											</div>
										</div>
										<hr style="clear: both;">
										@endforeach
										@endforeach
										@if(!isset($merchant_order))
											<div class="row">
												<div class="col-md-12">
													<div class="alert alert-warning">No records!</div>
												</div>
											</div>
										@endif
									@endif
                                </div>
                                
                                <div class="tab-pane @if($active_tab == 'shop') active @endif" id="shop">
									@if($enable_shop)
										@foreach($product_orders as $product_order)
										@foreach($product_order->merchant_orders as $shop_order)
										<div class="col-md-12 pad10">
											<div class="col-md-6 col-sm-6">
												<div class=" ">
													<div class="form-group ">
														<label for="name" class="col-lg-4 col-xs-4">Order ID:</label>
														<div class="col-lg-8">
															<span>{{$product_order->main_order->invoice_id}}</span>
														</div>
													</div>
												</div>
												<div class=" ">
													<div class="form-group ">
														<label for="name" class="col-lg-4 col-xs-4">Order Date:</label>
														<div class="col-lg-8">
															<span><?php echo date('F d,Y', strtotime($product_order->created_at));?></span>
														</div>
													</div>
												</div>
												<div class=" ">
													<div class="form-group ">
														<label for="name" class="col-lg-4 col-xs-4">Order Status:</label>
														<div class="col-lg-8">
															<span>{{$merchant_order_status[$shop_order->order_status]['name']}}</span>
														</div>
													</div>
												</div>
												<div class=" ">
													<div class="form-group ">
														<label for="name" class="col-lg-4 col-xs-4">Customer Detail :</label>
														<div class="col-lg-8">
															<span>{{$product_order->name}}, {{$product_order->email}}, {{$product_order->phone_number}}</span>
														</div>
													</div>
												</div>
											</div>
											<div class="col-md-6 col-sm-6">
												<div class="col-md-offset-4 col-md-4 mt10">
													<button class="btn btn-primary btn-sm shop_view" data-id="{{ $shop_order->id }}">View detail</button>
												</div>
											</div>
										</div>
										<hr style="clear: both;">
										@endforeach
										@endforeach
										@if(!isset($shop_order))
											<div class="row">
												<div class="col-md-12">
													<div class="alert alert-warning">No records!</div>
												</div>
											</div>
										@endif
									@endif
								</div>
                                
                                <div class="tab-pane @if($active_tab == 'table') active @endif" id="table">
									@if( $enable_table_booking )
										@foreach($booking_orders as $booking_order)
										@if( isset($booking_order->table_booking) )
											<?php
												$table_booking = $booking_order->table_booking;
											?>
											<div class="col-md-12 pad10">
												<div class="col-md-6 col-sm-6">
												<div class=" ">
													<div class="form-group ">
														<label for="name" class="col-lg-4 col-xs-4">Order ID:</label>
														<div class="col-lg-8">
															<span>{{$booking_order->invoice_id}}</span>
														</div>
													</div>
												</div>
													<div class=" ">
														<div class="form-group ">
															<label for="name" class="col-lg-4 col-xs-4">Order Date:</label>
															<div class="col-lg-8">
																<span><?php echo date('F d,Y', strtotime($booking_order->created_at));?></span>
															</div>
														</div>
													</div>
													<div class=" ">
														<div class="form-group ">
															<label for="name" class="col-lg-4 col-xs-4">Order Status:</label>
															<div class="col-lg-8">
																<span>{{$booking_order_status[$table_booking->status]['name']}}</span>
															</div>
														</div>
													</div>
													<div class=" ">
														<div class="form-group ">
															<label for="name" class="col-lg-4 col-xs-4">Customer Detail :</label>
															<div class="col-lg-8">
																<span>{{$table_booking->name}}, {{$table_booking->email}}, {{$table_booking->phone_number}}</span>
															</div>
														</div>
													</div>
												</div>
												<div class="col-md-6 col-sm-6">
													<div class="col-md-offset-4 col-md-4 mt10">
														<button class="btn btn-primary btn-sm table_view" data-id="{{$table_booking->id}}">View detail</button>
													</div>
												</div>
											</div>
											<hr style="clear: both;">
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
                                
                                <div class="tab-pane @if($active_tab == 'appointment') active @endif" id="appointment">
									@if( $enable_appointment )
										@foreach($booking_orders as $booking_order)
										@if( isset($booking_order->appointment_booking) )
											<?php
												$appointment_booking = $booking_order->appointment_booking;
											?>
											<div class="col-md-12 pad10">
												<div class="col-md-6 col-sm-6">
												<div class=" ">
													<div class="form-group ">
														<label for="name" class="col-lg-4 col-xs-4">Order ID:</label>
														<div class="col-lg-8">
															<span>{{$booking_order->invoice_id}}</span>
														</div>
													</div>
												</div>
													<div class=" ">
														<div class="form-group ">
															<label for="name" class="col-lg-4 col-xs-4">Order Date:</label>
															<div class="col-lg-8">
																<span><?php echo date('F d,Y', strtotime($booking_order->created_at));?></span>
															</div>
														</div>
													</div>
													<div class=" ">
														<div class="form-group ">
															<label for="name" class="col-lg-4 col-xs-4">Order Status:</label>
															<div class="col-lg-8">
																<span>{{$booking_order_status[$appointment_booking->status]['name']}}</span>
															</div>
														</div>
													</div>
													<div class=" ">
														<div class="form-group ">
															<label for="name" class="col-lg-4 col-xs-4">Customer Detail :</label>
															<div class="col-lg-8">
																<span>{{$appointment_booking->name}}, {{$appointment_booking->email}}, {{$appointment_booking->phone_number}}</span>
															</div>
														</div>
													</div>
												</div>
												<div class="col-md-6 col-sm-6">
													<div class="col-md-offset-4 col-md-4 mt10">
														<button class="btn btn-primary btn-sm appointment_view" data-id="{{$appointment_booking->id}}">View detail</button>
													</div>
												</div>
											</div>
											<hr style="clear: both;">
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
</div>

@stop

@section('footer')

@include('panels.merchant.'.$merchant_layout.'.orders.order_history.food_order_detail')

@include('panels.merchant.'.$merchant_layout.'.orders.order_history.shop_order_detail')

@include('panels.merchant.'.$merchant_layout.'.orders.order_history.table_booking_detail')

@include('panels.merchant.'.$merchant_layout.'.orders.order_history.appointment_booking_detail')

<script>
$(document).ready(function() {
	
});

$(document).on("click", ".food_view", function() {
	var id= $(this).data('id');
	
	var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
	var host = "{{ url('merchant/orders/food_order_detail') }}";
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
				renderFoodOrderDetail(res.data);
			}
			else
			{
				alert(res.msg);
			}

		}

	});
});
function renderFoodOrderDetail(result)
{
	var merchant_order = result.merchant_order;
	var main_order = merchant_order.main_order;
	var order = main_order.main_order;
	var order_details = result.merchant_order.order_details;
	var order_detail_status = result.order_detail_status;
	var order_status = '';
	var select_status = '';
	var action_txt = '';
	var selected = '';
	var item_name = '';
	var shipping_price = '';
	var html = "";
	
	if(merchant_order != 'undefined')
	{
		html += "<table class='table' cellspacing='0' width='100%'><thead>"
				+ "<tr>"
				+ "<th>Order ID</th><td>"+order.invoice_id+"</td>"
				+ "</tr>"
				+ "<tr>"
				+ "<th>Customer Name</th><td>"+main_order.name+"</td>"
				+ "<tr>"
				+ "<th>E-Mail</th><td>"+main_order.email+"</td>"
				+ "</tr>"
				+ "<tr>"
				+ "<th>Mobile</th><td>"+main_order.phone_number+"</td>"
				+ "</tr>"
				+ "<tr>"
				+ "<th>Delivery Fee</th><td>"+merchant_order.total_shipping_amount+"</td>"
				+ "</tr>"
				+ "<tr>"
				+ "<th>Total Amount</th><td>"+merchant_order.total_amount+"</td>"
				+ "</tr>"
				+ "</thead><tbody>";
						
		html += "</tbody></table>";
	}
	if(order_details != 'undefined')
	{
		html += "<table class='table' cellspacing='0' width='100%'><thead>"
						+ "<tr>"
						+ "<th>Item Name</th>"
						+ "<th>Status</th>"
						+ "<th>Quantity</th>"
						+ "<th>Unit Price</th>"
						+ "<th>Total Amount</th>"
						+ "</tr></thead><tbody>";
		$.each(order_details, function(index, data) {
			select_status = order_detail_status[data.status]['name'];
			
			item_name = '';
			shipping_price = '';
			if(data.menu_item != null) {
				item_name = data.menu_item.name;
			}
			
			html += "<tr>"
							+ "<td>"+item_name+"</td>"
							+ "<td>"+select_status+"</td>"
							+ "<td>"+data.quantity+"</td>"
							+ "<td>"+data.unit_price+"</td>"
							+ "<td>"+data.total_amount+"</td>"
							+ "</tr>";
		});
		html += "</tbody></table>";
		$('#view_food_order').html(html);
		$('#foodModal').modal('show');
	}
}

$(document).on("click", ".shop_view", function() {
	var id= $(this).data('id');
	
	var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
	var host = "{{ url('merchant/orders/shop_order_detail') }}";
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
				renderShopOrderDetail(res.data);
			}
			else
			{
				alert(res.msg);
			}

		}

	});
});
function renderShopOrderDetail(result)
{
	var merchant_order = result.merchant_order;
	var main_order = merchant_order.main_order;
	var order = main_order.main_order;
	var order_details = result.merchant_order.order_details;
	var order_detail_status = result.order_detail_status;
	var order_status = '';
	var select_status = '';
	var action_txt = '';
	var selected = '';
	var item_name = '';
	var shipping_price = '';
	var html = "";
	
	if(merchant_order != 'undefined')
	{
		html += "<table class='table' cellspacing='0' width='100%'><thead>"
				+ "<tr>"
				+ "<th>Order ID</th><td>"+order.invoice_id+"</td>"
				+ "</tr>"
				+ "<tr>"
				+ "<th>Customer Name</th><td>"+main_order.name+"</td>"
				+ "<tr>"
				+ "<th>E-Mail</th><td>"+main_order.email+"</td>"
				+ "</tr>"
				+ "<tr>"
				+ "<th>Mobile</th><td>"+main_order.phone_number+"</td>"
				+ "</tr>"
				+ "<tr>"
				+ "<th>Shipping</th><td>"+merchant_order.total_shipping_amount+"</td>"
				+ "</tr>"
				+ "<tr>"
				+ "<th>Total Amount</th><td>"+merchant_order.total_amount+"</td>"
				+ "</tr>"
				+ "</thead><tbody>";
						
		html += "</tbody></table>";
	}
	if(order_details != 'undefined')
	{
		html += "<table class='table' cellspacing='0' width='100%'><thead>"
						+ "<tr>"
						+ "<th>Item Name</th>"
						+ "<th>Status</th>"
						+ "<th>Quantity</th>"
						+ "<th>Unit Price</th>"
						+ "<th>Shipping Price</th>"
						+ "<th>Total Amount</th>"
						+ "</tr></thead><tbody>";
		$.each(order_details, function(index, data) {
			select_status = order_detail_status[data.status]['name'];
			
			item_name = '';
			shipping_price = '';
			if(data.shop_item != null) {
				item_name = data.shop_item.pro_name;
				shipping_price = data.shop_item.shipping_price;
			}
			
			html += "<tr>"
							+ "<td>"+item_name+"</td>"
							+ "<td>"+select_status+"</td>"
							+ "<td>"+data.quantity+"</td>"
							+ "<td>"+data.unit_price+"</td>"
							+ "<td>"+shipping_price+"</td>"
							+ "<td>"+data.total_amount+"</td>"
							+ "</tr>";
		});
		html += "</tbody></table>";
		$('#view_shop_order').html(html);
		$('#shopModal').modal('show');
	}
}

$(document).on("click", ".table_view", function() {
	var id= $(this).data('id');
	
	var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
	var host = "{{ url('merchant/orders/table_order_detail') }}";
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
				renderTableOrderDetail(res.data);
			}
			else
			{
				alert(res.msg);
			}

		}

	});
});
function renderTableOrderDetail(result)
{
	var order = result.order;
	var main_order = result.order.main_order;
	var order_status = result.order_status;
	
	if(order != 'undefined')
	{
		status = order_status[order.status]['name'];
		
		var html = "<table class='table' cellspacing='0' width='100%'><thead>"
						+ "<tr>"
						+ "<th>Order ID</th><td>"+main_order.invoice_id+"</td>"
						+ "</tr>"
						+ "<tr>"
						+ "<th>Order Status</th><td>"+status+"</td>"
						+ "</tr>"
						+ "<tr>"
						+ "<th>Customer Name</th><td>"+order.name+"</td>"
						+ "<tr>"
						+ "<th>E-Mail</th><td>"+order.email+"</td>"
						+ "</tr>"
						+ "<tr>"
						+ "<th>Mobile</th><td>"+order.phone_number+"</td>"
						+ "</tr>"
						+ "<tr>"
						+ "<th>No. of People</th><td>"+order.total_peoples+"</td>"
						+ "</tr>"
						+ "<tr>"
						+ "<th>Booked Date</th><td>"+order.booked_date+"</td>"
						+ "</tr>"
						+ "<tr>"
						+ "<th>Booked Time</th><td>"+order.booked_time+"</td>"
						+ "</tr>"
						+ "<tr>"
						+ "<th>Additional Request</th><td>"+order.additional_request+"</td>"
						+ "</tr>"
						+ "</thead><tbody>";
						
		html += "</tbody></table>";
		$('#view_table_order').html(html);
		$('#tableModal').modal('show');
	}
}

$(document).on("click", ".appointment_view", function() {
	var id= $(this).data('id');
	
	var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
	var host = "{{ url('merchant/orders/appointment_order_detail') }}";
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
				renderAPPtmtOrderDetail(res.data);
			}
			else
			{
				alert(res.msg);
			}

		}

	});
});
function renderAPPtmtOrderDetail(result)
{
	var order = result.order;
	var main_order = result.order.main_order;
	var order_status = result.order_status;
	
	if(order != 'undefined')
	{
		status = order_status[order.status]['name'];
		
		var html = "<table class='table' cellspacing='0' width='100%'><thead>"
						+ "<tr>"
						+ "<th>Order ID</th><td>"+main_order.invoice_id+"</td>"
						+ "</tr>"
						+ "<tr>"
						+ "<th>Order Status</th><td>"+status+"</td>"
						+ "</tr>"
						+ "<tr>"
						+ "<th>Customer Name</th><td>"+order.name+"</td>"
						+ "<tr>"
						+ "<th>E-Mail</th><td>"+order.email+"</td>"
						+ "</tr>"
						+ "<tr>"
						+ "<th>Mobile</th><td>"+order.phone_number+"</td>"
						+ "</tr>"
						+ "<tr>"
						+ "<th>Booked Date</th><td>"+order.booked_date+"</td>"
						+ "</tr>"
						+ "<tr>"
						+ "<th>Booked Time</th><td>"+order.booked_time+"</td>"
						+ "</tr>"
						+ "<tr>"
						+ "<th>Additional Request</th><td>"+order.additional_request+"</td>"
						+ "</tr>"
						+ "</thead><tbody>";
						
		html += "</tbody></table>";
		$('#view_appointment_order').html(html);
		$('#appointmentModal').modal('show');
	}
}
</script>
@stop