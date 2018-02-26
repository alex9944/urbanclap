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
			<h3>QR Code</h3>
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

					<form name="actionForm" action="{{url('admin/local-vendor/marketing-users/destroy')}}" method="post" onsubmit="return deleteConfirm();"/> 
						<input type="hidden" name="_token" value="{{csrf_token()}}">
						
						<h2> &nbsp;
							<span class="pull-left">
								<input type="text" name="input_no" id="input_no" value="1">
								<a href="javascript:;" class="btn btn-success btn-xs add_qr">Generate QR Code </a>
							</span>
							<span class="pull-right">								
								<button  class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> Delete All</button>							
							</span>
						</h2>
						<div class="x_title"></div>

						<table id="datatable-checkbox" class="table table-striped table-bordered bulk_action">
							<thead>
								<tr>
									<th><input type="checkbox" name="check_all" id="check_all" value=""/></th>
									<th>S.No</th>
									<th>Unique Code</th>
									<th>Action</th>                
								</tr>
							</thead>
							<tbody>
								@foreach ($qr_codes as $i => $qr_code)
								<tr class="rm{{ $qr_code->id }}">
									<td><input type="checkbox" name="selected_id[]" class="checkbox" value="{{ $qr_code->id }}"/></td>
									<td>{{++$i}}</td>
									<td>{{$qr_code->unique_code}}
									<td>
										<a href="javascript:;" class="delete btn btn-danger btn-xs" id="{{ $qr_code->id }}" >Delete</a>
										@if($qr_code->is_used)
											<a href="javascript:;" data-id="{{ $qr_code->id }}" class="used btn btn-success btn-xs" id="is_used_{{ $qr_code->id }}" >Used</a>
										@else
											<a href="javascript:;" data-id="{{ $qr_code->id }}" class="not_used btn btn-primary btn-xs" id="is_used_{{ $qr_code->id }}" >Not Used</a>
										@endif
										<a href="javascript:;" class="print btn btn-default btn-xs" data-type="landscape" data-id="{{ $qr_code->id }}" >Landscape Print</a>
                                        
										<a href="javascript:;" class="print btn btn-default btn-xs" data-type="portrait" data-id="{{ $qr_code->id }}" >Portrait Print</a>
										<div style="display:none;" id="change_status_{{ $qr_code->id }}" class="statuscls alert alert-success"></div>
									</td>
								</tr>
								@endforeach

							</tbody>
						</table>
					</form>
					
					<form name="actionCodeForm" id="actionCodeForm" action="{{url('admin/local-vendor/qr-code-create')}}" method="post"/> 
						<input type="hidden" name="_token" value="{{csrf_token()}}">
						<input type="hidden" name="no_of_code_to_generate" id="no_of_code_to_generate">
					</form>
					
				</div>
			</div>



			<div class="clearfix"></div>  

		</div>
	</div>
	
	<?php /*<div id="divToPrint"></div>*/?>
</div>

<script>
$(document).on("click", ".print", function() {
	var id= $(this).data('id');
	var type= $(this).data('type');
	
	var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
	var host = "{{ url('admin/local-vendor/qr-code-print') }}";
	$.ajax({
		type: 'POST',
		data:{id:id, type:type, '_token':CSRF_TOKEN},
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
				//$('#divToPrint').html(res.data);
				
				var contentToPrint = res.data;
				var popupWin = window.open('', '_blank', 'width=800,height=600');
				popupWin.document.open();
				popupWin.document.write('<html><body onload="window.print()">' + contentToPrint + '</html>');
				popupWin.document.close();
			}
			else
			{
				alert(res.msg);
			}

		}

	});
});
$(document).on("click", ".add_qr", function() {
	if($('#input_no').val() == ''){
		alert('Please enter how many codes to be created');
		return false;
	}
	$('#no_of_code_to_generate').val($('#input_no').val());
	$('#actionCodeForm').submit();	
});

$(document).on("click", ".not_used", function() {
	var id= $(this).data('id');
	change_status('not_used', id);
});
$(document).on("click", ".used", function() {
	var id= $(this).data('id');
	change_status('used', id);
});
function change_status(status, id)
{
	var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
	var host = "{{ url('admin/local-vendor/qr-code-status-change') }}";
	$.ajax({
		type: 'POST',
		data:{status:status, id:id, '_token':CSRF_TOKEN},
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
				$('#change_status_'+id).html(res.msg).show();
				if(status == 'used') {
					$('#is_used_'+id).removeClass('used').removeClass('btn-success').addClass('btn-primary').addClass('not_used').html('Not Used');
				} else {
					$('#is_used_'+id).removeClass('not_used').removeClass('btn-primary').addClass('btn-success').addClass('used').html('Used');
				}
				$('#change_status_'+id).delay(5000).fadeOut(400);
			}
			else
			{
				alert(res.msg);
			}

		}

	});
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