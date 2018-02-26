@extends('layouts.merchantmain')

@section('head')

@stop

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}" />


<div class="">
	<div class="page-title">
		<div class="title_left">
			<h3>Reviews</h3>
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

						<table id="review-table" class="table table-striped table-bordered bulk_action">
							<thead>
								<tr>					 
									<th>Name</th>
									<th>Email</th>
									<th>Comments</th>
									<th>Rating</th>
									<th>Status</th> 
									<th>Action</th>                      
								</tr>
							</thead>
							<tbody>
								@foreach ($allreviews as $review)
								<tr>
									<td>{{$review->name}}</td>
									<td>{{$review->email}}</td>
									<td>{{$review->comments}}</td>
									<td>{{$review->rating}}</td>
									<td>
									@if($review->approved==0)
									<span class="alert-danger btn btn-primary btn-xs">Disabled</span>
									@else
									<span class="alert-success btn btn-primary btn-xs">Enabled</span>
									@endif
									</td>
									<td>
									@if($review->approved==0)
										<a href="#" class="enable btn btn-primary btn-xs" id="{{ $review->r_id }}" >Enable</a>
										@else
										<a href="#" class="disable btn btn-primary btn-xs" id="{{ $review->r_id }}" >Disable</a>
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
$(document).ready(function(){
$('#review-table').DataTable();
$(document).on("click", ".enable", enable);
function enable(){ 
	var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
	var id= $(this).attr('id'); 
	var host="{{ url('merchant/reviews/approve') }}";
	$.ajax({
		type: 'POST',
		data:{'id': id,'_token':CSRF_TOKEN},
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
	var host="{{ url('merchant/reviews/reject') }}";
	$.ajax({
		type: 'POST',
		data:{'id': id, '_token':CSRF_TOKEN},
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
});
</script>

@stop