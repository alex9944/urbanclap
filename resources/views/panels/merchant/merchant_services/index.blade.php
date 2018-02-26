@extends('layouts.merchantmain')

@section('head')

@stop

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}" />


<div class="">
    <div class="page-title">
              <div class="title_left">
                <h3>Services</h3>
              </div>

    </div>
    <div class="clearfix"></div>           

	<div class="row">


		<!-- <div class="col-md-12 col-xs-12">
		 <div class="x_panel">-->
		 
		 <!-- LEFT BAR Start-->
		<div class="col-md-12 col-xs-12">
			<div class="x_panel">
			
				@if(Session::get('message')) <div class="alert alert-success" role="alert">{{Session::get('message')}} </div>@endif 
								
				<form name="actionForm" action="{{url('admin/services/destroy_all')}}" method="post"> 
					<h2> &nbsp;
						<span class="pull-right">
							
						</span>
					</h2>
					<div class="x_title"></div>

					<table class="table table-striped table-bordered bulk_action">
						<thead>
							<tr>					 
							<th>Service Name</th>
							<th>Status</th>
							<th>Action</th>                       
							</tr>
						</thead>
						<tbody>
							@foreach ($all_services as $service)
								@php
									$merchant_service = $service->merchant_service()->where('merchant_id', $user_id)->first();
									$merchant_service_id = '';
									if($merchant_service)
										$merchant_service_id = $merchant_service->id;
								@endphp
							<tr class="rm{{ $service->id }}">
								<td>{{ $service->name }}</td>
								<td>
									@if( isset($merchant_service->is_enable) and $merchant_service->is_enable)
										<span class="alert-success">Enable</span>
									@else
										<span class="alert-danger">Disable </span>
									 @endif	
								</td>
								<td>
									@if( isset($merchant_service->is_enable) and $merchant_service->is_enable)
										<a href="#" class="disable btn btn-primary btn-xs" id="{{ $service->id }}" data-merchant_service_id="{{ $merchant_service_id }}">
											Make Disable 
										</a>
									 @else
										<a href="#" class="enable btn btn-primary btn-xs" id="{{ $service->id }}" data-merchant_service_id="{{ $merchant_service_id }}" >
											Make Enable 
										</a>
									 @endif						
								</td>
							</tr> 
							@endforeach

						</tbody>
					</table>
				</form>	  
			</div>
		</div>
		
		
			 
		<div class="clearfix"></div>  
	
	</div>
</div>

<script>
//Change Status Enable

$(document).on("click", ".enable", enable);
function enable(){ 
	var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
	var id= $(this).attr('id'); 
	var merchant_service_id = $(this).data('merchant_service_id');
	var host="{{ url('merchant/services/enable') }}";
	$.ajax({
		type: 'POST',
		data:{'id': id, 'merchant_service_id': merchant_service_id,'_token':CSRF_TOKEN},
		url: host,
		dataType: "json", // data type of response		
		beforeSend: function(){
        $('.image_loader').show();
        },
        complete: function(){
        $('.image_loader').hide();
        },success:enableStatus
	
	})
	return false;
}
function enableStatus(res){
	location.reload();
}
 	
	
	
//Change Status Disable

$(document).on("click", ".disable", disable);
function disable(){ 
	var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
	var id= $(this).attr('id'); 
	var merchant_service_id = $(this).data('merchant_service_id');
	var host="{{ url('merchant/services/disable') }}";
	$.ajax({
		type: 'POST',
		data:{'id': id, 'merchant_service_id': merchant_service_id, '_token':CSRF_TOKEN},
		url: host,
		dataType: "json", // data type of response		
		beforeSend: function(){
        $('.image_loader').show();
        },
        complete: function(){
        $('.image_loader').hide();
        },success:disableStatus
	
	})
	return false;
}
function disableStatus(res){ 
	location.reload();
}
</script>

@stop