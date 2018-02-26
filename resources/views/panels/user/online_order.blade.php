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
                    <h2 class="title text-center">Online Orders</h2>

                        <div class="col-sm-12">
							
							@if(Session::get('message')) <div class="alert alert-success" role="alert">{{Session::get('message')}} </div>@endif 
							@if(Session::get('error_message')) <div class="alert alert-danger" role="alert">{{Session::get('error_message')}} </div>@endif 
                            
							<table id="datatable" class="table table-striped table-bordered bulk_action">
								<thead>
								<tr>		
									<th>Order Date</th>
									<th>Total Items</th>
									<th>Total Amount</th>    
									<th>Payment Type</th>   
									<th>Status</th>
									<th>Action</th>                
								</tr>
							</thead>
							<tbody>
								@foreach ($online_orders as $booking)
								<tr>
									<td>{{Carbon\Carbon::parse($booking->created_at)->format('M d, Y hA')}}</td>
									<td>{{$booking->total_items}}</td>
									<td>{{$currency->symbol . $booking->total_amount}}</td>
									<td>{{$booking->payment_type}}</td>
									<td>{{$order_status[$booking->status]['name']}}</td>
									<td>
										<a href="{{ url('user/online-order-detail/'.$booking->id) }}" class="btn btn-primary btn-xs">Detail</a> 
										<?php /*<a href="#" class="btn btn-danger btn-xs" id="{{ $booking->id }}" >Delete</a>*/?>
									</td>
								</tr>
								@endforeach

							</tbody>
							</table>
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
		$('#datatable').DataTable({"aaSorting": []});
	});
</script>
@stop