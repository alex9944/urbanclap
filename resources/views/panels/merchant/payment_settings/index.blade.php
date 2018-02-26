@extends('layouts.merchantmain')

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
                <h3>Payment Settings</h3>
              </div>

    </div>
            <div class="clearfix"></div>           

            <div class="row">
					
					 <!-- Right BAR Start-->
					<div class="col-md-8 col-xs-12">
					
						@if(Session::get('message')) <div class="alert alert-success" role="alert">{{Session::get('message')}} </div>@endif 
						
						<?php
							$url = url('merchant/payment-settings/add');
							$add = 'Add';
							$id = $account_holder_name = $account_id = $bank_name = $ifsc_code = '';
							if($payment_settings)
							{
								$add = 'Update';
								$id = $payment_settings->id;
								$account_holder_name = $payment_settings->account_holder_name;
								$account_id = $payment_settings->account_id;
								$bank_name = $payment_settings->bank_name;
								$ifsc_code = $payment_settings->ifsc_code;
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
					<div class="x_panel" id="add_div">
						
						<h2><span id="add_edit_label">{{ $add }}</span> Settings </h2>
						<div class="x_title">
						</div>
						
						<form id="listForm" method="post" action="{{ $url }}" class="form-horizontal">
							<input id="method" name="_method" type="hidden" value="POST">
							<input type="hidden" value="{{ $id }}" name="id" id="id" />
							<input type="hidden" name="_token" value="{{csrf_token()}}">
							
							<div class="form-group required">
								<label class="col-sm-3 control-label">Account holder name</label>
								<div class="col-sm-7">
									<input class="form-control" type="text" name="account_holder_name" id="account_holder_name" value="{{ old('account_holder_name', $account_holder_name) }}"/>
								</div>
							</div>							
							<div class="form-group required">
								<label class="col-sm-3 control-label">Account id</label>
								<div class="col-sm-7">
									<input class="form-control" type="text" name="account_id" id="account_id" value="{{ old('account_id', $account_id) }}"/>
								</div>
							</div>
							<div class="form-group required">
								<label class="col-sm-3 control-label">Bank name</label>
								<div class="col-sm-7">
									<input class="form-control" type="text" name="bank_name" id="bank_name" value="{{ old('bank_name', $bank_name) }}"/>
								</div>
							</div>
							<div class="form-group required">
								<label class="col-sm-3 control-label">IFSC code</label>
								<div class="col-sm-7">
									<input class="form-control" type="text" name="ifsc_code" id="ifsc_code" value="{{ old('ifsc_code', $ifsc_code) }}"/>
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

// EDit Blog
$(document).on("click", ".edit_blog", edit_blogs);
function edit_blogs(){ 
	$('#add_div').show();
	
	var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
	var id = $(this).attr('id'); 
	var settings_id = $(this).data('settings_id');
	
	if(settings_id == '')
	{
		$('#payment_gateway_id').val(id);
		return false;
	}

	var host = "{{ url('merchant/payment-gateway') }}" + '/' + settings_id;
	
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
	var settings = res.view_details;
		
	$('#id').val(settings.id);
	$('#payment_gateway_id').val(settings.payment_gateway_id);
	$('#business_email').val(settings.business_email);
	$('#api_key').val(settings.api_key);
	$('#secret_key').val(settings.secret_key);
	$('#original_price').val(settings.original_price);
	$('#currency_id').val(settings.currency_id);
	$('#mode').val(settings.mode);
	$('#status').val(settings.status);
}
//Change Status Enable
$(document).on("click", ".enable", enable);
function enable(){ 
	var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
	var id= $(this).attr('id'); 
	var settings_id = $(this).data('settings_id');
	var host="{{ url('merchant/payment-gateway/enable') }}";
	$.ajax({
		type: 'POST',
		data:{'id': id, 'settings_id': settings_id,'_token':CSRF_TOKEN},
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
	var settings_id = $(this).data('settings_id');
	var host="{{ url('merchant/payment-gateway/disable') }}";
	$.ajax({
		type: 'POST',
		data:{'id': id, 'settings_id': settings_id, '_token':CSRF_TOKEN},
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