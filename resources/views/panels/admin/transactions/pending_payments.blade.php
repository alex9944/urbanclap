@extends('layouts.adminmain')

@section('head')
<style>
.statuscls {
	width: 133px;
    height: 30px;
    padding: 5px;
    margin: 5px 0 0 0;
    text-align: center;
}
</style>
@stop


@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}" />


<div class="">
	<div class="page-title">
		<div class="title_left">
			<h3>Pending Payments</h3>
		</div>

	</div>
	<div class="clearfix"></div>           

	<div class="row">


		<!-- <div class="col-md-12 col-xs-12">
		<div class="x_panel">-->

			<!-- LEFT BAR Start-->
			<div class="col-md-6 col-xs-12">
				<div class="x_panel">

					@if(Session::get('message')) <div class="alert alert-success" role="alert">{{Session::get('message')}} </div>@endif 
					@if(Session::get('error_message')) <div class="alert alert-danger" role="alert">{{Session::get('error_message')}} </div>@endif 

					<form name="actionForm" action="" method="post"/> 
						<input type="hidden" name="_token" value="{{csrf_token()}}">
						
						<h2> &nbsp;
						</h2>
						<div class="x_title"></div>

						<table id="datatable-checkbox" class="table table-striped table-bordered bulk_action">
							<thead>
								<tr>
									<th>Listing</th>
									<th>No of orders</th>
									<th>Total order amount</th>
									<th>Total site commission amount</th> 
									<th>Merchant net amount</th> 
									<th>Action</th>               
								</tr>
							</thead>
							<tbody>
								@foreach ($pending_payments as $i => $pending_payment)
								<tr>
									<td>{{$pending_payment->listing->title}}</td>
									<td>{{$pending_payment->no_of_orders}}</td>
									<td>{{$pending_payment->total_order_amount}}</td>
									<td>{{$pending_payment->total_site_commission_amount}}</td>
									<td>{{$pending_payment->merchant_net_amount}}</td>
									<td>
										<a href="javascript:;" class="view btn btn-default btn-xs" data-id="{{ $pending_payment->listing->id }}" >View</a>
									</td>
								</tr>
								@endforeach

							</tbody>
						</table>
					</form>
					
				</div>
			</div>
			
			<div class="col-md-6 col-xs-12">
				<div class="x_panel" id="view_div" @if(!$summary) style="display:none"@endif>
					
					<ul class="nav nav-tabs">
						<li class="active"><a data-toggle="tab" href="#order_details">Order Details</a></li>
						<li><a data-toggle="tab" href="#pay">Pay To Vendor</a></li>
					</ul>
					
					@if ($errors->any())
					<div class="alert alert-danger">
						<ul>
							@foreach ($errors->all() as $error)
								<li>{{ $error }}</li>
							@endforeach
						</ul>
					</div>
					@endif
					
					<div class="tab-content">	
						
						
						<div id="order_details" class="tab-pane fade in active">
							<h2>Order Details</h2>
							<div class="x_title">
							</div>
							
							<div id="order_detail_data"></div>
						</div>
						
						<div id="pay" class="tab-pane fade">
							<h2>Pay To Vendor</h2>
							<div class="x_title">
							</div>
							
							<div id="pay_data"></div>
							
						</div>
						
					</div>
					
				</div>
			</div>

			<div class="clearfix"></div>  

		</div>
	</div>
	
	<?php /*<div id="divToPrint"></div>*/?>
</div>

<script>
$(document).on("click", ".view", function() {
	var id= $(this).data('id');
	
	var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
	var host = "{{ url('admin/merchants/get-payment-detail') }}";
	$.ajax({
		type: 'POST',
		data:{id:id, '_token':CSRF_TOKEN},
		url: host,
		dataType: "json", // data type of response		
		beforeSend: function(){
			$('.image_loader').show();
		},
		complete: function(){
			$('.image_loader').hide();
		},
		success: function(res)
		{
			if(res.success)
			{
				$('#view_div').show();
				renderDetail(res.data.merchant_payments);
				renderPay(res.data.summary);
				/*renderUsage(res.data.marketing_user.usage_history);
				renderTransactions(res.data.marketing_user.transactions);*/
			}
			else
			{
				$('#view_div').hide();
				alert(res.msg);
			}

		}

	});
	
	<?php if($summary):?>
		renderPay(<?php $summary;?>);
		$('.nav-tabs a[href="#pay"]').tab('show');
	<?php endif;?>
});

function renderDetail(order_details)
{
	var html = "<table class='order_details table' cellspacing='0' width='100%'><thead>"
							+ "<tr>"
							+ "<th>Order Id</th>"
							+ "<th>Order Date</th>"
							+ "<th>Customer Email</th>"
							+ "<th>Customer Phone</th>"
							+ "<th>Total Items</th>"
							+ "<th>Total Amount</th>"
							+ "<th>Site Commission</th>"
							+ "<th>Net Amount</th>"
							+ "</tr></thead><tbody>";
	if(order_details != 'undefined')
	{		
		$.each(order_details, function(index, data) {
			html += "<tr>"
							+ "<td>"+data.id+"</td>"
							+ "<td>"+data.created_at+"</td>"
							+ "<td>"+data.main_order.email+"</td>"
							+ "<td>"+data.main_order.phone_number+"</td>"
							+ "<td>"+data.total_items+"</td>"
							+ "<td>"+data.total_amount+"</td>"
							+ "<td>"+data.site_commission_amount+"</td>"
							+ "<td>"+data.merchant_net_amount+"</td>"
							+ "</tr>";
		});		
	}
	html += "</tbody></table>";
		$('#order_detail_data').html(html);
		$('.order_details').dataTable();
}

function renderPay(detail)
{
	var html = "<div class='alert alert-danger'>Account balance is 0. No need to pay.</div>";
	
	if(detail.merchant_net_amount > 0)
	{
		html = "<form action={{url('admin/merchants/marketing-user-pay')}} name='frmPay' method='post'>"
				+ "<table class='transactions table' cellspacing='0' width='100%'>"
				+ "<tr>"
					+ "<th>Total Orders</th>"
					+ "<td>"+detail.no_of_orders+"</td>"
				+ "</tr>"
				+ "<tr>"
					+ "<th>Total Order Amount</th>"
					+ "<td>"+detail.total_order_amount+"</td>"
				+ "</tr>"
				+ "<tr>"
					+ "<th>Total Site Commission</th>"
					+ "<td>"+detail.total_site_commission_amount+"</td>"
				+ "</tr>"
				+ "<tr>"
					+ "<th>Merchant Net Amount</th>"
					+ "<td>"+detail.merchant_net_amount+"</td>"
				+ "</tr>"
				+ "<tr>"
					+ "<th>Paid Amount</th>"
					+ "<td><input type='text' class='form-control' readonly name='paid_amount' value='"+detail.merchant_net_amount+"'></td>"
				+ "</tr>"
				+ "<tr>"
					+ "<th>Check No</th>"
					+ "<td><input type='text' class='form-control' name='cheque_no' value='{{old('cheque_no')}}'></td>"
				+ "</tr>"
				+ "<tr>"
					+ "<th>Check Release Date</th>"
					+ "<td><input type='text' class='form-control' name='cheque_release_date' id='cheque_release_date' value='{{old('cheque_release_date')}}'></td>"
				+ "</tr>"
				+ "<tr>"
					+ "<th>Comments</th>"
					+ "<td><textarea class='form-control' rows='4' name='comments'>{{old('comments')}}</textarea></td>"
				+ "</tr>"
				+ "</table>";
		html += '<div class="form-group">'
					+ '<div class="col-md-8 col-sm-8 col-xs-12 col-md-offset-4">'
						+'<input class="btn btn-success" type="submit" name="submit" id="submit" value="Submit"/>'
					+ '</div>'
				+ '</div>';
		html += '<input type="hidden" name="_token" value="{{csrf_token()}}">'
				+ '<input type="hidden" value="'+detail.listing_id+'" name="listing_id" />';
		html += '</form>';
	}
	
	$('#pay_data').html(html);
	
	if(detail.merchant_net_amount > 0)
	{
		$("#cheque_release_date").daterangepicker({
			singleDatePicker: true,
			//minDate:new Date()
		});
	}
}

function renderUsage(usage_history)
{
	if(usage_history != 'undefined')
	{
		var usage_html = "<table class='usage_history table' cellspacing='0' width='100%'><thead>"
							+ "<tr>"
							+ "<th>Used On</th>"
							+ "<th>Usage Type</th>"
							+ "<th>User Id</th>"
							+ "<th>User Name</th>"
							+ "<th>User Points</th>"
							+ "<th>Vendor Commission</th>"
							+ "</tr></thead><tbody>";
		$.each(usage_history, function(index, usage) {
			usage_html += "<tr>"
							+ "<td>"+usage.created_at+"</td>"
							+ "<td>"+usage.usage_type+"</td>"
							+ "<td>"+usage.user_id+"</td>"
							+ "<td>"+usage.user.first_name+"</td>"
							+ "<td>"+usage.user_points+"</td>"
							+ "<td>"+usage.local_vendor_commission+"</td>"
							+ "</tr>";
		});
		usage_html += "</tbody></table>";
		$('#usage_data').html(usage_html);
		$('.usage_history').dataTable();
	}
}

$(document).on("click", ".delete", deleteRow);
function deleteRow(){ 
	if (confirm("Are you sure want to delete the code?")) {
		
		var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
		var id= $(this).attr('id'); 
		var host = "{{ url('admin/local-vendor/marketing-user-delete') }}" + '/' + id;
		$.ajax({
			type: 'DELETE',
			data:{'_token':CSRF_TOKEN},
			url: host,
			dataType: "json", // data type of response		
			beforeSend: function(){
				$('.image_loader').show();
			},
			complete: function(){
				$('.image_loader').hide();
			},
			success: function(res)
			{
				if(res.success)
				{
					window.location = "{{ url('admin/local-vendor/qr-code') }}";
				}
				else
				{
					alert(res.msg);
				}

			}

		});

	}

	return false;
}
</script>


<script type="text/javascript">
	function deleteConfirm(){
		if($('.checkbox:checked').length == ''){
			alert('Please check atleast one row');
			return false;
		}	
	}
	$(document).ready(function(){
		$('#check_all').on('click',function(){
			if(this.checked){
				$('.checkbox').each(function(){
					this.checked = true;
				});
			}else{
				$('.checkbox').each(function(){
					this.checked = false;
				});
			}
		});

		$('.checkbox').on('click',function(){
			if($('.checkbox:checked').length == $('.checkbox').length){
				$('#check_all').prop('checked',true);
			}else{
				$('#check_all').prop('checked',false);
			}
		});
	});

</script>
@stop