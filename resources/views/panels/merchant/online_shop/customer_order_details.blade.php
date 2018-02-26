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
							<th>Status</th>
						</tr>
						<tr>
							<td id="amount">{{ $order_booking->total }}</td>
							<td id="status">{{ $order_booking->status }}</td>
						</tr>

						<tr>
							<th>Email</th>
							<th>Phone No</th>
						</tr>
						<tr>
							<td id="email">{{ $order_booking->email }}</td>
							<td id="phone">{{ $order_booking->phone_number }}</td>
						</tr>
					</table>

					<table class="table table-striped table-bordered bulk_action">

						<tr>
							<th>Billing Address</th>	
							<th>Delivery Address</th>					  
						</tr>
						<tr>
							<td>{{ $order_booking->billing_detail->b_name }}</td>	
							<td>{{ $order_booking->billing_detail->s_name }}</td>						 
						</tr>
						<tr>
							<td>
							{{ $order_booking->billing_detail->b_address_1 }}
							@if($order_booking->billing_detail->b_address_2)
								<br />{{ $order_booking->billing_detail->b_address_2 }}
							@endif
							</td>
							<td>
							{{ $order_booking->billing_detail->s_address_1 }}
							@if($order_booking->billing_detail->s_address_2)
								<br />{{ $order_booking->billing_detail->s_address_2 }}
							@endif
							</td>					 
						</tr>
						
						<tr>
							<td>{{ $order_booking->billing_detail->billing_city->name }}</td>
							<td>{{ $order_booking->billing_detail->delivery_city->name }}</td>				 
						</tr>
						<tr>
							<td>{{ $order_booking->billing_detail->billing_state->name }}</td>	
							<td>{{ $order_booking->billing_detail->delivery_state->name }}</td>					 
						</tr>
						<tr>
							<td>{{ $order_booking->billing_detail->billing_country->name }}</td>
							<td>{{ $order_booking->billing_detail->delivery_country->name }}</td>					 
						</tr>
					</table>
					
					<h3>Item Detail</h3>
					<table class="table table-striped table-bordered bulk_action">
						<tr>
							<td>Listing Name</td>
							<td>Product Name</td>
							<td>Quantity</td>
							<td>Unit Price</td>
							<td>Total Amount</td>
						</tr>
						@php
							$order_detail = $order_booking->shoporder_detail;
							$listing = $order_booking->listing;
						@endphp
						
						@foreach($order_detail as $detail)
							<tr>
								<td>{{ $listing->title }}</td>
								<td>{{ $detail->shop_item->pro_name }}</td>
								<td>{{ $detail->quantity }}</td>
								<td>{{ $detail->unit_price }}</td>
								<td>{{ $detail->total_amount }}</td>
							</tr>
						@endforeach
					</table>

					<form id="listForm" method="post" action="{{ url('merchant/customer-orders/'.$order_booking->id) }}" class="form-horizontal">
						<input id="method" name="_method" type="hidden" value="POST">
						<input type="hidden" name="_token" value="{{csrf_token()}}">
						
						<div class="form-group required">
							<label class="col-sm-5 control-label">Update Status</label>
							<div class="col-sm-5">
								<select name="status" id="status"  class="form-control">	
									@foreach($order_status as $status_key => $status_value)
									<option value="{{ $status_key }}" @if( old('status', $order_booking->status) == $status_key ) selected @endif>
										{{ $status_value }}
									</option>
									@endforeach
								</select>
							</div>
						</div>
						
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