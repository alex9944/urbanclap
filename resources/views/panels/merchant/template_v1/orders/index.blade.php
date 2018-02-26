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

<div>
    <div class="row">
        <div class="col-md-12">
            
			@include('panels.merchant.'.$merchant_layout.'.orders.head_tab')
			
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="orderList">
                                @if($merchant_services['food'])
									<h3>Food</h3>
									@foreach($food_orders as $order)
									@foreach($order->merchant_orders as $merchant_order)
									<div class="row">
										<div class="col-md-12">
											<div class="order_list_tv table-responsive">
												<span>Order No. {{$order->main_order->invoice_id}}</span>
												<table class="table">
													<thead>
														<tr>
															<th>Customer Name</th>
															<th>E-Mail</th>
															<th>Mobile</th>
															<th>Total Items</th>
															<th>Total Amount</th>
															<th>Payment Status</th>
															<th>Order Time</th>
															<th>Item Detail</th>
														</tr>
													</thead>
													<tbody>
														<tr>
															<td>{{$order->name}}</td>
															<td>{{$order->email}}</td>
															<td>{{$order->phone_number}}</td>
															<td>{{$merchant_order->total_items}}</td>
															<td><?php echo $currency->sympol;?> {{$merchant_order->total_amount}}</td>
															<td>{{$order->payment_status}}</td>
															<td><?php date('F d,Y', strtotime($order->created_at));?></td>
															<td><a href="javascript:;" class="food_view" data-id="{{ $merchant_order->id }}">Click here to view</a></td>
														</tr>
													</tbody>
												</table>
											</div>
											<div class="col-md-12">
												<div class="col-md-6">

												</div>
												<div class="col-md-6 col-xs-12 product-ctrls">
													<?php /*<div class="col-md-3 col-xs-6 pull-right decline"><a href="#" class="">Cancel</a></div>*/?>
													<div class="col-md-3 col-xs-6 pull-right accept">
														<a href="{{url('merchant/orders/food_invoice/'.$merchant_order->id)}}" target="_blank">Print&amp;Continue</a>
													</div>
												</div>
											</div>
										</div>
									</div>
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
                        </div>
                        <div class="col-md-12">
                            <div class="orderList">
                                @if($merchant_services['table'])
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
                                @if($merchant_services['shop'])
									<h3>Shop</h3>
									@foreach($product_orders as $product_order)
									@foreach($product_order->merchant_orders as $shop_order)
										<?php
										//$merchant_orders = $order->merchant_orders;
										?>
									<div class="row">
										<div class="col-md-12">
											<div class="order_list_tv table-responsive">
												<span>Order No. {{ $product_order->main_order->invoice_id }}</span>
												<table class="table">
													<thead>
														<tr>
															<th>Customer Name</th>
															<th>E-Mail</th>
															<th>Mobile</th>
															<th>No. of Items</th>
															<th>Order Date</th>
															<th>Product Name</th>
															<th>Product Code</th>
															<th>Total Amount</th>
														</tr>
													</thead>
													<tbody>
														<tr>
															<td>{{ $product_order->name }}</td>
															<td>{{ $product_order->email }}</td>
															<td>{{ $product_order->phone_number }}</td>
															<td>{{ $shop_order->total_items }}</td>
															<td><?php echo date('F d,Y', strtotime($shop_order->created_at));?></td>
															<td colspan="2"><a href="javascript:;" class="view" data-id="{{ $shop_order->id }}">Click here to view</a></td>
															<td><?php echo $currency->symbol . ' ';?>{{ $shop_order->total_amount }}</td>
														</tr>
													</tbody>
												</table>
											</div>
											<div class="col-md-12">
												<div class="col-md-6">
												</div>
												<div class="col-md-6 col-xs-12 product-ctrls">
													<div class="col-md-3 col-xs-6 pull-right accept"><a href="{{url('merchant/orders/shop_invoice/'.$shop_order->id)}}" target="_blank">Print&amp;Continue</a></div>
												</div>
											</div>
										</div>
									</div>
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
                        </div>
                        <div class="col-md-12">
                            <div class="orderList">
                                @if( $merchant_services['appointment'] )
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
                    <!-- row -->
                </div>
                <!-- tab pane -->
                
            </div>

        </div>

    </div>
</div>

@stop

@section('footer')

@include('panels.merchant.'.$merchant_layout.'.orders.shop_order_detail')

@include('panels.merchant.'.$merchant_layout.'.orders.food_order_detail')

<script>
$(document).ready(function() {
	
});

$(document).on("click", ".update_status", function() {
	var id= $(this).data('id');
	
	var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
	var host = "{{ url('merchant/online-order/update-status') }}";
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

$(document).on("click", ".view", function() {
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
	var order_details = result.merchant_order.order_details;
	var order_detail_status = result.order_detail_status;
	var order_status = '';
	var select_status = '';
	var action_txt = '';
	var selected = '';
	var item_name = '';
	var shipping_price = '';
	if(order_details != 'undefined')
	{
		var html = "<form method='post' action='{{ url('merchant/online-order/update-status') }}/"+merchant_order.id+"' class='form-horizontal'>"
						+ "<table class='order_details table' cellspacing='0' width='100%'><thead>"
						+ "<tr>"
						+ "<th>Date</th>"
						+ "<th>Item Name</th>"
						+ "<th>Status</th>"
						+ "<th>Quantity</th>"
						+ "<th>Unit Price</th>"
						+ "<th>Shipping Price</th>"
						+ "<th>Total Amount</th>"
						+ "</tr></thead><tbody>";
		$.each(order_details, function(index, data) {
			if(data.status == 'cancelled' || data.status == 'completed')
			{
				select_status = order_detail_status[data.status]['name'];
			}
			else
			{
				select_status = '<select name="status['+data.id+']" id="status"  class="form-control">';				
				
				var lower = true;
				$.each(order_detail_status, function(index_in, data_in) {
					
					selected = '';
					if( data.status == data_in['id'] )
						selected = ' selected';
					else if(lower)
						return true;
					
					lower = false;
					
					select_status += '<option value="'+data_in['id']+'" ' + selected +'>';
					select_status += data_in['name'];
					select_status += '</option>';
				});
				select_status += '</select>';
			}
			
			item_name = '';
			shipping_price = '';
			if(data.shop_item != null) {
				item_name = data.shop_item.pro_name;
				shipping_price = data.shop_item.shipping_price;
			}
			
			html += "<tr>"
							+ "<td>"+data.created_at+"</td>"
							+ "<td>"+item_name+"</td>"
							+ "<td>"+select_status+"</td>"
							+ "<td>"+data.quantity+"</td>"
							+ "<td>"+data.unit_price+"</td>"
							+ "<td>"+shipping_price+"</td>"
							+ "<td>"+data.total_amount+"</td>"
							+ "</tr>";
		});
		html += "<tr>"
				+ "<td colspan='6' align='center'><button type='submit' class='btn btn-default'>Update Status</button></td>"
				+ "</tr>";
		html += "</tbody></table><input type='hidden' name='_token' value='{{csrf_token()}}'></form>";
		$('#view_shop_order').html(html);
		$('#shopModal').modal('show');
	}
}

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
	var order_details = result.merchant_order.order_details;
	var order_detail_status = result.order_detail_status;
	var order_status = '';
	var select_status = '';
	var action_txt = '';
	var selected = '';
	var item_name = '';
	var shipping_price = '';
	if(order_details != 'undefined')
	{
		var html = "<form method='post' action='{{ url('merchant/online-order/update-status') }}/"+merchant_order.id+"' class='form-horizontal'>"
						+ "<table class='order_details table' cellspacing='0' width='100%'><thead>"
						+ "<tr>"
						+ "<th>Date</th>"
						+ "<th>Item Name</th>"
						+ "<th>Status</th>"
						+ "<th>Quantity</th>"
						+ "<th>Unit Price</th>"
						+ "<th>Total Amount</th>"
						+ "</tr></thead><tbody>";
		$.each(order_details, function(index, data) {
			if(data.status == 'cancelled' || data.status == 'completed')
			{
				select_status = order_detail_status[data.status]['name'];
			}
			else
			{
				select_status = '<select name="status['+data.id+']" id="status"  class="form-control">';				
				
				$.each(order_detail_status, function(index_in, data_in) {
					
					selected = '';
					if( data.status == data_in['id'] )
						selected = ' selected';
					
					select_status += '<option value="'+data_in['id']+'" ' + selected +'>';
					select_status += data_in['name'];
					select_status += '</option>';
				});
				select_status += '</select>';
			}
			
			item_name = '';
			shipping_price = '';
			if(data.menu_item != null) {
				item_name = data.menu_item.name;
			}
			
			html += "<tr>"
							+ "<td>"+data.created_at+"</td>"
							+ "<td>"+item_name+"</td>"
							+ "<td>"+select_status+"</td>"
							+ "<td>"+data.quantity+"</td>"
							+ "<td>"+data.unit_price+"</td>"
							+ "<td>"+data.total_amount+"</td>"
							+ "</tr>";
		});
		html += "<tr>"
				+ "<td colspan='6' align='center'><button type='submit' class='btn btn-default'>Update Status</button></td>"
				+ "</tr>";
		html += "</tbody></table><input type='hidden' name='_token' value='{{csrf_token()}}'></form>";
		$('#view_food_order').html(html);
		$('#foodModal').modal('show');
	}
}
</script>

@stop