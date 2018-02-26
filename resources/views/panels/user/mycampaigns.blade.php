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
					<h2 class="title text-center">My Campaigns</h2>

					<div class="col-sm-12">
						
						@if(Session::get('message')) <div class="alert alert-success" role="alert">{{Session::get('message')}} </div>@endif 
						@if(Session::get('error_message')) <div class="alert alert-danger" role="alert">{{Session::get('error_message')}} </div>@endif 
						
						<table id="datatable" class="table table-striped table-bordered bulk_action">
							<thead>
								<tr>		
									<th>Category</th>
									<th>State</th>
									<th>City</th>
									<th>Zone</th>    
									<th>Position</th>   
									<th>Mode</th>
									<th>Campaign Image</th>     
									
								</tr>
							</thead>
							<tbody>
								@foreach ($campaign as $campaign)
								<tr>
									<td>{{$campaign->category->c_title}}</td>
									<td>{{$campaign->state->name}}</td>
									<td>{{$campaign->city->name}}</td>
									
									<td>{{$campaign->zone}}</td>
									<td>{{$campaign->position->title}}</td>
									
									<td>{{$campaign->mode}}</td>
									<td><img src="{{url('')}}/uploads/campaigns/original/{{$campaign->image}}" width="50px" height="50px"></td>

									
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