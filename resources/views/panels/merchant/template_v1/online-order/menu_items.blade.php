@extends('layouts.'.$merchant_layout)

@section('head')
	<link rel="stylesheet" type="text/css" href="{{ asset($merchant_layout . '/css/outsorce.css') }}">
@stop
<style>
.error-ul{list-style:none;line-height:25px;}
</style>
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}" />

@if(Session::get('message')) <div class="alert alert-success" role="alert">{{Session::get('message')}} </div>@endif 
@if(Session::get('error_message')) <div class="alert alert-danger" role="alert">{{Session::get('error_message')}} </div>@endif 

@if ($errors->any())
<div class="alert alert-danger">
	<ul class="error-ul">
		@foreach ($errors->all() as $error)
		<li>{{ $error }}</li>
		@endforeach
	</ul>
</div>
@endif

<div class="services_app__block">
	<ul class="nav nav-pills">
		<li class="active"><a>Food</a></li>
		<li><a href="{{ url('merchant/table-booking/settings') }}">Table</a></li>
	</ul>
	
	<div class="tab-content clearfix">            
		<div class="tab-pane active" id="shop">
			@if($service_disable)
			<h3 class="appmt_title">Click to Activate</h3>
			<div class="shop_block">
				<div class="divbox">
					<a href="{{ url('merchant/online-order/enable-service') }}" class="ui-link">
						<span class="notify-badge " style="display: none;"><i class="fa fa-check" aria-hidden="true"></i></span>
						<div class="wrapperbox deactive">
							<div class="pay-content appmt_span">
								<div><i class="fa fa-calendar" aria-hidden="true"></i>
									<span class="appmt_inf"><i class="fa fa-info-circle" aria-hidden="true"></i></span>
								</div>
								<span>Food</span>
							</div>
						</div>
					</a>
				</div>
			</div>
			@else
			<h3 class="appmt_title">Click to Deactivate</h3>
			<div class="appmt_block">
				<div class="divbox">
					<a href="{{ url('merchant/online-order/disable-service') }}" class="ui-link active">
						<span class="notify-badge " style="display: none;"><i class="fa fa-check" aria-hidden="true"></i></span>
						<div class="wrapperbox">
							<div class="pay-content appmt_span">
								<div><i class="fa fa-calendar" aria-hidden="true"></i>
									<span class="appmt_inf"><i class="fa fa-info-circle" aria-hidden="true"></i></span>
								</div>
								<span>Food</span>
							</div>
						</div>
					</a>
				</div>
			</div>
			<?php
							$url = url('merchant/online-order/add-menu-item');										
							$edit_url = url('merchant/online-order/update-menu-item');
							$delevery_url = url('merchant/online-order/add-delevery-fee');
							
						?>
			<div class="container">
				<form id="listForm" method="post" action="{{ $url }}" >
				<input type="hidden" value="{{ $listing->id }}" name="listing_id" id="listing_id" />
				<input type="hidden" name="_token" value="{{csrf_token()}}">
				<input type="hidden" name="status" value="1">				
					<div class="col-sm-6">
						<div class="form-group">
							<label for="email">Menu:<span class="red">*</span></label>
							<select name="food_menu_id" id="food_menu_id"  class="form-control">	
										<option value="">Select</option>
										@foreach ($menus as $menu)
											<option value="{{ $menu->id }}" @if( old('food_menu_id') == $menu->id ) selected @endif>
												{{ $menu->name }}
											</option>
										@endforeach
									</select>
							
						</div>
						<div class="form-group mt">
							<label for="email">Item Name:<span class="red">*</span></label>
							<input class="form-control" type="text" name="name" id="name" value="{{ old('name') }}"/>
							
						</div>
					</div>
					<div class="col-sm-6">
						<div class="veg_non_veg paymentsel">
						
									<ul>
								<li>
									<div class="divbox">
										<input type="radio" name="item_type" value="veg"> Veg
									</div>
								</li>
								<li>
									<div class="divbox">
										<input type="radio" name="item_type" value="non-veg">Non-Veg
									</div>
								</li>
							</ul>
							<!--<ul>
								<li>
									<div class="divbox">
										<a href="#" class="ui-link active">
											<span class="notify-badge " style="display: none;"><i class="fa fa-check" aria-hidden="true"></i></span>
											<div class="wrapperbox veg_box">
												<div class="  veg_content">
													<span>Veg</span>
												</div>
											</div>
										</a>
									</div>
								</li>
								<li>
									<div class="divbox">
										<a href="#" class="ui-link">
											<span class="notify-badge " style="display: none;"><i class="fa fa-check" aria-hidden="true"></i></span>
											<div class="wrapperbox non_veg_box">
												<div class="  non_veg_content">
													<span>Non-Veg</span>
												</div>
											</div>
										</a>
									</div>
								</li>
							</ul>-->
						</div>
						<div class="clearfix"></div>
						<!-- orignal, discount, stock -->
						<div class="orginal_price">
							<form>
								<ul>
									<li>
										<div class="form-group">
											<label for="email">Original Price:<span class="red">*</span></label>
											<input class="form-control" type="text" name="original_price" id="original_price" value="{{ old('original_price') }}"/>
										
										</div>
									</li>
									<li>
										<div class="form-group">
											<label for="email">Discount Price:</label>											
											<input class="form-control" type="text" name="discounted_price" id="discounted_price" value="{{ old('discounted_price') }}"/>
										</div>
									</li>
									<li>
										<div class="form-group">
											<label for="email">Stock:<span class="red">*</span></label>
											<div class="input-group">
												<span class="input-group-btn">
		<button type="button" class="btn btn-stock" data-type="minus" data-field="quant[2]">
		<span class="glyphicon glyphicon-plus"></span>
												</button>
												</span>
												<input class="form-control input-number" type="text" name="stock" id="stock" value="{{ old('stock') }}"/>											
												<span class="input-group-btn">
		<button type="button" class="btn btn-stock" data-type="plus" data-field="quant[2]">
		  <span class="glyphicon glyphicon-minus"></span>
												</button>
												</span>
											</div>
										</div>
									</li>
						
						</div>
					</div>
					<div class="clearfix"></div>
					<?php /*<img src="images/apoyou.png" class="img-responsive" />*/?>
					<div class="clearfix"></div>
					<!-- add button-->
					<div class="text-center">
						<button type="submit" class="btn btn-primary btn-add">Submit</button> 
					</div>
					</form> 
					
					<form id="listForm" method="post" action="{{ $delevery_url }}" >
					<div class="col-sm-12" style="margin-bottom:20px;">
					<div class="col-sm-9"></div>
					<div class="col-sm-3">
					<p class="text-right delivery_fee">Delivery Fee</p>
					<div class="col-sm-6"><input type="text" name="delivery_fee" class="form-control input-number" value="@if(isset($orderonlinesettings->delivery_fee)){{$orderonlinesettings->delivery_fee}}@endif"></div><div class="col-sm-6"><input type="hidden" name="_token" value="{{csrf_token()}}"><button type="submit" class="btn btn-primary btn-add" style="width:100%">Save</button> </div>
					</div>
					</div>
					</form> 
					<div class="clearfix"></div>
					<div class="">
						<table class="table table-bordered table" id="table">
							<thead>
															<tr>
								
									<th>Item</th>
									<th>Menu</th>
									<th>Original Price</th>
									<th>Discount Price</th>
									<th>Stock</th>
									<th>Status</th>
								<th>Action</th>
								</tr>
							</thead>
							<tbody>
							@foreach ($menu_items as $menu_item)
								<tr>
								<!--	<td>
										<div class="checkbox">
											<label>
												<input type="checkbox" value="">
											</label>
										</div>
									</td>-->
									<td>{{ $menu_item->name }}</td>
									<td>{{ $menu_item->menu->name }}</td>
									<td>{{ $menu_item->original_price }}</td>
									<td>{{ $menu_item->discounted_price }}</td>
									<td>{{ $menu_item->stock }}</td>
									<td>
										<label class="switch">
											<input type="checkbox" id="{{ $menu_item->id }}" class="edit_status" @if($menu_item->status) checked @else @endif >
											<span class="slider round"></span>
										</label>
									</td>
									<td>
									  <a href="javascript:void(0);" class="edit_blog custom_pencil" id="{{ $menu_item->id }}" ><i class="fa fa-pencil" aria-hidden="true"></i></a>
                            <a href="javascript:void(0);" class="btn-xs delete_blog custom_pencil" id="{{ $menu_item->id }}"><i class="fa fa-trash-o custom_trash" aria-hidden="true"></i></a>
							
							
									
									</td>
								</tr>
							@endforeach
								
							</tbody>
						</table>
					</div>
					<!--<div class="text-center">
						<button class="btn btn-primary btn-add">Save</button>
					</div>-->
			</div>
			@endif
		</div>            
	</div>
</div>

@if(!$service_disable)
<!--Edit Model Box Start-->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Modal title</h4>
            </div>
            <div class="modal-body">
								<form id="listForm" method="post" action="{{ $edit_url }}" >
				<input type="hidden" value="" name="id" id="edit_id" />
				<input type="hidden" name="_token" value="{{csrf_token()}}">
						
					<div class="col-sm-4">
						<div class="form-group">
							<label for="email">Menu:<span class="red">*</span></label>
							<select name="food_menu_id" id="edit_food_menu_id"  class="form-control">	
										<option value="">Select</option>
										@foreach ($menus as $menu)
											<option value="{{ $menu->id }}" @if( old('food_menu_id') == $menu->id ) selected @endif>
												{{ $menu->name }}
											</option>
										@endforeach
									</select>
							
						</div>
						<div class="form-group mt">
							<label for="email">Item Name:<span class="red">*</span></label>
							<input class="form-control" type="text" name="name" id="edit_name" value="{{ old('name') }}"/>
							
						</div>
					</div>
					<div class="col-sm-8">
						<div class="veg_non_veg paymentsel">
						
									<ul id="rad">
								<li>
									<div class="divbox">
										<input type="radio" name="item_type" value="veg"> Veg
									</div>
								</li>
								<li>
									<div class="divbox">
										<input type="radio" name="item_type" value="non-veg">Non-Veg
									</div>
								</li>
							</ul>
							
						</div>
						<div class="clearfix"></div>
						<!-- orignal, discount, stock -->
						<div class="orginal_price" style="margin-top: 5%;">
							<form>
								<ul>
									<li>
										<div class="form-group">
											<label for="email">Original Price:<span class="red">*</span></label>
											<input class="form-control" type="text" name="original_price" id="edit_original_price" value="{{ old('original_price') }}"/>
										
										</div>
									</li>
									<li>
										<div class="form-group">
											<label for="email">Discount Price:</label>											
											<input class="form-control" type="text" name="discounted_price" id="edit_discounted_price" value="{{ old('discounted_price') }}"/>
										</div>
									</li>
									<li>
										<div class="form-group">
											<label for="email">Stock:<span class="red">*</span></label>
											<div class="input-group">
												
												<input class="form-control input-number" type="text" name="stock" id="edit_stock" value="{{ old('stock') }}"/>											
											
											</div>
										</div>
									</li>
						
						</div>
					</div>
					<div class="clearfix"></div>				
				
					<!-- add button-->
					<div class="text-center">
						<button type="submit" class="btn btn-primary btn-add" style="width: 18%;">Submit</button> 
					</div>
					</form>
            </div>
           <!-- <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>-->
        </div>
    </div>
</div>
@endif
<!--Edit Model Box End-->
@stop

@section('footer')
 <script> 
  $(document).ready(function() {
    var table = $('#table').DataTable( {
        responsive: true
    } );
 
    new $.fn.dataTable.FixedHeader( table );
} );
    </script>
<script>
//Status Change

 $(document).on("click", ".edit_status", edit_status);
function edit_status(){ 
	var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
	var id= $(this).attr('id'); 
	var host = "{{ url('merchant/online-order/status-menu-item') }}" + '/' + id;
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
        },success:renderstatus
	
	})
	//return true;
}
function renderstatus(res)
{ 
	var status = res.view_details.status;
	var id = res.view_details.id;
	
}
// EDit 
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
		
	$('#edit_id').val(menu_item.id);
	$('#edit_food_menu_id').val(menu_item.food_menu_id);
	//$('#edit_item_type').val(menu_item.item_type);
	$('#rad input[value='+menu_item.item_type+']').filter(':radio').prop('checked',true);
	$('#edit_name').val(menu_item.name);
	$('#edit_original_price').val(menu_item.original_price);
	$('#edit_discounted_price').val(menu_item.discounted_price);
	$('#edit_stock').val(menu_item.stock);
	$('#edit_status').val(menu_item.status);
	$('#myModal').modal('show');
}

$(document).on("click", ".delete_blog", deleteRow);
function deleteRow(){ 
	if (confirm("Are you sure want to delete the menu?")) {
		
		var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
		var id= $(this).attr('id'); 
		var host = "{{ url('/merchant/online-order/delete-menu-item') }}" + '/' + id;
		$.ajax({
			type: 'get',
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