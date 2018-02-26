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
			<h3>Menu Items</h3>
		</div>

	</div>
	<div class="clearfix"></div>           

	<div class="row">

		
					<!-- <div class="col-md-12 col-xs-12">
						<div class="x_panel">-->

							<!-- LEFT BAR Start-->
							<div class="col-md-6 col-xs-12">
								<div class="x_panel">
									<form name="actionForm" action="{{url('admin/online-order/delete-all-menu-items')}}" method="post" onsubmit="return deleteConfirm();"/> 
									<h2>Menu Item Lists <span class="pull-right"><a href="{{url('admin/menu-items')}}" class="btn btn-success btn-xs add_blog"><i class="fa fa-plus"></i> Add </a>  
										<button  class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> Delete All</button>
										<input type="hidden" name="_token" value="{{csrf_token()}}">
									</span></h2>
									<div class="x_title">
									</div>



									<table id="datatable-checkbox" class="table table-striped table-bordered bulk_action">
										<thead>
											<tr><th>
												<input type="checkbox" name="check_all" id="check_all" value=""/></th>						 
												<th>Item</th>
												<th>Menu</th>
												<th>Listing</th>
												<th>Stock</th>
												<th>Status</th>
												<th>Action</th>                         
											</tr>
										</thead>
										<tbody>
											@foreach ($menu_items as $menu_item)
											<tr class="rm{{ $menu_item->id }}">
												<td>
													<input type="checkbox" name="selected_id[]" class="checkbox" value="{{ $menu_item->id }}"/>				 	  
												</td>
												<td>{{ $menu_item->name }}</td>
												<td>{{ isset($menu_item->menu->name) ? $menu_item->menu->name : '' }}</td>
												<td>{{ @$menu_item->listing->title }}</td>
												<td>{{ $menu_item->stock }}</td>
												<td>
													@if($menu_item->status)
													Enable
													@else
													Disable
													@endif
												</td>
												<td>
													<!--<a href="#" class="btn btn-primary btn-xs"><i class="fa fa-folder"></i> View </a>-->
													<a href="javascript:void(0);" class="edit_blog btn btn-info btn-xs" id="{{ $menu_item->id }}" ><i class="fa fa-pencil"></i> Edit </a>
													<a href="javascript:void(0);" class="btn btn-danger btn-xs delete_blog" id="{{ $menu_item->id }}"><i class="fa fa-trash-o"></i> Delete </a></td>
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
								<div class="x_panel" id="add_div" style="">
									<?php
									$url = url('admin/online-order/add-menu-item');
									$add = 'Add';
									if(old('id'))
									{
										$url = url('admin/online-order/update-menu-item');
										$add = 'Edit';
									}
									?>

									<h2><span id="add_edit_label">{{ $add }}</span> Menu Item </h2>
									<div class="x_title">
									</div>
									@if(Session::get('message')) <div class="alert alert-success" role="alert">{{Session::get('message')}} </div>@endif 
									@if(Session::get('error_message')) <div class="alert alert-danger" role="alert">{{Session::get('error_message')}} </div>@endif
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
										<input type="hidden" value="{{ old('id') }}" name="id" id="id" />
										<input type="hidden" name="_token" value="{{csrf_token()}}">
										
										<div class="form-group required">
											<label class="col-sm-3 control-label">Listing</label>
											<div class="col-sm-7">
												<select name="listing_id" id="listing_id"  class="form-control">	
													<option value="">Select</option>
													@foreach($listings as $listing)
														<option value="{{$listing->id}}" @if($listing->id == old('listing_id')) selected @endif>{{$listing->title}}</option>
													@endforeach
												</select>
											</div>
										</div>
										<div class="form-group required">
											<label class="col-sm-3 control-label">Menu</label>
											<div class="col-sm-7">
												<select name="food_menu_id" id="food_menu_id"  class="form-control">	
													<option value="">Select</option>
													@foreach($menus as $menu)
														<option value="{{$menu->id}}" @if($menu->id == old('food_menu_id')) selected @endif>{{$menu->name}}</option>
													@endforeach
												</select>
											</div>
										</div>
										<div class="form-group required">
											<label class="col-sm-3 control-label">Item Type</label>
											<div class="col-sm-7">
												<select name="item_type" id="item_type"  class="form-control">
													<option value="" @if( old('item_type') == '' ) selected @endif>Select</option>
													<option value="veg" @if( old('item_type') == 'veg' ) selected @endif>Veg</option>
													<option value="non-veg" @if( old('item_type') === 'non-veg' ) selected @endif>Non Veg</option>
												</select>
											</div>
										</div>
										<div class="form-group required">
											<label class="col-sm-3 control-label">Item Name</label>
											<div class="col-sm-7">
												<input class="form-control" type="text" name="name" id="name" value="{{ old('name') }}"/>
											</div>
										</div>
										<div class="form-group hidden">
											<label class="col-sm-3 control-label">Description</label>
											<div class="col-sm-7">
												<textarea class="form-control" rows="4" type="text" name="description" id="description"/>{{ old('description') }}</textarea>
											</div>
										</div>							
										<div class="form-group required">
											<label class="col-sm-3 control-label">Original Price</label>
											<div class="col-sm-7">
												<input class="form-control" type="text" name="original_price" id="original_price" value="{{ old('original_price') }}"/>
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-3 control-label">Discounted Price</label>
											<div class="col-sm-7">
												<input class="form-control" type="text" name="discounted_price" id="discounted_price" value="{{ old('discounted_price') }}"/>
											</div>
										</div>
										<div class="form-group required">
											<label class="col-sm-3 control-label">Stock</label>
											<div class="col-sm-7">
												<input class="form-control" type="text" name="stock" id="stock" value="{{ old('stock') }}"/>
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

	var host = "{{ url('admin/online-order/edit-menu-item') }}" + '/' + id;
	var update_url = "{{url('admin/online-order/update-menu-item')}}";
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
	console.log(res);
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

	//$('#merchant_id').val(listing.user_id).change();
/*	if(listing.id)
		$('#listing_id').val(listing.id);*/
	$('#id').val(menu_item.id);
	$('#listing_id').val(menu_item.listing_id);
	$('#food_menu_id').val(menu_item.food_menu_id);
	$('#item_type').val(menu_item.item_type);
	$('#name').val(menu_item.name);
	$('#original_price').val(menu_item.original_price);
	$('#discounted_price').val(menu_item.discounted_price);
	$('#stock').val(menu_item.stock);
	$('#status').val(menu_item.status);
}

$(document).on("click", ".delete_blog", deleteRow);
function deleteRow(){ 
	if (confirm("Are you sure want to delete the menu?")) {
		
		var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
		var id= $(this).attr('id'); 
		var host = "{{ url('/admin/online-order/delete-menu-item') }}" + '/' + id;
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
					location.reload();
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
			alert('Please check atleast one item');
			return false;
		} else {
			if (confirm("Are you sure delete the all selected item?"))
				return true;
			else
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