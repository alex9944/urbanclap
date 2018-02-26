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
                <h3>Settings</h3>
              </div>

    </div>
            <div class="clearfix"></div>           

            <div class="row">
					
					
					 <!-- Right BAR Start-->
					<div class="col-md-8 col-xs-12">
					<div class="x_panel" id="add_div" style="">
						<?php
							$url = url('merchant/online-shop/settings');
							$add = 'Update';
						?>
						
						<h2><span id="add_edit_label">{{ $add }}</span> Settings </h2>
						<div class="x_title">
						</div>
						@if(Session::get('message')) <div class="alert alert-success" role="alert">{{Session::get('message')}} </div>@endif 
						<div class="alert alert-success hidden"></div>
								  
						@if ($errors->any())
						<div class="alert alert-danger">
							<ul>
								@foreach ($errors->all() as $error)
									<li>{{ $error }}</li>
								@endforeach
							</ul>
						</div>
						@endif
						<form id="listForm" method="post" action="{{ $url }}" class="form-horizontal">
							<input id="method" name="_method" type="hidden" value="POST">
							<input type="hidden" value="{{ old('id', @$setting->id) }}" name="id" id="id" />
							<input type="hidden" name="_token" value="{{csrf_token()}}">
							
							<div class="form-group required">
								<label class="col-sm-5 control-label">Delivery Fee</label>
								<div class="col-sm-7">
									<input class="form-control" type="text" name="delivery_fee" id="delivery_fee" value="{{ old('delivery_fee', @$setting->delivery_fee) }}"/>
								</div>
							</div>
							<?php /*
							<div class="form-group required">
								<label class="col-sm-5 control-label">Estimated Delivery Time</label>
								<div class="col-sm-7">
									<input class="form-control" type="text" name="estimated_delivery_time" id="estimated_delivery_time" value="{{ old('estimated_delivery_time', @$setting->estimated_delivery_time) }}"/> [ hr(s) ]
								</div>
							</div>
							*/?>
							
							<button type="submit" class="text-center btn btn-default">Submit</button>
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
	var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
	var id= $(this).attr('id'); 

	var host = "{{ url('merchant/online-order/edit-menu-item') }}" + '/' + id;
	var update_url = "{{url('merchant/online-order/update-menu-item')}}";
	$('#listForm').attr('action', update_url);
	$('#add_edit_label').html('Edit');
	
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
	var listing = res.view_details.listing;
	var menu_item = res.view_details.menu_item;
	
	// append listing
	/*if(listing != null)
	{
		$('#listing_id').append($('<option>', {
			value: listing.id,
			text: listing.title
		}));
	}*/
	$('#listing_id').val('');
	if(menu_item.listing_id)
		$('#listing_id').val(menu_item.listing_id);
		
	$('#id').val(menu_item.id);
	$('#food_menu_merchant_id').val(menu_item.food_menu_merchant_id);
	$('#item_type').val(menu_item.item_type);
	$('#name').val(menu_item.name);
	$('#original_price').val(menu_item.original_price);
	$('#discounted_price').val(menu_item.discounted_price);
	$('#status').val(menu_item.status);
}

$(document).on("click", ".delete_blog", deleteRow);
function deleteRow(){ 
	if (confirm("Are you sure want to delete the menu?")) {
		
		var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
		var id= $(this).attr('id'); 
		var host = "{{ url('/merchant/online-order/delete-menu-item') }}" + '/' + id;
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
					window.location = "{{ url('merchant/online-order/menu-items') }}";
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
		alert('Please check atleast one blog');
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