@extends('layouts.main')
	<!-- Datatables -->
  <link href="{{ asset('admin-assets/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('admin-assets/vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('admin-assets/vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('admin-assets/vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('admin-assets/vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css') }}" rel="stylesheet">

@section('head')

@stop

@section('content')
<?php /*@foreach($users as $users)
    {{$users}}
@endforeach
*/?>
<section>
    <div class="container">
        <div class="row m-t30">
            <div class="col-sm-3">
                @include('panels.user.myaccount_left_menu')				
            </div>

            <div class="col-sm-9 padding-right">
                <div class="features_items"><!--features_items-->
                    <h2 class="title text-center">Order Detail</h2>

                        <div class="col-sm-12">
							
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
									<td id="amount">{{ $currency->symbol . $order_booking->total }}</td>
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
							
							<h3>Product Detail</h3>
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
							
							<div class="form-group required">
								<div class="col-sm-5 col-sm-offset-5">
								<a href="{{ url('user/online-shoppings') }}" class="btn btn-default">Back</a>
								</div>
							</div>
                        </div>


                </div>

			</div><!--features_items-->
		</div>
	</div>
</section>
<!-- Datatables -->
<script src="{{ asset('admin-assets/vendors/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('admin-assets/vendors/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
<script src="{{ asset('admin-assets/vendors/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('admin-assets/vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js') }}"></script>
<script src="{{ asset('admin-assets/vendors/datatables.net-buttons/js/buttons.flash.min.js') }}"></script>
<script src="{{ asset('admin-assets/vendors/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('admin-assets/vendors/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
<script src="{{ asset('admin-assets/vendors/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js') }}"></script>
<script src="{{ asset('admin-assets/vendors/datatables.net-keytable/js/dataTables.keyTable.min.js') }}"></script>
<script src="{{ asset('admin-assets/vendors/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('admin-assets/vendors/datatables.net-responsive-bs/js/responsive.bootstrap.js') }}"></script>
<script src="{{ asset('admin-assets/vendors/datatables.net-scroller/js/dataTables.scroller.min.js') }}"></script>
<script>
	$(document).ready(function(){
		$('#datatable').DataTable();
	});
</script>
@stop