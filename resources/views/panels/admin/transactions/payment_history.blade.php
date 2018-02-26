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
			<h3>Payment History</h3>
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
									<th>Date</th>
									<th>Listing</th>
									<th>Paid Amount</th>
									<th>cheque_no</th>
									<th>Comments</th> 
									<th>Action</th>               
								</tr>
							</thead>
							<tbody>
								@foreach ($merchant_transactions as $i => $merchant_transaction)
								<tr>
									<td>{{$merchant_transaction->created_at}}</td>
									<td>{{$merchant_transaction->listing->title}}</td>
									<td>{{$merchant_transaction->paid_amount}}</td>
									<td>{{$merchant_transaction->cheque_no}}</td>
									<td>{{$merchant_transaction->comments}}</td>
									<td>
										<a href="javascript:;" class="view btn btn-default btn-xs" data-id="{{ $merchant_transaction->id }}" >View</a>
									</td>
								</tr>
								@endforeach

							</tbody>
						</table>
					</form>
					
				</div>
			</div>
			
			<div class="col-md-6 col-xs-12">
				<div class="x_panel" id="view_div" style="display:none">
					
					<ul class="nav nav-tabs">
						<li class="active"><a data-toggle="tab" href="#transaction">Transaction</a></li>
						<li><a data-toggle="tab" href="#order_details">Order Details</a></li>
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
						<div id="transaction" class="tab-pane fade in active">
							<h2>Transaction</h2>
							<div class="x_title">
							</div>
							
							<div id="transaction_data"></div>
							
						</div>	
						
						<div id="order_details" class="tab-pane fade">
							<h2>Order Details</h2>
							<div class="x_title">
							</div>
							
							<div id="order_detail_data"></div>
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
	var host = "{{ url('admin/merchants/get-transaction-detail') }}";
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
				renderDetail(res.data.merchant_transaction.orders);
				renderTransactions(res.data.merchant_transaction);
			}
			else
			{
				$('#view_div').hide();
				alert(res.msg);
			}

		}

	});
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

function renderTransactions(transaction)
{	
	var html = "";
	
	if(transaction != 'undefined')
	{		
		html = "<table class='table' cellspacing='0' width='100%'><thead>"
					+ "<tr>"
					+ "<td>Date</td>" + "<td>"+transaction.created_at+"</td>"
					+ "</tr><tr>"
					+ "<td>Paid Amount</td>" + "<td>"+transaction.paid_amount+"</td>"
					+ "</tr><tr>"
					+ "<td>Cheque No</td>" + "<td>"+transaction.cheque_no+"</td>"
					+ "</tr><tr>"
					+ "<td>Cheque Release Date</td>" + "<td>"+transaction.cheque_release_date+"</td>"
					+ "</tr><tr>"
					+ "<td>Comments</td>" + "<td>"+transaction.comments+"</td>"
					+ "</tr></thead><tbody>";
			
		html += "</tbody></table>";
	}
	
	$('#transaction_data').html(html);
}
</script>
@stop