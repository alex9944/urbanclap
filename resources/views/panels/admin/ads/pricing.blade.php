@extends('layouts.adminmain')

@section('head')

@stop

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}" />


<div class="">
	<div class="page-title">
		<div class="title_left">
			<h3>Pricing</h3>
		</div>

	</div>
	<div class="clearfix"></div>           

	<div class="row">

		
					<!-- <div class="col-md-12 col-xs-12">
						<div class="x_panel">-->

							<!-- LEFT BAR Start-->
							<div class="col-md-5 col-xs-12">
								<div class="x_panel">
									<form name="actionForm" action="{{url('admin/ads/pricing/destroy')}}" method="post" onsubmit="return deleteConfirm();"/> 
									<h2>All Pricing <span class="pull-right"><a href="#" class="btn btn-success btn-xs add_blog"><i class="fa fa-plus"></i> Add </a>  
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
											@foreach ($pricing as $pricing)
											<tr class="rm{{ $pricing->pid }}">
												<td>
													<input type="checkbox" name="selected_id[]" class="checkbox" value="{{ $pricing->pid }}"/>				 	  
												</td>
												<td>{{ $pricing->amd_title }} / {{ $pricing->aptitle }}</td>
												<td>
													<!--<a href="#" class="btn btn-primary btn-xs"><i class="fa fa-folder"></i> View </a>-->
													<a href="javascript:void(0);" class="edit_blog btn btn-info btn-xs" id="{{ $pricing->pid }}" ><i class="fa fa-pencil"></i> Edit </a>
													<a href="javascript:void(0);" class="btn btn-danger btn-xs delete_blog" id="{{ $pricing->pid }}"><i class="fa fa-trash-o"></i> Delete </a></td>
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
								<?php
								$id = '';
								$add = 'Add';
								$url = url('admin/ads/pricing/added');
								if(old('id') != '')
								{
									$id = old('id');
									$add = 'Edit';
									$url = url('admin/ads/pricing/updated');
								}
								?>
								
								<div class="x_panel">
									<h2><span id="add_div_label">{{$add}}</span> Pricing </h2>
									<div class="x_title">
									</div>
									
									@if(Session::get('message')) <div class="alert alert-success" role="alert">{{Session::get('message')}} </div>@endif
									@if(Session::get('error_message')) <div class="alert alert-danger" role="alert">{{Session::get('error_message')}} </div>@endif
									@if ($errors->any())
										<div class="alert alert-danger">
											<ul>
												@foreach ($errors->all() as $error)
													<li>{{ $error }}</li>
												@endforeach
											</ul>
										</div>
									@endif
									
									<!-- Add Form Start-->
									<form method="POST" action="{{$url}}" accept-charset="UTF-8" enctype="multipart/form-data" class="form-horizontal">

										<div id="reportArea">

											<!--<div class="form-group">
												<label class="col-sm-3 control-label">Title</label>
												<div class="col-sm-6">
													<input class="form-control" type="text" name="title" id="title" value="{{ old('title') }}" />
													<span class="error">{{ ($errors->has('title')) ? $errors->first('title') : ''}}</span>
												</div>
											</div> -->

											<div class="form-group">
												<label class="col-sm-3 control-label"> State</label>
												<div class="col-sm-6">
													<select name="state"   class="form-control states">
														<option value="0" @if(old('state') == 0) selected @endif>All</option>
														@foreach($states as $state)
														<option value="{{$state->id}}" @if(old('state') == $state->id) selected @endif>{{$state->name}}</option>
														@endforeach
													</select>									 

													<span class="error">{{ ($errors->has('state')) ? $errors->first('state') : ''}}</span>
												</div>
											</div> 
											<div class="form-group">
												<label class="col-sm-3 control-label"> City</label>
												<div class="col-sm-6">
													<select name="city" id="cities"  class="form-control cities"> 
														<option value="0" @if(old('state') == 0) selected @endif>All</option>
													</select>									 

													<span class="error">{{ ($errors->has('city')) ? $errors->first('city') : ''}}</span>
												</div>
											</div> 
											<div class="form-group">
												<label class="col-sm-3 control-label"> Zone</label>
												<div class="col-sm-6">
													<select name="zone" id="zone"  class="form-control zone"> 
														<option value="">--Choose--</option>
														<option value="east" @if(old('zone') == "east") selected @endif>East</option>
														<option value="west" @if(old('zone') == "west") selected @endif>West</option>
														<option value="north" @if(old('zone') == "north") selected @endif>North</option>
														<option value="south" @if(old('zone') == "south") selected @endif>South</option>

													</select>                                    

													<span class="error">{{ ($errors->has('zone')) ? $errors->first('zone') : ''}}</span>
												</div>
											</div> 
											<div class="form-group">
												<label class="col-sm-3 control-label"> Category</label>
												<div class="col-sm-6">
													<select name="category" id="category"  class="form-control category">				  
														<option value="">--Choose--</option>
														@foreach($Category as $Category)
														<option value="{{$Category->c_id}}">{{$Category->c_title}}</option>
														@endforeach
													</select>	
													<span class="error">{{ ($errors->has('category')) ? $errors->first('category') : ''}}</span>
												</div>
											</div> 
											
											<input type="hidden" name="modeads" value="{{$ads_mode_advertisement_id}}">
											
											<div class="form-group">
												<label class="col-sm-3 control-label"> Ads position</label>
												<div class="col-sm-6">
													<select name="adsposition" id="adsposition"  class="form-control">				  
														@foreach ($adsposition as $adsposition)
														<option value="{{ $adsposition->id }}">
															{{ $adsposition->title }}
														</option>
														@endforeach
													</select>									 

													<span class="error">{{ ($errors->has('adsposition')) ? $errors->first('adsposition') : ''}}</span>
												</div>
											</div> 
											<div class="form-group">
												<label class="col-sm-3 control-label"> Slot Placement</label>
												<div class="col-sm-6">
													<select name="slotplacement" id="slotplacement"  class="form-control">				  
														@foreach ($slotplacement as $slotplacement)
														<option value="{{ $slotplacement->id }}">
															{{ $slotplacement->title }}
														</option>
														@endforeach
													</select>									 

													<span class="error">{{ ($errors->has('slotplacement')) ? $errors->first('slotplacement') : ''}}</span>
												</div>
											</div> 
											<div class="form-group">
												<label class="col-sm-3 control-label"> Duration</label>
												<div class="col-sm-6">
													<select name="duration" id="duration"  class="form-control">				  
														@foreach ($duration as $duration)
														<option value="{{ $duration->id }}">
															{{ $duration->duration_time }} @if($duration->days_month=='1') Days @else Month @endif
														</option>
														@endforeach
													</select>									 

													<span class="error">{{ ($errors->has('duration')) ? $errors->first('duration') : ''}}</span>
												</div>
											</div>
											<div class="form-group">
												<label class="col-sm-3 control-label">Price</label>
												<div class="col-sm-6">
													<input class="form-control" type="text" name="price" id="price" value="{{ old('price') }}" />
													<span class="error">{{ ($errors->has('price')) ? $errors->first('price') : ''}}</span>
												</div>
											</div>	
											<div class="form-group">
												<label class="col-sm-3 control-label">Mode</label>
												<div class="col-sm-6">
													<select name="mode" class="form-control">
														<option value="">--Choose--</option>
														<option value="desktop">Desktop</option>
														<option value="mobile">Mobile</option>
													</select>
													<span class="error">{{ ($errors->has('mode')) ? $errors->first('mode') : ''}}</span>
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
	$(".editpro .alert-danger").addClass('hidden') ;
	$(".editpro .alert-success").addClass('hidden') ;

}

// EDit Blog
$(document).on("click", ".edit_blog", edit_blogs);
function edit_blogs(){ 
	var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
	var id= $(this).attr('id'); 

	var host="{{ url('admin/ads/pricing/getpricing/') }}";
	$('#add_div').hide();
	$('#edit_div').fadeIn("slow");
	
	$(".alert-danger").addClass('hidden') ;
	$(".alert-success").addClass('hidden') ;
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
	$('#edit_modeads').val(res.view_details.m_a_id);
	$('#edit_adsposition').val(res.view_details.a_p_id);
	$('#edit_slotplacement').val(res.view_details.a_s_p_id);
	$('#edit_duration').val(res.view_details.d_id);
	$('#edit_price').val(res.view_details.price);
	$('.states').html('');
	$('.states').append('<option>--Choose--</option>');

	$.each(res.states, function(index, data) {
		if(res.view_details.state_id==data.id){
			$('.states').append('<option value="'+data.id+'" selected="selected">'+data.name+'</option>');
			$('.states').change();
		}
		else{
			$('.states').append('<option value="'+data.id+'">'+data.name+'</option>');
		}

    }); 
	$('.category').html('');
	$('.category').append('<option>--Choose--</option>');
	$.each(res.Category, function(index, data) {
		if(res.view_details.cat_id==data.c_id){
			$('.category').append('<option value="'+data.c_id+'" selected="selected">'+data.c_title+'</option>');
			$('.category').change();
		}else {
			$('.category').append('<option value="'+data.c_id+'">'+data.c_title+'</option>');
		}
	});  

	$("#mode_val option").each(function(){
		var mode_value=$(this).val();
		if(res.view_details.mode==mode_value){
			$(this).attr('selected','selected');
		}
	});
    $("#edit_zone option").each(function(){
        var zone_value=$(this).val();
        if(res.view_details.zone==zone_value){
            $(this).attr('selected','selected');
        }
    });
	
}

$(document).on("click", ".delete_blog", deleteblog);
function deleteblog(){ 
	var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
	var id= $(this).attr('id'); 
	var host="{{ url('admin/ads/pricing/deleted/') }}";
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
	if (confirm("Are you sure delete pricing?")) {
		var id=res.delete_details.deletedid;
		$('.rm'+id).hide();
		$(".alert-success").html(res.delete_details.deletedtatus).removeClass('hidden');

	}

	return false;
}
// Get Sub Cities
$(document).on("change", ".states", getcities);
function getcities(){ 
	var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
	var id= $(this).val(); 

	var host="{{ url('admin/merchants/listing/getcities/') }}";	
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
		},success:rendergetcities

	})
	return false;
}
function rendergetcities(res){
	$('.cities').html('');
	$('.cities').append('<option value="0">All</option>');
	$.each(res.view_details, function(index, data) {
		if (index==0) {
			$('.cities').append('<option value="'+data.id+'">'+data.name+'</option>');
		}else {
			$('.cities').append('<option value="'+data.id+'">'+data.name+'</option>');
		};
	});   
}	
// Get Sub Category
$(document).on("change", ".category", getcategory);
function getcategory(){ 
	var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
	var id= $(this).val(); 

	var host="{{ url('admin/merchants/listing/getsubcategory/') }}";	
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
		},success:rendergetsubcategory

	})
	return false;
}
function rendergetsubcategory(res){
	$('.scategory').html('');
	$('.scategory').append('<option value="">--Choose--</option>')
	$.each(res.view_details, function(index, data) {
		if (index==0) {
			$('.scategory').append('<option value="'+data.c_id+'">'+data.c_title+'</option>');
		}else {
			$('.scategory').append('<option value="'+data.c_id+'">'+data.c_title+'</option>');
		};
	});   
}  

</script>

<script type="text/javascript">
	function deleteConfirm(){
		if($('.checkbox:checked').length == ''){
			alert('Please check atleast one pricing');
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