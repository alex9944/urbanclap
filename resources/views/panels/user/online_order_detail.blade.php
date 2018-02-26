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
									<th>Total Amount</th>
								</tr>
								<tr>
									<td id="order_id">{{ $order_booking->id }}</td>
									<td id="amount">{{ $currency->symbol . $order_booking->total_amount }}</td>
								</tr>

								<tr>
									<th>Order Status</th>
									<th>Phone No</th>
								</tr>
								<tr>
									<td id="status">{{ $order_booking->status }}</td>
									<td id="phone">{{ $order_booking->phone_number }}</td>
								</tr>

								<tr>
									<th>Email</th>
									<th>Delivery Address</th>
								</tr>
								<tr>
									<td id="email">{{ $order_booking->email }}</td>
									<td>
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
								<tr>
								</tr>
							</table>
							
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
										<td>Total Amount</td>
									</tr>						
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
										<td>{{ $listing->title }}</td>
										<td>{{ $item_name }}</td>
										<td><?php echo $order_detail_status[$detail->status]['name'];?></td>
										<td>{{ $detail->quantity }}</td>
										<td>{{ $detail->unit_price }}</td>
										<td>{{ $detail->total_amount }}</td>
									</tr>
									@endforeach
									@endif
									@if($merchant_order->total_shipping_amount)
									<tr>
										<td colspan="5" align="right">Delivery Fee</td>
										<td>{{$merchant_order->total_shipping_amount}}</td>
									</tr>
									@endif
									<tr>
										<td colspan="5" align="right"><b>Order Total</b></td>
										<td>{{$merchant_order->total_amount}}</td>
									</tr>
								</table>
							@endforeach
							
							<div class="form-group required">
								<div class="col-sm-5 col-sm-offset-5">
								<a href="{{ url('user/online-orders') }}" class="btn btn-default">Back</a>
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