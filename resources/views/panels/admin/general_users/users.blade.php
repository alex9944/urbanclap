@extends('layouts.adminmain')

@section('head')

@stop

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}" />


   <div class="">
    <div class="page-title">
              <div class="title_left">
                <h3>General Users</h3>
              </div>

    </div>
            <div class="clearfix"></div>           

            <div class="row">

		
					<!-- <div class="col-md-12 col-xs-12">
					 <div class="x_panel">-->
					 
					 <!-- LEFT BAR Start-->
					 <div class="col-md-6 col-xs-12">
								<div class="x_panel">
								<form name="actionForm" action="{{url('admin/general/users/destroy')}}" method="post" onsubmit="return deleteConfirm();"/> 
									<h2>All General Users <span class="pull-right"><a href="{{url('admin/general/users')}}" class="btn btn-success btn-xs"><i class="fa fa-plus"></i> Add </a>  
									<button  class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> Delete All</button>
									<input type="hidden" name="_token" value="{{csrf_token()}}">
									</span></h2>
								 <div class="x_title">
								  </div>
								
   
    
								  <table id="datatable-checkbox" class="table table-striped table-bordered bulk_action">
                      <thead>
                        <tr><th>
						<input type="checkbox" name="check_all" id="check_all" value=""/></th>						 
                          <th>Name</th>				 
                          <th>Email</th>				 
                          <th>Mobile</th>
                          <th>Action</th>                         
                        </tr>
                      </thead>
                      <tbody>
					@foreach ($users as $user)
                        <tr class="rm{{ $user->uid }}">
                          <td>
						  <input type="checkbox" name="selected_id[]" class="checkbox" value="{{ $user->uid }}"/>				 	  
						  </td>
                          <td>{{ $user->first_name }}</td>
                          <td>{{ $user->email }}</td>
                          <td>{{ $user->mobile_no }}</td>
                         <td>
						 @if($user->activated==1)
						 <a href="#" class="enable btn btn-primary btn-xs" id="{{ $user->uid }}"><i class="glyphicon glyphicon-ok"></i> Enable </a>
						 @else
							 <a href="#" class="disable btn btn-primary btn-xs" id="{{ $user->uid }}" ><i class="glyphicon glyphicon-remove"></i> Disable </a>
						 @endif
                            <a href="javascript:void(0);" class="edit_blog btn btn-info btn-xs" id="{{ $user->uid }}" ><i class="fa fa-pencil"></i> Edit </a>
                            <a href="javascript:void(0);" class="btn btn-danger btn-xs delete_blog" id="{{ $user->uid }}"><i class="fa fa-trash-o"></i> Delete </a></td>
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
						$id = '';
						$add = 'Add';
						$url = url('admin/general/users/added');
						if(old('id') != '')
						{
							$id = old('id');
							$add = 'Edit';
							$url = url('admin/general/users/updated');
						}
						?>
						
						<h2><span id="add_div_label">{{$add}}</span> General User </h2>
						<div class="x_title">
						</div>
						@if(Session::get('message')) <div class="alert alert-success" role="alert">{{Session::get('message')}} </div>@endif 
						<div class="alert alert-success hidden"></div>
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
						 <form id="addUser" method="POST" action="{{$url}}" accept-charset="UTF-8" enctype="multipart/form-data" class="form-horizontal">
							<input type="hidden" value="{{old('id')}}" name="id" id="id" />
                            <div id="reportArea">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Name</label>
                                    <div class="col-sm-6">
                                        <input class="form-control" type="text" name="firstname" id="edit_firstname" value="{{ old('firstname') }}"/>
                                    </div>
                                </div>
								<div class="form-group">
                                    <label class="col-sm-2 control-label">Email</label>
                                    <div class="col-sm-6">
                                        <input class="form-control" type="text" name="email" id="edit_email" value="{{ old('email') }}"/>
                                    </div>
                                </div>
								<div class="form-group">
                                    <label class="col-sm-2 control-label">  Mobile</label>
                                    <div class="col-sm-6">
                                        <input class="form-control" type="text" name="mobile_no" id="edit_mobile_no" value="{{ old('mobile_no') }}"/>
                                    </div>
                                </div>
								<div class="form-group">
                                    <label class="col-sm-2 control-label">Password</label>
                                    <div class="col-sm-6">
                                        <input class="form-control" type="text" name="password" id="edit_password" value="{{ old('password') }}"/>
                                    </div>
                                </div>
								<div class="form-group">
                                    <label class="col-sm-2 control-label">Confirm Password</label>
                                    <div class="col-sm-6">
                                        <input class="form-control" type="text" name="cpassword" id="edit_cpassword" value="{{ old('cpassword') }}"/>
                                    </div>
                                </div>
								
								  <div class="form-group">
                                    <label class="col-sm-2 control-label">Profile Image</label>
                                    <div class="col-sm-6">
									 <div class="col-sm-9">
                                        <input class="form-control" type="file" name="photo" id="photo"  /></div>
										 <div class="col-sm-3"><img src="" id="edit_photo" style="height:40px">
										 </div>
										
										
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
	
	$('#add_div_label').html('Edit');
	
	var url = "{{url('admin/general/users/updated')}}";
	$('#addUser').attr('action', url);

	var host="{{ url('admin/general/users/getusers/') }}";
	
	$(".alert-danger").addClass('hidden') ;
	$(".alert-success").addClass('hidden') ;
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
var url="{{ url('') }}";
var image_path=res.view_details.image;
	$('#id').val(res.view_details.id);
	$('#edit_mobile_no').val(res.view_details.mobile_no);
	$('#edit_firstname').val(res.view_details.first_name);
    $('#edit_photo').attr('src',url+'/uploads/thumbnail/'+image_path);
	$('#edit_email').val(res.view_details.email);
}

 $(document).on("click", ".delete_blog", deleteblog);
function deleteblog(){ 
	if (confirm("Are you sure delete user?")) {
		var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
		var id= $(this).attr('id'); 
		var host="{{ url('admin/general/users/deleted/') }}";
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
			},
			success:function(res)
			{
				if(res.success)
				{
					window.location = "{{ url('admin/general/users') }}";
				}
				else
				{
					alert(res.msg);
				}

			}
		
		})
		return false;
	}
}
function deleteStatus(res){ 
 
	var id=res.delete_details.deletedid;
	 $('.rm'+id).hide();
	$(".alert-success").html(res.delete_details.deletedtatus).removeClass('hidden');

}
	
	
//Change Status Enable

 $(document).on("click", ".enable", enable);
	function enable(){ 
  var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
   var id= $(this).attr('id'); 
	var host="{{ url('admin/general/users/enable/') }}";
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
	var host="{{ url('admin/general/users/disable/') }}";
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
        },success:disableStatus
	
	})
	return false;
}
function disableStatus(res){ 
location.reload();
    }	
		
	
    </script>
	
	<script type="text/javascript">
function deleteConfirm(){
	if($('.checkbox:checked').length == ''){
		alert('Please check atleast one user');
		return false;
	} else {
		if (confirm("Are you sure delete the all selected users?"))
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