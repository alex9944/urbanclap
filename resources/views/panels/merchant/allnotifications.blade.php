@extends('layouts.merchantmain')

@section('head')

@stop

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}" />


<div class="">
	<div class="page-title">
		<div class="title_left">
			<h3>Notifications</h3>
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
						@if(Session::get('error_message')) <div class="alert alert-danger" role="alert">{{Session::get('error_message')}} </div>@endif 

						<form name="actionForm" action="" method="post"> 
							<h2> &nbsp;
								<span class="pull-right">

								</span>
							</h2>

							<table id="bookings" class="table table-striped  bulk_action">
								
								<tbody class="notify">

								</tbody>
							</table>
						</form>	  
					</div>
				</div>



				<div class="clearfix"></div>  

			</div>
		</div>

		<script>
			$(document).ready(function(){
				var notification='';
				$('.notify').html('');
				$.ajax({
					type:'get',
					url:"{{url('')}}/merchant/all_notification",
					dataType:"json",
					success:function(res){
						
						if(res.length>0){
							$.each(res,function(key,value){
								if(value.read==0){
									notification+='<tr style="font-weight: bold; font-size:15px">';
								}
								else{
									notification+='<tr>';
								}
								notification+='<td>'+(key+1)+'</td>\
								<td>'+value.name+'</td><td>';
								if(value.type=='Appointment'){
									notification+='<a class="read_status"  data-notid="'+value.id+'" href="{{url('')}}/merchant/allappointments">'+value.message+'</a></td>';
								}
								else if(value.type=='Table'){
									notification+='<a class="read_status" data-notid="'+value.id+'" href="{{url('')}}/merchant/allbookings" >'+value.message+'</a></td>';
								}
								else if(value.type=='Review'){
									notification+='<a class="read_status" data-notid="'+value.id+'" href="{{url('')}}/merchant/allreviews" >'+value.message+'</a></td>';
								}
								else{
									notification+='<a class="read_status"  data-notid="'+value.id+'" href="{{url('')}}/merchant/customer-orders">'+value.message+'</a></td>';
								}
								
								notification+='<td>'+value.time+'</td></tr>';
							});
							$('.notify').append(notification);
						}
					}
				});
			});
		</script>

		@stop