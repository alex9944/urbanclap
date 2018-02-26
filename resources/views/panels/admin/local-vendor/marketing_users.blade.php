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
			<h3>Loval Vendor For Marketing</h3>
		</div>

	</div>
	<div class="clearfix"></div>           

	<div class="row">


		<!-- <div class="col-md-12 col-xs-12">
		<div class="x_panel">-->

			<!-- LEFT BAR Start-->
			<div class="col-md-5 col-xs-12">
				<div class="x_panel">

					@if(Session::get('message')) <div class="alert alert-success" role="alert">{{Session::get('message')}} </div>@endif 
					@if(Session::get('error_message')) <div class="alert alert-danger" role="alert">{{Session::get('error_message')}} </div>@endif 

					<form name="actionForm" action="{{url('admin/local-vendor/marketing-users/destroy')}}" method="post" onsubmit="return deleteConfirm();"/> 
						<input type="hidden" name="_token" value="{{csrf_token()}}">
						
						<h2> &nbsp;
						</h2>
						<div class="x_title"></div>

						<table id="datatable-checkbox" class="table table-striped table-bordered bulk_action">
							<thead>
								<tr>
									<th>Shop Name</th>
									<th>Unique Code</th>
									<th>Account Balance</th>
									<th>Status</th> 
									<th>Action</th>               
								</tr>
							</thead>
							<tbody>
								@foreach ($marketing_users as $i => $marketing_user)
								<tr>
									<td>{{$marketing_user->shop_name}}</td>
									<td>{{$marketing_user->unique_code}}</td>
									<td>{{$marketing_user->account_balance}}</td>
									<td>
										@if($marketing_user->vendor_status)
											Active
										@else
											In-Active
										@endif
									</td>
									<td>
										<a href="javascript:;" class="view btn btn-default btn-xs" data-id="{{ $marketing_user->id }}" >View</a> 
										<a href="javascript:;" class="delete btn btn-danger btn-xs" data-id="{{ $marketing_user->id }}" >Delete</a>
									</td>
								</tr>
								@endforeach

							</tbody>
						</table>
					</form>
					
				</div>
			</div>
			
			<div class="col-md-7 col-xs-12">
				<div class="x_panel" id="view_div" @if(old('id') == '') style="display:none"@endif>
					
					<ul class="nav nav-tabs">
						<li class="active"><a data-toggle="tab" href="#vendor_detail">Vendor Detail</a></li>
						<li><a data-toggle="tab" href="#usage">Usage</a></li>
						<li><a data-toggle="tab" href="#transaction">Transaction</a></li>
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
						<div id="vendor_detail" class="tab-pane fade in active">
							<h2>Vendor Detail</h2>
							<div class="x_title">
							</div>
							<form method="POST" action="{{url('admin/local-vendor/marketing-user-update')}}"  enctype="multipart/form-data" class="form-horizontal">
								<div class="form-group">
									<label class="col-sm-3 control-label">Marketing Person ID</label>
									<div class="col-sm-6">
										<span id="marketing_person_id">{{old('marketing_person_id')}}</span>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label">Marketing Person Name</label>
									<div class="col-sm-6">
										<span id="marketing_person_name">{{old('marketing_person_name')}}</span>
									</div>
								</div>
								
								<div class="form-group">
									<label class="col-sm-3 control-label">Shop name</label>
									<div class="col-sm-6">
										<input class="form-control" type="text" name="shop_name" id="shop_name" value="{{ old('shop_name') }}"/>
									</div>
								</div>
								
								<div class="form-group">
									<label class="col-sm-3 control-label">Customer name</label>
									<div class="col-sm-6">
										<input class="form-control" type="text" name="customer_name" id="customer_name" value="{{ old('customer_name') }}"/>
									</div>
								</div>
								
								<div class="form-group">
									<label class="col-sm-3 control-label">Address</label>
									<div class="col-sm-6">
										<input class="form-control" type="text" name="address" id="address" value="{{ old('address') }}"/>
									</div>
								</div>
								
								<div class="form-group">
									<label class="col-sm-3 control-label">Phone</label>
									<div class="col-sm-6">
										<input class="form-control" type="text" name="phone" id="phone" value="{{ old('phone') }}"/>
									</div>
								</div>
								
								<div class="form-group">
									<label class="col-sm-3 control-label">State</label>
									<div class="col-sm-6">
										<select class="form-control" name="state_id" id="state_id">
											<option value="">Select</option>
											
											<?php foreach($states as $state):?>
												<option value="<?php echo $state['id'];?>" <?php if($state['id'] == old('state_id')):?> selected<?php endif;?>><?php echo $state['name'];?></option>
											<?php endforeach;?>
										  </select>
									</div>
								</div>
								
								<div class="form-group">
									<label class="col-sm-3 control-label">City</label>
									<div class="col-sm-6">
										<select class="form-control" name="city_id" id="city_id">
											<option value="">Select</option>
											@foreach($cities as $city)
												<option value="{{$city->id}}" @if($city->id == old('city_id')) selected @endif>{{$city->name}}</option>
											@endforeach
										</select>
									</div>
								</div>
								
								<div class="form-group">
									<label class="col-sm-3 control-label">Status</label>
									<div class="col-sm-6">
										<select class="form-control" name="vendor_status" id="vendor_status">
											<option value="1" @if(1 == old('vendor_status')) selected @endif>Active</option>
											<option value="0" @if(0 == old('vendor_status')) selected @endif>In-Active</option>					
										</select>
									</div>
								</div>
								
								<div class="form-group">
									<div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">						
										<input class="btn btn-success" type="submit" name="submit" id="submit" value="Submit"/>
									</div>
								</div>
								<input type="hidden" name="_token" value="{{csrf_token()}}">
								<input type="hidden" value="" name="id" id="id" />
								<input type="hidden" value="" name="marketing_person_id" id="marketing_person_id_hidden" />
								<input type="hidden" value="" name="marketing_person_name" id="marketing_person_name_hidden" />
								<input type="hidden" value="" name="unique_code" id="unique_code" />
							</form>
						</div>
						
						<div id="usage" class="tab-pane fade">
							<h2>Usage Detail</h2>
							<div class="x_title">
							</div>
							
							<div id="usage_data"></div>
						</div>
						
						<div id="transaction" class="tab-pane fade">
							<h2>Vendor Transaction</h2>
							<div class="x_title">
							</div>
							
							<div id="transactions_data"></div>
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
	var host = "{{ url('admin/local-vendor/get-marketing-user') }}";
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
				renderDetail(res.data.marketing_user);
				renderUsage(res.data.marketing_user.usage_history);
				renderTransactions(res.data.marketing_user.transactions);
				renderPay(res.data.marketing_user);
			}
			else
			{
				$('#view_div').hide();
				alert(res.msg);
			}

		}

	});
});

function renderTransactions(transactions)
{
	if(transactions != 'undefined')
	{
		var html = "<table class='transactions table' cellspacing='0' width='100%'><thead>"
							+ "<tr>"
							+ "<th>Date</th>"
							+ "<th>Paid Amount</th>"
							+ "<th>Cheque No</th>"
							+ "<th>Cheque Release Date</th>"
							+ "<th>Comments</th>"
							+ "</tr></thead><tbody>";
		$.each(transactions, function(index, data) {
			html += "<tr>"
							+ "<td>"+data.created_at+"</td>"
							+ "<td>"+data.paid_amount+"</td>"
							+ "<td>"+data.cheque_no+"</td>"
							+ "<td>"+data.cheque_release_date+"</td>"
							+ "<td>"+data.comments+"</td>"
							+ "</tr>";
		});
		html += "</tbody></table>";
		$('#transactions_data').html(html);
		$('.transactions').dataTable();
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
			var name = '';
			if(usage.user != 'undefined')
				name = usage.user.first_name;
			
			usage_html += "<tr>"
							+ "<td>"+usage.created_at+"</td>"
							+ "<td>"+usage.usage_type+"</td>"
							+ "<td>"+usage.user_id+"</td>"
							+ "<td>"+name+"</td>"
							+ "<td>"+usage.user_points+"</td>"
							+ "<td>"+usage.local_vendor_commission+"</td>"
							+ "</tr>";
		});
		usage_html += "</tbody></table>";
		$('#usage_data').html(usage_html);
		$('.usage_history').dataTable();
	}
}

function renderPay(detail)
{
	var html = "<div class='alert alert-danger'>Account balance is 0. No need to pay.</div>";
	
	if(detail.account_balance > 0)
	{
		html = "<form action={{url('admin/local-vendor/marketing-user-pay')}} name='frmPay' method='post'>"
				+ "<table class='transactions table' cellspacing='0' width='100%'>"
				+ "<tr>"
					+ "<th>Account Balance</th>"
					+ "<td>"+detail.account_balance+"</td>"
				+ "</tr>"
				+ "<tr>"
					+ "<th>Paid Amount</th>"
					+ "<td><input type='text' class='form-control' name='paid_amount' value='"+detail.account_balance+"'></td>"
				+ "</tr>"
				+ "<tr>"
					+ "<th>Check No</th>"
					+ "<td><input type='text' class='form-control' name='cheque_no' value=''></td>"
				+ "</tr>"
				+ "<tr>"
					+ "<th>Check Release Date</th>"
					+ "<td><input type='text' class='form-control' name='cheque_release_date' id='cheque_release_date' value=''></td>"
				+ "</tr>"
				+ "<tr>"
					+ "<th>Comments</th>"
					+ "<td><textarea class='form-control' rows='4' name='comments'></textarea></td>"
				+ "</tr>"
				+ "</table>";
		html += '<div class="form-group">'
					+ '<div class="col-md-8 col-sm-8 col-xs-12 col-md-offset-4">'
						+'<input class="btn btn-success" type="submit" name="submit" id="submit" value="Submit"/>'
					+ '</div>'
				+ '</div>';
		html += '<input type="hidden" name="_token" value="{{csrf_token()}}">'
				+ '<input type="hidden" value="'+detail.id+'" name="id" />';
		html += '</form>';
	}
	
	$('#pay_data').html(html);
	
	if(detail.account_balance > 0)
	{
		$("#cheque_release_date").daterangepicker({
			singleDatePicker: true,
			//minDate:new Date()
		});
	}
}

function renderDetail(detail)
{
	$('#id').val(detail.id);
	$('#unique_code').val(detail.unique_code);
	$('#marketing_person_id_hidden').val(detail.marketing_person_id);
	$('#marketing_person_name_hidden').val(detail.marketing_person_name);
	$('#marketing_person_id').html(detail.marketing_person_id);
	$('#marketing_person_name').html(detail.marketing_person_name);
	$('#shop_name').val(detail.shop_name);
	$('#customer_name').val(detail.customer_name);
	$('#address').val(detail.address);
	$('#phone').val(detail.phone);
	$('#state_id').val(detail.state_id);
	$('#vendor_status').val(detail.vendor_status);
	
	// render cities
	var cities = detail.all_cities;
	$.each(cities, function(index, data) {
		$('#city_id').append('<option value="'+data.id+'">'+data.name+'</option>');
	});
	
	$('#city_id').val(detail.city_id);
}

$(document).on("click", ".delete", deleteRow);
function deleteRow(){ 
	if (confirm("Are you sure want to remove the local vendor from QR code?")) {//. This action will remove all data related to the local vendor from DB
		
		var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
		var id= $(this).data('id'); 
		var host = "{{ url('admin/local-vendor/marketing-user-detach') }}" + '/' + id;
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
					window.location = "{{ url('admin/local-vendor/marketing-users') }}";
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