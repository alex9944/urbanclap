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
                    <h2 class="title text-center">Appointment Booking</h2>

                        <div class="col-sm-12">
                            
							<table id="datatable" class="table table-striped table-bordered bulk_action">
								<thead>
									<tr>					 
										<th>Booking on</th>				 					 
										<th>Booked date</th>
										<th>Booked time</th>				 
										<th>Listing</th>
										<th>Address and Phone</th>
										<th>Status</th>                       
										<th>Link</th>
									</tr>
								</thead>
								<tbody>
									@foreach($appointment_bookings as $appointment_booking)
										<tr>
											<td>{{ $appointment_booking->order_date }}</td>
											<td>{{ $appointment_booking->booked_date }}</td>
											<td>{{ $appointment_booking->booked_time }}</td>
											<td>{{ $appointment_booking->listing->title }}</td>
											<td>
												@php
													$address = $appointment_booking->listing->address1;
													$city = $appointment_booking->listing->listing_city->name;
													$phone = $appointment_booking->listing->phoneno;
													if($appointment_booking->listing->address2)
														$address .= ', ' . $appointment_booking->listing->address2;
													echo $address, ', ' , $city . '. <br />', $phone;
												@endphp
											</td>
											<td>{{ $appointment_booking->status }}</td>
											<td><a href="{{ url($appointment_booking->listing->slug) }}" target="_blank">View</a></td>
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
		$('#datatable').DataTable();
	});
</script>
@stop