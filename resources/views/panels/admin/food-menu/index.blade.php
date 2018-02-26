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
			<h3>Menu Category</h3>
		</div>

	</div>
	<div class="clearfix"></div>           

	<div class="row">

		
					<!-- <div class="col-md-12 col-xs-12">
						<div class="x_panel">-->

							<!-- LEFT BAR Start-->
							<div class="col-md-5 col-xs-12">
								<div class="x_panel">
									<form name="actionForm" action="{{url('admin/food-menu/destroy_all')}}" method="post" onsubmit="return deleteConfirm();"/> 
									<h2>Menu Lists <span class="pull-right"><a href="{{url('admin/food-menu')}}" class="btn btn-success btn-xs add_blog"><i class="fa fa-plus"></i> Add </a>  
										<button  class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> Delete All</button>
										<input type="hidden" name="_token" value="{{csrf_token()}}">
									</span></h2>
									<div class="x_title">
									</div>



									<table id="datatable-checkbox" class="table table-striped table-bordered bulk_action">
										<thead>
											<tr><th>
												<input type="checkbox" name="check_all" id="check_all" value=""/></th>						 
												<th>Menu Name</th>
												<th>Status</th>
												<th>Action</th>                         
											</tr>
										</thead>
										<tbody>
											@foreach ($food_menus as $food_menu)
											<tr class="rm{{ $food_menu->id }}">
												<td>
													<input type="checkbox" name="selected_id[]" class="checkbox" value="{{ $food_menu->id }}"/>				 	  
												</td>
												<td>{{ $food_menu->name }}</td>
												<td>
													@if($food_menu->status)
													Enable
													@else
													Disable
													@endif
												</td>
												<td>
													<!--<a href="#" class="btn btn-primary btn-xs"><i class="fa fa-folder"></i> View </a>-->
													<a href="javascript:void(0);" class="edit_blog btn btn-info btn-xs" id="{{ $food_menu->id }}" ><i class="fa fa-pencil"></i> Edit </a>
													<a href="javascript:void(0);" class="btn btn-danger btn-xs delete_blog" id="{{ $food_menu->id }}"><i class="fa fa-trash-o"></i> Delete </a></td>
												</tr> 
												@endforeach

											</tbody>
										</table>
									</form>	  
								</div>
							</div>
							<!-- LEFT BAR End-->


							<!-- Right BAR Start-->
							<div class="col-md-7 col-xs-12">
								<div class="x_panel" id="add_div" style="">
									<?php
									$url = url('admin/food-menu/add');
									$add = 'Add';
									if(old('id'))
									{
										$url = url('admin/food-menu/update');
										$add = 'Edit';
									}
									?>

									<h2><span id="add_edit_label">{{ $add }}</span> Menu </h2>
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
										<input type="hidden" value="{{ old('id') }}" name="id" id="id" />
										<input type="hidden" name="_token" value="{{csrf_token()}}">
										
										<div class="form-group required">
											<label class="col-sm-3 control-label">Name</label>
											<div class="col-sm-7">
												<input class="form-control" type="text" name="name" id="name" value="{{ old('name') }}"/>
											</div>
										</div>
										<div class="form-group hidden">
											<label class="col-sm-3 control-label">Image</label>
											<div class="col-sm-7">
												<input class="form-control" type="file" name="image_name" id="image_name"  />
											</div>
											<div class="col-sm-2"><img src="" id="edit_photo" style="height:40px"></div>
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

	var host = "{{ url('admin/food-menu') }}" + '/' + id + '/edit';
	var update_url = "{{url('admin/food-menu/update')}}";
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
	var food_menu = res.view_details;
	
	$('#id').val(food_menu.id);
	$('#name').val(food_menu.name);
	$('#status').val(food_menu.status);
}

$(document).on("click", ".delete_blog", deleteRow);
function deleteRow(){ 
	if (confirm("Are you sure want to delete the menu?")) {
		
		var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
		var id= $(this).attr('id'); 
		var host = "{{ url('/admin/food-menu') }}" + '/' + id;
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
					window.location = "{{ url('admin/food-menu') }}";
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
			alert('Please check atleast one category');
			return false;
		} else {
			if (confirm("Are you sure delete the all selected categories?"))
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