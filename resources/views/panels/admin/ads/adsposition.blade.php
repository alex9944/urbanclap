@extends('layouts.adminmain')

@section('head')

@stop

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}" />


<div class="">
	<div class="page-title">
		<div class="title_left">
			<h3>Ads Position</h3>
		</div>

	</div>
	<div class="clearfix"></div>           

	<div class="row">

		
					<!-- <div class="col-md-12 col-xs-12">
						<div class="x_panel">-->

							<!-- LEFT BAR Start-->
							<div class="col-md-5 col-xs-12">
								<div class="x_panel">
									<form name="actionForm" action="{{url('admin/ads/position/destroy')}}" method="post" onsubmit="return deleteConfirm();"/> 
									<h2>All Ads Position <span class="pull-right"><a href="{{url('admin/ads/position')}}" class="btn btn-success btn-xs add_blog"><i class="fa fa-plus"></i> Add </a>  
										<button  class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> Delete All</button>
										<input type="hidden" name="_token" value="{{csrf_token()}}">
									</span></h2>
									<div class="x_title">
									</div>

									

									<table id="datatable-checkbox" class="table table-striped table-bordered bulk_action">
										<thead>
											<tr><th>
												<input type="checkbox" name="check_all" id="check_all" value=""/></th>						 
												<th>Title</th>
												<th>Action</th>                         
											</tr>
										</thead>
										<tbody>
											@foreach ($adsposition as $adsposition)
											<tr class="rm{{ $adsposition->id }}">
												<td>
													<input type="checkbox" name="selected_id[]" class="checkbox" value="{{ $adsposition->id }}"/>				 	  
												</td>
												<td>{{ $adsposition->title }}</td>
												<td>
													<!--<a href="#" class="btn btn-primary btn-xs"><i class="fa fa-folder"></i> View </a>-->
													<a href="javascript:void(0);" class="edit_blog btn btn-info btn-xs" id="{{ $adsposition->id }}" ><i class="fa fa-pencil"></i> Edit </a>
													<a href="javascript:void(0);" class="btn btn-danger btn-xs delete_blog" id="{{ $adsposition->id }}"><i class="fa fa-trash-o"></i> Delete </a></td>
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
									<h2>Add Ads Position </h2>
									<div class="x_title">
									</div>
									@if(Session::get('message')) <div class="alert alert-success" role="alert">{{Session::get('message')}} </div>@endif 
									@if(Session::get('error_message')) <div class="alert alert-danger" role="alert">{{Session::get('error_message')}} </div>@endif
						
									<!-- Add Form Start-->
									<form method="POST" action="{{url('admin/ads/position/added')}}" accept-charset="UTF-8" enctype="multipart/form-data" class="form-horizontal">

										<div id="reportArea">

											<div class="form-group">
												<label class="col-sm-4 control-label">Title</label>
												<div class="col-sm-6">
													<input class="form-control" type="text" name="title" id="title" value="{{ old('title') }}" />(Eg: Banner)
													<span class="error">{{ ($errors->has('title')) ? $errors->first('title') : ''}}</span>
												</div>
											</div>	
											<?php /*<div class="form-group">
												<label class="col-sm-2 control-label">Type</label>
												<div class="col-sm-6">
													<select class="form-control" name="type" id="type">
														<option value="">--Choose--</option>
														<option value="banner">BANNER</option>
														<option value="pop">POPS</option>
													</select>
													<span class="error">{{ ($errors->has('type')) ? $errors->first('type') : ''}}</span>
												</div>
											</div>	*/?>
											<div class="form-group">
												<label class="col-sm-4 control-label">Upload Image for desktop</label>
												<div class="col-sm-6">
													<input type="file" name="ad_d_image">
													<span class="error">{{ ($errors->has('ad_d_image')) ? $errors->first('ad_d_image') : ''}}</span>
												</div>
											</div>
											<div class="form-group">
												<label class="col-sm-4 control-label">Upload Image for mobile</label>
												<div class="col-sm-6">
													<input type="file" name="ad_m_image">
													<span class="error">{{ ($errors->has('ad_m_image')) ? $errors->first('ad_m_image') : ''}}</span>
												</div>
											</div>		
											<input type="hidden" name="_token" value="{{csrf_token()}}">
											<div class="ln_solid"></div>
											<div class="form-group">
												<div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">						
													<input class="btn btn-success" type="submit" name="submit" id="submit" value="Submit"/>

												</div>
											</div>



											<div class="clearfix visible-lg"></div>
										</div>
									</form>


									<!-- Add Form End-->




								</div>
								
								
								
								<div class="x_panel" id="edit_div" style=" display:none">
									<h2>Edit Ads Position </h2>
									<div class="x_title">
									</div>					
									<!-- Edit Form Start-->
									<form method="POST" action="{{url('admin/ads/position/updated')}}"  enctype="multipart/form-data" class="form-horizontal">
										<input type="hidden" value="{{old('id')}}" name="id" id="edit_id" />
										<div id="reportArea">

											<div class="form-group">
												<label class="col-sm-4 control-label">Title</label>
												<div class="col-sm-6">
													<input class="form-control" type="text" name="title" id="edit_title" />(Eg: Banner)
													<span class="error">{{ ($errors->has('title')) ? $errors->first('title') : ''}}</span>
												</div>
											</div>	 
											
											<div class="form-group">
												<label class="col-sm-4 control-label">Upload Image for desktop</label>
												<div class="col-sm-4">
													<input type="file" name="edit_d_image">
													<span class="error">{{ ($errors->has('edit_d_image')) ? $errors->first('edit_d_image') : ''}}</span>
												</div>
												<div class="col-md-2">
													<img src="" id="camp_image" style="width:100px;height:100px">
												</div>
											</div>	
											<div class="form-group">
												<label class="col-sm-4 control-label">Upload Image for mobile</label>
												<div class="col-sm-4">
													<input type="file" name="edit_m_image">
													<span class="error">{{ ($errors->has('edit_m_image')) ? $errors->first('edit_m_image') : ''}}</span>
												</div>
												<div class="col-md-2">
													<img src="" id="m_image" style="width:100px;height:100px">
												</div>
											</div>
											<input type="hidden" name="_token" value="{{csrf_token()}}">
											<div class="ln_solid"></div>
											<div class="form-group">
												<div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">						
													<input class="btn btn-success" type="submit" name="submit" id="submit" value="Submit"/>

												</div>
											</div>



											<div class="clearfix visible-lg"></div>
										</div>
									</form>


									<!-- Edit Form End-->




								</div>
								
								
								
								
							</div>
							<!-- Right BAR End-->
					<!--</div>
					</div>-->
					<div class="clearfix"></div>  
				</div>
			</div>
			<script>
// Add Blog
$(document).on("click", ".add_blog", add_blog);
function add_blog(){ 
	var id= $(this).attr('id');  
	$('#edit_div').hide();
	$('#add_div').fadeIn("slow");	
	$(".alert-success").addClass('hidden') ;
	$(".alert-danger").addClass('hidden') ;
	$(".error").addClass('hidden') ;

}

// EDit Blog
$(document).on("click", ".edit_blog", edit_blogs);
function edit_blogs(){ 
	var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
	var id= $(this).attr('id'); 

	var host="{{ url('admin/ads/position/getposition/') }}";
	$('#add_div').hide();
	$('#edit_div').fadeIn("slow");
	
	$(".alert-success").addClass('hidden') ;
	$(".alert-danger").addClass('hidden') ;
	$(".error").addClass('hidden') ;
	$.ajax({
		type: 'POST',
		data:{'id': id,'_token':CSRF_TOKEN},
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
function renderListform(res){ 
	$('#edit_id').val(res.view_details.id);
	$('#edit_title').val(res.view_details.title);
	$('#camp_image').attr("src","{{url('')}}/assets/images/Ad/"+res.view_details.d_image);
	$('#m_image').attr("src","{{url('')}}/assets/images/Ad/"+res.view_details.m_image);
	$("#edit_type option").each(function(){
		var type=$(this).val();
		if(res.view_details.type==type){
			$(this).attr('selected','selected');
		}
	});
}

$(document).on("click", ".delete_blog", deleteblog);
function deleteblog(){ 
	var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
	var id= $(this).attr('id'); 
	var host="{{ url('admin/ads/position/deleted/') }}";
	$.ajax({
		type: 'POST',
		data:{'id': id,'_token':CSRF_TOKEN},
		url: host,
		dataType: "json", // data type of response		
		beforeSend: function(){
			$('.image_loader').show();
		},
		complete: function(){
			$('.image_loader').hide();
		},success:deleteStatus

	})
	return false;
}
function deleteStatus(res){ 
	if (confirm("Are you sure delete position?")) {
		var id=res.delete_details.deletedid;
		$('.rm'+id).hide();
		$(".alert-success").html(res.delete_details.deletedtatus).removeClass('hidden');

	}

	return false;
}
</script>

<script type="text/javascript">
	function deleteConfirm(){
		if($('.checkbox:checked').length == ''){
			alert('Please check atleast one position');
			return false;
		}	
	}
	$(document).ready(function(){
		
		<?php
		if(old('id')):
		?>
		$('#edit_div').show();
		$('#add_div').hide();
		<?php
		endif;
		?>
		
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