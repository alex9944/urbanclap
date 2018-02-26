@extends('layouts.adminmain')

@section('head')
<style>
.tab-pane{    padding-top: 30px;}
</style>
@stop

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}" />


   <div class="">
   
    <div class="page-title">
              <div class="title_left">
                <h3>Payment Gateway</h3>
              </div>

    </div>
            <div class="clearfix"></div>           

            <div class="row">

		
					<!-- <div class="col-md-12 col-xs-12">
					 <div class="x_panel">-->
					 
					 <!-- LEFT BAR Start-->
					 <div class="col-md-6 col-xs-12">
						
						@if(Session::get('message')) <div class="alert alert-success" role="alert">{{Session::get('message')}} </div>@endif 
						
						<div class="x_panel">
							<h2>Payment Gateway Lists <span class="pull-right"> <a href="{{url('admin/payment-gateway/subscription')}}" class="btn btn-success btn-xs"><i class="fa fa-plus"></i> Add </a>
							<input type="hidden" name="_token" value="{{csrf_token()}}">
							</span></h2>
							<div class="x_title">
						  </div>
								
   
    
						<table id="datatable-checkbox" class="table table-striped table-bordered bulk_action">
						  <thead>
							<tr>					 
							  <th>Name</th>
							  <th>Status</th>
							  <th>Action</th>                         
							</tr>
						  </thead>
						  <tbody>
						@foreach ($payment_gateway_site as $gateway)
							<tr>
							  <td>{{ $gateway->name }}</td>
							  <td>
								@if($gateway->status)
									Enable
								@else
									Disable
								@endif
							  </td>
								<td>
									@if($gateway->has_settings)
									<a href="javascript:void(0);" class="edit_settings btn btn-primary btn-xs" id="{{ $gateway->id }}" data-name="{{ $gateway->name }}">Settings </a> 
									@endif
									<a href="javascript:void(0);" class="edit_row btn btn-info btn-xs" id="{{ $gateway->id }}">Edit </a> 
									<a href="javascript:void(0);" class="delete_row btn btn-danger btn-xs" id="{{ $gateway->id }}">Delete </a>
								</td>
							</tr> 
						@endforeach
															
						  </tbody>
						</table>
	</form>	  
									</div>
						</div>
					 <!-- LEFT BAR End-->
					
					
					 <!-- Right BAR Start-->
					<div class="col-md-6 col-xs-12">
						<?php
							$url = url('admin/payment-gateway/subscription');
							$add = 'Add';
							if(old('id'))
							{
								$add = 'Update';
							}
							$display = '';
							if(old('payment_gateway_site_id'))
							{
								$display = ' style=display:none;';
							}
						?>						
								  
						@if ($errors->any())
						<div class="alert alert-danger">
							<ul>
								@foreach ($errors->all() as $error)
									<li>{{ $error }}</li>
								@endforeach
							</ul>
						</div>
						@endif
					<div class="x_panel" id="add_div"{{$display}}>
						
						<h2><span id="add_edit_label">{{ $add }}</span> Payment Gateway </h2>
						<div class="x_title">
						</div>
						
						<form id="listForm" method="post" action="{{ $url }}" class="form-horizontal">
							<input id="method" name="_method" type="hidden" value="POST">
							<input type="hidden" value="{{ old('id') }}" name="id" id="id" />
							<input type="hidden" name="_token" value="{{csrf_token()}}">
							
							<div class="form-group">
								<label class="col-sm-3 control-label">Name</label>
								<div class="col-sm-7">
									<input class="form-control" type="text" name="name" id="name" value="{{ old('name') }}"/>
								</div>
							</div>							
							<div class="form-group">
								<label class="col-sm-3 control-label">Has Settings?</label>
								<div class="col-sm-7">
									<input class="" type="radio" name="has_settings" id="has_settings_1" value="1" @if( old('has_settings') == 1 ) checked @endif/> Yes
									<input class="" type="radio" name="has_settings" id="has_settings_0" value="0" @if( old('has_settings') === 0 || old('has_settings') == '' ) checked @endif/> No
								</div>
							</div>						
							<div class="form-group">
								<label class="col-sm-3 control-label">Is Default?</label>
								<div class="col-sm-7">
									<input class="" type="radio" name="is_default" id="is_default_1" value="1" @if( old('is_default') == 1 ) checked @endif/> Yes
									<input class="" type="radio" name="is_default" id="is_default_0" value="0" @if( old('is_default') === 0 || old('is_default') == '' ) checked @endif/> No
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Status</label>
								<div class="col-sm-7">
									<select name="status" id="status"  class="form-control">
										<option value="1" @if( old('status') == 1 || old('status') == '' ) selected @endif>Enable</option>
										<option value="0" @if( old('status') === 0 ) selected @endif>Disable</option>
									</select>
								</div>
							</div>
							
							<button type="submit" class="btn btn-default">Submit</button>
						</form>

						
								  
												  
								  <!-- Add Form End-->
					</div>
					
					<?php
						$url = url('admin/payment-gateway/update-settings');
						$add = 'Update';
						$display = ' style=display:none;';
						if(old('payment_gateway_site_id'))
						{
							$display = '';
						}
					?>	
					
					<div class="x_panel" id="settings_div"{{$display}}>
						
						<h2><span id="settings_label">{{old('gateway_name')}}</span> Settings </h2>
						<div class="x_title">
						</div>
						
						<form id="settingsForm" method="post" action="{{ $url }}" class="form-horizontal">
							<input id="method" name="_method" type="hidden" value="POST">
							<input type="hidden" value="{{ old('setting_id') }}" name="setting_id" id="setting_id" />
							<input type="hidden" value="{{ old('gateway_name') }}" name="gateway_name" id="gateway_name" />
							<input type="hidden" value="{{ old('payment_gateway_site_id') }}" name="payment_gateway_site_id" id="payment_gateway_site_id" />
							<input type="hidden" name="_token" value="{{csrf_token()}}">
							
							<div class="form-group">
								<label class="col-sm-3 control-label">Business email</label>
								<div class="col-sm-7">
									<input class="form-control" type="text" name="business_email" id="business_email" value="{{ old('business_email') }}"/>
								</div>
							</div>							
							<div class="form-group">
								<label class="col-sm-3 control-label">Api key / Account Id</label>
								<div class="col-sm-7">
									<input class="form-control" type="text" name="api_key" id="api_key" value="{{ old('api_key') }}"/>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Secret key</label>
								<div class="col-sm-7">
									<input class="form-control" type="text" name="secret_key" id="secret_key" value="{{ old('secret_key') }}"/>
								</div>
							</div>
							<div class="form-group required">
								<label class="col-sm-3 control-label">Mode (test/live)</label>
								<div class="col-sm-7">
									<input class="form-control" type="text" name="mode" id="mode" value="{{ old('mode') }}"/>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Currency</label>
								<div class="col-sm-7">
									<select name="currency_id" id="currency_id"  class="form-control">
										@foreach($currencies as $currency)
											<option value="{{$currency->id}}" @if( old('currency_id') == $currency->id || (old('currency_id') == '' and $currency->is_default) ) selected @endif>
												{{$currency->country->name . ' / ' . $currency->code}}
											</option>
										@endforeach
									</select>
								</div>
							</div>
							
							<button type="submit" class="btn btn-default">Submit</button>
						</form>

						
								  
												  
								  <!-- Add Form End-->
					</div>
								
								
								
					</div>
					 <!-- Right BAR End-->
					<!--</div>
					 </div>-->
<div class="clearfix"></div>  
			</div>	
	
	
	</div>
<script>
// EDit
$(document).on("click", ".edit_row", edit_blogs);
function edit_blogs(){ 
	$('#add_div').show();
	$('#settings_div').hide();
	
	$('#add_edit_label').html('Edit');
	
	var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
	var id = $(this).attr('id'); 
	
	if(id == '')
	{
		return false;
	}

	var host = "{{ url('admin/payment-gateway/subscription') }}" + '/' + id;
	
	$(".alert-danger").addClass('hidden') ;
	$(".alert-success").addClass('hidden') ;
	$.ajax({
		type: 'GET',
		//data:{'id': id},
		url: host,
		dataType: "json", // data type of response		
		beforeSend: function(){
        $('.image_loader').show();
        },
        complete: function(){
        $('.image_loader').hide();
        },success:renderListform
	
	})
	return false;
}
function renderListform(res)
{ 
	var payment_gateway = res.view_details;
		
	$('#id').val(payment_gateway.id);
	$('#name').val(payment_gateway.name);
	
	if(payment_gateway.has_settings)	
		$('#has_settings_1').prop("checked", true);
	else
		$('#has_settings_0').prop("checked", true);
	
	if(payment_gateway.is_default)	
		$('#is_default_1').prop("checked", true);
	else
		$('#is_default_0').prop("checked", true);
	
	$('#status').val(payment_gateway.status);
}
$(document).on("click", ".delete_row", deleteRow);
function deleteRow(){ 
	if (confirm("Are you sure want to delete the payment gateaway?")) {
		
		var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
		var id= $(this).attr('id'); 
		var host = "{{ url('/admin/payment-gateway/subscription') }}" + '/' + id;
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
					window.location = "{{ url('admin/payment-gateway/subscription') }}";
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

$(document).on("click", ".edit_settings", edit_settings);
function edit_settings(){ 
	$('#add_div').hide();
	$('#settings_div').show();
	
	$("#settingsForm")[0].reset();
	
	var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
	var id = $(this).attr('id'); 
	var gateway_name = $(this).data('name');
	$('#settings_label').html(gateway_name);
	$('#gateway_name').val(gateway_name);
	$('#payment_gateway_site_id').val(id);

	var host = "{{ url('admin/payment-gateway/edit-settings') }}" + '/' + id;
	
	$(".alert-danger").addClass('hidden') ;
	$(".alert-success").addClass('hidden') ;
	$.ajax({
		type: 'GET',
		//data:{'id': id},
		url: host,
		dataType: "json", // data type of response		
		beforeSend: function(){
        $('.image_loader').show();
        },
        complete: function(){
        $('.image_loader').hide();
        },success:renderSettingsform
	
	})
	return false;
}
function renderSettingsform(res)
{ 
	var settings = res.view_details;
	
	if(settings)
	{
		$('#setting_id').val(settings.id);
		$('#payment_gateway_site_id').val(settings.payment_gateway_site_id);
		$('#business_email').val(settings.business_email);
		$('#api_key').val(settings.api_key);
		$('#secret_key').val(settings.secret_key);
		$('#original_price').val(settings.original_price);
		$('#currency_id').val(settings.currency_id);
		$('#mode').val(settings.mode);
	}
}
</script>
@stop