@extends('layouts.merchantmain')

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
							<th>Payment Type</th>
						</tr>
						<tr>
							<td id="order_id">{{ $order_booking->id }}</td>
							<td id="payment_type">{{ $order_booking->payment_type }}</td>
						</tr>

						<tr>
							<th>Total Amount</th>
							<th>Payment Status</th>
						</tr>
						<tr>
							<td id="amount">{{ $order_booking->total_amount }}</td>
							<td id="payment_status">{{ $order_booking->payment_status }}</td>
						</tr>

						<tr>
							<th>Order Status</th>
							<th>Phone No</th>
						</tr>
						<tr>
							<td id="status">{{ $merchant_order_status[$order_booking->order_status]['name'] }}</td>
							<td id="phone">{{ $order_booking->main_order->phone_number }}</td>
						</tr>
					<tr>
						<th>Email</th>
						<th>Delivery Address</th>
					</tr>
					<tr>
						<td id="email">{{ $order_booking->main_order->email }}</td>
						<td>
							{{ $order_booking->main_order->billing_detail->s_name }}<br />
							{{ $order_booking->main_order->billing_detail->s_address_1 }}
							@if($order_booking->main_order->billing_detail->s_address_2)
							<br />{{ $order_booking->main_order->billing_detail->s_address_2 }}
							@endif
							<br />
							{{ $order_booking->main_order->billing_detail->delivery_city->name }}<br />
							{{ $order_booking->main_order->billing_detail->delivery_state->name }}<br />
							{{ $order_booking->main_order->billing_detail->delivery_country->name }}
						</td>
					</tr>
					</table>
					
					<h3>Item Detail</h3>
					
					<form id="listForm" method="post" action="{{ url('merchant/online-order/booking-customers/'.$order_booking->id) }}" class="form-horizontal">
						<input id="method" name="_method" type="hidden" value="POST">
						<input type="hidden" name="_token" value="{{csrf_token()}}">
						
						<table class="table table-striped table-bordered bulk_action">
							<tr>
								<td>Item Name</td>
								<td>Status</td>
								<td>Quantity</td>
								<td>Unit Price</td>
								<td>Total Amount</td>
							</tr>
						@php
							$order_detail = $order_booking->order_details;
							$listing = $order_booking->listing;
							$order_type = $order_booking->order_type;
						@endphp
						
						@if(sizeof($order_detail)>0)
						@foreach($order_detail as $detail)
							<?php
							if($order_type == 'online_order') {
								$item_name = $detail->menu_item->name;
							} else {
								$item_name = $detail->shop_item->pro_name;
							}
							?>
						<tr>
							<td>{{ $item_name }}</td>
							<td>
								@if($detail->status == 'cancelled' || $detail->status == 'completed')
									{{$order_detail_status[$detail->status]['name']}}
								@else
									<select name="status[{{$detail->id}}]" id="status"  class="form-control">
										@foreach($order_detail_status as $status_arr)
										<option value="{{ $status_arr['id'] }}" @if( old('status', $detail->status) == $status_arr['id'] ) selected @endif>
											{{ $status_arr['name'] }}
										</option>
										@endforeach
									</select>
								@endif
							</td>
							<td>{{ $detail->quantity }}</td>
							<td>{{ $detail->unit_price }}</td>
							<td>{{ $detail->total_amount }}</td>
						</tr>
						@endforeach
						@endif
						@if($order_booking->total_shipping_amount)
						<tr>
							<td colspan="4" align="right">Delivery Fee</td>
							<td>{{$order_booking->total_shipping_amount}}</td>
						</tr>
						@endif
						<tr>
							<td colspan="4" align="right"><b>Order Total</b></td>
							<td>{{$order_booking->total_amount}}</td>
						</tr>
						</table>

						
						<div class="form-group required">
							<div class="col-sm-5 col-sm-offset-5">
							<button type="submit" class="btn btn-default">Submit</button> 
							<a href="{{ url('merchant/online-order/booking-customers') }}" class="btn btn-default">Back</a>
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