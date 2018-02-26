@extends('layouts.adminmain')

@section('head')

@stop

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}" />


<div class="">
	<div class="page-title">
		<div class="title_left">
			<h3>Booking Detail</h3>
		</div>

	</div>
	<div class="clearfix"></div>           

	<div class="row">
		<div class="col-md-12 col-xs-12">
			<div class="x_panel" id="vieworder">
				<h2></h2>
				<div class="x_title">
				</div>	

				@if(Session::get('message')) <div class="alert alert-success" role="alert">{{Session::get('message')}} </div>@endif 
				@if(Session::get('error_message')) <div class="alert alert-danger" role="alert">{{Session::get('error_message')}} </div>@endif 

				<table class="table table-striped table-bordered bulk_action">

					<tr>
						<th>Booking Id</th>
						<th>Payment Gateway</th>
					</tr>
					<tr>
						<td id="order_id">{{ $order_booking->id }}</td>
						<td id="payment_type">{{ $order_booking->payment_type }}</td>
					</tr>

					<tr>
						<th>Total Amount</th>
						<th>Status</th>
					</tr>
					<tr>
						<td id="amount">{{ $order_booking->total}}</td>
						<td id="status">{{ @$order_status[$order_booking->status]['name'] }}</td>
					</tr>

					<tr>
						<th>Email</th>
						<th>Phone No</th>
					</tr>
					<tr>
						<td id="email">{{ $order_booking->email }}</td>
						<td id="phone">{{ $order_booking->phone_number }}</td>
					</tr>
					<tr>
						<th colspan="2">Delivery Address</th>
					</tr>
					<tr>
						<td colspan="2">
							{{ $order_booking->billing_detail->s_name }}<br />
							{{ $order_booking->billing_detail->s_address_1 }}
							@if($order_booking->billing_detail->s_address_2)
							<br />{{ $order_booking->billing_detail->s_address_2 }}
							@endif
							<br />
							{{ $order_booking->billing_detail->delivery_city->name }}<br />
							{{ $order_booking->billing_detail->delivery_state->name }}<br />
							{{ $order_booking->billing_detail->delivery_country->name }}
						</td>
					</tr>
				</table>

				
				<form id="listForm" method="post" action="{{ url('admin/online-order/booking-customers/'.$order_booking->id) }}" class="form-horizontal">
					<input id="method" name="_method" type="hidden" value="POST">
					<input type="hidden" name="_token" value="{{csrf_token()}}">
					
					<h3>Order Detail</h3>
					@foreach($merchant_orders as $merchant_order)
						<?php
							$order_detail = $merchant_order->order_details;
							$listing = $merchant_order->listing;
							$order_type = $merchant_order->order_type;
						?>
						<table class="table table-striped table-bordered bulk_action">
							<tr>
								<td>Listing Name</td>
								<td>Item Name</td>
								<td>Status</td>
								<td>Quantity</td>
								<td>Unit Price</td>
								@if($order_type == 'online_shopping')
									<td>Shipping Price</td>
								@endif
								<td>Total Amount</td>
							</tr>						
							@if(sizeof($order_detail)>0)
							@foreach($order_detail as $detail)
								<?php
								if($order_type == 'online_order') {
									$item_name = @$detail->menu_item->name;
									$shipping_price = 0;
								} else {
									$item_name = @$detail->shop_item->pro_name;
									$shipping_price = @$detail->shop_item->shipping_price;
								}
								$total_amount = isset($detail->total_amount) ? $detail->total_amount : 0;
								?>
							<tr>
								<td>{{ @$listing->title }}</td>
								<td>{{ $item_name }}</td>
								<td><?php echo @$order_detail_status[$detail->status]['name'];?></td>
								<td>{{ @$detail->quantity }}</td>
								<td>{{ @$detail->unit_price }}</td>
								@if($order_type == 'online_shopping')
									<td>{{ $shipping_price }}</td>
								@endif
								<td>{{$total_amount  + $shipping_price }}</td>
							</tr>
							@endforeach
							@endif
							<?php /*
							<tr>
								<td colspan="3"></td>
								<td>
									<div class="form-group required">
										<label class="col-sm-5 control-label">Update Status</label>
										<div class="col-sm-5">
											<select name="status" id="status"  class="form-control">	
												@foreach($order_status as $status_arr)
												<option value="{{ $status_arr['id'] }}" @if( old('status', $merchant_order->status) == $status_arr['id'] ) selected @endif>
													{{ $status_arr['name'] }}
												</option>
												@endforeach
											</select>
										</div>
									</div>
								</td>
							</tr>
							*/?>
							@if($order_type == 'online_order')
							<tr>
								<td colspan="@if($order_type == 'online_shopping') 6 @else 5 @endif" align="right">Delivery Fee</td>
								<td>{{$merchant_order->total_shipping_amount}}</td>
							</tr>
							@endif
							<tr>
								<td colspan="@if($order_type == 'online_shopping') 6 @else 5 @endif" align="right"><b>Order Total</b></td>
								<td>{{$merchant_order->total_amount}}</td>
							</tr>
						</table>
					@endforeach

					<div class="form-group required">
						<div class="col-sm-5 col-sm-offset-5">
							<?php /*<button type="submit" class="btn btn-default">Submit</button> */?>
							<a href="{{ url('admin/online-order/booking-customers') }}" class="btn btn-default">Back</a>
						</div>
					</div>
				</form>
			</div>

		</div>
		<!-- Right BAR End-->
				<!--</div>
				</div>-->
				<div class="clearfix"></div>  
			</div>
		</div>

		<!-- Button trigger modal -->

		<!-- Modal -->
		<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id="myModalLabel">View Product </h4>
					</div>
					<div class="modal-body" id="viewItem">

					</div>
      <!--<div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
    </div>-->
</div>
</div>
</div>

@stop